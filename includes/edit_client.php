<?php 
	$client = (object)$_POST;
	
	$error = 0;
	
	if($client->client_email != "" && !valid_email($client->client_email)):
		$error = 1; $message = "La direcci&oacute;n de email no es v&aacute;lida"; $field = 'client_email';
	elseif(!is_numeric($client->client_phone)):
		$error = 1; $message = "El tel&eacute;fono debe contener solo n&uacute;meros"; $field = 'client_phone';
	elseif( $client->client_id == "" && username_exists ($client->client_username ) ):
		$error = 1; $message = "El nombre de usuario ya esta siendo utilizado"; $field = 'client_username';
	elseif( $client->client_id == "" && email_exists ($client->client_email ) ):
		$error = 1; $message = "El email ya esta siendo utilizado"; $field = 'client_username';
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
		if($client->client_id != "")
		{
			$user_data = array(
				"user_nicename"	=> $client->client_company_name,
				"user_email"	=> $client->client_email,
				"first_name"	=> $client->client_firstname,
				"last_name"		=> $client->client_lastname,
				"description"	=> $client->client_address,
				"ID"			=> $client->client_id
				);
				
			$client_id = wp_update_user($user_data);
		}
		else
		{
			$client_id = wp_create_user($client->client_username, $client->client_username);
			$user_data = array(
				"user_nicename"	=> $client->client_company_name,
				"user_email"	=> $client->client_email,
				"first_name"	=> $client->client_firstname,
				"last_name"		=> $client->client_lastname,
				"description"	=> $client->client_address,
				"ID"			=> $client_id
				);
				
			wp_update_user($user_data);
		}

		update_user_meta( $client_id, "dbp_client_phone", $client->client_phone, true);
		update_user_meta( $client_id, "dbp_client_img", $client->src, true);
		
		$data = array(
			'message'	=> 'Se ha guardado la factura <a href="' . admin_url("admin.php?page=dbp_clients&view=" . $client_id) . '">Ver cliente</a>',
			'class'	=> 'ui-state-highlight',
			'dbp_client_id'	=> $client_id
			);
	}