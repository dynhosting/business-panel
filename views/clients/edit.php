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
$dbp_client_id  = ( ! empty ( $_REQUEST["edit"] ) && $_REQUEST["edit"] != "new" ) ? $_REQUEST["edit"] : "0";
$new_invoice_number = get_dbp_invoice_number();
if(isset($_REQUEST["edit"]))
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
					<label class="dbp_blocked" for="client_username">Rif o la c&eacute;dula del cliente <small>(Esto no se puede cambiar)</small></label>
					<input type="hidden" name="client_id" id="client_id" value="<?php echo ( isset($_REQUEST["edit"]) ) ? $the_client->ID : ''?>" />
					<input type="text" name="client_username" <?php echo ( isset ( $_REQUEST["edit"] ) && $_REQUEST["edit"] != "new" ) ? "readonly=\"readonly\"" : ""?> id="client_username" class="dbp_input_text required" placeholder="Cedula o RIF..." value="<?php echo ( isset($_REQUEST["edit"]) ) ? $the_client->user_login : ''?>" />
					<label class="dbp_blocked" for="client_company_name">Raz&oacute;n social</label>
					<input type="text" name="client_company_name" id="client_company_name" class="dbp_input_text required" placeholder="Raz&oacute;n social..." value="<?php echo ( isset($_REQUEST["edit"]) ) ? $the_client->user_nicename : ''?>" />
					<label class="dbp_blocked" for="client_firstname">Nombres</label>
					<input type="text" name="client_firstname" id="client_firstname" class="dbp_input_text required" placeholder="Nombre..." value="<?php echo ( isset($_REQUEST["edit"]) ) ? $the_client->first_name : ''?>" />
					<label class="dbp_blocked" for="client_lastname">Apellidos</label>
					<input type="text" name="client_lastname" id="client_lastname" class="dbp_input_text required" placeholder="Apellido..." value="<?php echo ( isset($_REQUEST["edit"]) ) ? $the_client->last_name : ''?>" />
					<label class="dbp_blocked" for="client_address">Direcci&oacute;n</label>
					<input type="text" name="client_address" id="client_address" class="dbp_input_text required" placeholder="Direcci&oacute;n..." value="<?php echo ( isset($_REQUEST["edit"]) ) ? $the_client->description : ''?>" />
					<label class="dbp_blocked" for="client_email">Email</label>
					<input type="text" name="client_email" id="client_email" class="dbp_input_text" placeholder="Email..." value="<?php echo ( isset($_REQUEST["edit"]) ) ? $the_client->user_email : ''?>" />
					<label class="dbp_blocked" for="client_phone">Tel&eacute;fono</label>
					<input type="text" name="client_phone" id="client_phone" class="dbp_input_text required" placeholder="Tel&eacute;fono..." value="<?php echo ( isset($_REQUEST["edit"]) ) ? $the_client_phone : ''?>" /><br />
				</div>
			</div>	
		</div>
</div>

<div id="poststuff dbp-modal-window" class="metabox-holder dbp-right-box">
		<div id="form-sortables" class="meta-box-sortables ui-sortable ">
			<div class="dbp_form_settings_panel postbox">
				<div class="handlediv"><br /></div>
				<h3 class="hndle"><span>Imagen del cliente</span></h3>
				<div class="inside">
					<div id="dbp-images-holder">
						<input type="text" name="src" id="image_src" placeholder="Seleccione una imagen" value="<?php echo $the_client_img?>" class="dbp_long_input_text"
						value = "" />
						<a href="#" id="image_button" class="upload_image_button button">Subir</a>
						<a href="#" id="remove_image_button" class="remove_image_button button">Quitar</a><br />
					</div>
					<img id="only-one" src="<?php echo $the_client_img?>" />
				</div>
			</div>	
		</div>
</div>

<input type="hidden" name="dbp_invoice_id" value="<?php echo $dbp_invoice_id?>" />
<p class="clr dbp_center">
	<?php if( isset ( $data["dbp_client_id"] ) OR $dbp_client_id != "new" ):
		$get_the_id = ( isset ( $data["dbp_client_id"] ) ) ? $data["dbp_client_id"] : $dbp_client_id?>
		<a href="<?php echo admin_url("admin.php?page=dbp_clients&view=$get_the_id")?>" class="button">Ver cliente</a>
	<?php endif?>
	<input type="submit" name="save_quit" class="button-primary" value="<?php echo (! empty ($_REQUEST["edit"])) ? "Guardar cliente" : "Crear cliente"?>" />
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