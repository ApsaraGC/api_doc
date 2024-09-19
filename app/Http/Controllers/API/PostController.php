<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data['posts']=Post::all();
        return response()->json([
            'status'=>true,
            'message'=>'All post data',
            'data'=>$data,

        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validateUser =Validator::make(
            $request->all(),
            [
                'title'=>'required',
                'description'=>'required',
                'image'=>'required|mimes:jpg,png,jpeg,gif,'
            ]
            );
            if($validateUser->fails()){
                return response()->json([
                    'status'=>false,
                    'message'=>'Validation Error',
                    'errors'=>$validateUser->errors()->all()

                ],401);
            }
            $img = $request->image;
$extension = $img->getClientOriginalExtension(); // Correct method name
$imageName = time().'.'.$extension;
$img->move(public_path('/uploads'), $imageName);


            $post =Post::create([
                'title'=>$request->title,
                'description'=>$request->description,
                'image'=>$imageName,
            ]);
            return response()->json([
                'status'=>True,
                'message'=>'Post created successfully',
                'user'=>$post,
               // 'errors'=>$validateUser->errors()->all()

            ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //for single post
        $data['posts']=Post::select(
            'id','title','description','image'
        )->where(['id'=>$id])->get();

        return response()->json([
            'status'=>True,
            'message'=>'Single post',
            'data'=>$data,


        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $validateUser =Validator::make(
            $request->all(),
            [
                'title'=>'required',
                'description'=>'required',
                'image'=>'required|mimes:jpg,png,jpeg,gif,'
            ]
            );
            if($validateUser->fails()){
                return response()->json([
                    'status'=>false,
                    'message'=>'Validation Error',
                    'errors'=>$validateUser->errors()->all()

                ],401);
            }
            $postImage =Post::select('id','image')
            ->where(['id'=>$id])->get();
//for delete old image
if ($request->image !='') {
    $path = public_path('/uploads');

    if ($postImage[0]->image != '' && $postImage[0]->image != null) {
        $old_file = $path . '/' . $postImage[0]->image;
        if (file_exists($old_file)) {
            unlink($old_file);
        }
    }

    $img = $request->image;
    $extension = $img->getClientOriginalExtension(); // Correct method name
    $imageName = time() . '.' . $extension;
    $img->move(public_path('/uploads'), $imageName);
} else {
    $imageName = $postImage->image;
}





            $post =Post::where(['id'=>$id])->update([
                'title'=>$request->title,
                'description'=>$request->description,
                'image'=>$imageName,
            ]);
            return response()->json([
                'status'=>True,
                'message'=>'Post updated successfully',
                'post'=>$post,
               // 'errors'=>$validateUser->errors()->all()

            ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $imagePath=Post::select('image')->where('id',$id)->get();
        $filePath =public_path().'/uploads/'.$imagePath[0]['image'];
        unlink($filePath);
        $post=Post::where('id',$id)->delete();


        return response()->json([
            'status'=>true,
            'message'=>'Your post has been removed',
            'post'=>$post,
        ]);
    }
}
