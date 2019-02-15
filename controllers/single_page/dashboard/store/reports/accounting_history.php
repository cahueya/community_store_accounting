<?php
namespace Concrete\Package\CommunityStoreAccounting\Controller\SinglePage\Dashboard\Store\Reports;

use \Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Package\Package;
use Database;
use Core;
use Loader;
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
