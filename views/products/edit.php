<?php 
dbp_header($heading);
if(! empty ($_POST["save"] )):
	require_once( DBP_PLUGIN_DIR . "/includes/edit_product.php");
?>
	<div id="message" class="updated <?php echo $class?>"><p><strong><?php echo $message?></strong></p></div>
<?php endif;
if(isset($_REQUEST["edit"]))
{
	$dbp_product_id = $_REQUEST["edit"];
	$the_product = get_post($dbp_product_id);
	$the_product_price = get_post_meta($dbp_product_id, "dbp_product_price", true);
	$dbp_product_price_sell = get_post_meta($dbp_product_id, "dbp_product_price_sell", true);
	$the_product_quantity = get_post_meta($dbp_product_id, "dbp_product_quantity", true);
	$the_product_unit_singular = get_post_meta($dbp_product_id, "dbp_product_unit_singular", true);
	$dbp_product_unit_plural = get_post_meta($dbp_product_id, "dbp_product_unit_plural", true);
	$the_product_sold = get_post_meta($dbp_product_id, "dbp_product_sold", true);
	$the_product_blocked = get_post_meta($dbp_product_id, "dbp_product_blocked", true);
	$dbp_promotion_active = get_post_meta($dbp_product_id, "dbp_promotion_active", true);
	$the_product_init_promotion = get_post_meta($dbp_product_id, "dbp_product_init_promotion", true);
	$the_product_end_promotion = get_post_meta($dbp_product_id, "dbp_product_end_promotion", true);
	$the_product_images = unserialize(get_post_meta($dbp_product_id, "dbp_product_images", true));
	$the_product_quantity_history = unserialize ( get_post_meta($dbp_product_id, "dbp_product_quantity_history", true) );
}?>
<form action="" method="post" id="dbp-create-invoice">
<div id="message" class="ui-corner-all"></div>
<div class="dbp-left-box">
	<div class="metabox-holder">
		<div id="form-sortables" class="meta-box-sortables ui-sortable ">
			<div class="dbp_form_settings_panel postbox">
				<div class="handlediv"><br /></div>
				<h3 class="hndle"><span>Nueva Compra</span></h3>
				<div class="inside">
					<h2><?php echo $the_product->post_title?></h2>
					<input type="hidden" name="product_id" id="product_id" value="<?php echo $the_product->ID?>" />
					<input type="text" name="product_date" id="product_date" class="dbp_input_text required" placeholder="Fecha de compra..." /><br />
					<input type="text" name="product_price" id="product_price" class="dbp_input_text required" placeholder="Precio de compra..." /><br />
					<input type="text" name="product_price_sell" id="product_price_sell" class="dbp_input_text required" placeholder="Precio de venta..." /><br />
					<input type="text" name="product_quantity" id="product_quantity" class="dbp_input_text required" placeholder="Cantidad comprada..." /><br />
					<input type="text" name="product_unit_singular" id="product_unit_singular" class="dbp_input_text required" placeholder="Unidad de medida (Singular)..." /><br />
					<input type="text" name="product_unit_plural" id="product_unit_plural" class="dbp_input_text required" placeholder="Unidad de medida (Plural)..."  /><br />
					<h4>Es una promoci&oacute;n?</h4>
					<input type="checkbox" name="dbp_promotion_active" id="dbp_promotion_active" value="1" <?php echo ($dbp_promotion_active == 1) ? "checked=\"checked\"" : ""?> />
					<label for="dbp_promotion_active">Activar promoci&oacute;n</label><br />
					<input type="text" name="product_init_promotion" id="product_init_promotion" class="dbp_input_short_text required" placeholder="Inicio de promoci&oacute;n..." value="<?php echo $the_product_init_promotion?>" />
					<input type="text" name="product_end_promotion" id="product_end_promotion" class="dbp_input_short_text required" placeholder="Fin de promoci&oacute;n..." value="<?php echo $the_product_end_promotion?>" /><br />
				</div>
			</div>	
		</div>
	</div>


	<div class="metabox-holder">
		<div id="form-sortables" class="meta-box-sortables ui-sortable ">
			<div class="dbp_form_settings_panel postbox">
				<div class="handlediv"><br /></div>
				<h3 class="hndle"><span>Estatus actual de producto</span></h3>
				<div class="inside">
				<?php $the_right_class = ($the_product_price == 0) ? "dbp-sold" : "dbp-available"?>
					<p><b>Precio de compra:</b> <?php echo get_option("dbp_currency_symbol")?> <span class="<?php echo $the_right_class?>"><?php echo number_format($the_product_price,2,',','.');?></span> </p>
					<p><b>Precio de venta:</b> <?php echo get_option("dbp_currency_symbol")?> <span class="<?php echo $the_right_class?>"><?php echo number_format($dbp_product_price_sell,2,',','.');?></span> </p>
					<p><b>Ganancia bruta:</b> <?php echo get_option("dbp_currency_symbol")?> <span class="<?php echo $the_right_class?>"><?php echo number_format(($dbp_product_price_sell-$the_product_price),2,',','.');?></span> </p>					
					<p><b>Cantidad disponible:</b> <?php echo $the_product_quantity?> <?php echo ($the_product_quantity == 1) ? $the_product_unit_singular : $dbp_product_unit_plural?></p>
					<?php global $wpdb;
					$the_meta = $wpdb->get_results("select post_id, meta_value from " . $wpdb->postmeta . " where meta_key = 'dbp_invoice_item'");
					
					$lala=0;
					$soso = "";
					foreach($the_meta as $meta)
					{
						$echo = $meta->meta_value;
						$cch = unserialize($echo);
						$tth = unserialize($cch);
						$invoice_status = get_post_status($meta->post_id);
						if($dbp_product_id == $tth[0]["post_id"] && $invoice_status != "unpaiable")
						{
							$lala += $tth[0]["quantity"];
							//$soso .= $tth[0]["post_id"] . " " . $invoice_status . ", ";
						}
					}
					?>
					<p><b>Vendidos :</b> <?php echo $lala?> <?php echo ($lala == 1) ? $the_product_unit_singular : $dbp_product_unit_plural?></p>
					<!--<p><?php echo $soso?></p>-->
					<p><b>&iquest;Esta en promoci&oacute;n? :</b> <?php echo ($dbp_promotion_active) ? "S&iacute;" : "No"?></p>
					<?php if($dbp_promotion_active):?>
						<p>V&aacute;lida desde el <?php echo date("d-m-Y", strtotime($the_product_init_promotion) )?> hasta el <?php echo date("d-m-Y", strtotime($the_product_end_promotion) )?></p>
					<?php endif?>
				</div>
			</div>	
		</div>
	</div>
