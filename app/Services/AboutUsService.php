<?php

namespace App\Services;

use App\Models\AboutUs;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class AboutUsService
{
    public function show($request)
    {
        if ($request->ajax()) {
            $row = AboutUs::first();
            return Response::json(['data' => $row]);
        }

        return view('home');
    }

    public function store($request)
    {
        try {
            DB::beginTransaction();

            $row = AboutUs::first() ?? new AboutUs();

            $row->address = $request->address;
            $row->phone = $request->phone;
            $row->lat = $request->lat;
            $row->lng = $request->lng;
            $row->working_hours = $request->working_hours;
            $row->facebook = $request->facebook;
            $row->instagram = $request->instagram;
            $row->linkedin = $request->linkedin;
            $row->telegram = $request->telegram;
            $row->viber = $request->viber;
            $row->whatsapp = $request->whatsapp;
            $row->save();

            DB::commit();

            return Response::json([
                'isSuccess' => true,
                'data' => $row
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return Response::json(['message'=>$e->getMessage()],400);
        }
    }
}
