<?php

namespace App\Controllers;

use TgoeSrv\Member\Api\UserLoginHelper;
use GuzzleHttp;

class Login extends BaseController
{
    
    public function index()
    {
        $data = [];

        //in case logins provided, try to login
        $usr = $this->request->getPost('loginform-usr');
        $pwd = $this->request->getPost('loginform-pwd');
        if( isset($usr) && isset($pwd) ) {
            $h = new UserLoginHelper();
            $userdata = $h->verifyUserLogin($usr, $pwd);
            
            if( is_object($userdata)) {
                //if credetials successfull, set session as logged in
                //and redirect to admin home.
                session()->set('userdata', $userdata);
                
                return redirect()->route('admin');
            }
            
            $data['messages'][] = 'Benutzername/Passwort falsch.';
        }
        
        return view('login', $data);
    }
    
    public function logout(): string
    {
        session()->remove('userdata');
        
        $data = [
            'messages' => ['Sie wurden erfolgreich abgemeldet.']
        ];
        
        return view('login', $data);
    }
}
