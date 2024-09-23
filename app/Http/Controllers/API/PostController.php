<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 */
class PostController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/posts",
     *     tags={"Posts"},
     *     summary="Get all posts",
     *     security={{"bearerAuth":{}}},
     *     description="Fetch a list of all posts",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Post"))
     *         )
     *     )
     * )
     */
    public function index()
    {
        //
        $data['posts'] = Post::all();
        return response()->json([
            'status' => true,
            'message' => 'All post data',
            'data' => $data,

        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/posts",
     *     tags={"Posts"},
     *     summary="Create a new post",
     *       security={{"bearerAuth":{}}},
     *     description="Store a new post in the database",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title", "description", "image"},
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="image", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="post", ref="#/components/schemas/Post")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="errors", type="array", @OA\Items(type="string"))
     *         )
     *     )
     * )
     */

    public function store(Request $request)
    {
        //
        $validateUser = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'description' => 'required',
                'image' => 'required|mimes:jpg,png,jpeg,gif,'
            ]
        );
        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validateUser->errors()->all()

            ], 401);
        }
        $img = $request->image;
        $extension = $img->getClientOriginalExtension(); // Correct method name
        $imageName = time() . '.' . $extension;
        $img->move(public_path('/uploads'), $imageName);


        $post = Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imageName,
        ]);
        return response()->json([
            'status' => True,
            'message' => 'Post created successfully',
            'user' => $post,
            // 'errors'=>$validateUser->errors()->all()

        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/posts/{id}",
     *     tags={"Posts"},
     *      security={{"bearerAuth":{}}},
     *     summary="Get a single post",
     *     description="Fetch details of a single post by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Single post data",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", ref="#/components/schemas/Post")
     *         )
     *     )
     * )
     */
    public function show(string $id)
    {
        //for single post
        $data['posts'] = Post::select(
            'id',
            'title',
            'description',
            'image'
        )->where(['id' => $id])->get();

        return response()->json([
            'status' => True,
            'message' => 'Single post',
            'data' => $data,


        ], 200);
    }

/**
 * @OA\Put(
 *     path="/api/posts/{id}",
 *     summary="Update a post",
 *     tags={"Posts"},
 * security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the post to update",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="title",
 *                     type="string",
 *                     description="Title of the post"
 *                 ),
 *                 @OA\Property(
 *                     property="description",
 *                     type="string",
 *                     description="Description of the post"
 *                 ),
 *                 @OA\Property(
 *                     property="image",
 *                     type="string",
 *                     format="binary",
 *
 *                 ),
 *                 required={"title", "description"}
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Post updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Post updated successfully"),
 *             @OA\Property(
 *                 property="post",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="title", type="string", example="New Title"),
 *                 @OA\Property(property="description", type="string", example="Updated description"),
 *                 @OA\Property(property="image", type="string", example="image.jpg")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Validation Error",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Validation Error"),
 *             @OA\Property(property="errors", type="array", @OA\Items(type="string"))
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Post not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Post not found")
 *         )
 *     )
 * )
 */

public function update(Request $request, string $id)
    {
        // Validate input
       // return($request->all);
        $validatedData = Validator::make(
            $request->all(),
            [
                'title' => 'required|string',
                'description' => 'required|string',
                'image' => 'nullable'
            ]
        );

        if ($validatedData->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validatedData->errors()->all()
            ], 401);
        }

        // Fetch the post to update
        $post = Post::find($id);
        if (!$post) {
            return response()->json([
                'status' => false,
                'message' => 'Post not found'
            ], 404);
        }

        // Update the title and description
        $post->title = $request->input('title');
        $post->description = $request->input('description');

        // Handle image upload if present
        if ($request->hasFile('image')) {
            $img = $request->file('image');
            $extension = $img->getClientOriginalExtension();
            $imageName = time() . '.' . $extension;
            $img->move(public_path('/uploads'), $imageName);

            // Delete old image
            if ($post->image) {
                $oldImagePath = public_path('/uploads/' . $post->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // Update image field
            $post->image = $imageName;
        }

        // Save the updated post
        $post->save();

        return response()->json([
            'status' => true,
            'message' => 'Post updated successfully',
            'post' => $post
        ], 200);
    }




    /**
     * @OA\Delete(
     *     path="/api/posts/{id}",
     *     tags={"Posts"},
     *
     *     summary="Delete a post",
     *     security={{"bearerAuth":{}}},
     *     description="Remove a post from the database",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function destroy(string $id)
    {
        //
        $imagePath = Post::select('image')->where('id', $id)->get();
        $filePath = public_path() . '/uploads/' . $imagePath[0]['image'];
        unlink($filePath);
        $post = Post::where('id', $id)->delete();
        return response()->json([
            'status' => true,
            'message' => 'Your post has been removed',
            'post' => $post,
        ]);
    }
}
