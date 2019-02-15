<?php


namespace Concrete\Package\CommunityStoreAccounting\Controller\SinglePage\Dashboard\Store\Settings;
use Concrete\Package\CommunityStoreAccounting;
use Concrete\Core\Page\Controller\DashboardPageController;
//use Config;
use Core;
use Package;
use Concrete\Core\Page\Single as SinglePage;

defined('C5_EXECUTE') or die("Access Denied.");
class Accounting extends DashboardPageController
{

    public function view() {  
		$pkg = Package::getByHandle('community_store_accounting');

        $username = $pkg->getConfig()->get('settings.accounting.username');
        $password = $pkg->getConfig()->get('settings.accounting.password');
		$submission_method = $pkg->getConfig()->get('settings.accounting.submission_method');
		$submission_email = $pkg->getConfig()->get('settings.accounting.submission_email');
        $APIurl = $pkg->getConfig()->get('settings.accounting.APIurl');
        $APIkey = $pkg->getConfig()->get('settings.accounting.APIkey');
        $startOrder = $pkg->getConfig()->get('settings.accounting.startOrder');
        $PEC = $pkg->getConfig()->get('settings.accounting.PEC');
        $codice_destinatorio = $pkg->getConfig()->get('settings.accounting.codice_destinatorio');
        $codice_fiscale = $pkg->getConfig()->get('settings.accounting.codice_fiscale');
        
        $this->set('username', $username);
        $this->set('password', $password);
        $this->set('submission_method', $submission_method);
        $this->set('submission_email', $submission_email);
        $this->set('APIurl', $APIurl);
        $this->set('APIkey', $APIkey);
        $this->set('startOrder', $startOrder);
        $this->set('PEC', $PEC);
        $this->set('codice_destinatorio', $codice_destinatorio);
        $this->set('partita_iva', $partita_iva);
        $this->set('codice_fiscale', $codice_fiscale);		
    }

    
    
    public function update_configuration() {
    
        if ($this->isPost()) {
           $username = $this->post('username');
           $password = $this->post('password');
           $submission_method = $this->post('submission_method');
           $submission_email = $this->post('submission_email');
           $APIurl = $this->post('APIurl');
           $APIkey = $this->post('APIkey');
           $startOrder = $this->post('startOrder');
           $PEC = $this->post('PEC');
           $codice_destinatorio = $this->post('codice_destinatorio');
           $partita_iva = $this->post('partita_iva');
           $codice_fiscale = $this->post('codice_fiscale');
               
           $pkg = Package::getByHandle('community_store_accounting');
           $pkg->getConfig()->save('settings.accounting.username', $username);
           $pkg->getConfig()->save('settings.accounting.password', $password);
           $pkg->getConfig()->save('settings.accounting.submission_method', $submission_method);
           $pkg->getConfig()->save('settings.accounting.submission_email', $submission_email);
           $pkg->getConfig()->save('settings.accounting.APIurl', $APIurl);
           $pkg->getConfig()->save('settings.accounting.APIkey', $APIkey);
           $pkg->getConfig()->save('settings.accounting.startOrder', $startOrder);
           $pkg->getConfig()->save('settings.accounting.PEC', $PEC);
           $pkg->getConfig()->save('settings.accounting.codice_destinatorio', $codice_destinatorio);
           $pkg->getConfig()->save('settings.accounting.partita_iva', $partita_iva);
           $pkg->getConfig()->save('settings.accounting.codice_fiscale', $codice_fiscale);
                    
           $this->set('message', t("Configuration saved"));
        }

        $this->view();
    }

    
    
    
    
    public function config_saved() {
        $this->set('message', t("Configuration saved"));
        $this->view();
    }

}
?>
