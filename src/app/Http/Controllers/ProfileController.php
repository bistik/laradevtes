<?php

namespace App\Http\Controllers;


use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

class ProfileController
{
    use ValidatesRequests;

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'nullable|string',
            'username' => 'nullable|unique:users,user_name',
            'email' => 'nullable|email',
            'role' => 'nullable|in:admin,user',
            'avatar' => 'dimensions:max_width=256,max_height=256',
        ]);



        $user = $request->user();
        $user->name = $request->input('name', $user->name);
        $user->user_name = $request->input('username', $user->user_name);
        $user->email = $request->input('email', $user->email);
        $user->user_role = $request->input('role', $user->user_role);
        if ($request->has('avatar')) {
            $user->avatar = $request->file('avatar')->store('avatars');
        }
        $user->save();

        return $user;
    }
}
