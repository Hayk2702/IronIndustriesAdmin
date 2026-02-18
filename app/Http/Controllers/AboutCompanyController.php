<?php
namespace App\Http\Controllers;

use App\Http\Requests\AboutCompanyShowRequest;
use App\Http\Requests\AboutCompanyStoreRequest;
use App\Services\AboutCompanyService;
use Illuminate\Http\Request;

class AboutCompanyController extends Controller
{
    public function __construct(private AboutCompanyService $service) {}

    public function index(AboutCompanyShowRequest $request)
    {
        return $this->service->show($request);
    }

    public function store(AboutCompanyStoreRequest $request)
    {
        return $this->service->store($request);
    }
}
