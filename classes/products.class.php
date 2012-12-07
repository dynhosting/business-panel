<?php
if ( ! class_exists( 'WP_List_Table' ) )
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	
class dbp_products extends WP_List_Table
{
    
    const post_type = 'post';

	public static $found_items = 0;
    
    function __construct()
	{
        parent::__construct( array(
            'singular'  => 'producto',
            'plural'    => 'productos',
            'ajax'      => false
        ) );
    }
    
	function get_columns()
	{
		return array(
			"cb"		=> "<input type=\"checkbox\" />",
			"title"		=> "Producto",
			"product_date"		=> "Fecha",
			"creator"	=> "Creador",
			"price"		=> "Precio",
			"quantity"	=> "Disponibles",
			"status"	=> "Estatus"
			);
	}
	
	function get_sortable_columns()
	{
		return array(
			'invoice_number'	=> array('invoice_number'),
			"title"	=> array("title", false),
			"product_date"	=> array("date", false),
			"creator"	=> array("creator", false),
			"price"		=> array("price", false),
			"quantity"	=> array("quantity", false),
			"status"	=> array("status", false),
			);
	}
	
	function get_bulk_actions()
	{
        $actions = array(
            'delete'    => 'Borrar'
        );
        return $actions;
    }
	
	function process_bulk_action() 
	{
         //Detect when a bulk action is being triggered...
        if( 'delete'===$this->current_action() )
		{
			if( ! empty ( $_GET[$this->_args['singular']] ) )
			{
				foreach($_GET[$this->_args['singular']] as $product)
				{
					wp_delete_post( $product, true );
				}
				
				echo '<div id="message" class="updated ui-state-highlight"><strong>Se han eliminado las facturas</strong></div>';
			}
        }
        
    }
	
	function column_default($item, $column_name)
	{
		switch($column_name)
		{
			case "client": $the_value = "N/A"; break;
			case "status": $the_value = $item->post_status;  break;
		}
		return $the_value;
	}
	
	function column_cb( $item )
	{
		return sprintf(
			'<input type="checkbox" name="%1$s[]" value="%2$s" />',
			$this->_args['singular'],
			$item->ID );
	}
	
	function column_title($item)
	{
		$url = admin_url( 'admin.php?page=dbp_products' );
		$edit_url = add_query_arg(array("edit"=>$item->ID), $url);
		
		$actions = array(
			"edit"	=> '<a href="' . $edit_url . '" class="dbp-product-edit-link">Editar</a>'
			);


		$a = sprintf( '<a class="row-title" href="' . $edit_url . '" title="%2$s">%3$s</a>',
			$actions,
			esc_attr( sprintf( 'Editar', $item->post_title ) ),
			esc_html( $item->post_title ) );

		return '<strong>' . $a . '</strong> ' . $this->row_actions( $actions );
	}
	
	function column_rif($item)
	{
		$client_id = get_post_meta($item->ID, "dbp_client_id", true);
		$client = get_userdata($client_id);
		return $client->user_login;
	}
	function column_product_date($item)
	{
		return "<strong>" . date("d-m-Y h:ia", strtotime($item->post_date)) . "</strong>";
	}
	
	function column_creator($item)
	{
		$seller = get_userdata( $item->post_author );
		$url = admin_url( 'admin.php?page=db_sellers&profile=' . $item->ID );
		$edit_link = add_query_arg( array( 'action' => 'edit' ), $url );
		$actions = array(
			'edit'	=> '<a href="' . $edit_link . '">Editar</a>');
			
		$a = sprintf( '<a class="row-title" href="' . $url . '" title="%2$s">%3$s</a>',
			$actions,
			esc_attr( sprintf( 'Editar', $seller->user_email ) ),
			esc_html( $seller->user_email ) );

		return '<strong>' . $a . '</strong> ' . $this->row_actions( $actions );
	}
	
