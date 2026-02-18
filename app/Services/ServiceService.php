<?php

namespace App\Services;

use App\Models\Service;
use App\Models\ServiceImages;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class ServiceService
{
    public function show($request)
    {
        if ($request->ajax()) {
            $services = $this->getData($request);
            return Response::json($services);
        }
        return view('home');
    }

    private function getData($request)
    {
        $sortOrder = (($request->has('sortDesc') && $request->sortDesc == 'true') ? 'DESC' : 'ASC');
        $sortBy = (($request->has('sortBy') && $request->sortBy != '') ? $request->sortBy : 'id');

        $q = Service::query();

        // your filter logic (same pattern as users)
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
                if ($filter->text || $filter->text == 0) {
                    $text = ($action == "LIKE") ? ('%' . trim($filter->text) . '%') : trim($filter->text);

                    if ($cond === "AND") $q = $q->where($filter->key->value, $action, $text);
                    else $q = $q->orWhere($filter->key->value, $action, $text);
                }
            }
        }

        return $q->with('images')->orderBy($sortBy, $sortOrder)->paginate($request->perPage);
    }

    public function store($request)
    {
        try {
            DB::beginTransaction();

            $service = ($request->id) ? Service::find($request->id) : new Service();
            $service->title = $request->title;
            $service->description = $request->description;
            $service->save();

            // delete images by ids (from edit form)
            $deletedIds = $request->deleted_image_ids ?? [];
            if (is_array($deletedIds) && count($deletedIds)) {
                $imgs = ServiceImages::where('service_id', $service->id)->whereIn('id', $deletedIds)->get();
                foreach ($imgs as $img) {
                    Storage::disk('public')->delete($img->image_path);
                    $img->delete();
                }
            }

            // add new images
            if ($request->hasFile('images')) {
                $maxSort = (int) ServiceImages::where('service_id', $service->id)->max('sort');
                foreach ($request->file('images') as $file) {
                    $path = $file->store('services', 'public');

                    ServiceImages::create([
                        'service_id' => $service->id,
                        'image_path' => $path,
                        'sort' => ++$maxSort,
                    ]);
                }
            }

            DB::commit();

            $service->load('images');
            return Response::json([
                'isSuccess' => true,
                'data' => $service,
                'message' => __('variable.updated_successfully'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return Response::json(["message" => $e->getMessage()], 400);
        }
    }

    public function destroy($id)
    {
        if (!(Auth::user() && Auth::user()->can('deleteservices'))) {
            return abort(403);
        }

        try {
            $service = Service::with('images')->find($id);
            if (!$service) {
                return Response::json(['isSuccess' => false, 'message' => __('variable.not_found_error')]);
            }

            // delete files
            foreach ($service->images as $img) {
                Storage::disk('public')->delete($img->image_path);
            }

            $service->delete();

            return Response::json(['isSuccess' => true, 'message' => __('variable.deleted_successfully')]);
        } catch (\Exception $e) {
            return Response::json(['isSuccess' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function deleteImage($serviceId, $imageId)
    {
        if (!(Auth::user() && Auth::user()->can('editservices'))) {
            return abort(403);
        }

        $img = ServiceImages::where('service_id', $serviceId)->where('id', $imageId)->first();
        if (!$img) {
            return Response::json(['isSuccess' => false, 'message' => __('variable.not_found_error')], 404);
        }

        Storage::disk('public')->delete($img->image_path);
        $img->delete();

        return Response::json(['isSuccess' => true]);
    }
}
