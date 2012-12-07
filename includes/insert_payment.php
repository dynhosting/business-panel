<?php
$post = (object)$_POST;
$invoice_status = get_post_status($post->the_invoice_id);

$the_invoice_payment_history = unserialize ( get_post_meta($post->the_invoice_id, "dbp_invoice_payments", true) );
$the_invoice_items = unserialize ( get_post_meta($post->the_invoice_id, "dbp_invoice_item", true) );

if($invoice_status == "paid")
{
	$error = 1;
	$message = "No puede agregar m&aacute;s pagos a esta factura";
	$class = "ui-state-error";
	$field = "";
}
elseif( empty ($post->dbp_payment_ammount ) OR empty ($post->dbp_payment_date ) )
{
	$error = 1;
	$message = "Debe escribir una cantidad y una fecha de pago";
	$class = "ui-state-error";
	$field = "#dbp_payment_ammount, #dbp_payment_date";
}
elseif( ! is_numeric ( $post->dbp_payment_ammount ) )
{
	$error = 1;
	$message = "La cantidad pagada debe contener solo n&uacute;meros";
	$class = "ui-state-error";
	$field = "#dbp_payment_ammount";
}

$invoice_total = 0;
$invoice_tax = 0;

foreach($the_invoice_items as $invoice_item)
{
	$total_item = $invoice_item["quantity"]*$invoice_item["price"];
	$tax = ($invoice_item["tax"]=="true") ? ($total_item*str_replace('%', '', get_option('dbp_shop_tax_v')))/100 : 0;
	
	$invoice_total += $total_item;
	$invoice_tax += $tax;
}

$invoice_total_ammount = $invoice_total + $invoice_tax;

$payments_history=0;

if( is_array( $the_invoice_payment_history ) )
{
	foreach($the_invoice_payment_history as $payment)
	{
		$payments_history += $payment["payment_ammount"];
	}
}

$payments_total = $payments_history + $post->dbp_payment_ammount;

if($payments_total > $invoice_total_ammount)
{
	$error = 1;
	$message = "No puede ingresar un pago mas alto que el total de la factura";
	$class = "ui-state-error";
	$field = "#dbp_payment_ammount";
}

if( ! isset ($error) )
{	
	$rest = $invoice_total_ammount - $payments_total;

	$the_invoice_payment_history[] = array(
		"payment_type"		=> $post->dbp_payment_type,
		"payment_date"		=> $post->dbp_payment_date,
		"payment_ammount"	=> $post->dbp_payment_ammount
		);
	
	$dbp_invoice_payment_history = serialize($the_invoice_payment_history);
	
	update_post_meta( $post->the_invoice_id, "dbp_invoice_payments", $dbp_invoice_payment_history);
	
	if($rest == 0)
	{
		$invoicedata = array(
			'post_status'	=> "paid",
			'ID'	=> $post->the_invoice_id
			);

		wp_update_post($invoicedata);
	}
	
	$message = "Se ha insertado el pago";
	$class = "ui-state-highlight";
}