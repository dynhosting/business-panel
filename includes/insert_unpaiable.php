<?php
$post = (object)$_POST;

if( empty ( $post->dbp_unpaiable_date ) )
{
	$error = 1;
	$message = "Debe colocar la fecha";
	$class = "ui-state-error";
	$field = "#dbp_unpaiable_date";
}
elseif( empty ($post->dbp_unpaiable_delivered ) )
{
	$error = 1;
	$message = "Debe decir si se entreg&oacute; o no la mercanc&iacute;a";
	$class = "ui-state-error";
	$field = "";
}

if( !isset($error) )
{
	if($post->dbp_unpaiable_delivered == "no")
	{
		$the_invoice_items = unserialize ( get_post_meta($post->the_invoice_id, "dbp_invoice_item", true) );
		
		foreach($the_invoice_items as $invoice_item)
		{
			$quantity_available = get_post_meta($invoice_item["post_id"], "dbp_product_quantity", true);
			$new_quantity_available = $invoice_item["quantity"]+$quantity_available;
			
			update_post_meta($invoice_item["post_id"], "dbp_product_quantity", $new_quantity_available);
		}
	}
	
	$unpaiable_data = array(
		"date"	=> $post->dbp_unpaiable_date,
		"delivered"	=> $post->dbp_unpaiable_delivered,
		"observation"	=> $post->dbp_unpaiable_reason
		);
		
	update_post_meta($post->the_invoice_id, "dbp_invoice_unpaiable_data", $unpaiable_data );
	
	$invoicedata = array(
		'post_status'	=> "unpaiable",
		'ID'	=> $post->the_invoice_id
		);
		
	wp_update_post($invoicedata);

	$message = "La factura se guard&oacute; como no cobrada";
	$class = "ui-state-highlight";
	$field = "";
}