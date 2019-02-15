<?php
namespace  Concrete\Package\CommunityStoreAccounting\Src;

use Concrete\Core\Http\ServerInterface;
use Concrete\Core\Package\Package;
use Database;
use Core;
use Events;
use Log;
use Loader;
use Config;
use Whoops\Exception\ErrorException;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Order\OrderList as StoreOrderList;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Order\OrderItem as StoreOrderItem;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Product\Product as StoreProduct;
use Concrete\Package\CommunityStore\Src\CommunityStore\Order\Order as StoreOrder;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Utilities\Price as Price;
use \Concrete\Core\Job\Job as AbstractJob;
use Concrete\Core\Job\Job;
use \Concrete\Package\CommunityStore\Src\Attribute\Key\StoreOrderKey as StoreOrderKey;
use Concrete\Package\CommunityStore\Src\CommunityStore\Payment\Method as StorePaymentMethod;
use Concrete\Package\CommunityStore\Src\Attribute\Value\StoreOrderValue as StoreOrderValue;
use \Concrete\Core\Entity\File\Version;
use Concrete\Core\Tree\Node\Type\FileFolder;
use Concrete\Core\File\FolderItemList;
use Concrete\Core\File\Filesystem;
use Concrete\Core\File\File;
use \Concrete\Core\File\Importer;
use \Concrete\Core\File\Service\File as FileHelper;
use \Concrete\Core\Utility\Service\Xml as SimpleXMLElement;
use Sabre\Xml;


