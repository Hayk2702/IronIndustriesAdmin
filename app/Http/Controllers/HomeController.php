<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionsGetRequest;
use App\Http\Requests\RolesGetRequest;
use App\Services\HomeService;
class HomeController extends Controller
{
    private $homeService;

    public function __construct(HomeService $homeService)
    {
        $this->homeService = $homeService;
        $this->middleware('auth');
    }
    public function index()
    {
        return view('home');
    }
    public function abort403()
    {
        return abort(403,__('variable.abort_403'));
    }
    public function setLocale($lang)
    {
        return $this->homeService->setLocale($lang);
    }
    public function getRoles(RolesGetRequest $request)
    {
        return $this->homeService->getRoles();
    }
    public function getPermissions(PermissionsGetRequest $request)
    {
        return $this->homeService->getPermissions();
    }

}
