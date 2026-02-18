<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImages;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function show($request)
    {
        if ($request->ajax()) {
            $products = $this->getData($request);
            return Response::json($products);
        }
        return view('home');
    }

    private function getData($request)
    {
        $sortOrder = (($request->has('sortDesc') && $request->sortDesc == 'true') ? 'DESC' : 'ASC');
        $sortBy = (($request->has('sortBy') && $request->sortBy != '') ? $request->sortBy : 'id');

        $q = Product::query()->with(['service:id,title', 'images']);

        // filter (same logic as your users)
        $filterArray = $request->filter ?? [];
        foreach ($filterArray as $index => $filter) {
            $filter = json_decode($filter);
            $cond = "AND";
            if ($index > 0) {
                $cond = json_decode($filterArray[$index - 1])->condition ?? "AND";
            }

            $action = (property_exists($filter, 'action') && in_array($filter->action, ['LIKE', '=', '!=', '>', '<', '>=', '<=']))
                ? $filter->action
                : "LIKE";

            if ($filter && $filter->key && $filter->key->value) {
                if ($filter->text || $filter->text === 0) {
                    $text = ($action == "LIKE") ? ('%' . trim($filter->text) . '%') : trim($filter->text);

                    if ($cond == "AND") $q = $q->where($filter->key->value, $action, $text);
                    else $q = $q->orWhere($filter->key->value, $action, $text);
                }
            }
        }

        $perPage = $request->perPage ?: 10;

        return $q->orderBy($sortBy, $sortOrder)->paginate($perPage);
    }

    public function store($request)
    {
        try {
            DB::beginTransaction();

            $isEdit = $request->id ? true : false;

            if ($isEdit) {
                if (!(Auth::user() && Auth::user()->can('editproducts'))) abort(403);
                $product = Product::with('images')->find($request->id);
                if (!$product) return Response::json(['isSuccess' => false, 'message' => __('variable.not_found_error')], 404);
            } else {
                if (!(Auth::user() && Auth::user()->can('createproducts'))) abort(403);
                $product = new Product();
            }

            $product->service_id = $request->service_id ?: null;
            $product->title = $request->title;
            $product->description = $request->description ?: null;
            $product->price = $request->price ?: null;
            $product->size = $request->size ?: null;
            $product->weight = $request->weight !== null ? $request->weight : null;
            $product->type = $request->type ?: null;
            $product->material = $request->material ?: null;
            $product->save();

            // delete images by ids
            $deleted = $request->deleted_image_ids ?: [];
            if (is_array($deleted) && count($deleted)) {
                $imgs = ProductImages::where('product_id', $product->id)
                    ->whereIn('id', $deleted)->get();

                foreach ($imgs as $img) {
                    if ($img->image_path) Storage::disk('public')->delete($img->image_path);
                    $img->delete();
                }
            }

            // upload new images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    if (!$file) continue;
                    $path = $file->store('products/' . $product->id, 'public');

                    ProductImages::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                    ]);
                }
            }

            DB::commit();

            $product = Product::with(['service:id,title', 'images'])->find($product->id);

            return Response::json([
                'isSuccess' => true,
                'message' => __('variable.updated_successfully'),
                'data' => $product,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return Response::json(["message" => $e->getMessage()], 400);
        }
    }

    public function destroy($id)
    {
        if (!(Auth::user() && Auth::user()->can('deleteproducts'))) abort(403);

        try {
            $product = Product::with('images')->find($id);
            if (!$product) {
                return Response::json(['isSuccess' => false, 'message' => __('variable.not_found_error')]);
            }

            foreach ($product->images as $img) {
                if ($img->image_path) Storage::disk('public')->delete($img->image_path);
            }

            $product->delete();

            return Response::json(['isSuccess' => true, 'message' => __('variable.deleted_successfully')]);
        } catch (\Exception $e) {
            return Response::json(['isSuccess' => false, 'message' => $e->getMessage()], 400);
        }
    }
}
