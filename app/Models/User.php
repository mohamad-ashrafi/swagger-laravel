<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable , HasApiTokens;

    /**
     * @OA\Schema(
     *     schema="User",
     *     type="object",
     *     title="User",
     *     required={"username", "country_code" , "mobile_number" , "password"},
     *     @OA\Property(
     *         property="id",
     *         type="integer",
     *         description="User ID",
     *         example="1"
     *     ),
     *     @OA\Property(
     *         property="username",
     *         type="string",
     *         description="User Name",
     *         example="mohamad ashrafi"
     *     ),
     *     @OA\Property(
     *         property="country_code",
     *         type="integer",
     *         description="User Country Code",
     *         example="98"
     *     ),
     *     @OA\Property(
     *         property="mobile_number",
     *         type="integer",
     *         description="User Phone Number",
     *         example="9102403100"
     *     ),
     *     @OA\Property(
     *         property="password",
     *         type="integer",
     *         description="User Password",
     *         example="12345678"
     *     ),
     *     @OA\Property(
     *         property="created_at",
     *         type="string",
     *         format="date-time",
     *         description="Creation date"
     *     ),
     *     @OA\Property(
     *         property="updated_at",
     *         type="string",
     *         format="date-time",
     *         description="Update date"
     *     )
     * )
     */
    protected $fillable = [
        'username',
        'country_code',
        'mobile_number',
        'password',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
