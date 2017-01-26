<?php
/**
 * Daniel Cobb
 * ASL - nmbley v3.0
 * 1-25-2017
 */

namespace app\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Response;
use App\Post;
use App\Comment;
use App\Mod;
use App\Ban;
use App\Message;
use Illuminate\Support\Facades\DB;

class ModController extends Controller
{
    /**
     * returns the mod view
     * @param  integer $id [category id]
     * @return array     [view]
     */
    private function buildView($id)
    {
        $category = $id;
        $user = session('id');
        $posts = DB::table('posts')
        ->join('users', 'users.userId', 'posts.user')
        ->select('users.fname as firstname', 'users.lname as lastname', 'posts.*')
        ->where([['categoryId', $category], ['mod_del', null]])
        ->latest()
        ->paginate(5);

        $categoryName = DB::table('categorys')
        ->select('categorys.name')
        ->where('catId', $category)
        ->get();

        $msg = DB::table('messages')->where('reciever', $user)->get();

        $comments = DB::table('comments')
        ->join('users', 'comments.userId', 'users.userId')
        ->select('comments.*', 'users.fname as first', 'users.lname as last')
        ->oldest()
        ->get();

        $replies = DB::table('commentRelatives')
        ->join('comments', 'comments.commentId', 'commentRelatives.commentId')
        ->join('users', 'users.userId', 'comments.userId')
        ->oldest()
        ->get();

        $mods = DB::table('mods')
        ->where('catId', $category)
        ->join('users', 'users.userId', 'mods.userId')
        ->select('mods.*', 'users.fname', 'users.lname')
        ->get();

        return view('mod', ['posts'=>$posts, 'categoryName'=>$categoryName, 'catId'=>$category, 'msgs'=>$msg, 'comments'=>$comments, 'replies'=>$replies, 'mods'=>$mods]);
    }

    /**
     * calls buildView if the user is an admin
     * @param  integer $id [category id]
     * @return array/redirect     [returns view or a redirect to home]
     */
    public function modView($id)
    {
        $currentCategory = $id;
        $user = session('id');
        $categoryInfo = DB::table('mods')
        ->where([['userId', $user],['catId', $currentCategory]])
        ->get();
        if(!empty($categoryInfo)){
            return $this->buildView($id);
        }
        else{
            return redirect('/home');
        }
    }

    /**
     * removes a flag on reported post
     * @param  Request $request [form data]
     * @return null
     */
    public function unflag(Request $request)
    {
        $postId = $request->id;
        DB::table('posts')
        ->where('postId', $postId)
        ->update(['flagged'=>null]);
    }

    /**
     * adds a moderator
     * @param Request $request [form data]
     */
    public function addMod(Request $request)
    {
        $name = DB::table('categorys')->select('name')->where('catId', $request->catId)->first();
        $user = $request->id;
        $catId = $request->catId;
        $mod = new Mod;
        $mod->catId = $catId;
        $mod->userId = $user;
        $mod->name = $name->name;
        $mod->adminAdd = session('id');
        $mod->save();
    }

    /**
     * removes a moderator
     * @param  Request $request [form data]
     * @return array           [response]
     */
    public function removeMod(Request $request)
    {
        $user = $request->user;
        $cat = $request->cat;
        $remove = DB::table('mods')->where([['catId', $cat], ['userId', $user]])->delete();
        return response($remove);
    }

    /**
     * retrieves a list of mods
     * @param  integer $cat [category id]
     * @return array      [array of mods]
     */
    public function getMods($cat)
    {
        $category = $cat;
        $mods = DB::table('mods')
        ->where('catId', $category)
        ->get();
        return($mods);
    }

    /**
     * returns banned users
     * @param  integer $cat [category id]
     * @return array      [banned users]
     */
    public function getBans($cat)
    {
        $category = $cat;
        $bans = DB::table('bans')
        ->where('catId', $category)
        ->get();
        return($bans);
    }

    /**
     * post as a moderator
     * @param  Request $request [form data]
     * @return null
     */
    public function modPost(Request $request)
    {
        $catJ = DB::table('categorys')->select('catId')->where('name', strtolower($request->cat))->first();
        $post = new Post;
        $post->title = $request->title;
        $post->type = 2;
        $post->likes = 0;
        $post->user = session('id');
        $post->text = $request->post;
        $post->categoryId = $request->cat;
        $post->postId = hash('md5', time() . $request->title);
        $post->save();
    }

    /**
     * bans a user
     * @param  Request $request [form data]
     * @return null
     */
    public function banUser(Request $request)
    {
        $user = $request->user;
        $category = $request->cat;
        $admin = session('id');
        $ban = new Ban;
        $ban->catId = $category;
        $ban->banner = $admin;
        $ban->userId = $user;
        $ban->save();
    }

    /**
     * unbans a user
     * @param  Request $request [form data]
     * @return null
     */
    public function unbanUser(Request $request)
    {
        DB::table('bans')
        ->where([['catId', $request->cat], ['userId', $request->user]])
        ->delete();
    }

    /**
     * deletes a users post
     * @param  Request $request [form data]
     * @return null
     */
    public function delPost(Request $request)
    {
        DB::table('posts')
        ->where('postId', $request->post)
        ->update(['mod_del' => 1]);
    }

    /**
     * sends a preformatted delete message after post delete
     * @param  Request $request [form data]
     * @return null
     */
    public function sendDelMessage(Request $request)
    {
        $postTitle = DB::table('posts')->select('title')->where('postId', $request->postId)->get();
        $text = "It looks like your post, ".$postTitle[0]->title.", was deleted for the following reason: ".$request->reason.". You will still be able to see your posts in My Posts, but it will no longer be shown in the category.";
        $sender = session('id');
        $message = new Message;
        $message->msgId = hash('md5', time() . $sender);
        $message->sender = $sender;
        $message->reciever = $request->user;
        $message->subject = $request->title;
        $message->text = $text;
        $message->parentId = 0;
        $message->read = 0;
        $message->senddel = 0;
        $message->readdel = 0;
        $message->save();
    }

    /**
     * deletes a comment
     * @param  Request $request [form data]
     * @return null
     */
    public function deleteComment(Request $request)
    {
        // delete from db
        DB::table('comments')->where('commentId', $request->id)->delete();

    }


}
