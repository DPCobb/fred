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
use App\Mod;
use App\Ban;
use App\Message;
use Illuminate\Support\Facades\DB;

class ModController extends Controller
{
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

    public function modView($id)
    {
        $currentCategory = $id;
        $user = session('id');
        $categoryInfo = DB::table('categorys')
        ->where([['adminId', $user],['catId', $currentCategory]])
        ->get();
        if(!empty($categoryInfo)){
            return $this->buildView($id);
        }
        else{
            return redirect('/home');
        }
    }


    public function unflag(Request $request)
    {
        $postId = $request->id;
        DB::table('posts')
        ->where('postId', $postId)
        ->update(['flagged'=>null]);
    }

    public function addMod(Request $request)
    {
        $user = $request->id;
        $catId = $request->catId;
        $mod = new Mod;
        $mod->catId = $catId;
        $mod->userId = $user;
        $mod->adminAdd = session('id');
        $mod->save();
    }

    public function removeMod(Request $request)
    {
        $user = $request->user;
        $cat = $request->cat;
        $remove = DB::table('mods')->where([['catId', $cat], ['userId', $user]])->delete();
        return response($remove);
    }

    public function getMods($cat)
    {
        $category = $cat;
        $mods = DB::table('mods')
        ->where('catId', $category)
        ->get();
        return($mods);
    }
    public function getBans($cat)
    {
        $category = $cat;
        $bans = DB::table('bans')
        ->where('catId', $category)
        ->get();
        return($bans);
    }

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
    public function unbanUser(Request $request)
    {
        DB::table('bans')
        ->where([['catId', $request->cat], ['userId', $request->user]])
        ->delete();
    }
    public function delPost(Request $request)
    {
        DB::table('posts')
        ->where('postId', $request->post)
        ->update(['mod_del' => 1]);
    }

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

    public function deleteComment(Request $request)
    {
        // delete from db
        DB::table('comments')->where('commentId', $request->id)->delete();

    }


}
