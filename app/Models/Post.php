<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



/**
 * @OA\Schema(
 *     schema="Post",
 *     type="object",
 *     title="Post",
 *     description="A Post model",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID of the post",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="Title of the post",
 *         example="My first post"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="Description of the post",
 *         example="This is the description of the post"
 *     ),
 *     @OA\Property(
 *         property="image",
 *         type="string",
 *         description="Image filename",
 *         example="image.jpg"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Creation timestamp",
 *         example="2024-09-19T14:28:23Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Last update timestamp",
 *         example="2024-09-19T14:28:23Z"
 *     )
 * )
 */

class Post extends Model
{
    use HasFactory;
    protected $fillable=[
        'title','description','image'
    ];
}
