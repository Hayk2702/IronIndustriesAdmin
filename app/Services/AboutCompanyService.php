<?php
namespace App\Services;

use App\Models\AboutCompany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class AboutCompanyService
{
    public function show($request)
    {
        if ($request->ajax()) {
            $row = AboutCompany::query()->first();
            return Response::json([
                'data' => $row ? [
                    'id' => $row->id,
                    'title' => $row->title,
                    'image_path' => $row->image_path,
                    'image_url' => $row->image_path ? Storage::url($row->image_path) : null,
                    'description' => $row->description,
                ] : null
            ]);
        }

        return view('home');
    }

    public function store($request)
    {
        try {
            DB::beginTransaction();

            $row = AboutCompany::query()->first() ?? new AboutCompany();
            $row->title = $request->title;
            $row->description = $request->description;

            if ($request->hasFile('image')) {
                // delete old
                if ($row->image_path && Storage::disk('public')->exists($row->image_path)) {
                    Storage::disk('public')->delete($row->image_path);
                }

                $path = $request->file('image')->store('about-company', 'public');
                $row->image_path = $path;
            }

            $row->save();

            DB::commit();

            return Response::json([
                'isSuccess' => true,
                'message' => __('variable.updated_successfully'),
                'data' => [
                    'id' => $row->id,
                    'title' => $row->title,
                    'image_path' => $row->image_path,
                    'image_url' => $row->image_path ? Storage::url($row->image_path) : null,
                    'description' => $row->description,
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return Response::json(["message" => $e->getMessage()], 400);
        }
    }
}
