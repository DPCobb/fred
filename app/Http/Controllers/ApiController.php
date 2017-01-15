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
use App\Follow;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    /**
     * used to search for categories with a limit of 3 returned categories
     * @param  string $data [search values]
     * @return array       [search results]
     */
    public function categoryList($data)
    {
        // search for category names like $data
        $catList = DB::table('categorys')
        ->where('name', 'LIKE', '%' . $data . '%')
        ->limit(3)
        ->get();
        return response($catList);
    }

    /**
     * reply to comment
     * @param  integer $post   [post id]
     * @param  integer $parent [parent id]
     * @param  text $msg    [reply text]
     * @return null
     */
    public function reply($post, $parent, $msg)
    {
        $comment = new Comment;
        $comment->postId = $post;
        $comment->msg = $msg;
        $comment->userId = session('id');
        $comment->commentId = substr(hash('md5', time() . session('id')), 0, 10);
        $comment->parent = $parent;
        $comment->save();
    }

    /**
     * get post for editing
     * @param  integer $data [post id]
     * @return array       [post data]
     */
    public function getPost($data)
    {
        $post = DB::table('posts')
        ->where('postId', $data)
        ->join('categorys', 'categorys.catId', 'posts.categoryId')
        ->get();
        return response($post);
    }

    /**
     * get comment for edits
     * @param  integer $data [comment id]
     * @return array       [comment data]
     */
    public function getComment($data)
    {
        $comment = DB::table('comments')
        ->where('commentId', $data)
        ->get();
        return response($comment);
    }

    /**
     * used to update photo post - currently not used
     * @param  integer $id       [post id]
     * @param  string $title    [post title]
     * @param  string $category [category name]
     * @return string           [success message]
     */
    public function editPhoto($id, $title, $category)
    {
        $catid = DB::table('categorys')->select('catId')->where('name', $category)->get();

        DB::table('posts')
        ->where('id', $id)
        ->update(['title'=>$title, 'category'=>$catId]);
        return response('success');
    }

    /**
     * search for categories with 10 returned values
     * @param  string $data [search terms]
     * @return array       [search results]
     */
    public function search($data)
    {
        $list = DB::table('categorys')
        ->where('name', 'LIKE', '%' . $data . '%')
        ->limit(10)
        ->get();
        return response($list);
    }

    /**
     * follows a category
     * @param  Request $request [form data]
     * @return null
     */
    public function follow(Request $request)
    {
        $user = session('id');
        $follow = new Follow;
        $follow->userId = $user;
        $follow->catId = $request->id;
        $follow->save();
    }

    /**
     * unfollow a category
     * @param  Request $request [form data]
     * @return null
     */
    public function unfollow(Request $request)
    {
        $user = session('id');
        $id = $request->id;
        DB::table('follows')->where('catId', $id)->where('userId', $user)->delete();
    }
}
