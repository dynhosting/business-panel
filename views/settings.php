<?php wp_enqueue_script( 'jquery-image-selecter' );?>
<div id="icon-options-general" class="icon32"><br /></div>
<h2>Configuraci&oacute;n Dyn Bussiness Panel</h2><?php
		
		if (!current_user_can('manage_options'))
		{
		  wp_die( __('You do not have sufficient permissions to access this page.') );
		}
		
		// See if the user has posted us some information
		// If they did, this hidden field will be set to 'Y'
		if( isset($_POST[ "Submit" ]))
		{
			// Salvar datos de tienda
			update_option( "dbp_shop_name", $_POST["dbp_shop_name"] );
			update_option("dbp_shop_invoice_logo", $_POST["dbp_shop_invoice_logo"]);
			update_option("dbp_shop_invoice_banner", $_POST["dbp_shop_invoice_banner"]);
			update_option("dbp_shop_what_to_use", $_POST["dbp_shop_what_to_use"]);
			update_option( "dbp_shop_nickname", $_POST["dbp_shop_nickname"] );
			update_option( "dbp_shop_rif", $_POST["dbp_shop_rif"] );
			update_option( "dbp_shop_tlf_local", $_POST["dbp_shop_tlf_local"] );
			update_option( "dbp_shop_tlf_mobile", $_POST["dbp_shop_tlf_mobile"] );
			update_option( "dbp_shop_addr", $_POST["dbp_shop_addr"] );
			
			// Salvar datos comerciales
			update_option( "dbp_active_sales", $_POST["dbp_active_sales"] );
			update_option ("dbp_currency", $_POST["dbp_currency"] );
			update_option ("dbp_currency_symbol", $_POST["dbp_currency_symbol"] );
			update_option( "dbp_shop_tax_n", $_POST["dbp_shop_tax_n"] );
			update_option( "dbp_shop_tax_v", $_POST["dbp_shop_tax_v"] );
			
			// Salvar datos bancarios
			update_option("dbp_active_bank_transfer" , $_POST["dbp_active_bank_transfer"]);
			update_option("dbp_account_holder" , $_POST["dbp_account_holder"]);
			update_option("dbp_account_holder_document" , $_POST["dbp_account_holder_document"]);
			update_option("dbp_account_bank" , $_POST["dbp_account_bank"]);
			update_option("dbp_account_number" , $_POST["dbp_account_number"]);
			update_option("dbp_account_holder_email" , $_POST["dbp_account_holder_email"]);
			
			// Salvar datos de producto
			update_option("dbp_product_quantity_images" , $_POST["dbp_product_quantity_images"]);
			$dbp_product_categories_selected = ( $_POST["category"] != "" ) ? serialize($_POST["category"]) : '';
			update_option("dbp_product_categories", $dbp_product_categories_selected);
			?>
			<div class="updated"><p><strong>La informaci&oacute;n y configuraci&oacute;n se ha guardado</strong></p></div>
			<?php
		}
		// Leer datos de tienda
		$dbp_shop_name = get_option("dbp_shop_name");
		$dbp_shop_invoice_logo = get_option("dbp_shop_invoice_logo");
		$dbp_shop_invoice_banner = get_option("dbp_shop_invoice_banner");
		$dbp_shop_nickname = get_option("dbp_shop_nickname");
		$dbp_shop_what_to_use = get_option("dbp_shop_what_to_use");
		$dbp_shop_rif = get_option("dbp_shop_rif");
		$dbp_shop_tlf_local = get_option("dbp_shop_tlf_local");
		$dbp_shop_tlf_mobile = get_option("dbp_shop_tlf_mobile");
		$dbp_shop_addr = get_option("dbp_shop_addr");
		
		// Leer datos comerciales
		$dbp_active_sales = get_option("dbp_active_sales");
		$dbp_currency = get_option("dbp_currency");
		$dbp_currency_symbol= get_option("dbp_currency_symbol");
		$dbp_shop_tax_n = get_option("dbp_shop_tax_n");
		$dbp_shop_tax_v = get_option("dbp_shop_tax_v");
		
		// Leer datos bancarios
		$dbp_active_bank_transfer = get_option("dbp_active_bank_transfer");
		$dbp_account_holder = get_option("dbp_account_holder");
		$dbp_account_holder_document = get_option("dbp_account_holder_document");
		$dbp_account_bank = get_option("dbp_account_bank");
		$dbp_account_number = get_option("dbp_account_number");
		$dbp_account_holder_email = get_option("dbp_account_holder_email");
		
		// Leer datos de productos
		$dbp_product_quantity_images = get_option("dbp_product_quantity_images");
		$dbp_product_categories = get_option("dbp_product_categories");
		
		
		$categories = get_categories('hide_empty=0&orderby=name&order=ASC');?>
		<form action="" method="post" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
			<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
				<li class="ui-state-default ui-corner-top"><a href="#tab1" class="dbp-ui-nav">Datos de la tienda</a></li>
				<li class="ui-state-default ui-corner-top"><a href="#tab2" class="dbp-ui-nav">Datos comerciales</a></li>
				<li class="ui-state-default ui-corner-top"><a href="#tab3" class="dbp-ui-nav">Datos de pago</a></li>
				<li class="ui-state-default ui-corner-top"><a href="#tab4" class="dbp-ui-nav">Datos de productos</a></li>
			</ul>
			<div class="dbp_form_settings_panel postbox" id="tab1">
				<div class="handlediv"><br /></div>
				<h3 class="hndle"><span>Datos de la tienda</span></h3>
				<div class="inside">
						<h2>Informaci&oacute;n b&aacute;sica</h2>
						
						<p><label>Nombre de la tienda</label>
						<input type="text" class="dbp_input_text" name="dbp_shop_name" value="<?php echo $dbp_shop_name; ?>" />
						</p>
						
						<p><label>Logo</label><br />
						<input type="text" class="dbp_long_input_text" name="dbp_shop_invoice_logo" value="<?php echo $dbp_shop_invoice_logo; ?>" />
						<a href="#" class="upload_image_button button">Subir</a>
						<a href="#" class="remove_image_button button">Quitar</a><br />
						</p>
						
						<p><label>Banner</label><br />
						<input type="text" class="dbp_long_input_text" name="dbp_shop_invoice_banner" value="<?php echo $dbp_shop_invoice_banner; ?>" />
						<a href="#" class="upload_image_button button">Subir</a>
						<a href="#" class="remove_image_button button">Quitar</a><br />
						</p>
						
						<p><label>Que utilizar como encabezado de factura?</label><br />
							<input type="radio" name="dbp_shop_what_to_use" value="use_logo" id="dbp_shop_use_logo" <?php echo ($dbp_shop_what_to_use=="use_logo") ? 'checked="checked"' : ''?> />
							<label for="dbp_shop_use_logo">Usar logo</label>
							<input type="radio" name="dbp_shop_what_to_use" value="use_banner" id="dbp_shop_use_banner" <?php echo ($dbp_shop_what_to_use=="use_banner") ? 'checked="checked"' : ''?> />
							<label for="dbp_shop_use_banner">Usar banner</label>
							<input type="radio" name="dbp_shop_what_to_use" value="use_name" id="dbp_shop_use_name" <?php echo ($dbp_shop_what_to_use=="use_name") ? 'checked="checked"' : ''?> />
							<label for="dbp_shop_use_name">Usar nombre</label>
						</p>
						
						<p><label class="dpb_blocked">Nombre comercial <small>(Seud&oacute;nimo)</small></label>
						<input type="text" class="dbp_input_text" name="dbp_shop_nickname" value="<?php echo $dbp_shop_nickname; ?>" />
						</p>
						
						<p><label class="dpb_blocked">RIF</label>
						<input type="text" class="dbp_input_text" name="dbp_shop_rif" value="<?php echo $dbp_shop_rif; ?>" />
						</p>
						
						<p><label class="dpb_blocked">Tel&eacute;fono fijo</label>
						<input type="text" class="dbp_input_text" name="dbp_shop_tlf_local" value="<?php echo $dbp_shop_tlf_local; ?>" />
						</p>
						
						<p><label class="dpb_blocked">Tel&eacute;fono movil</label>
						<input type="text" class="dbp_input_text" name="dbp_shop_tlf_mobile" value="<?php echo $dbp_shop_tlf_mobile; ?>" />
						</p>
						
						<p><label class="dpb_blocked">Direcci&oacute;n</label>
						<textarea class="dbp_textarea" name="dbp_shop_addr"><?php echo $dbp_shop_addr; ?></textarea>
						</p>
						
						<p class="dbp_center"><input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" /></p>
				</div>
			</div>
			
			<div class="dbp_form_settings_panel postbox" id="tab2">
			<div class="handlediv"><br /></div>
			<h3 class="hndle"><span>Datos comerciales</span></h3>
				<div class="inside">
					<h2>Ventas/Moneda/Impuesto</h2>
						<p><label class="dpb_blocked">Ventas</label>
							<input id="dpb_sales_radio1" type="radio" class="dbp_input_radio" name="dbp_active_sales" value="sales_on" <?php echo ($dbp_active_sales=="sales_on") ? 'checked="checked"' : ''?> />
							<label for="dpb_sales_radio1">Activar</label>
							<input id="dpb_sales_radio2" type="radio" class="dbp_input_radio" name="dbp_active_sales" value="catalog_on" <?php echo ($dbp_active_sales=="catalog_on") ? 'checked="checked"' : ''?> />
							<label for="dpb_sales_radio2">Cat&aacute;logo</label>
							<input id="dpb_sales_radio3" type="radio" class="dbp_input_radio" name="dbp_active_sales" value="tpb_only_on" <?php echo ($dbp_active_sales=="tpb_only_on") ? 'checked="checked"' : ''?> />
							<label for="dpb_sales_radio3">Solo Punto de Venta</label>
							<input id="dpb_sales_radio4" type="radio" class="dbp_input_radio" name="dbp_active_sales" value="sales_off" <?php echo ($dbp_active_sales=="sales_off") ? 'checked="checked"' : ''?> />
							<label for="dpb_sales_radio4">Desactivar</label>
						</p>
						
						<p><label class="dpb_blocked">Moneda</label>
						<input type="text" class="dbp_input_text" name="dbp_currency" value="<?php echo $dbp_currency; ?>" />
						</p>
						
						<p><label class="dpb_blocked">S&iacute;mbolo de moneda</label>
						<input type="text" class="dbp_input_text" name="dbp_currency_symbol" value="<?php echo $dbp_currency_symbol; ?>" />
						</p>
						
						<p><label class="dpb_blocked">Nombre de impuesto</label>
						<input type="text" class="dbp_input_text" name="dbp_shop_tax_n" value="<?php echo $dbp_shop_tax_n; ?>" />
						</p>
						
						<p><label class="dpb_blocked">Valor del impuesto</label>
						<input type="text" class="dbp_input_text" name="dbp_shop_tax_v" value="<?php echo $dbp_shop_tax_v; ?>" />
						</p>
						
						<p class="dbp_center"><input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" /></p>
					
				</div>
			</div>
			
			<div class="dbp_form_settings_panel postbox" id="tab3">
				<div class="handlediv"><br /></div>
				<h3 class="hndle"><span>Datos de pago</span></h3>
				<div class="inside">
						<h2>Cuenta de banco</h2>
						<p>
						<input id="dpb_bank_checkbox" type="checkbox" class="dbp_input_checkbox" name="dbp_active_bank_transfer" value="transfer_on" <?php echo ($dbp_active_bank_transfer=="transfer_on") ? 'checked="checked"' : ''?> />
							<label for="dpb_bank_checkbox">Activar transferencias</label>
						</p>
						
						<p><label class="dpb_blocked">Titular de la cuenta</label>
						<input type="text" class="dbp_input_text" name="dbp_account_holder" value="<?php echo $dbp_account_holder; ?>" />
						</p>
						
						<p><label class="dpb_blocked">Documento de identificaci&oacute;n <small>(ID, RIF, etc)</small></label>
						<input type="text" class="dbp_input_text" name="dbp_account_holder_document" value="<?php echo $dbp_account_holder_document; ?>" />
						</p>
						
						<p><label class="dpb_blocked">Banco</label>
						<input type="text" class="dbp_input_text" name="dbp_account_bank" value="<?php echo $dbp_account_bank; ?>" />
						</p>
						
						<p><label class="dpb_blocked">Cuenta bancaria</label>
						<input type="text" class="dbp_input_text" name="dbp_account_number" value="<?php echo $dbp_account_number; ?>" />
						</p>
						
						<p><label class="dpb_blocked">Email</label>
						<input type="text" class="dbp_input_text" name="dbp_account_holder_email" value="<?php echo $dbp_account_holder_email; ?>" />
						</p>
						
						<p class="dbp_center"><input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" /></p>
				</div>
			</div>
			
			<div class="dbp_form_settings_panel postbox" id="tab4">
				<div class="handlediv"><br /></div>
				<h3 class="hndle"><span>Datos de productos</span></h3>
				<div class="inside">
						<h2>Im&aacute;genes de productos</h2>
						
						<p><label class="dpb_blocked">Cantidad de im&aacute;genes para cada producto</label>
						<input type="text" class="dbp_input_text" name="dbp_product_quantity_images" value="<?php echo $dbp_product_quantity_images; ?>" />
						</p>
						
						<ol class="dbp-settings-ol">
						<?php $i=1;
							$selected_categories = ( $dbp_product_categories != "" && ! is_array( $dbp_product_categories ) ) ? unserialize ( $dbp_product_categories ) : array();
							foreach($categories as $categoria):?>
							<li><input <?php echo (in_array($categoria->term_id, $selected_categories)) ? "checked=\"checked\"" : ""?>  type="checkbox" name="category[<?php echo $i?>]" value="<?php echo $categoria->term_id?>" id="cat_<?php echo $i?>" />&nbsp;<label for="cat_<?php echo $i?>"><?php echo $categoria->name ?></label></li>
							<?php $i++; endforeach;?>
							<li style="list-style:none;">
								<div class="dbp-buttonset">
									<input type="checkbox" id="select-all" />
									<label for="select-all">Seleccionar todas</label>
								</div>
							</li>
						</ol>
						
						<p class="dbp_center"><input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" /></p>
				</div>
			</div>
		</form>
