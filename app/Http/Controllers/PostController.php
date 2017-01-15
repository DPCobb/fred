<?php

namespace app\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Post;
use App\Comment;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{

    /**
     * Add a new post or comment
     * @param  string  $type    [post type]
     * @param  Request $request [form data]
     * @return redirect           [redirects to home]
     */
    public function post($type, Request $request)
    {
        // text post
        if ($type == 'text') {
            // validate
            $validator = Validator::make($request->all(), [
                'title' => 'required|max:240',
                'post' => 'required',
                'cat'=> 'required|max:50',
            ]);
            if ($validator->fails()) {
                return redirect('/home')
                    ->withInput()
                    ->withErrors($validator);
            }
            // add to db
            $catJ = DB::table('categorys')->select('catId')->where('name', strtolower($request->cat))->first();
            $post = new Post;
            $post->title = $request->title;
            $post->type = 1;
            $post->likes = 0;
            $post->user = session('id');
            $post->categoryId = $catJ->catId;
            $post->text = $request->post;
            $post->postId = hash('md5', time() . $request->title);
            $post->save();
            return redirect('/home');

        // image post
        } elseif ($type == 'image') {
            //validate
            $validator = Validator::make($request->all(), [
                'title' => 'required|max:240',
                'pic' => 'required',
                'cat'=> 'required|max:50',
            ]);
            if ($validator->fails()) {
                return redirect('/home')
                    ->withInput()
                    ->withErrors($validator);
            }
            // prepare the image for upload
            $image = ' ';
            $dir = "./userImages/";
            $tempName = $_FILES['pic']['tmp_name'];
            $uploadName = $dir . basename($_FILES['pic']['name']);
            $fileType = $_FILES['pic']['tmp_name'];
            // upload if jpg or png
            if (exif_imagetype($fileType) == IMAGETYPE_JPEG) {
                if (move_uploaded_file($tempName, $uploadName)) {
                    $image = $uploadName;
                }
            } elseif (exif_imagetype($fileType) == IMAGETYPE_PNG) {
                if (move_uploaded_file($tempName, $uploadName)) {
                    $image = $uploadName;
                }
            }
            // add info to db
            $catJ = DB::table('categorys')->select('catId')->where('name', strtolower($request->cat))->first();
            $post = new Post;
            $post->title = $request->title;
            $post->type = 5;
            $post->likes = 0;
            $post->user = session('id');
            $post->categoryId = $catJ->catId;
            $post->image = $image;
            $post->postId = hash('md5', time() . $request->title);
            $post->save();
            return redirect('/home');
        // comment
        } elseif ($type == 'comment') {
            //validate
            $validator = Validator::make($request->all(), [
                'newcom' => 'required',
                'postid' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect('/home')
                    ->withInput()
                    ->withErrors($validator);
            }
            // add comment to db
            $comment = new Comment;
            $comment->postId = $request->postid;
            $comment->msg = $request->newcom;
            $comment->userId = session('id');
            $comment->commentId = substr(hash('md5', time() . session('id')), 0, 10);
            $comment->save();
            return redirect('/home');
        } else {
        }
    }
    /**
     * Delete comment removes a comment
     * @param  integer $id [comment id]
     * @return redirect     [redirect to home]
     */
    public function deleteComment($id)
    {
        // delete from db
        DB::table('comments')->where('commentId', $id)->delete();
        return redirect('/home');
    }
    /**
     * Returns the data needed to construct the category page /c/category
     * @param  string $category [category name]
     * @return array           [array of data]
     */
    public function catHome($category)
    {
        // get info needed to build category page
        $current = $category;
        $myId = session('id');
        $catId = DB::table('categorys')
        ->select('categorys.catId')
        ->where('categorys.name', $current)
        ->get();
        $cats = DB::table('follows')
        ->join('categorys', 'categorys.catId', '=', 'follows.catId')
        ->where('userId', $myId)
        ->get();
        $myPosts = DB::table('posts')
        ->join('categorys', 'posts.categoryId', '=', 'categorys.catId')
        ->join('users', 'posts.user', '=', 'users.userId')
        ->select('posts.*', 'categorys.*', 'users.*')
        ->where('categorys.name', $category)
        ->latest()
        ->get();
        $comments = DB::table('comments')
        ->join('users', 'comments.userId', 'users.userId')
        ->select('comments.*', 'users.fname as first', 'users.lname as last')
        ->oldest()
        ->get();
        $likes = DB::table('likes')
        ->select('likes.postId as likedPost')
        ->where('likes.userId', session('id'))
        ->get();
        $replies = DB::table('commentRelatives')
        ->join('comments', 'comments.commentId', 'commentRelatives.commentId')
        ->join('users', 'users.userId', 'comments.userId')
        ->oldest()
        ->get();
        $follows = DB::table('follows')
        ->where([['follows.catId', '=', $catId[0]->catId], ['follows.userId', '=', $myId]])
        ->get();
        // if the category does not exist
        if ($catId->isEmpty()) {
            return view('nocategory', ['cats'=>$cats, 'posts'=>$myPosts, 'comments'=>$comments, 'likes'=>$likes, 'categoryname'=>$current, 'categoryid'=>$catId[0]->catId, 'follows'=>$follows]);
        } else {
            return view('category', ['cats'=>$cats, 'posts'=>$myPosts, 'comments'=>$comments, 'likes'=>$likes, 'categoryname'=>$current, 'replies'=>$replies, 'categoryid'=>$catId[0]->catId, 'follows'=>$follows]);
        }
    }

    /**
     * updates a photo post
     * @param  Request $request [form data]
     * @return redirect           [redirect back to my posts]
     */
    public function editPhoto(Request $request)
    {
        // edit photo, validate
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:240',
            'cat'=> 'required|max:50',
        ]);
        if ($validator->fails()) {
            return redirect('/activity/myposts')
                ->withInput()
                ->withErrors($validator);
        }
        // set vars
        $catid = DB::table('categorys')->where('name', $request->cat)->get();
        $cat = $catid[0]->catId;
        $id = $request->postid;
        $title = $request->title;
        //update
        DB::table('posts')
        ->where('postId', $id)
        ->update(['title'=>$title, 'categoryId'=>$cat]);
        return redirect('/activity/myposts');
    }

    /**
     * update a text post
     * @param  Request $request [form data]
     * @return redirect           [redirect to my posts]
     */
    public function editText(Request $request)
    {
        // text update, validate
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:240',
            'post' => 'required',
            'cat'=> 'required|max:50',
        ]);
        if ($validator->fails()) {
            return redirect('/home')
                ->withInput()
                ->withErrors($validator);
        }
        // set vars
        $catid = DB::table('categorys')->where('name', $request->cat)->get();
        $cat = $catid[0]->catId;
        $id = $request->postid;
        $title = $request->title;
        $post = $request->post;
        // update
        DB::table('posts')
        ->where('postId', $id)
        ->update(['title'=>$title, 'categoryId'=>$cat, 'text'=>$post]);
        return redirect('/activity/myposts');
    }

    /**
     * edits a comment
     * @param  Request $request [form data]
     * @return redirect           [redirect to home]
     */
    public function editComment(Request $request)
    {
        // edit comment, validate
        $validator = Validator::make($request->all(), [
            'editcom' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect('/home')
                ->withInput()
                ->withErrors($validator);
        }
        //set vars
        $id = $request->commentid;
        $msg = $request->editcom;
        //update
        DB::table('comments')
        ->where('commentId', $id)
        ->update(['msg'=>$msg]);
        return redirect('/home');
    }
}