	function column_price($item)
	{
		$currency = get_option("dbp_currency_symbol");
		$price = ( is_numeric( get_post_meta($item->ID, "dbp_product_price", true) ) ) ? number_format(get_post_meta($item->ID, "dbp_product_price", true), 2, ',', '.') : "0,00";
		return "$currency $price";
	}
	
	function column_quantity($item)
	{
		$quantity = get_post_meta($item->ID, "dbp_product_quantity", true);
		
		$unit = get_post_meta($item->ID, "dbp_product_unit_singular", true);
		$units = get_post_meta($item->ID, "dbp_product_unit_plural", true);
		
		$available = $quantity;
		
		$the_unit = ($available == 0 OR $available > 1) ? $units : $unit;
		
		return  $available . " " . $the_unit;
	}
	
	function column_status($item)
	{
		$quantity = get_post_meta($item->ID, "dbp_product_quantity", true);
		$sold = get_post_meta($item->ID, "dbp_product_sold", true);
		$unit = get_post_meta($item->ID, "dbp_product_unit");
		
		$half_quantity = $quantity/2;
		
		$available = $quantity-$sold;
		
		if($available == 0):
			$class = "dbp-sold"; $text = "Agotado";
		elseif($available < $half_quantity):
			$class = "dbp-not-much"; $text = "Menos de la mitad";
		elseif($available >= $half_quantity):
			$class = "dbp-available"; $text = "Disponible";
		else:
			$class = "dbp-available"; $text = "Disponible";
		endif;
			
		return "<strong class=\"$class\">$text</strong>";
	}
	
	function prepare_items()
	{
		global $wpdb;
		
		$pagination_args = array(
			"total_items "	=> "-1",
			"per_page"		=> 15
			);
		$this->set_pagination_args( $pagination_args );
		
		$this->_column_headers = array( 
			$this->get_columns(),
			array(),
			$this->get_sortable_columns()
			);
		
		$args = array(
			'posts_per_page' => $per_page,
			'orderby' => 'ID',
			'order' => 'DESC',
			'offset' => ( $this->get_pagenum() - 1 ) * $per_page );

		if ( ! empty( $_REQUEST['s'] ) )
			$args['s'] = $_REQUEST['s'];

		if ( ! empty( $_REQUEST['orderby'] ) ) {
			if ( 'title' == $_REQUEST['orderby'] )
				$args['orderby'] = 'title';
			elseif ( 'author' == $_REQUEST['orderby'] )
				$args['orderby'] = 'author';
			elseif ( 'date' == $_REQUEST['orderby'] )
				$args['orderby'] = 'date';
		}

		if ( ! empty( $_REQUEST['order'] ) ) {
			if ( 'asc' == strtolower( $_REQUEST['order'] ) )
				$args['order'] = 'ASC';
			elseif ( 'desc' == strtolower( $_REQUEST['order'] ) )
				$args['order'] = 'DESC';
		}
		
		if ( ! empty( $_REQUEST['post_status'] ) ) {
				$args['post_status'] = $_REQUEST['post_status'];
		}
		
		if ( get_option("dbp_product_categories") != "" )
		{
			$categories = unserialize( get_option("dbp_product_categories") );
			$args["cat"] = "";
			foreach($categories as $cat)
			{
				$args["cat"] .= "$cat, ";
			}
			
			$args["cat"] = trim($args["cat"]);
		}
		
		$this->process_bulk_action();
		
		$this->items = $this->find( $args );
	}
	
	function find( $args = '' ) {
		$defaults = array(
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'offset' => 0,
			'orderby' => 'ID',
			'order' => 'ASC',
			'cat'	=> null);

		$args = wp_parse_args( $args, $defaults );

		$args['post_type'] = self::post_type;

		$q = new WP_Query();
		$posts = $q->query( $args );

		self::$found_items = $q->found_posts;

		$objs = array();

		foreach ( (array) $posts as $post )
			$objs[] =  $post ;

		return $objs;
	}
}