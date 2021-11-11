<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class UserController extends Controller
{
    public function test(){
        return 'user test';
    }
    public function post_index(){
        return Post::latest()->where('status', true)->get();
    }
    public function post_show($id){
        return Post::find($id);
    } 
}
