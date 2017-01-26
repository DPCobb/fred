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

    /**
     * like a post
     * @param  Request $request [array]
     * @return null
     */
    public function like(Request $request)
    {
        $user = session('id');
        $like = new Like;
        $like->postId = $request->id;
        $like->userId = $user;
        $like->save();

    }

    /**
     * unlike a post
     * @param  Request $request [array]
     * @return null
     */
    public function unlike(Request $request)
    {
        $user = session('id');
        $post = $request->id;
        $unlike = DB::table('likes')->where([['postId', $post], ['userId', $user]])->delete();
        return response($unlike);
    }

    /**
     * create a new category
     * @param  Request $request [array]
     * @return string           [success/failure message]
     */
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

    /**
     * send a message to a user
     * @param  Request $request [array]
     * @return string           [success message]
     */
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

    /**
     * check if a user has unread mail
     * @return array [user messages]
     */
    public function gotMail()
    {
        $msg = DB::table('messages')->where([['reciever', session('id')], ['read', '0']])->get();
        return($msg);
    }

    /**
     * mark a message as read
     * @param  Request $request [array]
     * @return string           [success message]
     */
    public function read(Request $request)
    {
        DB::table('messages')
        ->where('msgId', $request->id)
        ->update(['read'=>1]);
        return 'success';
    }

    /**
     * marked as deleted by reader
     * @param  Request $request [array]
     * @return string           [success message]
     */
    public function deleteMsgR(Request $request)
    {
        DB::table('messages')
        ->where('msgId', $request->id)
        ->update(['readdel'=>1, 'read'=>1]);
        return 'success';
    }

    /**
     * marked as deleted by sender
     * @param  Request $request [array]
     * @return string           [success message]
     */
    public function deleteMsgS(Request $request)
    {
        DB::table('messages')
        ->where('msgId', $request->id)
        ->update(['senddel'=>1]);
        return 'success';
    }

    /**
     * add a message reply
     * @param  Request $request [array]
     * @return string           [success message]
     */
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
        $contents = view('common/postdisplay',['cats'=>$cats, 'posts'=>$posts, 'comments'=>$comments, 'likes'=>$likes, 'replies'=>$replies, 'admin'=>$admin, 'msg'=>$msg])->render();
        return ($contents);
    }

    public function getCount()
    {
        $id = session('id');
        $posts = DB::table('follows')
        ->join('posts', 'follows.catId', '=', 'posts.categoryId')
        ->join('categorys', 'follows.catId', '=', 'categorys.catId')
        ->join('users', 'posts.user', '=', 'users.userId')
        ->leftJoin('likes', [['likes.postId', '=', 'posts.postId'], ['likes.userId', '=', 'follows.userId']])
        ->select('follows.catId', 'posts.*', 'categorys.*', 'users.*', 'likes.postId as liked', 'likes.userId as likedBy')
        ->where('follows.userId', $id)
        ->latest('posts.created_at')
        ->paginate();
        return $posts->count();

    }

    public function report(Request $request)
    {
        $postId = $request->id;
        DB::table('posts')
        ->where('postId', $postId)
        ->update(['flagged'=>1]);
    }



}
