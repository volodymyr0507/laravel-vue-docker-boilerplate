<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateUserRequest;
use App\Models\Address;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * Create a new User and then authenticate them.
     *
     * @param CreateUserRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create(CreateUserRequest $request)
    {
        $type = $request->driver;

        if (!empty($type) && !is_bool($type)) {
            return response()->json([
                'error' => [
                    'message' => trans('errors.something_went_wrong')
                ]
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($newUser = User::create($request->only('name', 'email', 'phone', 'password'))) {
            $newUser->attachRole($type ? Role::$ROLE_DRIVER : Role::$ROLE_MECHANIC);

            Address::create([
                'user_id' => $newUser->id,
                'latitude' => $request->lat,
                'longitude' => $request->lng,
                'address' => $request->address
            ]);

            Auth::login($newUser);

            return response()->json([], Response::HTTP_NO_CONTENT);
        }
    }
}
