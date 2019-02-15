<?php 

defined('C5_EXECUTE') or die("Access Denied.");
$form = $ih = Core::make('helper/form');
$ci = Loader::helper('concrete/ui');


?>

<form method="POST" action="<?php echo $this->action('run_job'); ?>">
    <input type="submit" value="<?php echo t('Send again') ?>" class="btn btn-primary pull-right"/>
</form>


<table class="table table-striped">
		<col width="15%">
        <col width="15%">
        <col width="70%">
		<thead>
		<tr>
	        <th><?php echo t('Order #') ?></th>
            <th><?php echo t('Sent') ?></th> 
            <th><?php echo t('Error Message') ?></th>
		</tr>
		</thead>
		<tbody>
		<?php foreach($fattura_rows as $row){			
			?>
			<tr>
                <td> 
                <a href="/dashboard/store/orders/order/<?php echo $row['oID']; ?>" target="_blank">
                    <?php echo($row['oID']);?>
                </a>
                </td>
                <td><?php echo($row['oSent']);?></td>
                <td><?php echo($row['oErrorMsg']);?></td>
			</tr>
         <?php } ?>
		</tbody>