<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessageMail;
use App\Models\AboutCompany;
use App\Models\AboutUs;
use App\Models\Category;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use GuzzleHttp\Client;
use App\Models\MaterialPrice;
use App\Models\MaterialPriceThickness;

class ApiController extends Controller
{

    public function aboutOurCompany(Request $request)
    {
        try {
            $data = AboutCompany::select("title", "image_path", "description")->first();
            return Response::json(["data" => $data, "error" => ""]);
        } catch (\Exception $e) {
            return Response::json(["data" => null, "error" => "Error Code 400"], 400);
        }

    }

    public function contactUs(Request $request)
    {
        try {

            $data = AboutUs::select("address", "phone", "lat", "lng", "working_hours", "facebook", "instagram", "linkedin", "telegram", "viber", "whatsapp")->first();
            return Response::json(["data" => $data, "error" => ""]);
        } catch (\Exception $e) {
            return Response::json(["data" => null, "error" => "Error Code 400"], 400);
        }

    }

    public function services(Request $request)
    {
        try {

            $query = Service::select([
                'id',
                'title',
                'description',
            ])
                ->with([
                    'images' => function ($q) {
                        $q->select([
                            'id',
                            'service_id',
                            'image_path',
                        ])
                            ->orderBy('sort', 'asc');
                    }
                ]);

            if ($request->filled('id')) {

                $service = $query->find($request->id);

                if (!$service) {
                    return response()->json([
                        'data' => null,
                        'error' => 'Service not found'
                    ], 404);
                }

                return response()->json([
                    'data' => $service,
                    'error' => ''
                ]);
            }

            // All services
            $services = $query->latest()->get();

            return response()->json([
                'data' => $services,
                'error' => ''
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'data' => null,
                'error' => 'Server Error'
            ], 500);
        }
    }
    public function categories(Request $request)
    {
        try {

            $query = Category::select([
                'id',
                'title',
                'slug',
                'description',
            ])
                ->with([
                    'images' => function ($q) {
                        $q->select([
                            'id',
                            'category_id',
                            'image_path',
                        ])
                            ->orderBy('sort', 'asc');
                    }
                ]);

            if ($request->filled('id')) {

                $categories = $query->find($request->id);

                if (!$categories) {
                    return response()->json([
                        'data' => null,
                        'error' => 'Service not found'
                    ], 404);
                }

                return response()->json([
                    'data' => $categories,
                    'error' => ''
                ]);
            }

            // All categories
            $categories = $query->latest()->get();

            return response()->json([
                'data' => $categories,
                'error' => ''
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'data' => null,
                'error' => 'Server Error'
            ], 500);
        }
    }

    public function products(Request $request)
    {
        try {

            $query = Product::select([
                'id',
                'title',
                'description',
                'price',
                'size',
                'weight',
                'type',
                'material',
                'category_id',
            ])
                ->with([
                    'images' => function ($q) {
                        $q->select([
                            'id',
                            'product_id',
                            'image_path',
                        ]);
                    },
                    'category:id,title,description'
                ]);

            if ($request->filled('category_id')) {
                $query->where('category_id', (int)$request->category_id);
            }

            // single product
            if ($request->filled('id')) {

                $product = $query->find($request->id);

                if (!$product) {
                    return response()->json([
                        'data' => null,
                        'error' => 'Product not found'
                    ], 404);
                }

                return response()->json([
                    'data' => $product,
                    'error' => ''
                ]);
            }

            // pagination
            if ($request->filled('offset')) {
                $query->offset((int)$request->offset);
            }

            if ($request->filled('limit')) {
                $query->limit((int)$request->limit);
            }

            $products = $query->latest('id')->get();

            return response()->json([
                'data' => $products,
                'error' => ''
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'data' => null,
                'error' => 'Server Error',
            ], 500);
        }
    }

    public function prices(Request $request)
    {
        try {
            $query = MaterialPrice::select([
                'id',
                'material_name',
                'cut_cost',
                'material_cost_per_kg',
                'density_kg_m3',
                'bend_price',
                'created_at',
                'updated_at',
            ])->with([
                'thicknesses' => function ($q) {
                    $q->select([
                        'id',
                        'material_price_id',
                        'thickness_mm',
                    ])->orderBy('thickness_mm', 'asc');
                }
            ]);

            // single item by id
            if ($request->filled('id')) {
                $item = $query->find((int)$request->id);

                if (!$item) {
                    return response()->json([
                        'data' => null,
                        'error' => 'Price not found'
                    ], 404);
                }

                return response()->json([
                    'data' => $item,
                    'error' => ''
                ]);
            }

            // FILTERS
            if ($request->filled('material_name')) {
                $query->where('material_name', 'LIKE', '%' . trim($request->material_name) . '%');
            }

            // numeric range helpers
            $applyMinMax = function ($field, $minKey, $maxKey) use ($request, $query) {
                if ($request->filled($minKey)) {
                    $query->where($field, '>=', (float)$request->input($minKey));
                }
                if ($request->filled($maxKey)) {
                    $query->where($field, '<=', (float)$request->input($maxKey));
                }
            };

            $applyMinMax('cut_cost', 'min_cut_cost', 'max_cut_cost');
            $applyMinMax('material_cost_per_kg', 'min_cost_per_kg', 'max_cost_per_kg');
            $applyMinMax('density_kg_m3', 'min_density', 'max_density');
            $applyMinMax('bend_price', 'min_bend_price', 'max_bend_price');

            // filter by thickness (exact)
            // example: ?thickness=3 or ?thickness=6.5
            if ($request->filled('thickness')) {
                $th = (float)str_replace(',', '.', $request->thickness);

                $query->whereHas('thicknesses', function ($q) use ($th) {
                    $q->where('thickness_mm', $th);
                });
            }

            // pagination like your products
            if ($request->filled('offset')) {
                $query->offset((int)$request->offset);
            }
            if ($request->filled('limit')) {
                $query->limit((int)$request->limit);
            }

            $items = $query->latest('id')->get();

            return response()->json([
                'data' => $items,
                'error' => ''
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'data' => null,
                'error' => 'Server Error',
                'error2' => $e->getMessage(),
            ], 500);
        }
    }


    public function sendMessage(Request $request)
    {
        try {
            $validated = $request->validate([
                'email'    => ['required', 'email', 'max:255'],
                'fullname' => ['required', 'string', 'max:255'],
                'phone'    => ['nullable', 'string', 'max:50'],
                'message'  => ['required', 'string', 'max:5000'],
                'file'     => ['nullable', 'file', 'max:10240', 'mimes:jpg,jpeg,png,pdf,doc,docx,txt'],
            ]);

            $toEmail = "armorbita@gmail.com";

            if (!$toEmail) {
                return response()->json([
                    'data' => null,
                    'error' => 'CONTACT_TO_EMAIL is not configured'
                ], 500);
            }

            $uploadedFile = $request->file('file');

            Mail::to([$toEmail])
                ->send(new ContactMessageMail(
                    email: $validated['email'],
                    fullname: $validated['fullname'],
                    phone: $validated['phone'] ?? null,
                    messageText: $validated['message'],
                    file: $uploadedFile
                ));

            return response()->json([
                'data' => ['sent' => true],
                'error' => ''
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'data' => null,
                'error' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'data' => null,
                'error' => 'Server Error',
                'error2' => $e->getMessage(),
            ], 500);
        }
    }


}
