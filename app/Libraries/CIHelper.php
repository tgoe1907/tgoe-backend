<?php
namespace App\Libraries;

class CIHelper
{
    private $headline = "Headline not set.";
    private $messages = array();
    
    public const MSG_ERROR = 3;
    public const MSG_WARNING = 2;
    public const MSG_INFO = 1;
    public const MSG_SUCCESS = 0;
    
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
}

