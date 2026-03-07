<?php

namespace App\Http\Controllers;

use App\Http\Requests\PreorderShowRequest;
use App\Services\PreorderService;

class PreorderController extends Controller
{
    public function __construct(private PreorderService $preorderService) {}

    public function index(PreorderShowRequest $request)
    {
        return $this->preorderService->show($request);
    }

    public function destroy($id)
    {
        return $this->preorderService->destroy($id);
    }

    public function markViewed($id)
    {
        return $this->preorderService->markViewed($id);
    }

    public function detail($id)
    {
        return $this->preorderService->detail($id);
    }
}
