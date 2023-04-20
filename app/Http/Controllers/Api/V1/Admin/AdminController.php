<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\CreateAdminRequest;
use App\Http\Resources\V1\Admin\CreateAdminResource;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Create an admin account
     *
     * @param AdminCreateUserRequest $request payload
     */
    public function createAdmin(CreateAdminRequest $request): CreateAdminResource
    {
        $data = $request->safe()->all();

        /** @var User $user*/
        $user = new User();
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->email = $request['email'];
        $user->password = Hash::make($request['password']);
        $user->address = $request['address'];
        $user->phone_number = $request['phone_number'];
        $user->avatar = $request['avatar'];
        $user->is_admin = true;
        $user->save();

        return new CreateAdminResource($user);
    }
}
