<?php 
	$invoice = (object)$_POST;
	
	$error = 0;
	
	if($invoice->client_email != "" && !valid_email($invoice->client_email)):
		$error = 1; $message = "La direcci&oacute;n de email no es v&aacute;lida"; $field = 'client_email';
	elseif(!is_numeric($invoice->client_phone)):
		$error = 1; $message = "El tel&eacute;fono debe contener solo n&uacute;meros"; $field = 'client_phone';
	endif;
	
	if($error)
	{
		$data = array(
			'message'	=> $message,
			'class'		=> 'ui-state-error',
			'field'		=> $field
			);
	}
	else
	{
		if($invoice->client_update_info == "dbp_update")
		{
			$user_data = array(
				"user_nicename"	=> $invoice->client_company_name,
				"user_email"	=> $invoice->client_email,
				"first_name"	=> $invoice->client_firstname,
				"last_name"		=> $invoice->client_lastname,
				"description"	=> $invoice->client_address,
				"ID"			=> $invoice->client_id
				);
		}
		elseif($invoice->client_id == "")
		{
			$user_data = array(
				"user_login"	=> $invoice->client_username,
				"user_pass"		=> $invoice->client_username,
				"user_nicename"	=> $invoice->client_company_name,
				"user_email"	=> $invoice->client_email,
				"first_name"	=> $invoice->client_firstname,
				"last_name"		=> $invoice->client_lastname,
				"description"	=> $invoice->client_address,
				"role"			=> "client",
				"ID"			=> $invoice->client_id
				);
		}
		else
			$user_data = false;
			
		$client_id = (!$user_data) ? $invoice->client_id : wp_insert_user($user_data);

		update_user_meta( $client_id, "dbp_client_phone", $invoice->client_phone, true);
		
		$invoice_title = ($invoice->client_company_name != "") ? $invoice->client_company_name . " - " . $invoice->client_fistname . " " . $invoice->client_lastname : $invoice->client_firstname . " " . $invoice->client_lastname;
		
		$invoicedata = array(
			'post_status'	=> "unpaid",
			'post_title'	=> $invoice->client_company_name . " " . $invoice->client_firstname . ' ' . $invoice->client_lastname,
			'post_type'		=> 'dbp_invoice'
			);
		

		$invoicedata['ID'] = $invoice->dbp_invoice_id;

		wp_update_post($invoicedata);
		
		update_post_meta($invoice->dbp_invoice_id, "dbp_client_id", $client_id);
		update_post_meta($invoice->dbp_invoice_id, "dbp_invoice_number", $invoice->dbp_invoice_number);
		update_post_meta($invoice->dbp_invoice_id, "dbp_payment_reference", $invoice->dbp_payment_reference);
		
		$current_invoice_items = unserialize ( get_post_meta($invoice->dbp_invoice_id, "dbp_invoice_item", true) );
		
		if(is_array($current_invoice_items))
		{
			foreach($current_invoice_items as $current_item)
			{
				$curent_quantity_available = get_post_meta($current_item["post_id"], "dbp_product_quantity", true);
				
				$plus = $curent_quantity_available+$current_item["quantity"];
				
				update_post_meta($current_item["post_id"], "dbp_product_quantity", $plus);
			}
		}
		
		foreach($invoice->item as $item)
		{
			$quantity_available = get_post_meta($item["post_id"], "dbp_product_quantity", true);
			
			$rest = $quantity_available-$item["quantity"];
			
			update_post_meta($item["post_id"], "dbp_product_quantity", $rest);
			
			$dbp_invoice_item[] = $item;
		}
		$dbp_invoice_items = serialize($dbp_invoice_item);
		update_post_meta($invoice->dbp_invoice_id, "dbp_invoice_item", $dbp_invoice_items);
		
		$data = array(
			'message'	=> 'Se ha guardado la factura <a href="' . admin_url("admin.php?page=dbp_invoices&view=" . $invoice->dbp_invoice_id) . '">Ver factura</a>',
			'class'	=> 'ui-state-highlight',
			'dbp_invoice_id'	=> $invoice_id
			);
	}