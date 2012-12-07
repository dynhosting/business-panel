<?php require_once("../../../../../wp-load.php");
header('Content-type: application/pdf');
header('Content-Disposition: compress; filename="downloaded.pdf"');


/* Get Invoices header */
// Leer datos de tienda
	$dbp_shop_name = get_option("dbp_shop_name");
	$dbp_shop_invoice_logo = get_option("dbp_shop_invoice_logo");
	$dbp_shop_invoice_banner = get_option("dbp_shop_invoice_banner");
	$dbp_shop_nickname = get_option("dbp_shop_nickname");
	$dbp_shop_what_to_use = get_option("dbp_shop_what_to_use");
	$dbp_shop_nickname = get_option("dbp_shop_nickname");
	$dbp_shop_rif = get_option("dbp_shop_rif");
	$dbp_shop_tlf_local = get_option("dbp_shop_tlf_local");
	$dbp_shop_tlf_mobile = get_option("dbp_shop_tlf_mobile");
	$dbp_shop_addr = get_option("dbp_shop_addr");
/* end header */

$dbp_active_bank_transfer = get_option("dbp_active_bank_transfer");

$dbp_invoice_id = $_REQUEST["invoice_id"];
$new_invoice_number = get_dbp_invoice_number();
if(! empty ( $_REQUEST["invoice_id"] ) )
{
	$the_invoice = get_post($dbp_invoice_id);
	$invoice_number = get_post_meta($dbp_invoice_id, "dbp_invoice_number", true);
	$the_invoice_number = ($invoice_number != "") ? $invoice_number : $new_invoice_number;
	$the_invoice_items = unserialize ( get_post_meta($dbp_invoice_id, "dbp_invoice_item", true) );
	$the_invoice_payment_history = unserialize ( get_post_meta($dbp_invoice_id, "dbp_invoice_payments", true) );
	$invoice_status = get_post_status_object($the_invoice->post_status);
	
	$the_client_id = get_post_meta($dbp_invoice_id, "dbp_client_id", true);
	$the_client = get_userdata($the_client_id);
	$the_client_phone = get_user_meta( $the_client_id, "dbp_client_phone", true );
}?>

