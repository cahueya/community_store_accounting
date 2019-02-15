<?php 


defined('C5_EXECUTE') or die("Access Denied.");
$form = $ih = Core::make('helper/form');
$ci = Loader::helper('concrete/ui');
?>



<div class="container">

<form method="post" action="<?= $view->action('update_configuration')?>">
    <fieldset><legend><?php echo t('Accounting Data'); ?></legend>

		    <div class="col-md-12">
				
			    <label><?php echo t('Submission Method')?></label>
			        
                <?php     
                if ($submission_method == '') {
                echo '<p class="help-block">';
                echo t('Please choose on how the accounting should be transmitted. You need to save this configuration before adding the other inputs.');
                echo '</p>';
                } ?>
		       
            </div>
		    <div class="col-xs-6">
                <div class="form-group">
                <label><?= $form->radio('submission_method','api', $submission_method == 'api' || $submission_method == '' ); ?> <?php  echo t('API Method'); ?></label>
                
  
                </div>
            </div>
		    <div class="col-xs-6">
                <div class="form-group">
	            <label><?= $form->radio('submission_method','email',$submission_method == 'email'); ?> <?php  echo t('E-mail Method'); ?></label>
                </div>
            </div>
 


    <?php 
    
    if ($submission_method == 'api') {
    echo '<div class="col-xs-12 col-md-6">';        
    echo    '<div class="form-group">';
    echo        '<label for="APIurl">' . t('API Url') . '</label>';
    echo            $form->text('APIurl', $APIurl, array('class' => 'span2', 'placeholder'=>t('URL of the API endpoint')));
    echo    '</div>';
    echo '</div>';        
    echo '<div class="col-xs-12 col-md-6">';    
    echo    '<div class="form-group">';
    echo        '<label for="APIkey">' . t('API Key') . '</label>';
    echo            $form->text('APIkey', $APIkey, array('class' => 'span2', 'placeholder'=>t('API Key of your API service')));
    echo    '</div>';
    echo '</div>';
    echo '<div class="col-xs-12 col-md-6">';        
    echo    '<div class="form-group">';
    echo        '<label for="username">' . t('Username') . '</label>';
    echo            $form->text('username', $username, array('class' => 'span2', 'placeholder'=>t('Username of your API service')));
    echo    '</div>';
    echo '</div>';        
    echo '<div class="col-xs-12 col-md-6">';    
    echo    '<div class="form-group">';
    echo        '<label for="password">' . t('Password') . '</label>';
    echo            $form->text('password', $password, array('class' => 'span2', 'placeholder'=>t('Password of your API service')));
    echo    '</div>';
    echo '</div>'; 
    
    
    }
    
    if ($submission_method == 'email') {

    echo '<div class="col-xs-12 col-md-6">';    
    echo    '<div class="form-group">';
    echo        '<label for="submission_email">' . t('Submission Email') . '</label>';
    echo            $form->text('submission_email', $submission_email, array('class' => 'span2', 'placeholder'=>t('E-Mail to submit the orders to')));
    echo    '</div>';
    echo '</div>'; 
    
    
    }
    
    
    if ($submission_method !=='') {
    echo '<div class="col-xs-12 col-md-6">';    
    echo    '<div class="form-group">';
    echo        '<label for="startOrder">' . t('Start at Order No') . '</label>';
    echo            $form->text('startOrder', $startOrder, array('class' => 'span2', 'placeholder'=>t('Input a Order No')));
    echo    '</div>';
    echo '</div>'; 
    
    echo '<div class="col-xs-12 col-md-6">';    
    echo    '<div class="form-group">';
    echo        '<label for="PEC">' . t('PEC Email') . '</label>';
    echo            $form->text('PEC', $PEC, array('class' => 'span2', 'placeholder'=>t('PEC for Italian Accounting')));
    echo    '</div>';
    echo '</div>'; 
    
    echo '<div class="col-xs-12 col-md-6">';    
    echo    '<div class="form-group">';
    echo        '<label for="codice_destinatorio">' . t('Codice destinatorio') . '</label>';
    echo            $form->text('codice_destinatorio', $codice_destinatorio, array('class' => 'span2','maxlength' => '7', 'placeholder'=>t('Codice destinatorio for Italian accounting')));
    echo    '</div>';
    echo '</div>'; 
    
    echo '<div class="col-xs-12 col-md-6">';    
    echo    '<div class="form-group">';
    echo        '<label for="partita_iva">' . t('VAT Number') . '</label>';
    echo            $form->text('partita_iva', $partita_iva, array('class' => 'span2', 'placeholder'=>t('VAT Number')));
    echo    '</div>';
    echo '</div>'; 
    
        echo '<div class="col-xs-12 col-md-6">';    
    echo    '<div class="form-group">';
    echo        '<label for="codice_fiscale">' . t('Tax Number') . '</label>';
    echo            $form->text('codice_fiscale', $codice_fiscale, array('class' => 'span2', 'placeholder'=>t('Tax Number')));
    echo    '</div>';
    echo '</div>'; 
    
    } else {
    echo '<span>';
    echo t('Please choose a method as a first step. If you choose the <strong>E-Mail Method</strong>, each confirmed transaction will be sent as a XML File to the E-Mail address you defined. If you choose <strong>API Method</strong>, you need to input your API Url, and API Key or Username or Password of the Accounting Service you are using. Every confirmed Order will be sent out as a CURL request to that address.');
    echo '</span>';
    }
    
    ?>
    
  

    </fieldset>


</div>


    <div class="ccm-dashboard-form-actions-wrapper">
        <div class="ccm-dashboard-form-actions">
            <button class="pull-right btn btn-success" type="submit" ><?php echo t('Save')?></button>
        </div>
    </div>
</form>