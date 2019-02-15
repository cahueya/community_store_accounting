<?php


namespace Concrete\Package\CommunityStoreAccounting\Controller\SinglePage\Dashboard\Store\Reports;
use \Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Package\Package;
use Database;
use Core;
use Log;
use Whoops\Exception\ErrorException;
use Loader;
use Config;
use Concrete\Package\CommunityStore\Src\CommunityStore\Order;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Utilities\Price as Price;
use Concrete\Package\CommunityStore\Src\CommunityStore\Order\Order as StoreOrder;
use \Concrete\Package\CommunityStore\Src\Attribute\Key\StoreOrderKey as StoreOrderKey;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Product\Product as StoreProduct;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Order\OrderList as StoreOrderList;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Order\OrderItem as StoreOrderItem;
use Concrete\Package\CommunityStore\Src\Attribute\Value\StoreOrderValue as StoreOrderValue;
use Concrete\Package\CommunityStore\Src\CommunityStore\Payment\Method as StorePaymentMethod;

use Job;








defined('C5_EXECUTE') or die("Access Denied.");
class AccountingHistory extends DashboardPageController
{

    public function view() {  
        $db = \Database::connection();
		$fattura_rows = $db->Execute("SELECT * FROM CommunityStoreAccounting");
		$this->set('fattura_rows', $fattura_rows);
    }
    
    public function run_job() {  
        $sending_job = Job::getByHandle('accounting_job');
		$sending_job->executeJob();
        $this->set('message', t("Job done"));
        
    }

    

}
?>
