<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Post",
 *     type="object",
 *     title="Post",
 *     required={"user_id" , "title", "body"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Post ID"
 *     ),
 *     @OA\Property(
 *         property="user_id",
 *         type="integer",
 *         description="User ID"
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="Post title"
 *     ),
 *     @OA\Property(
 *         property="body",
 *         type="string",
 *         description="Post content"
 *     ),
 *     @OA\Property(
 *         property="like",
 *         type="string",
 *         description="Post Like"
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
class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'body', 'like'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
