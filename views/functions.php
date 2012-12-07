<?php

function dbp_settings(){ include(dirname(__FILE__) . "/settings.php"); }

function dbp_index()
{
	dbp_header("Dyn Bussiness Panel");?>
	
	</div><?php
}

function dbp_invoices()
{
	if( ! empty ( $_REQUEST["edit"] ) ):
		include(dirname(__FILE__) . "/invoices/edit.php");
	elseif( ! empty ( $_REQUEST["view"] ) ):
		wp_enqueue_style( 'dbp-printer' );
		include(dirname(__FILE__) . "/invoices/view.php");
	elseif( ! empty ( $_REQUEST["payment"] ) ):
		include(dirname(__FILE__) . "/invoices/payment.php");
	else:
		include(dirname(__FILE__) . "/invoices/index.php");
	endif;
}

function dbp_clients()
{
	if( ! empty ( $_REQUEST["edit"] ) )
		include(dirname(__FILE__) . "/clients/edit.php");
	elseif( ! empty ( $_REQUEST["view"] ) )
		include(dirname(__FILE__) . "/clients/view.php");
	else
		include(dirname(__FILE__) . "/clients/index.php");
}

function dbp_sellers(){ include(dirname(__FILE__) . "/sellers.php"); }

function dbp_suppliers(){ include(dirname(__FILE__) . "/supliers.php"); }

function dbp_products()
{
	if( empty( $_REQUEST["edit"] ) )
		include(dirname(__FILE__) . "/products/index.php");
	else
		include(dirname(__FILE__) . "/products/edit.php"); wp_enqueue_script( 'jquery-image-selecter' );
}


// Validation and stuff

function valid_email($str)
{
	return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
}

function get_dbp_invoice_number()
{
	global $wpdb;
	$invoice_numbers = $wpdb->get_row("SELECT * FROM " . $wpdb->postmeta . " WHERE meta_key = 'dbp_invoice_number' ORDER BY meta_value DESC LIMIT 1");
	$total = count($invoice_numbers);
	$next_invoice = $invoice_numbers->meta_value;
	$next_invoice++;
	return ($total == 0) ? ($total+1) : $next_invoice;
}

function dbp_header($heading = array())
{
	$defaults = array(
		"heading"	=> "Facturas",
		"page"	=> "dbp_invoices",
		"id"	=> "icon-invoice-page",
		"url"	=> admin_url('admin.php?page=dbp_invoices&edit=new'),
		"new-text"	=> "Nueva entrada"
		);
		
	$headings = wp_parse_args($heading, $defaults);
	?>
    <div class="wrap">
        
        <div id="<?php echo $headings["id"]?>" class="icon32 icon32-posts-post"><br /></div>
        <h2><?php echo $headings["heading"]?>
        <a id="<?php echo $headings["id"]?>-new" href="<?php echo $headings["url"]?>" class="add-new-h2" /><?php echo $headings["new-text"]?></a></h2><?php
}