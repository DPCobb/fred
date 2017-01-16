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
use App\Post;
use App\Comment;
use Illuminate\Support\Facades\DB;

class MyContent extends Controller
{
    /**
     * returns the view for my posts page
     * @return array [array of data for the view]
     */
    public function getInfo()
    {
        // get id
        $myId = session('id');
        // get categories followed
        $cats = DB::table('follows')
        ->join('categorys', 'categorys.catId', '=', 'follows.catId')
        ->where('userId', $myId)
        ->get();
        // get user posts
        $myPosts = DB::table('posts')
        ->join('categorys', 'posts.categoryId', '=', 'categorys.catId')
        ->join('users', 'posts.user', '=', 'users.userId')
        ->leftJoin('likes', [['likes.postId', '=', 'posts.postId'], ['likes.userId', '=', 'users.userId']])
        ->select('posts.*', 'categorys.*', 'users.*', 'likes.postId as liked', 'likes.userId as likedBy')
        ->where('posts.user', $myId)
        ->latest('posts.updated_at')
        ->get();
        // get comments
        $comments = DB::table('comments')
        ->join('users', 'comments.userId', 'users.userId')
        ->select('comments.*', 'users.fname as first', 'users.lname as last')
        ->oldest()
        ->get();
        //get user likes
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
        $admin = DB::table('categorys')
        ->where('adminId', $myId)
        ->get();
        return view('myposts', ['cats'=>$cats, 'posts'=>$myPosts, 'comments'=>$comments, 'likes'=>$likes, 'replies'=>$replies, 'admin'=>$admin]);
    }

    /**
     * deletes a users posts
     * @param  integer $id [post id]
     * @return redirect     [redirects to my posts page]
     */
    public function deleteInfo($id)
    {
        DB::table('posts')->where('postId', $id)->where('user', session('id'))->delete();
        return redirect('/activity/myposts');
    }
}
