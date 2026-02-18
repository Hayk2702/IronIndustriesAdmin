<?php

namespace App\Services;

use App\Models\BookingType;
use App\Models\Country;
use App\Models\MobileUser;
use App\Models\Notification as myNotification;
use App\Models\Organizations;
use App\Models\Permission;
use App\Models\RolePermission;
use App\Models\Roles;
use App\Models\User;
use App\Models\UserPermission;
use App\Models\UserRole;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class HomeService
{

    public function __construct()
    {
    }

    public function setLocale($lang)
    {
        App::setLocale($lang);
        session()->put('lang', $lang);
        return Response::json(['isSuccess' => true, 'redirectUrl' => route('dashboard.index', ['locale' => app()->getLocale()])]);
    }

    public function getRoles()
    {

        $roles = Roles::where('slug', '<>', 'superadmin')->get();
        return Response::json($roles);

    }

    public function getPermissions()
    {
        $permissions = new Permission();
        if (!Auth::user()->isSuperAdmin()) {
            $permArr = UserPermission::where('user_id', Auth::user()->id)->pluck('permission_id')->toArray();
            $userRole = UserRole::where('user_id', Auth::user()->id)->pluck('role_id')->toArray();
            $arr = RolePermission::whereIn('role_id', $userRole)->pluck('permission_id')->toArray();
            $permissions = $permissions->whereIn('id', $permArr)->orWhereIn('id', $arr);
        }
        $permissions = $permissions->get();
        return Response::json($permissions);
    }

    public function getCountryCodes()
    {
        $countries = Country::where("status",1)->get();

        return Response::json($countries);
    }
    public function getBookingTypes()
    {
        $bookingTypes = BookingType::get();

        return Response::json($bookingTypes);
    }


}
