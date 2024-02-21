<?php
namespace App\Libraries;

class CIHelper
{
    private $headline = "Headline not set.";
    private $messages = array();
    private $menuitems = array( "n" => array("far fa-image", "not defined", "#"));
    private $activeMenuItem = "";
    
    public const MSG_ERROR = 3;
    public const MSG_WARNING = 2;
    public const MSG_INFO = 1;
    public const MSG_SUCCESS = 0;
    
    /**
     * @return number
     */
    public function getMenuitems()
    {
        return $this->menuitems;
    }

    /**
     * @return number
     */
    public function getActiveMenuItem()
    {
        return $this->activeMenuItem;
    }

    /**
     * @param number $menuitems
     */
    public function setMenuitems($menuitems)
    {
        $this->menuitems = $menuitems;
    }

    /**
     * @param number $activeMenuItem
     */
    public function setActiveMenuItem($activeMenuItem)
    {
        $this->activeMenuItem = $activeMenuItem;
    }

    /**
     * @return array:
     */
    public function getMessages()
    {
        return $this->messages;
    }
    
    /**
     * @return boolean:
     */
    public function hasMessages()
    {
        return count($this->messages)>0;
    }

    /**
     * @param string: $message
     * @param int: $severity
     */
    public function addMessage($title, $message, $severity)
    {
        $this->messages[] = array($title, $message, $severity);
    }

    /**
     * @return string
     */
    public function getHeadline()
    {
        return $this->headline;
    }

    /**
     * @param string $headline
     */
    public function setHeadline($headline)
    {
        $this->headline = $headline;
    }

    public function view( $name, $data = array() ) {
        $data['ci'] = $this;
        
        $content  = view( 'common/header', $data );
        $content .= view( $name, $data );
        $content .= view( 'common/footer', $data );
        
        return $content;
    }
    
    public function initMenuAnonymous() {
        $this->menuitems = array();
    }
    
    public function initMenuLoggedin() {
        //icon schemes fas, far, fal, fad, and fab
        
        $memberManagement = array();
        $memberManagement["member-data-confirmation"] = array("far fa-list-alt",     "Listen Sportgruppen",    "/admin/member-data-confirmation");
        $memberManagement["division-statistics"]      = array("fa fa-chart-line",    "Abteilungs-Statistik",   "/admin/division-statistics");
        $memberManagement["data-quality-check"]       = array("fas fa-check-square", "Qualitätsprüfung",       "/admin/data-quality-check");
        
        $trainerAccounting = array();
        $trainerAccounting["trainer-administration"]   = array("fas fa-users",        "Übungsleiter verwalten",  "#");
        $trainerAccounting["trainer-record-hours"]     = array("fas fa-edit",         "Stunden erfassen",        "#");
        
        
        $this->menuitems = array();
        if( count( $memberManagement ) > 0 ) {
            $this->menuitems["headline-member-services"] = array(null, "MITGLIEDERVERWALTUNG", null);
            $this->menuitems = array_merge($this->menuitems, $memberManagement);
        }

        if( count( $trainerAccounting ) > 0 ) {
            $this->menuitems["trainer-accounting"] = array(null, "ÜBUNGSLEITERABRECHNUNG", null);
            $this->menuitems = array_merge($this->menuitems, $trainerAccounting);
        }
      
    }
}

