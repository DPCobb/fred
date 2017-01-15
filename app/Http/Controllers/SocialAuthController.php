<?php

namespace app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\SocialAccountService;
use Socialite;

class SocialAuthController extends Controller
{
    /**
     * login with FB
     * @return array [data]
     */
    public function login()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * get user name and id
     * @param  integer $id   [user id from fb]
     * @param  string $name [user name from fb]
     * @return null
     */
    public function userCheck($id, $name)
    {
        // set vars
        $id = $id;
        $nameFull = $name;
        // if the user exists do nothing
        if (DB::table('users')->where('userId', $id)->value('userId')) {
        }
        // if the user does not exist add to db
        else {
            // explode the full name to get first and last names
            $name = explode(" ", $nameFull);
            // add user
            DB::table('users')->insert(
                ['userId' => $id, 'fname' => $name[0], 'lname' => $name[1]]
            );
        }
    }

    /**
     * get info from facebook and set session id
     * @return redirect [redirect to home page]
     */
    public function callback()
    {
        $providerUser = \Socialite::driver('facebook')->user();
        $nameFull = $providerUser->getName();
        $id = $providerUser->getId();
        session(['id' => $id]);
        $this->userCheck($id, $nameFull);
        return redirect('/home');
    }
}
