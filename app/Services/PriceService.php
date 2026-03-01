<?php

namespace App\Services;

use App\Models\MaterialPrice;
use App\Models\MaterialPriceThickness;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class PriceService
{
    public function show($request)
    {
        if ($request->ajax()) {
            return Response::json($this->getData($request));
        }
        return view('home');
    }

    private function getData($request)
    {
        $sortOrder = (($request->has('sortDesc') && $request->sortDesc == 'true') ? 'DESC' : 'ASC');
        $sortBy = (($request->has('sortBy') && $request->sortBy != '') ? $request->sortBy : 'id');

        $q = MaterialPrice::query()->with([
            'thicknesses:id,material_price_id,thickness_mm'
        ]);

        // your existing filter logic (same as products/users)
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
                if (!(Auth::user() && Auth::user()->can('editprices'))) abort(403);
                $item = MaterialPrice::with('thicknesses')->find($request->id);
                if (!$item) return Response::json(['isSuccess' => false, 'message' => __('variable.not_found_error')], 404);
            } else {
                if (!(Auth::user() && Auth::user()->can('createprices'))) abort(403);
                $item = new MaterialPrice();
            }

            $item->material_name = $request->material_name;
            $item->cut_cost = $request->cut_cost !== null && $request->cut_cost !== '' ? $request->cut_cost : null;
            $item->material_cost_per_kg = $request->material_cost_per_kg !== null && $request->material_cost_per_kg !== '' ? $request->material_cost_per_kg : null;
            $item->density_kg_m3 = $request->density_kg_m3 !== null && $request->density_kg_m3 !== '' ? $request->density_kg_m3 : null;
            $item->bend_price = $request->bend_price !== null && $request->bend_price !== '' ? $request->bend_price : null;
            $item->save();

            // sync thicknesses (replace all)
            $th = $request->thicknesses ?: [];
            if (!is_array($th)) $th = [];

            // normalize: numeric, unique, sort
            $normalized = [];
            foreach ($th as $v) {
                if ($v === null || $v === '') continue;
                $num = (float)$v;
                if ($num <= 0) continue;
                $normalized[(string)$num] = $num;
            }
            $normalized = array_values($normalized);
            sort($normalized);

            MaterialPriceThickness::where('material_price_id', $item->id)->delete();
            foreach ($normalized as $mm) {
                MaterialPriceThickness::create([
                    'material_price_id' => $item->id,
                    'thickness_mm' => $mm,
                ]);
            }

            DB::commit();

            $item = MaterialPrice::with('thicknesses')->find($item->id);

            return Response::json([
                'isSuccess' => true,
                'message' => __('variable.updated_successfully'),
                'data' => $item,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return Response::json(["message" => $e->getMessage()], 400);
        }
    }

    public function destroy($id)
    {
        if (!(Auth::user() && Auth::user()->can('deleteprices'))) abort(403);

        try {
            $item = MaterialPrice::find($id);
            if (!$item) {
                return Response::json(['isSuccess' => false, 'message' => __('variable.not_found_error')]);
            }

            $item->delete();

            return Response::json(['isSuccess' => true, 'message' => __('variable.deleted_successfully')]);
        } catch (\Exception $e) {
            return Response::json(['isSuccess' => false, 'message' => $e->getMessage()], 400);
        }
    }
}
