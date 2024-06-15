<?php
namespace App\Controllers;

use App\Libraries\CIHelper;
use TgoeSrv\Member\Api\UserLoginHelper;
use TgoeSrv\Tools\ConfigManager;
use TgoeSrv\Tools\ConfigKey;
use TgoeSrv\Database\DAO\UserAccountDAO;

class Home extends BaseController
{
    public function index()
    {
        $ci = new CIHelper();

        $data = [];
        
        // if user is not logged in, try to get user information from environment and login to
        // our session (if user exists).
        $user = session()->get('userdata');
        if( $user === null ) {
            // try to get user from environment variable
            $email = getenv(ConfigManager::getValue(ConfigKey::AUTH_SSO_ENV_VAR_NAME));
            if( strlen ($email) == 0 ) {
                //if no environment variable provided, check for development mode user
                $email = ConfigManager::getValue(ConfigKey::AUTH_DEVELOPMENTLOGINAS);
                if( strlen ($email) > 0 ) {
                    $ci->addMessage('DEVELOPMENT MODE', 'Login als '.$email.' ohne Berechtigungprüfung. Nur für Testzwecke, nicht auf produktiven Umgebungen verwenden!', CIHelper::MSG_ERROR);
                }
            }
            
            // try to find user
            if( strlen ($email) > 0 ) {
                $userAccount = UserAccountDAO::findUniqueUserAccountByEMail($email);
                
                if (is_object($userAccount)) {
                    // if credetials successfull, set session as logged in
                    // and redirect to admin home.
                    session()->set('userdata', $userAccount);
                }
                else {
                    $ci->addMessage('Login fehlgeschlagen', 'Kein Benutzerkonto gefunden für E-Mail '.$email, CIHelper::MSG_ERROR);
                }
            }
            else {
                $ci->addMessage('Login fehlgeschlagen', 'Kein Benutzer via SSO authentifiziert.', CIHelper::MSG_ERROR);
            }
            
        }

        return $this->renderPage($ci, $data);
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
