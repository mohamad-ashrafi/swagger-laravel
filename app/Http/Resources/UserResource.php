<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
/**
 * @OA\Schema(
 *     schema="UserResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="username", type="string", example="john_doe"),
 *     @OA\Property(property="country_code", type="string", example="98"),
 *     @OA\Property(property="mobile_number", type="string", example="9123456789"),
 *     @OA\Property(property="password", type="string", example="12345678"),
 *     @OA\Property(property="created_at", type="string", example="2024-06-24 12:34:56"),
 *     @OA\Property(property="updated_at", type="string", example="2024-06-24 12:34:56")
 * )
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);
        return [
            'id' => $this->id,
            'username'=>$this->username,
            'country_code' => $this->country_code,
            'mobile_number' => $this->mobile_number,
            'password' => $this->password,
            'created_at' => $this->created_at ,
            'updated_at' => $this->updated_at,
        ];
    }
}
