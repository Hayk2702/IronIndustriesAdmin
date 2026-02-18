<?php


namespace App\Services;


use App\Models\User;
use App\Models\UserPermission;
use App\Models\UserRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class UserService
{
    public function show($request)
    {

        if ($request->ajax()) {
            $users = $this->getUserData($request);
            return Response::json($users);
        }
        return view('home');
    }
    private function getUserData($request)
    {

        $sortOrder = (($request->has('sortDesc') and $request->sortDesc == 'true') ? 'DESC' : 'ASC');
        $sortBy = (($request->has('sortBy') and $request->sortBy != '') ? $request->sortBy : 'id');

        $users = User::whereDoesntHave('roles', function ($query) {
            $query->where('slug', 'superadmin');
        })->where("id","!=",Auth::user()->id);


        $filterArray = $request->filter;
        foreach ($filterArray as $index => $filter) {
            $filter = json_decode($filter);
            $cond = "AND";
            if ($index > 0) {
                $cond = json_decode($filterArray[$index - 1])->condition ?? "AND";
            }
            if (property_exists($filter, 'action') and in_array($filter->action, ['LIKE', '=', '!=', '>', '<', '>=', '<='])) {
                $action = $filter->action;
            } else {
                $action = "LIKE";
            }
            if ($filter and $filter->key and $filter->key->value) {
                if ($filter->text or $filter->text == 0) {
                    if ($action == "LIKE") {
                        $text = '%' . trim($filter->text) . '%';
                    } else {
                        $text = trim($filter->text);
                    }
                    if ($cond == "AND") {
                        $users = $users->where($filter->key->value, $action, $text);
                    } else {
                        $users = $users->orWhere($filter->key->value, $action, $text);
                    }
                }
            }
        }

        $users = $users->with('roles', 'permissions')->orderBy($sortBy, $sortOrder)->paginate($request->perPage);

        return $users;
    }

    public function store($request)
    {
        try {
            DB::beginTransaction();
            if ($request->has('id') and $request->id)
                $user = User::find($request->id);
            else
                $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;

            if ($request->password)
                $user->password = Hash::make($request->password);
            $user->save();
            UserRole::where('user_id', $user->id)->delete();
            if ($request->role_id) {
                foreach ($request->role_id as $roleId) {
                    $userRole = new UserRole();
                    $userRole->user_id = $user->id;
                    $userRole->role_id = $roleId;
                    $userRole->save();
                }
            }
            UserPermission::where('user_id', $user->id)->delete();
            if ($request->permission_id) {
                foreach ($request->permission_id as $permId) {
                    $userPermission = new UserPermission();
                    $userPermission->user_id = $user->id;
                    $userPermission->permission_id = $permId;
                    $userPermission->save();
                }
            }
            DB::commit();
            return Response::json(['isSuccess' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return Response::json(["message" => $e->getMessage()], 400);
        }
    }

    public function destroy($id)
    {
        if (!(Auth::user() and Auth::user()->can('deleteusers'))) {
            return abort(403);
        }
        try {
            $user = User::find($id);
            if (!$user) {
                return Response::json(['isSuccess' => false, 'message' => __('variable.not_found_error')]);
            }
            UserRole::where('user_id', $id)->delete();
            UserPermission::where('user_id', $id)->delete();
            $user->delete();
            return Response::json(['isSuccess' => true, 'message' => __('variable.deleted')]);

        } catch (\Exception $e) {
            return Response::json(['isSuccess' => false, 'message' => $e->getMessage()], 400);
        }
    }

}