<script>
$(".dbp_form_settings_panel").hide();

$("#select-all").click(function(){
	if($(this).attr("checked")=="checked") {
		$(".dbp-settings-ol input:checkbox").attr("checked","checked");
		}else{
		$(".dbp-settings-ol input:checkbox").removeAttr("checked");
		}
	});
	
var the_hash = window.location.hash;
if(the_hash=="")
{
	$("#tab1").fadeIn();
	$("a[href=#tab1]").parent("li").addClass("ui-tabs-selected, ui-state-active");
}
else
{
	$(".dbp_form_settings_panel").fadeOut();
	$(the_hash).show();
	$("a[href=" + the_hash + "]").parent("li").addClass("ui-tabs-selected, ui-state-active");
}

$(".dbp-ui-nav").click(function(){
	var show = $(this).attr("href");
	$(".dbp_form_settings_panel").fadeOut();
	$(".ui-tabs-selected, .ui-state-active").removeClass("ui-tabs-selected, ui-state-active");
	$("a[href=" + show + "]").parent("li").addClass("ui-tabs-selected, ui-state-active");
	$(show).fadeIn();
	return false;
	});

$(".remove_image_button").click(function(){
	var empty_it = '';
	$(this).prev().prev('input.dbp_long_input_text').val(empty_it);
	});
</script>
<script src="<?php echo DBP_PLUGIN_URL?>js/dbp-custom.js"></script>