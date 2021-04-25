<?php

namespace App\Http\Controllers;

use App\Mail\UserPin;
use App\Models\Invite;
use App\Models\Pin;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class AuthController
{
    use ValidatesRequests;

    public function login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = $this->getUser($request);

        return response()->json(
          ['token' => $user->createToken('access-token')->plainTextToken]
        );
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|unique:users,user_name',
            'password' => 'required|string',
            'invite' => 'required|string|exists:invites,invite_key',
        ]);

        $pin = random_int(100000, 999999);

        $newUser = DB::transaction(function () use ($request, $pin) {
            $invite = Invite::where([
                'invite_key' => $request->input('invite')
            ])->first();

            $registeredUser = User::create(
                [
                    'user_name' => $request->input('username'),
                    'password' => Hash::make($request->input('password')),
                    'registered_at' => Carbon::now(),
                    'email' => $invite->email,
                ]
            );

            Pin::create(
                [
                    'user_id' => $registeredUser->id,
                    'value' => $pin,
                ]
            );

            return $registeredUser;
        });

        Mail::to($newUser)->queue(new UserPin($pin));

        return response()->json(['pin' => $pin], 201);
    }

    public function confirm(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|string',
            'pin' => 'required|exists:pins,value',
        ]);

        $user = $this->getUser($request, false);

        DB::transaction(function () use ($user, $request) {
            $pin = Pin::where(
                ['user_id' => $user->id, 'value' => $request->input('pin')]
            );
            if (!$pin) {
                throw ValidationException::withMessages([
                    'pin' => 'Invalid pin'
                ]);
            }
            $user->active = true;
            $user->save();
            $pin->delete();
        });

        return $user;
    }

    private function getUser(Request $request, bool $active = true): User
    {
        $username = $request->input('username');
        $password = $request->input('password');

        $user = User::where(
            [
                'user_name' => $username,
                'active' => $active,
            ],
        )->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'user' => ['Invalid username/password']
            ]);
        }

        return $user;
    }
}
