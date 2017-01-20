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
use App\Like;
use App\Comment;
use App\Follow;
use App\Category;
use App\Message;
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
    public function reply(Request $request)
    {
        $comment = new Comment;
        $comment->postId = $request->postId;
        $comment->msg = $request->msg;
        $comment->userId = session('id');
        $comment->commentId = substr(hash('md5', time() . session('id')), 0, 10);
        $comment->parent = $request->parent;
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
    public function like(Request $request)
    {
        $user = session('id');
        $like = new Like;
        $like->postId = $request->id;
        $like->userId = $user;
        $like->save();

    }
    public function unlike(Request $request)
    {
        $user = session('id');
        $post = $request->id;
        $unlike = DB::table('likes')->where([['postId', $post], ['userId', $user]])->delete();
        return response($unlike);
    }
    public function newCategory(Request $request)
    {
        $newcategory = $request->name;
        if(DB::table('categorys')->where('name', $newcategory)->value('name')){
            return 'This category already exists.';
        }
        else{
            $category = new Category;
            $category->adminId = session('id');
            $category->name = $newcategory;
            $category->catId = hash('md5', time() . $newcategory);
            $category->save();
            return 'Category Created!';
        }
    }
    public function sendMessage(Request $request)
    {
        $sender = session('id');
        $message = new Message;
        $message->msgId = hash('md5', time() . $sender);
        $message->sender = $sender;
        $message->reciever = $request->recieve;
        $message->subject = $request->subject;
        $message->text = $request->message;
        $message->parentId = 0;
        $message->read = 0;
        $message->senddel = 0;
        $message->readdel = 0;
        $message->save();
        return 'Message Sent';

    }

    public function gotMail()
    {
        $msg = DB::table('messages')->where([['reciever', session('id')], ['read', '0']])->get();
        return($msg);
    }
    public function read(Request $request)
    {
        DB::table('messages')
        ->where('msgId', $request->id)
        ->update(['read'=>1]);
        return 'success';
    }
    public function deleteMsgR(Request $request)
    {
        DB::table('messages')
        ->where('msgId', $request->id)
        ->update(['readdel'=>1, 'read'=>1]);
        return 'success';
    }
    public function deleteMsgS(Request $request)
    {
        DB::table('messages')
        ->where('msgId', $request->id)
        ->update(['senddel'=>1]);
        return 'success';
    }

    public function replyMessage(Request $request)
    {
        $sender = session('id');
        $message = new Message;
        $message->msgId = hash('md5', time() . $sender);
        $message->sender = $sender;
        $message->reciever = $request->recieve;
        $message->parentId = $request->parent;
        $message->text = $request->message;
        $message->read = 2;
        $message->senddel = 0;
        $message->readdel = 0;
        $message->save();
        DB::table('messages')
        ->where('msgId', $request->parent)
        ->update(['read'=>0]);
        return 'Message Sent';
    }
}
