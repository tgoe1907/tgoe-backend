<?php
namespace App\Controllers;

use App\Libraries\CIHelper;
use TgoeSrv\Member\Api\UserLoginHelper;

class Home extends BaseController
{
    public function index()
    {
        $ci = new CIHelper();

        $data = [];

        // in case logins provided, try to login
        $usr = $this->request->getPost('loginform-usr');
        $pwd = $this->request->getPost('loginform-pwd');
        if (isset($usr) && isset($pwd)) {
            $h = new UserLoginHelper();
            $userdata = $h->verifyUserLogin($usr, $pwd);

            if (is_object($userdata)) {
                // if credetials successfull, set session as logged in
                // and redirect to admin home.
                session()->set('userdata', $userdata);
            }
            else {
                $ci->addMessage('Login fehlgeschlagen', 'Der eingegebene Benutzername oder das Passwort ist falsch.', CIHelper::MSG_ERROR);
            }
        }

        return $this->renderPage($ci, $data);
    }

    public function logout(): string
    {
        session()->remove('userdata');

        $ci = new CIHelper();
        $ci->addMessage('Logout erfolgreich', 'Sie wurden erfolgreich abgemeldet.', CIHelper::MSG_INFO);

        return $this->renderPage($ci);
    }
    
    private function renderPage( $ci, $data = array() ) : string {
        $user = session()->get('userdata');
        
        if( $user === null ) {
            $ci->setHeadline("Willkommen zur TGÖ Service App!");
            $ci->initMenuAnonymous();
            return $ci->view('home-anonymous');
        }
        else {
            $ci->setHeadline("Hallo {$user->getFirstName()}!");
            $ci->initMenuLoggedin();
            return $ci->view('home-loggedin');
        }
    }
}
