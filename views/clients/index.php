<?php
$testListTable = new dbp_clients();
$testListTable->prepare_items();

$heading_args = array(
		"heading"	=> "Clientes",
		"page"	=> "dbp_invoices",
		"id"	=> "icon-sellers-page",
		"url"	=> admin_url('admin.php?page=dbp_clients&edit=new')
		);
dbp_header($heading_args);?>
	<!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
	<form id="movies-filter" method="get">
		<!-- For plugins, we also need to ensure that the form posts back to our current page -->
		<input type="text" name="page" id="dbp_search_client" placeholder="Buscar cliente..." />
		<!-- Now we can render the completed list table -->
		<?php $testListTable->display() ?>
	</form>
</div>
<?php $args = array(
		'count_total' => false,
		'fields' => 'all_with_meta',
	 );
	 $clients = get_users( $args );?>
<script>
// Clients info
var clients = [<?php foreach($clients as $client):
				$client_phone_result = get_user_meta($client->ID, "dbp_client_phone", true);
				$resultado = " { id: \"" . $client->ID . "\", label : \"" . $client->user_login . " " . htmlspecialchars($client->first_name . " " . $client->last_name) . "\", value : \"" . $client->user_login . "\", first_name : \"" . $client->first_name . "\", last_name : \"" . $client->last_name . "\", email : \"" . $client->user_email . "\", address : \"" . $client->user_description . "\", phone : \"" . $client_phone_result . "\" }, ";
				trim($resultado);
				echo $resultado;
				endforeach;?>];
var theUrl = "<?php echo admin_url("admin.php?page=dbp_clients&view=")?>";
</script>
<script type="text/javascript" src="<?php echo DBP_PLUGIN_URL?>js/dbp-clients.js"></script>