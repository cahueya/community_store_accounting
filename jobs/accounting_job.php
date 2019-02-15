<?php
namespace Concrete\Package\CommunityStoreAccounting\Job;
use Concrete\Core\Http\ServerInterface;
use Concrete\Core\Package\Package;
use Database;
use Core;
use Log;
use Whoops\Exception\ErrorException;
use Events;
use Job;
use Loader;
use Config;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Order\OrderList as StoreOrderList;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Order\OrderItem as StoreOrderItem;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Product\Product as StoreProduct;
use Concrete\Package\CommunityStore\Src\CommunityStore\Order\Order as StoreOrder;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Utilities\Price as Price;

use \Concrete\Package\CommunityStore\Src\Attribute\Key\StoreOrderKey as StoreOrderKey;
use Concrete\Package\CommunityStore\Src\CommunityStore\Payment\Method as StorePaymentMethod;
use Concrete\Package\CommunityStore\Src\Attribute\Value\StoreOrderValue as StoreOrderValue;



use Concrete\Package\CommunityStoreAccounting\Src;

final class AccountingJob extends Job
{

    public function getJobName()
    {
        return t("Submit Electroic Accounting.");
    }

    public function getJobDescription()
    {
        return t("Submits Accounting Data to the Tax handler.");
    }

    public function run() 
    {        
  
        $accounting = \Concrete\Package\CommunityStoreAccounting\Src\AccountingProcess::run_job();
        
        
        return t(
            'The accounting submission was successfull.'
        );
        
        
        	
    }
}    
    
    
    
    




    