</div>
<div class="dbp-right-box">
	<div class="metabox-holder">
		<div id="form-sortables" class="meta-box-sortables ui-sortable ">
			<div class="dbp_form_settings_panel postbox">
				<div class="handlediv"><br /></div>
				<h3 class="hndle"><span>Im&aacute;genes del producto</span></h3>
				<div class="inside">
					<div id="dbp-images-holder">
						<?php $quantity_images = get_option("dbp_product_quantity_images");
						for($n = 1; $n <= $quantity_images; $n++):?>
						<input type="text" name="images[<?php echo $n?>][src]" id="image_src_<?php echo $n?>" placeholder="Seleccione una imagen" class="dbp_long_input_text"
						value = "<?php echo $the_product_images[$n]["src"]?>" />
						<a href="#" id="image_button_<?php echo $n?>" class="upload_image_button button">Subir</a>
						<a href="#" id="remove_image_button_<?php echo $n?>" class="remove_image_button button">Quitar</a><br />
						<?php endfor?>
					</div>
					<!--<p><a href="#" class="button" id="add-image-button">Agregar imagen</a></p>-->
				</div>
			</div>	
		</div>
	</div>

	<div class="metabox-holder">
		<div id="form-sortables" class="meta-box-sortables ui-sortable ">
			<div class="dbp_form_settings_panel postbox">
				<div class="handlediv"><br /></div>
				<h3 class="hndle"><span>Historial de compras de Producto</span></h3>
				<div class="inside">
				<?php  if( is_array(  $the_product_quantity_history ) ) :?>
				<table width="100%" border="1" cellpadding="5" id="dbp-payment-history-table">
					<tr class="ui-widget-header">
						<th>Fecha de compra</th>
						<th>Disponibles</th>
						<th>Precio compra</th>
						<th>Precio venta</th>
						<th>Promo</th>
					</tr>
						<?php foreach( $the_product_quantity_history as $history ):
					?>
						<tr>
							<td><?php echo date("d-m-Y", $history["product-date"])?></td>
							<td align="right"><?php echo number_format($history["quantity"],2,',','.')?></td>
							<td align="right"><?php echo get_option("dbp_currency_symbol") . " " . number_format($history["buying-price"],2,',','.')?></td>
							<td align="right"><?php echo get_option("dbp_currency_symbol") . " " . number_format($history["sell-price"],2,',','.')?></td>
							<td><?php echo ($history["promo"]) ? "S&iacute;" : "No"?></td>
						</tr>					
						<?php $n++; endforeach;?>
					</table>
					<?php else:?>
						<h4><i>No se han hecho compras de este producto</i></h4>
					<?php endif?>
				</div>
			</div>	
		</div>
	</div>
</div>
<input type="hidden" name="dbp_invoice_id" value="<?php echo $dbp_invoice_id?>" />
<p class="clr dbp_center">
	<input type="submit" name="save" class="button-primary" value="Guardar producto" />
</p>
</form>
<?php ?>
<script type="text/javascript">
$("#product_init_promotion, #product_end_promotion, #product_date").datepicker({ dateFormat: "yy-mm-dd", changeMonth: true });
$(".remove_image_button").click(function(){
	var empty_it = '';
	$(this).prev().prev('input.dbp_long_input_text').val(empty_it);
	});
</script>
<?php if( ! empty ($_POST["save"]) ):?>
<script>
	$("<?php echo $field?>").addClass("<?php echo $class?>").focus(function(){ $(this).removeClass("ui-state-error"); $("#message").slideUp("slow"); });
	$("#message").show();
</script>
<?php endif?>
<script src="<?php echo DBP_PLUGIN_URL?>js/dbp-products.js"></script>
<script src="<?php echo DBP_PLUGIN_URL?>js/dbp-custom.js"></script>