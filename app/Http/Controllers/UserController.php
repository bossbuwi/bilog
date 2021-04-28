<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource as UserResource;
use App\Models\Configuration;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return UserResource:: collection($users);
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
        //variable to temporarily store username and password values from request
        $username = $request->username;
        $password = $request->password;

        $errorUser = new User();
        $errorUser->username = '';
        $errorUser->password = '';
        $errorUser->admin = false;

        if ($password == NULL || $username == NULL) {
            return new UserResource($errorUser);
        } else if ($password == '' || $username == '') {
            return new UserResource($errorUser);
        } else if (ctype_space($password) || ctype_space($username)) {
            return new UserResource($errorUser);
        } else if (User::where('username', $username)->where('password', $password)->exists()) {
            $user = User::where('username', $username)->where('password', $password)->first();
            $user->password = '';
            return new UserResource($user);
        } else {
            $ldap = ldap_connect("ldap://misys.global.ad:389");
            $ldaprdn = 'MISYSROOT' . "\\" . $username;
            ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);

            if (@ldap_bind($ldap, $ldaprdn, $password)) {
                if (User::where('username', $username)->exists()) {
                    $user = User::where('username', $username)->first();
                    $user->password = '';
                    return new UserResource($user);
                } else {
                    $newUser = new User();
                    $newUser->username = $username;
                    $newUser->admin = false;
                    if ($newUser->save()) {
                        return new UserResource($newUser);
                    } else {
                        return new UserResource($errorUser);
                    }
                }
            } else {
                return new UserResource($errorUser);
            }
        }
    }

    public function show(Request $request)
    {
        $username = $request->username;
        if (User::where('username', $username)->exists()) {
            $user = User::where('username', $username)->first();
            if ($user->admin) {
                return 'true';
            } else {
                return 'false';
            }
        } else {
            return 'false';
        }
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

    public function updateAdmins(Request $request)
    {
        $itemCount = count($request->input('*.*.username'));
        if ($itemCount > 0) {
            for ($i = 0; $i < $itemCount; $i++) {
                if(User::where('id', $request->input('*.'.$i.'.id'))->exists()) {
                    $update = User::where('id', $request->input('*.'.$i.'.id'))->first();
                    $update->admin = $request->input('*.'.$i.'.admin')[0];
                    if ($update->save()) {

                    }
                }
            }
        }

        return true;
    }
}
