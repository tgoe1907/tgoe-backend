<?php 
namespace TgoeSrv\Database\Entities;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

#[Entity]
#[Table(name: 'settings')]
class Settings
{
    #[Id]
    #[Column(name: 'key_name', unique: true, length: 50)]
    private string $key;
    
    #[Column(name: 'string_value', length: 255)]
    private string $stringValue;
    
    #[Column(name: 'int_value')]
    private string $intValue;
    
    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getStringValue()
    {
        return $this->stringValue;
    }

    /**
     * @return string
     */
    public function getIntValue()
    {
        return $this->intValue;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @param string $stringValue
     */
    public function setStringValue($stringValue)
    {
        $this->stringValue = $stringValue;
    }

    /**
     * @param string $intValue
     */
    public function setIntValue($intValue)
    {
        $this->intValue = $intValue;
    }

 
}
?>