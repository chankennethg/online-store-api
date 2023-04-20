<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\LoginRequest;
use App\Http\Resources\V1\Admin\AdminLoginResource;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * User Login
     *
     * @param LoginRequest $request Login Payload
     */
    public function login(LoginRequest $request): AdminLoginResource
    {
        $credentials = $request->safe()->all();
        $credentials['is_admin'] = true;

        // Check if credentials match
        if (!Auth::attempt($credentials)) {
            throw new AuthenticationException();
        }

        /** @var User $user*/
        $user = Auth::user();
        $user->last_login_at = now();
        $user->save();

        return new AdminLoginResource($user);
    }
}
