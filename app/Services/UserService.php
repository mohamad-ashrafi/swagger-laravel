<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public static function create(Request $request)
    {
        DB::beginTransaction();
            $user = User::create([
                'username' => $request['username'],
                'country_code' => $request['country_code'],
                'mobile_number' => $request['mobile_number'] ? $request['mobile_number'] : null,
                'password' => $request['password'] ? $request['password'] : null,
            ]);
        DB::commit();
        return $user;
    }



    public static function update(Request $request, User $user)
    {
        $user->load('profile');
        DB::transaction(function () use ($request, &$user) {
            $user->phone = $request['phone'];
            $user->email = $request['email'];
            if ($request['password']) {
                $user->password = Hash::make($request['password']);
            }
            $user->save();
            $profile = $user->profile;
            $profile->first_name = $request['first_name'] ? $request['first_name'] : null;
            $profile->last_name = $request['last_name'] ? $request['last_name'] : null;
            $profile->full_name = $request['first_name'] .' '. $request['last_name'];
            $profile->age = $request['age'] ? $request['age'] : null;
            //add api to upload avatar
            $profile->avatar = $request['avatar'] ? $request['avatar'] : null;
            $profile->district = $request['district'] ? $request['district'] : null;
            $profile->save();
        });
        return $user;
    }

}
