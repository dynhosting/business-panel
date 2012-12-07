<?php require_once "../../../../../wp-load.php";
$dbp_active_bank_transfer = get_option("dbp_active_bank_transfer");
$dbp_account_holder = get_option("dbp_account_holder");
$dbp_account_holder_document = get_option("dbp_account_holder_document");
$dbp_account_bank = get_option("dbp_account_bank");
$dbp_account_number = get_option("dbp_account_number");
$dbp_account_holder_email = get_option("dbp_account_holder_email");?>

<div class="wrap">
	<div id="icon-invoice-page" class="icon32 icon32-posts-post"><br /></div>
	<h2>Insertar pago</h2>
	<section id="dbp-insert-payment">
		<div class="ui-widget-header ui-corner-top">&nbsp;</div>
		<div class="ui-widget-content ui-corner-bottom">
			<form action="" method="post" id="dbp-insert-payment-form">
				
				
				
				<input type="submit" class="button" value="Insertar" />
				
			</form>
		</div>
	</section>
</div>
<script type="text/javascript">
	$("#TB_ajaxWindowTitle").html("Insertar pago");
</script>