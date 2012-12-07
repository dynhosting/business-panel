<?php dbp_header();
global $wpdb;?>
<form action="" method="post" id="dbp-create-invoice">
<?php
if( ! empty ($_POST["save_quit"]) ):
	require_once( DBP_PLUGIN_DIR . "/includes/edit_invoice.php");?>
		<div id="message" class="updated <?php echo $data["class"]?>"><strong><?php echo $data["message"]?></strong></div>
<?php endif;
$invoicearr = array(
	'post_type'	=> 'dbp_invoice',
	'comment_status'	=> false,
	'ping_status'	=> false,
	'post_title'	=> 'Nueva factura'
	);
$dbp_invoice_id = ( ! empty ( $_REQUEST["edit"] ) && $_REQUEST["edit"] != "new" ) ? $_REQUEST["edit"] : wp_insert_post($invoicearr);
$new_invoice_number = get_dbp_invoice_number();
if(isset($_REQUEST["edit"]))
{
	$the_invoice = get_post($dbp_invoice_id);
	$invoice_number = get_post_meta($dbp_invoice_id, "dbp_invoice_number", true);
	$the_invoice_number = ($invoice_number != "") ? $invoice_number : $new_invoice_number;
	$the_invoice_items = unserialize ( get_post_meta($dbp_invoice_id, "dbp_invoice_item", true) );
	$invoice_status = get_post_status_object($the_invoice->post_status);
	
	$the_client_id = get_post_meta($dbp_invoice_id, "dbp_client_id", true);
	$the_client = get_userdata($the_client_id);
	$the_client_phone = get_user_meta( $the_client_id, "dbp_client_phone", true );
}?>

<div id="poststuff dbp-modal-window" class="metabox-holder dbp-left-box">
		<div id="form-sortables" class="meta-box-sortables ui-sortable ">
			<div class="dbp_form_settings_panel postbox">
				<div class="handlediv"><br /></div>
				<h3 class="hndle"><span>Datos del cliente</span></h3>
				<div class="inside">
					<label class="dbp_blocked" for="client_username">Escriba por favor el rif o la c&eacute;dula del cliente</label>
					<input type="hidden" name="client_id" id="client_id" value="<?php echo ( isset($_REQUEST["edit"]) ) ? $the_client->ID : ''?>" />
					<input type="text" name="client_username" id="client_username" class="dbp_input_text required" placeholder="Cedula o RIF..." value="<?php echo ( isset($_REQUEST["edit"]) ) ? $the_client->user_login : ''?>" /><br />
					<input type="text" name="client_company_name" id="client_company_name" class="dbp_input_text required" placeholder="Raz&oacute;n social..." value="<?php echo ( isset($_REQUEST["edit"]) ) ? $the_client->user_nicename : ''?>" /><br />
					<input type="text" name="client_firstname" id="client_firstname" class="dbp_input_text required" placeholder="Nombre..." value="<?php echo ( isset($_REQUEST["edit"]) ) ? $the_client->first_name : ''?>" /><br />
					<input type="text" name="client_lastname" id="client_lastname" class="dbp_input_text required" placeholder="Apellido..." value="<?php echo ( isset($_REQUEST["edit"]) ) ? $the_client->last_name : ''?>" /><br />
					<input type="text" name="client_address" id="client_address" class="dbp_input_text required" placeholder="Direcci&oacute;n..." value="<?php echo ( isset($_REQUEST["edit"]) ) ? $the_client->description : ''?>" /><br />
					<input type="text" name="client_email" id="client_email" class="dbp_input_text" placeholder="Email..." value="<?php echo ( isset($_REQUEST["edit"]) ) ? $the_client->user_email : ''?>" /><br />
					<input type="text" name="client_phone" id="client_phone" class="dbp_input_text required" placeholder="Tel&eacute;fono..." value="<?php echo ( isset($_REQUEST["edit"]) ) ? $the_client_phone : ''?>" /><br />
					<div class="dbp-buttonset" align="center">
						<input type="checkbox" name="client_update_info" id="client_update_info" value="dbp_update" <?php echo (isset($the_client->ID)) ? 'checked="checked"' : ''?> />
						<label for="client_update_info"><small>Actualizar informaci&oacute;n de cliente</small></label>
					</div>
				</div>
			</div>	
		</div>
