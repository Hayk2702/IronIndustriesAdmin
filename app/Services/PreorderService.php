<?php

namespace App\Services;

use App\Models\Preorder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class PreorderService
{
    public function show($request)
    {
        if ($request->ajax()) {
            $items = $this->getData($request);
            return Response::json($items);
        }

        return view('home');
    }

    private function getData($request)
    {
        $sortOrder = (($request->has('sortDesc') && $request->sortDesc == 'true') ? 'DESC' : 'ASC');
        $sortBy = (($request->has('sortBy') && $request->sortBy != '') ? $request->sortBy : 'id');

        $allowedSorts = ['id', 'full_name', 'phone_number', 'email', 'is_viewed', 'created_at'];
        if (!in_array($sortBy, $allowedSorts, true)) {
            $sortBy = 'id';
        }

        $q = Preorder::query();

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

                    if ($cond === "AND") {
                        $q = $q->where($filter->key->value, $action, $text);
                    } else {
                        $q = $q->orWhere($filter->key->value, $action, $text);
                    }
                }
            }
        }

        return $q
            ->orderBy($sortBy, $sortOrder)
            ->paginate($request->perPage);
    }

    public function detail($id)
    {
        if (!(Auth::user() && Auth::user()->can('showpreorders'))) {
            return abort(403);
        }

        $item = Preorder::find($id);

        if (!$item) {
            return Response::json([
                'isSuccess' => false,
                'message' => __('variable.not_found_error'),
            ], 404);
        }

        if (!$item->is_viewed) {
            $item->update(['is_viewed' => true]);
            $item->refresh();
        }

        return Response::json([
            'isSuccess' => true,
            'data' => $item,
        ]);
    }

    public function markViewed($id)
    {
        if (!(Auth::user() && Auth::user()->can('editpreorders'))) {
            return abort(403);
        }

        $item = Preorder::find($id);

        if (!$item) {
            return Response::json([
                'isSuccess' => false,
                'message' => __('variable.not_found_error'),
            ], 404);
        }

        $item->update([
            'is_viewed' => true,
        ]);

        return Response::json([
            'isSuccess' => true,
            'message' => __('variable.updated_successfully'),
        ]);
    }

    public function destroy($id)
    {
        if (!(Auth::user() && Auth::user()->can('deletepreorders'))) {
            return abort(403);
        }

        try {
            $item = Preorder::find($id);

            if (!$item) {
                return Response::json([
                    'isSuccess' => false,
                    'message' => __('variable.not_found_error'),
                ]);
            }

            $item->delete();

            return Response::json([
                'isSuccess' => true,
                'message' => __('variable.deleted_successfully'),
            ]);
        } catch (\Exception $e) {
            return Response::json([
                'isSuccess' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
