<?php

namespace App\Services;

use App\Events\SmsOtp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthService
{

    public static function register(Request $request)
    {
        $user = UserService::create($request);
            //$otp = random_int(4);
            $otp = '1234';
            //event(new SmsOtp($otp, $request->country_code.$request->phone));
            Cache::put("otp{$user->id}", $otp , now()->addMinutes(5));
        return $user;
    }



    public static function login(Request $request)
    {
        $credentials = $request->only('country_code' , 'mobile_number');
        $user = User::where('mobile_number', $credentials['mobile_number'])
            ->where('country_code', $credentials['country_code'])
            ->first();
        if ($user) {
            //$otp = random_int(4);
            $otp = '1234';
            //event(new SmsOtp($otp, $request->country_code.$request->phone));
            Cache::put("otp{$user->id}", $otp , now()->addMinutes(5));
            return $user;
        }
        return null;
    }
}