</div>
<div id="poststuff dbp-modal-window" class="metabox-holder dbp-right-box">
		<div id="form-sortables" class="meta-box-sortables ui-sortable ">
			<div class="dbp_form_settings_panel postbox">
				<div class="handlediv"><br /></div>
				<h3 class="hndle"><span>Datos de la compra</span></h3>
				<div class="inside">
					<p class="right">
						<!--<select name="invoice_status">
							<option <?php echo ($the_invoice->post_status == "unpaid") ? 'selected="selected"' : ''?> value="unpaid">No pagada</option>
							<option <?php echo ($the_invoice->post_status == "paid") ? 'selected="selected"' : ''?> value="paid">Pagada</option>
						</select>-->
						<input value="<?php echo $the_invoice_number?>" type="text" name="dbp_invoice_number" id="shopping_invoice_number" class="dbp_input_short_text right" placeholder="N&uacute;mero de factura" />
					</p>
					<label for="shopping_autocomplete">Seleccione los productos</label>
					<input type="text" name="product_search" id="shopping_autocomplete" class="dbp_input_text" placeholder="Escriba el nombre del producto..." />
					<div class="ui-widget-header ui-corner-tr ui-corner-tl" id="shopping_invoice_items_header">
						<div class="left">Descripci&oacute;n</div>
						<div class="left first">Cantidad</div>
						<div class="left second">IMP</div>
						<div class="right third">Borrar</div>
						<div class="clr"></div>
					</div>
					<div id="shopping_invoice_items" class="ui-widget-content ui-corner-bl ui-corner-br" <?php echo (isset($_REQUEST["edit"])) ? "style=\"display: block;\"" : ""?>>
						<?php if(isset($_REQUEST["edit"]) && is_array($the_invoice_items)):
							$i=0;
							foreach($the_invoice_items as $invoice_item):
							$the_product = get_post($invoice_item["post_id"])?>
								<div class="dbp-item-holder">
									<div id="get-the-item-<?php echo $the_product->ID?>" class="left"><?php echo $the_product->post_title?></div>
									<div class="left first">
										<input type="text" class="dbp_invoice_item" size="3" id="get-the-q-<?php echo $the_product->ID?>" value="<?php echo $invoice_item["quantity"]?>" name="item[<?php echo $i?>][quantity]" />
									</div>
									<div class="left second">
										<input type="checkbox" class="dbp_invoice_item" name="item[<?php echo $i?>][tax]" value="true" <?php echo ($invoice_item["tax"]=="true") ? 'checked="checked"' : ""?> />
									</div>
									<div class="right third">
										<a id="delete-item-<?php echo $i?>" class="dbp-delete" href="#">Borrar</a>
									</div>
									<input type="hidden" name="item[<?php echo $i?>][post_id]" value="<?php echo $the_product->ID?>" class="dbp_invoice_item">
									<input type="hidden" name="item[<?php echo $i?>][price]" value="<?php echo $invoice_item["price"]?>" class="dbp_invoice_item">
									<div class="clr"></div>
									<hr />
								</div>
								<?php $i++;
							endforeach;
						else:?>
						<i id="dbp-deletable">No hay productos seleccionados</i>
						<?php endif?>
					</div>
				</div>
			</div>	
		</div>
</div>
<input type="hidden" name="dbp_invoice_id" value="<?php echo $dbp_invoice_id?>" />
<p class="clr dbp_center">
	<a href="<?php echo admin_url("admin.php?page=dbp_invoices&view=$dbp_invoice_id")?>" class="button">Ver factura</a>
	<input type="submit" name="save_quit" class="button-primary" value="<?php echo (! empty ($_REQUEST["edit"])) ? "Guardar factura" : "Crear factura"?>" />
</p>
</form>
<?php 
	$args = array(
		'count_total' => false,
		'fields' => 'all_with_meta',
	 );
	$clients = get_users( $args );
	$products = $wpdb->get_results("SELECT * FROM " . $wpdb->posts . " WHERE post_status = 'publish' AND post_type = 'post'");
?>
<script type="text/javascript">
// Clients info
var clients = [<?php foreach($clients as $client):
				$client_phone_result = get_user_meta($client->ID, "dbp_client_phone", true);
				$resultado = " { id: \"" . $client->ID . "\", label : \"" . $client->user_login . " " . htmlspecialchars($client->first_name . " " . $client->last_name) . "\", value : \"" . $client->user_login . "\", first_name : \"" . $client->first_name . "\", last_name : \"" . $client->last_name . "\", email : \"" . $client->user_email . "\", address : \"" . $client->user_description . "\", phone : \"" . $client_phone_result . "\" }, ";
				trim($resultado);
				echo $resultado;
				endforeach;?>];
				
// Products info
var products = [<?php foreach($products as $product):
				$dbp_quantity_available = get_post_meta($product->ID, "dbp_product_quantity", true);
				$dbp_price = get_post_meta($product->ID, "dbp_product_price_sell", true);
				$quantity_available = ($dbp_quantity_available != "") ? $dbp_quantity_available : "0";
				$resultado = " { id: \"" . $product->ID . "\", label : \"" .htmlspecialchars($product->post_title) . "\", quantity : \"" . $quantity_available . "\", price : \"" . $dbp_price . "\" }, ";
				trim($resultado);
				echo $resultado;
				endforeach;?>];zzz
</script>
<?php if( ! empty ($_POST["save_quit"]) ):?>
<script>
	$("#<?php echo $data["field"]?>").addClass("<?php echo $data["class"]?>").focus(function(){ $(this).removeClass("ui-state-error"); $("#message").slideUp("slow"); });
	$("#message").show();
</script>
<?php endif?>
<script src="<?php echo DBP_PLUGIN_URL?>js/dbp-invoices.js"></script>
<script src="<?php echo DBP_PLUGIN_URL?>js/dbp-custom.js"></script>