class AccountingProcess
{
    public function run_job() {
        $app = Core::make('app');
        $config = $app->make('config');
        $dh = Core::make('helper/date');
        $db = $app->make('database');
        
        // Get the config values
        $pkg = Package::getByHandle('community_store_accounting'); 
        $submission_method = $pkg->getConfig()->get('settings.accounting.submission_method');      
        $startOrder = $pkg->getConfig()->get('settings.accounting.startOrder');
        $submission_email = $pkg->getConfig()->get('settings.accounting.submission_email');
        $username = $pkg->getConfig()->get('settings.accounting.username');
        $password = $pkg->getConfig()->get('settings.accounting.password');
        $APIurl = $pkg->getConfig()->get('settings.accounting.APIurl');
        
        if (Config::get('concrete.email.default.address') && strstr(Config::get('concrete.email.default.address'), '@')) {
                $FromEmailAddress = Config::get('concrete.email.default.address');
            } else {
                $adminUserInfo = UserInfo::getByID(USER_SUPER_ID);
                $FromEmailAddress = $adminUserInfo->getUserEmail();
            }
       
        
        
        // Get order numbers of paid orders after the startOrder value
        $unsent_paid_oders = $db->Execute("SELECT oID FROM CommunityStoreOrders WHERE oPaid IS NOT NULL AND oID >=? AND oID not in (SELECT oID FROM CommunityStoreAccounting WHERE oSent IS NOT NULL)" , [$startOrder]);
        foreach($unsent_paid_oders as $row) {
    
			$fattura_rows = $db->GetOne("SELECT * FROM CommunityStoreAccounting WHERE oID=?" , [$row["oID"]]);

            // If there are any, write their order numbers into the Accounting table
            if (!$fattura_rows) {
                $db->Execute('INSERT INTO CommunityStoreAccounting (oID,oSent,oErrorMsg) VALUES(?,?,?)' , [$row["oID"],NULL,NULL]);
            
                // Get the order object based on the order number
                $order = StoreOrder::getByID($row["oID"]);
                
                //Get the values of the order
            	$order_id = $order->getOrderID();
            	$order_date = $dh->formatDate($order->getOrderDate());
            	$order_payment_method = t($order->getPaymentMethodName());
            	$order_subtotal = Price::format($order->getSubTotal());
            	$order_total = Price::format($order->getTotal());
            	$order_tax_included = $order->getTaxTotal();
            	$order_transaction_reference = $order->getTransactionReference();
            	$order_paid_date = $dh->formatDate($order->getPaid());
            	$order_email = $order->getAttribute("email");
    			$order_first_name = $order->getAttribute("billing_first_name");
				$order_last_name = $order->getAttribute("billing_last_name"); 
                $order_billing_address = $order->getAttribute("billing_address");
                $order_billing_address->getValue('displaySanitized', 'display');

                $billingaddress = $order->getAttributeValueObject(StoreOrderKey::getByHandle('billing_address'));
                $billingaddressvalue = $billingaddress->getValue();
                $city =  $billingaddressvalue->getCity();
                // --- Get more Values from the Order Object --->
            	
                
                if ($submission_method == 'email') {

                    // Just for mockup to test mailing - put data into a TXT file and save to /tmp dir
				    //---------TXT-----------------------------------------------------------------------
                    $tmppath = FileHelper::getTemporaryDirectory();
				    $filepath   = $tmppath . "/" . $order_id . ".txt";  
                    // Here we need to build the XML File!           
				    //-----------------------------------------------------------------------------------
				    $filehandle = fopen($filepath, "w") or die ("Unable to open file!");
				    fwrite($filehandle, $order_id);
				    fwrite($filehandle, ' from: ');
                    fwrite($filehandle, $order_email     );
				    fclose($filehandle);
				    //-----------------------------------------------------------------------------------
				    // Import TXT file into FileManager
                    $importer = new Importer();
				    $result = $importer->import($filepath, "order_" . $order_id . '_' . time() . ".txt");
                
                    // Delete from /tmp dir
                    unlink($filepath);
				    if ($result instanceof \Concrete\Core\Entity\File\Version) {
					    $mailService = Core::make('mail');
					    $mailService->setTesting(false); // or true to throw an exception on error.
					    $mailService->load('accounting', 'community_store_accounting');
					    $mailService->from($FromEmailAddress, 'Accounting');
					    $mailService->to($order_email);
					    $fileid=$result->getFileID();
					    $f = \File::getByID($fileid);
					    $mailService->addAttachment($f);
					    $mailService->sendMail();
				    } else {
					Log::addInfo(t('Mail could not be submitted: ') . $fileid);
                    }
                }
                
                if ($submission_method == 'api') {
                
                // Example from from https://documenter.getpostman.com/view/4852169/RWMCu9aG
                // Untested!
                    /*
                    $curl = curl_init();
                        // Prepare the call
                        curl_setopt_array($curl, array(
                        CURLOPT_URL => $APIurl,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => false,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        //Here we add the XML once it is built
                        CURLOPT_POSTFIELDS =>"{\n  \"invoice_xml\": \n    \"<p:FatturaElettronica versione='FPA12'>\n        <FatturaElettronicaHeader>\n      <DatiTrasmissione>\n        <IdTrasmittente>\n          <IdCodice>01234567890</IdCodice>\n        </IdTrasmittente>\n        <FormatoTrasmissione>FPA12</FormatoTrasmissione>\n        <CodiceDestinatario>AAAAAA</CodiceDestinatario>\n        ...\n\"} \n",
                        CURLOPT_HTTPHEADER => array(
                        "Content-Type: application/json",
                        '"Authorization: Basic "' . base64_encode($username:$password);'"'
                        ),
                    ));
                    // Send it all out
                    $response = curl_exec($curl);
                    $err = curl_error($curl);
                    curl_close($curl);
                    // if something goes wrong, write to Log
                    if ($err) {
                        Log::addInfo('CURL error: ' . $err);
                    } else {
                        Log::addInfo('CURL back: ' . $response);

                    }
                    */   
                    // We set success to 1 for testing here. It should be result of the cURL response
                    $success = 1;
                    if ($success) {
                    // If cURL reponds successfully, add the timestamp to the DB
                    $db->Execute('UPDATE CommunityStoreAccounting SET oSent=CURRENT_TIMESTAMP WHERE oID=?' , $row["oID"]);
                    } else {
                    // Or write to ErrorMsg
                    $errormsg = "Error";
                    $db->Execute("UPDATE CommunityStoreAccounting SET oErrorMsg=? WHERE oID=?", [$errormsg,$row["oID"]]);
                    }
                }
            }	
        }    
    }
}


