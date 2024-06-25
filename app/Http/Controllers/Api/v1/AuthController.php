<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Jobs\SendEmailJob;
use App\Models\User;
use App\Services\AuthService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;


class AuthController extends Controller
{
    /**
     * @OA\PathItem (path="/api")
     *
     * @OA\Info (
     *  version="1.0.0",
     *  title="Api Documentation"
     * ),
     *  * @OA\SecurityScheme(
     *     type="http",
     *     description="Use a Bearer token to access these endpoints",
     *     name="Authorization",
     *     in="header",
     *     scheme="bearer",
     *     bearerFormat="JWT",
     *     securityScheme="bearerAuth",
     * ),
     * @OA\Post(
     *     path="/api/user/register",
     *     summary="Register a new user",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="username",
     *                     type="string",
     *                     description="uniq username without space and unexpected chars"
     *                 ),
     *                 @OA\Property(
     *                     property="country_code",
     *                     type="integer",
     *                     description="user country code without ziro. exp: 98"
     *                 ),
     *                 @OA\Property(
     *                     property="mobile_number",
     *                     type="integer",
     *                     description="user mobile number with standard format without country_code and ziro. exp: 9102403100"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     description="8-30 char string password"
     *                 ),
     *                 required={"username", "country_code", "mobile_number" , "password"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful registration",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object", ref="#/components/schemas/UserResource"),
     *             @OA\Property(property="message", type="string", example="send sms successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable ",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="خطای اعتبارسنجی فیلدها"),
     *         )
     *     ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function Register(RegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = AuthService::register($request);
            return Response()->json([
                'user' => new UserResource($user),
                'message' => 'send sms successfully'
            ], \Symfony\Component\HttpFoundation\Response::HTTP_OK);
    }
    /**
     * /**
     * @OA\Schema(
     *     schema="VerifyOtp",
     *     type="object",
     *     @OA\Property(property="country_code", type="string", example="98"),
     *     @OA\Property(property="mobile_number", type="string", example="9123456789"),
     *     @OA\Property(property="code", type="string", example="1234"),
     * )
     * @OA\Post(
     *     path="/api/user/verify-otp",
     *     summary="Verify OTP",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="country_code",
     *                     type="string",
     *                     description="user country code without ziro. exp: 98"
     *                 ),
     *                 @OA\Property(
     *                     property="mobile_number",
     *                     type="string",
     *                     description="user mobile number with standard format without country_code and ziro. exp: 9102403100"
     *                 ),
     *                 @OA\Property(
     *                     property="code",
     *                     type="string",
     *                     description="otp code received from sms"
     *                 ),
     *                 required={"country_code","mobile_number", "code"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OTP verified successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="user",
     *                     type="object",
     *                     ref="#/components/schemas/UserResource"
     *                 )
     *             ),
     *             @OA\Property(property="token", type="string", example="Bearer token"),
     *             @OA\Property(property="expires_at", type="string", example="2024-06-24 12:34:56")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Invalid OTP code",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid Code"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="code",
     *                     type="array",
     *                     @OA\Items(type="string", example="Invalid Code")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function verifyOtp(Request $request)
    {
        $user = User::all()->where('mobile_number', $request->mobile_number)
            ->where('country_code',$request->country_code)
            ->first();

        if ($user && Cache::get("otp{$user->id}") == $request->code) {
            $tokenResult = $user->createToken('Laravel Personal Access Client');
            $token = $tokenResult->token;
            $token->expires_at = \Illuminate\Support\Carbon::now()->addMonth(1);
            $token->save();
            return response()->json([
                'data' => [
                    'user' => $user
                ],
                'token' => 'Bearer ' . $tokenResult->accessToken,
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString(),
            ], 200);
        }
        return response()->json([
            'message' => 'Invalid Code',
            "errors" => ["code" => ["Invalid Code"]]
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }


    /**
     * @OA\Schema(
     *     schema="Login",
     *     type="object",
     *     @OA\Property(property="country_code", type="string", example="98"),
     *     @OA\Property(property="mobile_number", type="string", example="9123456789"),
     * )
     * @OA\Post(
     *     path="/api/user/login",
     *     summary="Login user with mobile number",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="country_code",
     *                     type="string",
     *                     description="Country code of the user"
     *                 ),
     *                 @OA\Property(
     *                     property="mobile_number",
     *                     type="string",
     *                     description="Mobile number of the user"
     *                 ),
     *                 required={"country_code", "mobile_number"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User logged in successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object", ref="#/components/schemas/UserResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid credentials"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="credentials",
     *                     type="array",
     *                     @OA\Items(type="string", example="Invalid mobile number or password")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function login(Request $request)
    {
        $user = AuthService::login($request);
        if($user){
            return response()->json([
                'user' => new UserResource($user),
            ], Response::HTTP_OK);
        }else{
            return response()->json([
                'message' => 'Invalid credentials',
                "errors" => ["credentials" => ["Invalid mobile number or password"]]
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

    }


    /**
     * @OA\Schema(
     *     schema="ResendOtp",
     *     type="object",
     *     @OA\Property(property="country_code", type="string", example="98"),
     *     @OA\Property(property="mobile_number", type="string", example="9123456789"),
     * )
     * @OA\Post(
     *     path="/api/user/resend-otp",
     *     summary="Resend OTP code",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="country_code",
     *                     type="string",
     *                     description="Country code of the user"
     *                 ),
     *                 @OA\Property(
     *                     property="mobile_number",
     *                     type="string",
     *                     description="Mobile number of the user"
     *                 ),
     *                 required={"country_code", "mobile_number"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OTP resent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="OTP has been resent successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid credentials"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="credentials",
     *                     type="array",
     *                     @OA\Items(type="string", example="Invalid mobile number or country code")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function resendOtp(Request $request)
    {
        $credentials = $request->only('country_code', 'mobile_number');
        $user = User::where('mobile_number', $credentials['mobile_number'])
            ->where('country_code', $credentials['country_code'])
            ->first();

        if ($user) {
            $otp = '1234';  // در اینجا باید از یک سرویس واقعی ارسال پیامک استفاده کنید
            Cache::put("otp{$user->id}", $otp, now()->addMinutes(5));
            // event(new SmsOtp($otp, $credentials['country_code'] . $credentials['mobile_number']));

            return response()->json([
                'message' => 'OTP has been resent successfully'
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'Invalid credentials',
                "errors" => ["credentials" => ["Invalid mobile number or country code"]]
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }


    /**
     * Log the user out.
     *
     * @OA\Get(
     *     path="/api/user/logout",
     *     summary="Logout the user",
     *     tags={"Auth"},
     *     @OA\Response(
     *         response=200,
     *         description="User logged out successfully",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - User not logged in",
     *     ),
     *     security={{ "bearerAuth": {} }}
     * )
     */
    public function logout()
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized - User not logged in'], 401);
        }
        $token = $user->token();
        $token->revoke();
        return response()->json(['message' => 'User logged out successfully'], 200);
    }





}
