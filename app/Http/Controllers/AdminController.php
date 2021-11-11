<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;

use Auth;
use Image;
use File;

class AdminController extends Controller
{
    public function post_index(){
        return Post::latest()->get();
    }
    public function post_show($id){
        return Post::find($id);
    }    
    public function post_store(Request $request){
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
        ]);

        //Check for thumbnail which is nullable
        if($request->hasFile('thumbnail')){
            $file = $request->file('thumbnail');
            $extension = $file->getClientOriginalExtension();
            $file_name = time().'.'.$extension;
            Image::make($file)->resize(700, 400)->save(public_path('/images/post_img/'.$file_name));
        }
        else{
            $file_name = 'no_image.png';
        }

        $post = new Post;

        $post->title = $request->input('title');
        $post->description = $request->input('description');
        $post->user_id = Auth::user()->id;
        $post->thumbnail = $file_name;
        $post->status = true;

        $post->save();

        return $post;
    }
    public function post_update(Request $request, $id){
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
        ]);

        $post = Post::find($id);
        $old_thumbnail = $post->thumbnail;
        $user_id = $post->user_id;

        if(Auth::user()->id !== $user_id){
            return response([
                'message' => 'Unauthorized request'
            ], 401);
        }else{
            //Check for thumbnail which is nullable
            if($request->hasFile('thumbnail')){
                $file = $request->file('thumbnail');
                $extension = $file->getClientOriginalExtension();
                $file_name = time().'.'.$extension;
                Image::make($file)->resize(700, 400)->save(public_path('/images/post_img/'.$file_name));
                if($old_thumbnail != 'no_image.png'){
                    File::delete(public_path('/images/post_img/'.$old_thumbnail));
                }
            }
            else{
                $file_name = $old_thumbnail;
            }

            $post->title = $request->input('title');
            $post->description = $request->input('description');
            $post->thumbnail = $file_name;
            $post->status = $request->input('status');

            $post->save();

            return $post;

        }   

    }
    public function post_destroy($id){
        $post = Post::find($id);
        if(Auth::user()->id != $post->user_id){
            return response([
                'message' => 'Unauthorized request'
            ], 401);
        }else{
            $post->delete();
            return $post;
        }
    }
    //store new admin
    public function admin_store(Request $request){
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        // $user = User::create([
        //     'name' => $fields['name'],
        //     'email' => $fields['email'],
        //     'role' => 'admin',
        //     'password' => bcrypt($fields['password'])
        // ]);
        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->role = 'admin';
        $user->password = bcrypt($request->input('password'));
        $user->save();

        return response($user, 201);
    }
    
}
