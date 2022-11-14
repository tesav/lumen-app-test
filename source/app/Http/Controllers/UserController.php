<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'password' => 'required',
            'email' => 'required|email|unique:users',
        ]);

        $user = new User;
        $user->fill($request->all());
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return response(new UserResource($user));
    }

    public function signIn(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->input('email'))->first();
        if (Hash::check($request->input('password'), $user->password)) {
            $token = hash('sha256', Str::random(80));
            $user->timestamps = false;
            $user->api_token = $token;
            $user->save();
            return response()->json(['status' => 'success', 'api_token' => $token]);
        } else {
            return response()->json(['status' => 'fail'], 401);
        }
    }
}
