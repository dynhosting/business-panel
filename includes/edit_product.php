<?php
$product = (object)$_POST;
$product->product_id;

if( ! is_numeric ( $product->product_price ) OR ! is_numeric ( $product->product_quantity ) OR ! is_numeric ( $product->product_price_sell ) )
{
	$error = 1;
	$message = "El precio y la cantidad deben ser solo n&uacute;meros";
	$class = "ui-state-error";
	$field = "#product_price, #product_quantity";
}
elseif( $product->dbp_promotion_active && ( $product->product_init_promotion == "" || $product->product_end_promotion == ""  ) )
{
	$error = 1;
	$message = "Si el producto es una promoci&oacute;n, debe tener fecha de inicio y de fin";
	$class = "ui-state-error";
	$field = "#product_init_promotion, #product_end_promotion";
}

if(! isset( $error ) OR !$error )
{
	update_post_meta($product->product_id, "dbp_product_price", $product->product_price);
	update_post_meta($product->product_id, "dbp_product_price_sell", $product->product_price_sell);
	
	$the_product_quantity = get_post_meta($product->product_id, "dbp_product_quantity", true);
	$dbp_total_quantity = $the_product_quantity + $product->product_quantity;
	
	update_post_meta($product->product_id, "dbp_product_quantity", $dbp_total_quantity);
	
	$the_product_quantity_history = unserialize ( get_post_meta($product->product_id, "dbp_product_quantity_history", true) );
	$the_product_quantity_history[] = array(
			"product-date"	=> strtotime($product->product_date),
			"quantity"		=> $product->product_quantity,
			"product-unit-sing"	=> $product->product_unit_singular,
			"product-unit-plural"	=>	$product->product_unit_plural,
			"buying-price"	=> $product->product_price,
			"sell-price"	=> $product->product_price_sell,
			"promo"			=> $product->dbp_promotion_active
			);
	$dbp_product_quantity_history = serialize ( $the_product_quantity_history );
	
	update_post_meta($product->product_id, "dbp_product_quantity_history", $dbp_product_quantity_history );
	
	update_post_meta($product->product_id, "dbp_product_unit_singular", $product->product_unit_singular);
	update_post_meta($product->product_id, "dbp_product_unit_plural", $product->product_unit_plural);
	update_post_meta($product->product_id, "dbp_promotion_active", $product->dbp_promotion_active);
	update_post_meta($product->product_id, "dbp_product_init_promotion", $product->product_init_promotion);
	update_post_meta($product->product_id, "dbp_product_end_promotion", $product->product_end_promotion);

	$dbp_product_images_saved = serialize($product->images);
	update_post_meta($product->product_id, "dbp_product_images", $dbp_product_images_saved);
	
	$message = "Se ha guardado la informaci&oacute;n del producto";
	$class = "ui-state-highlight";
}