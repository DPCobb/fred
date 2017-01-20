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

class MessageController extends Controller
{
    /**
     * builds the home page
     * @return array [returns the array of data for the view]
     */
    public function messageView()
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
        $msg = DB::table('messages')->where([['reciever', $id], ['readdel', 0]])
        ->orWhere([['sender', $id], ['senddel', 0]])
        ->join('users', 'users.userId', 'messages.sender')
        ->select('messages.*', 'users.lname as senderlast', 'users.fname as senderfirst' )
        ->orderBy('id', 'desc')
        ->get();
        return view('messages', ['cats'=>$cats, 'admin'=>$admin, 'msgs'=>$msg, 'replys'=>$msg]);
    }
}
