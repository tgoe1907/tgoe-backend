<?php

namespace App\Controllers\Account;

use App\Controllers\BaseController;
use TgoeSrv\Member\Api\MemberGroupService;
use App\Libraries\DataQualityCheckResult;
use App\Libraries\DataQualityCheckHelper;
use App\Libraries\CIHelper;
use TgoeSrv\Member\Enums\MemberGroupCustomProperty;

class Passwd extends BaseController
{
    
    public function index()
    {
        $ci = new CIHelper();
        $usr = session()->get('userdata');
        
        $currentPwd = $this->request->getPost('current-pwd');
        $newPwd = $this->request->getPost('new-pwd');
        $confirmPwd = $this->request->getPost('confirm-pwd');
        
        if( isset( $currentPwd)) {
            //data has been entered and should be processed
            $err = false;
            
            if (!password_verify($currentPwd, $usr->getPasswordHash())) {
                $ci->addMessage('Fehler', 'Das alte Passwort ist nicht korrekt.', CIHelper::MSG_ERROR);
                $err = true;
            }
            
            if( !$err ) {
                $uppercase = preg_match('@[A-Z]@', $newPwd);
                $lowercase = preg_match('@[a-z]@', $newPwd);
                $number    = preg_match('@[0-9]@', $newPwd);
                $specialChars = preg_match('@[^\w]@', $newPwd);
                
                if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($newPwd) < 8) {
                    $ci->addMessage('Fehler', 'Das neue Passwort erfüllt nicht die Passwortrichtlinien.', CIHelper::MSG_ERROR);
                    $err = true;
                }
            }
            
            if( !$err ) {
                if( $newPwd != $confirmPwd ) {
                    $ci->addMessage('Fehler', 'Die Passwortwiederholung stimmt nicht mit dem Passwort überein.', CIHelper::MSG_ERROR);
                    $err = true;
                }
            }
            
            if( !$err) {
                //all checks passed, change password
                $ci->addMessage('Passwort geändert', 'Das neue Passwort wurde aktiviert.', CIHelper::MSG_INFO);
                
                $usr->setPasswordHash( password_hash($newPwd));
                
                $em = DbHelper::getEntityManager();
                $em->persist($usr);
                $em->flush();
            }
        }
        
        
        $ci->setHeadline("Passwort ändern");
        $ci->initMenuLoggedin();
        return $ci->view('account/passwd/home');
    }
    

}
