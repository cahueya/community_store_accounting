<?php
namespace Concrete\Package\CommunityStoreAccounting;

use Database;
use Package;
use SinglePage;
use Core;

class Controller extends Package {
	protected $pkgHandle = 'community_store_accounting';
	protected $appVersionRequired = '5.7.5';
	protected $pkgVersion = '0.0.4';


	public function getPackageDescription () {
		return t("Community Store Accounting");
	}

	public function getPackageName () {
		return t("Community Store Accounting");
	}

	public function install () {
		$installed = Package::getInstalledHandles();
		if (!(is_array($installed) && in_array('community_store', $installed))) {
			throw new ErrorException(t('This package requires that Community Store be installed'));
		} else {
			$pkg = parent::install();
            SinglePage::add('/dashboard/store/settings/accounting',$pkg);
            SinglePage::add('/dashboard/store/reports/accounting_history',$pkg);
            \Concrete\Core\Job\Job::installByPackage('accounting_job', $pkg);
		}
	}

    public function uninstall(){
        $pkg = parent::uninstall();
        $db = \Database::connection();
        $db->query('drop table CommunityStoreAccounting');
    }

	public function upgrade () {
		$pkg =parent::upgrade();
		$pkg = Package::getByHandle($this->pkgHandle);
	}


    public function on_start()
    {   
        require $this->getPackagePath() . '/vendor/autoload.php';
    }
}
