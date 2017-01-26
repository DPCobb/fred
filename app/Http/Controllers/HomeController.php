<?php
/**
 * Daniel Cobb
 * ASL - nmbley v2.0
 * 1-22-2017
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
        ->join('users', 'posts.user', '=', 'users.userId')
        ->leftJoin('likes', [['likes.postId', '=', 'posts.postId'], ['likes.userId', '=', 'follows.userId']])
        ->select('follows.catId', 'posts.*', 'categorys.*', 'users.*', 'likes.postId as liked', 'likes.userId as likedBy')
        ->where([['follows.userId', $id], ['posts.mod_del', null]])
        ->latest('posts.created_at')
        ->paginate(4);
        //->get();
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
        $admin = DB::table('mods')
        ->where('userId', $id)
        ->get();
        $msg = DB::table('messages')->where('reciever', session('id'))->get();
        return view('home', ['cats'=>$cats, 'posts'=>$posts, 'comments'=>$comments, 'likes'=>$likes, 'replies'=>$replies, 'admin'=>$admin, 'msg'=>$msg]);
    }
    public function links()
    {
        // get the user id
        $id = session('id');
        // get followed categories
        $cats = DB::table('follows')
        ->join('categorys', 'categorys.catId', '=', 'follows.catId')
        ->where('userId', $id)
        ->get();

        $admin = DB::table('categorys')
        ->where('adminId', $id)
        ->get();
        $msg = DB::table('messages')->where('reciever', session('id'))->get();
        return view('links', ['cats'=>$cats, 'admin'=>$admin, 'msg'=>$msg]);
    }
    public function homeBannedView($catban)
    {
        // get the user id
        $id = session('id');
        // return banned category
        $catJ = DB::table('categorys')->where('catId', $catban)->get();
        // get followed categories
        $cats = DB::table('follows')
        ->join('categorys', 'categorys.catId', '=', 'follows.catId')
        ->where('userId', $id)
        ->get();
        // get posts from followed
        $posts = DB::table('follows')
        ->join('posts', 'follows.catId', '=', 'posts.categoryId')
        ->join('categorys', 'follows.catId', '=', 'categorys.catId')
        ->join('users', 'posts.user', '=', 'users.userId')
        ->leftJoin('likes', [['likes.postId', '=', 'posts.postId'], ['likes.userId', '=', 'follows.userId']])
        ->select('follows.catId', 'posts.*', 'categorys.*', 'users.*', 'likes.postId as liked', 'likes.userId as likedBy')
        ->where([['follows.userId', $id], ['mod_del', null]])
        ->latest('posts.created_at')
        ->paginate(4);
        //->get();
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
        $admin = DB::table('categorys')
        ->where('adminId', $id)
        ->get();
        $msg = DB::table('messages')->where('reciever', session('id'))->get();
        return view('home', ['cats'=>$cats, 'posts'=>$posts, 'comments'=>$comments, 'likes'=>$likes, 'replies'=>$replies, 'admin'=>$admin, 'msg'=>$msg, 'banned'=>$catJ]);

    }
}