<div id="poststuff" class="metabox-holder">
	<section id="dbp-invoice-header">
		<table width="100%">
			<tr>
				<td width="80%">
					<?php switch($dbp_shop_what_to_use):
						case "use_name":?>
							<h1 class="dbp-invoice-header"><?php echo $dbp_shop_name?></h1>
							<h2 class="dbp-invoice-subheading"><?php echo $dbp_shop_nickname?></h2>
						<?php break;
						case "use_logo":?>
							<h1><img src="<?php echo $dbp_shop_invoice_logo?>" align="left" /><?php echo $dbp_shop_name?><br /><small><?php echo $dbp_shop_nickname?></small></h1>
						<?php break;
						case "use_banner":?>
							<img src="<?php echo $dbp_shop_invoice_banner?>" />
						<?php break;
					endswitch?>
				</td>
				<td>
					<strong class="dbp-invoice-number">Factura N&num;<?php echo $the_invoice_number?></strong>
					<br />
					<i><?php echo date("d-m-Y", strtotime($the_invoice->post_date))?></i>
					<br />
					<strong class="<?php echo ($invoice_status->name == "unpaid") ? 'dbp-sold' : ($invoice_status->name == "unpaiable") ? 'dbp-unpaiable' : 'dbp-available'?>"><?php echo $invoice_status->label ?></strong>
				</td>
			<tr>
			<tr>
				<td colspan="2">
					<p class="dbp-invoice-shop-info">
						RIF: <?php echo $dbp_shop_rif?><br />
						Direcci&oacute;n: <?php echo $dbp_shop_addr?><br />
						Tel&eacute;fonos: <?php echo ($dbp_shop_tlf_local != "") ? "$dbp_shop_tlf_local / " : ""?><?php echo $dbp_shop_tlf_mobile?>
					</p>
				</td>
			</tr>
		</table>
	</section>
	<hr />
	<section id="dbp-invoice-clientinfo">
		<table width="100%">
			<tr>
				<td width="80%">
					<strong>Raz&oacute;n social: </strong>
						<?php echo ( isset($_REQUEST["invoice_id"]) AND ($the_client->first_name == "" && $the_client->last_name == "") ) ? $the_client->user_nicename : ''?>
						<?php echo ( isset($_REQUEST["invoice_id"]) AND ($the_client->first_name != "" && $the_client->last_name != "")) ? $the_client->first_name . " " . $the_client->last_name : ''?>
					</strong>
				</td>
				<td>
					<strong>C&eacute;dula/RIF: </strong> <?php echo ( isset($_REQUEST["invoice_id"]) ) ? $the_client->user_login : ''?>
				</td>
			</table>
		<table width="100%">
			<tr>
				<td colspan="2">
					<strong>Direcci&oacute;n: </strong><?php echo ( isset($_REQUEST["invoice_id"]) ) ? $the_client->description : ''?>
				</td>
			</tr>
			<tr>
				<?php if( isset($_REQUEST["invoice_id"]) && $the_client->user_email != ""):?>
				<td>
					<strong>Email: </strong><span style="text-transform: lowercase"><?php echo ( isset($_REQUEST["invoice_id"]) ) ? $the_client->user_email : ''?></span>
				</td>
				<?php endif?>
				<td>
					<strong>Tel&eacute;fono: </strong><?php echo ( isset($_REQUEST["invoice_id"]) ) ? $the_client_phone : ''?>
				</td>
			</tr>
		</table>
	</section>
	<hr />
	<section id="dbp-invoice-items">
		<table width="100%" cellpadding="4" cellspacing="0" border="1" id="dbp-invoice-items-table">
			<tr class="ui-widget-header">
				<th>Descripci&oacute;n</th>
				<th>Cantidad</th>
				<th>Precio</th>
				<th>Total</th>
			</tr>
			<?php if(isset($_REQUEST["invoice_id"]) && is_array($the_invoice_items)):
				#$invoice_item["tax"]=="true"
				$invoice_total=0;
				$invoice_tax=0;
				foreach($the_invoice_items as $invoice_item):
				$the_product = get_post($invoice_item["post_id"]);
				//$the_product_price = get_post_meta($invoice_item["post_id"], "dbp_product_price", true);
				$the_product_total = $invoice_item["price"] * $invoice_item["quantity"];
				$the_product_tax = ($invoice_item["tax"]=="true") ? ($the_product_total*str_replace('%', '', get_option('dbp_shop_tax_v')))/100 : 0	?>
					<tr>
						<td>
							<?php echo $the_product->post_title?>
							<?php echo ($invoice_item["tax"] == "") ? "(E)" : ""?>
						</td>
						<td align="center">
							<?php echo $invoice_item["quantity"]?>
						</td>
						<td align="right">
							<?php echo get_option("dbp_currency_symbol") . " " . number_format($invoice_item["price"],2,',','.')?>
						</td>
						<td align="right">
							<?php echo get_option("dbp_currency_symbol") . " " . number_format($the_product_total,2,',','.')?>
						</td>
					</tr>
					<?php $i++;
				$invoice_total += $the_product_total;
				$invoice_tax += $the_product_tax;
				endforeach;
			endif?>
			<tr>
				<td colspan="2"  rowspan="3" class="left-blank">&nbsp;</td>
				<td align="right"><b>Subtotal</b></td>
				<td align="right">
					<b><?php echo get_option("dbp_currency_symbol") . " " . number_format($invoice_total,2,',','.')?></b>
				</td>
				
			</tr>
			<?php if(get_option("dbp_shop_tax_n") != ""):?>
				<tr>
					<td align="right"><b><?php echo get_option("dbp_shop_tax_n") . " " . get_option("dbp_shop_tax_v")?></b></td>
					<td align="right">
						<b><?php echo get_option("dbp_currency_symbol") . " " . number_format($invoice_tax,2,',','.')?></b>
					</ditd>
				</tr>
			<?php endif?>
			<tr>
				<td align="right"><b>Total</b></td>
				<td align="right">
					<b><?php echo get_option("dbp_currency_symbol") . " " . number_format(($invoice_total+$invoice_tax),2,',','.')?></b>
				</td>
			</tr>
		</table>
	</section>
	<hr />
	<?php if($invoice_status->name == "unpaiable"):
	$unpaiable_data = get_post_meta($dbp_invoice_id, "dbp_invoice_unpaiable_data", true);?>
	<section id="dbp-invoice-history">
		<strong id="dbp-invoice-history-title" class="dbp-unpaiable">Factura imposible de cobrar</strong>
		<p><b>Fecha: </b><span class="dbp-unpaiable"><?php echo date("d-m-Y", strtotime($unpaiable_data["date"]))?></span></p>
		<p><b>Mercanc&iacute;a entregada: </b><span class="dbp-unpaiable"><?php echo ($unpaible_data["delivered"]=="yes") ? "S&iacute;" : "No"?></span></p>
		<p><b>Observaciones: </b><span class="dbp-unpaiable"><?php echo $unpaiable_data["observation"]?></span></p>
	</section>
	<?php else:?>
	<section id="dbp-invoice-history">
		<strong id="dbp-invoice-history-title">Historial de pago</strong>
		<div id="dbp-invoice-new-payment">
		<?php if( is_array( $the_invoice_payment_history) ):?>
			<table width="50%" cellspacing="0" cellpadding="3" id="dbp-payment-history-table" border="1">
				<tr class="ui-widget-header">
					<th>Fecha</th>
					<th>Tipo de pago</th>
					<th>Cantidad</th>
				</tr>
			<?php 
			$total_payments=0;
			foreach( $the_invoice_payment_history as $payment ):
			switch($payment["payment_type"])
			{
				case "cash" : $payment_type = "Efectivo"; break;
				case "transfer" : $payment_type = "Transferencia bancaria"; break;
			}?>
			
			
				<tr>
					<td><?php echo date("d-m-Y", strtotime($payment["payment_date"]))?></td>
					<td><?php echo $payment_type?></td>
					<td align="right"><?php echo get_option("dbp_currency_symbol") . number_format($payment["payment_ammount"],2,',','.')?></td>
				</tr>
			<?php $total_payments += $payment["payment_ammount"]; endforeach?>
				<tr>
					<td colspan="2" align="right"><strong><i>Total pagado</i></strong></td>
					<td align="right"><strong><?php echo get_option("dbp_currency_symbol") . number_format($total_payments,2,',','.')?></strong></td>
				</tr>
				<?php $payment_rest = ($invoice_total+$invoice_tax)-$total_payments;
				if($payment_rest > 0):?>
					<tr>
						<td colspan="2" align="right"><strong><i>Resta</i></strong></td>
						<td align="right"><strong><?php echo get_option("dbp_currency_symbol") . number_format($payment_rest,2,',','.')?></strong></td>
					</tr>
				<?php endif?>
			</table>
		<?php else:?>
			<h4><i>No hay pagos registrados</i></h4>
		<?php endif?>
		</div>
	</section>
	<?php endif?>
</div>