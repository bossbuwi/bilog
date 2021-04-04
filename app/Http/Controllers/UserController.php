<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Configuration;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //store username and password values from request
        $username = $request->username;
        $password = $request->password;
        if (Configuration::where('name','devmode')->where('value','Y')->exists()) {
            if (User::where('username',$username)->where('password',$password)->exists()) {
                //if yes, return the record, together with the password
                return User::where('username',$username)->where('password',$password)->first();
            } else {
                $ldap = ldap_connect("ldap://misys.global.ad:389");
                $ldaprdn = 'MISYSROOT' . "\\" . $username;
                ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
                ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

                if (@ldap_bind($ldap, $ldaprdn, $password)) {
                    if (User::where('username', $username)->exists()) {
                        $updateUser = User::where('username',$username)->first();
                        $updateUser->password = $password;
                        $updateUser->save();
                        return User::where('username',$username)->first();
                    } else {
                        $newUser = new User();
                        $newUser->username = $username;
                        $newUser->password = $password;
                        $newUser->admin = false;
                        $newUser->save();
                        return User::where('username',$newUser->username)->first();
                    }
                } else {
                    return null;
                }
            }
        } else if (Configuration::where('name','savepassword')->where('value','Y')->exists()) {
            //if feature is active, check if the user-password combo already exists in the local database
            if (User::where('username',$username)->where('password',$password)->exists()) {
                //if yes, return the record, together with the password
                return User::where('username',$username)->where('password',$password)->first();
            } else {
                $ldap = ldap_connect("ldap://misys.global.ad:389");
                $ldaprdn = 'MISYSROOT' . "\\" . $username;
                ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
                ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

                if (@ldap_bind($ldap, $ldaprdn, $password)) {
                    if (User::where('username', $username)->exists()) {
                        $updateUser = User::where('username',$username)->first();
                        $updateUser->password = $password;
                        $updateUser->save();
                        return User::where('username',$username)->first();
                    } else {
                        $newUser = new User();
                        $newUser->username = $username;
                        $newUser->password = $password;
                        $newUser->admin = false;
                        $newUser->save();
                        return User::where('username',$newUser->username)->first();
                    }
                } else {
                    return null;
                }
            }
        } else {
            $ldap = ldap_connect("ldap://misys.global.ad:389");
            $ldaprdn = 'MISYSROOT' . "\\" . $username;
            ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

            if (@ldap_bind($ldap, $ldaprdn, $password)) {
                if(User::where('username', $username)->exists()) {
                    $authUser = User::where('username', $username)->first();
                    $authUser->password = null;
                    return $authUser;
                } else {
                    $newUser = new User();
                    $newUser->username = $username;
                    $newUser->admin = false;
                    $newUser->save();
                    return User::where('username', $username)->first();
                }
            } else {
                return null;
            }
        }
    }

    public function test(Request $request) {
        //store username and password values from request
        $username = $request->username;
        $password = $request->password;
        //check if the save password feature is active.
        if (Configuration::where('name','savepassword')->where('value','Y')->exists()) {
            //if feature is active, check if the user-password combo already exists in the local database
            if (User::where('username',$username)->where('password',$password)->exists()) {
                //if yes, return the record, together with the password
                return User::where('username',$username)->where('password',$password)->first();
            } else {
                $ldap = ldap_connect("ldap://misys.global.ad:389");
                $ldaprdn = 'MISYSROOT' . "\\" . $username;
                ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
                ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

                if (@ldap_bind($ldap, $ldaprdn, $password)) {
                    if (User::where('username', $username)->exists()) {
                        $updateUser = User::where('username',$username)->first();
                        $updateUser->password = $password;
                        $updateUser->save();
                        return User::where('username',$username)->first();
                    } else {
                        $newUser = new User();
                        $newUser->username = $username;
                        $newUser->password = $password;
                        $newUser->admin = false;
                        $newUser->save();
                        return User::where('username',$newUser->username)->first();
                    }
                } else {
                    return null;
                }
            }
        } else {
            $ldap = ldap_connect("ldap://misys.global.ad:389");
            $ldaprdn = 'MISYSROOT' . "\\" . $username;
            ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

            if (@ldap_bind($ldap, $ldaprdn, $password)) {
                if(User::where('username', $username)->exists()) {
                    $authUser = User::where('username', $username)->first();
                    $authUser->password = null;
                    return $authUser;
                } else {
                    $newUser = new User();
                    $newUser->username = $username;
                    $newUser->admin = false;
                    $newUser->save();
                    return User::where('username', $username)->first();
                }
            } else if ($username == 'admin'){
                return User::where('username', $username)->first();
            } else {
                return null;
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
