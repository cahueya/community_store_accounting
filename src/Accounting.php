<?php
namespace  Concrete\Package\CommunityStoreAccounting\Src;
use Concrete\Core\Http\ServerInterface;
use Concrete\Core\Package\Package;
use Database;
use Core;
use Events;
use Log;
use Loader;
use Whoops\Exception\ErrorException;

/**
 * @Entity
 * @Table(name="CommunityStoreAccounting")
 */
class
Accounting
{
    /**
     * @Id @Column(type="integer")
     */
    protected $oID;

    /**
     * @Column(type="datetime",nullable=true)
     */
    protected $oSent;
    
    /**
     * @Column(type="string",nullable=true)
     */
    protected $oErrorMsg; 
   

	public function getValue()
    {
        return $this->value;
    }
    
    	public function getID()
    {
        return $this->oID;
    }
    
    	public function setID($oID)
    {
        $this->oID = $oID;
    }

    
    public function getSentDate()
    {
        return $this->oSent;
    }
    
    	public function setSentDate($oSent)
    {
        $this->value = $oSent;
    }
    
    
    public function getErrorMsg()
    {
        return $this->oErrorMsg;
    }
    
    	public function setErrorMsg($oErrorMsg)
    {
        $this->value = $oErrorMsg;
    }
    
    
    	

    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}	




