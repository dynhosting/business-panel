<?php $heading_args = array(
		"heading"	=> "Clientes",
		"page"	=> "dbp_invoices",
		"id"	=> "icon-sellers-page",
		"url"	=> admin_url('admin.php?page=dbp_clients&edit=new')
		);
dbp_header($heading_args);

global $wpdb;?>
<form action="" method="post" id="dbp-create-client">
<?php
if( ! empty ($_POST["save_quit"]) ):
	require_once( DBP_PLUGIN_DIR . "/includes/edit_client.php");
endif;
$dbp_client_id  = ( ! empty ( $_REQUEST["view"] ) && $_REQUEST["view"] != "new" ) ? $_REQUEST["view"] : "0";
$new_invoice_number = get_dbp_invoice_number();
if(isset($_REQUEST["view"]))
{
	$the_client = get_userdata($dbp_client_id);
	$the_client_phone = get_user_meta( $dbp_client_id, "dbp_client_phone", true );
	$the_client_img = get_user_meta( $dbp_client_id, "dbp_client_img", true);
}?>
<div id="message" class="updated <?php echo (isset ( $data["class"] ) ) ? $data["class"] : "" ?>"><strong><?php echo ( isset ( $data["message"] ) ) ? $data["message"] : ""?></strong></div>
<div id="poststuff dbp-modal-window" class="metabox-holder dbp-left-box">
		<div id="form-sortables" class="meta-box-sortables ui-sortable ">
			<div class="dbp_form_settings_panel postbox">
				<div class="handlediv"><br /></div>
				<h3 class="hndle"><span>Datos del cliente</span></h3>
				<div class="inside">
					<img id="only-one-smaller" src="<?php echo ($the_client_img != "") ? $the_client_img : DBP_PLUGIN_URL . "/images/no-profile-image.jpg"?>" />
					<div id="the-right-profile-box">
						<label class="dbp_blocked" for="client_username">Rif o la c&eacute;dula del cliente : <b><?php echo ( isset($_REQUEST["view"]) ) ? $the_client->user_login : ''?></b></label>
						<label class="dbp_blocked" for="client_company_name">Raz&oacute;n social : <b><?php echo ( isset($_REQUEST["view"]) ) ? $the_client->user_nicename : ''?></b></label>
						<label class="dbp_blocked" for="client_firstname">Nombres : <b><?php echo ( isset($_REQUEST["view"]) ) ? $the_client->first_name : ''?></b></label>
						<label class="dbp_blocked" for="client_lastname">Apellidos : <b><?php echo ( isset($_REQUEST["view"]) ) ? $the_client->last_name : ''?></b></label>
						<label class="dbp_blocked" for="client_address">Direcci&oacute;n : <b><?php echo ( isset($_REQUEST["view"]) ) ? $the_client->description : ''?></b></label>
						<label class="dbp_blocked" for="client_email">Email : <b class="email"><?php echo ( isset($_REQUEST["view"]) ) ? $the_client->user_email : ''?></b></label>
						<label class="dbp_blocked" for="client_phone">Tel&eacute;fono : <b><?php echo ( isset($_REQUEST["view"]) ) ? $the_client_phone : ''?></b></label>
					</div>
					<br class="clr" />
				</div>
			</div>	
		</div>
</div>
<div id="poststuff dbp-modal-window" class="metabox-holder dbp-right-box">
		<div id="form-sortables" class="meta-box-sortables ui-sortable ">
			<div class="dbp_form_settings_panel postbox">
				<div class="handlediv"><br /></div>
				<h3 class="hndle"><span>&Uacute;ltimas Facturas</span></h3>
				<div class="inside">
					<?php global $wpdb;
					$invoices = $wpdb->get_results("SELECT * FROM " . $wpdb->postmeta . " WHERE meta_key = 'dbp_client_id' AND meta_value = '" . $dbp_client_id . "'");
					if ( count ( $invoices ) > 0 ):?>
					<table width="100%" border="1" id="show-client-invoices" cellpadding="5">
						<tr class="ui-widget-header">
							<th>No Factura</th>
							<th>Fecha</th>
							<th>Monto</th>
							<th>Pagado</th>
							<th>Resta</th>
							<th>Estatus</th>
						</tr>
					<?php foreach ( $invoices as $invoice ):
						$the_invoice = get_post($invoice->post_id);
						$invoice_number = get_post_meta($the_invoice->ID, "dbp_invoice_number", true);
						$the_invoice_items = unserialize ( get_post_meta($the_invoice->ID, "dbp_invoice_item", true) );
						$the_invoice_payment_history = unserialize ( get_post_meta($the_invoice->ID, "dbp_invoice_payments", true) );
						$invoice_status = get_post_status_object($the_invoice->post_status);
						
						$currency_symbol = get_option("dbp_currency_symbol");
						
						foreach($the_invoice_items as $item)
						{
							
							$the_price = $item["price"]*$item["quantity"];
							$the_tax = ($item["tax"]=="true") ? ($the_price*str_replace('%', '', get_option('dbp_shop_tax_v')))/100 : 0;
							
							$ammount += $the_price;
							$tax += $the_tax;
						}
						
						$the_ammount = number_format(($ammount + $tax), 2, ',', '.');
						
						$payments_history=0;

						if( is_array( $the_invoice_payment_history ) )
						{
							foreach($the_invoice_payment_history as $payment)
							{
								$payments_history += $payment["payment_ammount"];
							}
						}?>
						<tr>
							<td align="center"><a href="<?php echo admin_url("admin.php?page=dbp_invoices&view=" . $the_invoice->ID)?>"><?php echo $invoice_number?></a></td>
							<td align="center"><?php echo date("d-m-Y", strtotime($the_invoice->post_date))?></td>
							<td align="right"><?php echo $currency_symbol . ' ' . $the_ammount?></td>
							<td align="right"><?php echo $currency_symbol . ' ' . number_format( $payments_history, 2, ',', '.' )?></td>
							<td align="right"><?php echo number_format (  ( $the_ammount-$payments_history ), 2, ',' , '.' )?></td>
							<td align="center">
								<strong class="<?php echo ($invoice_status->name == "unpaid") ? 'dbp-sold' : ($invoice_status->name == "unpaiable") ? 'dbp-unpaiable' : 'dbp-available'?>"><?php echo ucfirst ( $invoice_status->label )?></strong>
							</td>
						</tr>
					<?php endforeach;
					endif?>
					</table>
				</div>
			</div>	
		</div>
</div>
<p class="clr dbp_center">
	<a href="<?php echo admin_url("admin.php?page=dbp_clients&edit=$dbp_client_id")?>" class="button">Editar cliente</a>
</p>
</form>

<script type="text/javascript">
</script>
<?php if( ! empty ($_POST["save_quit"]) ):?>
<script>
	$("#<?php echo $data["field"]?>").addClass("<?php echo $data["class"]?>").focus(function(){ $(this).removeClass("ui-state-error"); $("#message").slideUp("slow"); });
	$("#message").show();
</script>
<?php endif?>
<script src="<?php echo DBP_PLUGIN_URL?>js/dbp-select-image.js"></script>
<script src="<?php echo DBP_PLUGIN_URL?>js/dbp-clients.js"></script>
<script src="<?php echo DBP_PLUGIN_URL?>js/dbp-custom.js"></script>