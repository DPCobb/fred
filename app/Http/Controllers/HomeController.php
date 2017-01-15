<?php
/**
 * Daniel Cobb
 * ASL - nmbley v1.0
 * 1-8-2017
 */

namespace app\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Response;
use App\Post;
use App\Comment;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * builds the home page
     * @return array [returns the array of data for the view]
     */
    public function homeView()
    {
        // get the user id
        $id = session('id');
        // get followed categories
        $cats = DB::table('follows')
        ->join('categorys', 'categorys.catId', '=', 'follows.catId')
        ->where('userId', $id)
        ->get();
        // get posts from followed
        $posts = DB::table('follows')
        ->join('posts', 'follows.catId', '=', 'posts.categoryId')
        ->join('categorys', 'follows.catId', '=', 'categorys.catId')
        ->join('users', 'follows.userId', '=', 'users.userId')
        ->select('follows.catId', 'posts.*', 'categorys.*', 'users.*')
        ->where('follows.userId', $id)
        ->latest()
        ->get();
        // get the comments for posts
        $comments = DB::table('comments')
        ->join('users', 'comments.userId', 'users.userId')
        ->select('comments.*', 'users.fname as first', 'users.lname as last')
        ->oldest()
        ->get();
        // get user likes
        $likes = DB::table('likes')
        ->select('likes.postId as likedPost')
        ->where('likes.userId', session('id'))
        ->get();
        // get replies
        $replies = DB::table('commentRelatives')
        ->join('comments', 'comments.commentId', 'commentRelatives.commentId')
        ->join('users', 'users.userId', 'comments.userId')
        ->oldest()
        ->get();
        return view('home', ['cats'=>$cats, 'posts'=>$posts, 'comments'=>$comments, 'likes'=>$likes, 'replies'=>$replies]);
    }
}
