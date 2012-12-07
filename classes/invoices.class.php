<?php
if ( ! class_exists( 'WP_List_Table' ) )
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	
class dbp_invoices extends WP_List_Table
{
    
    const post_type = 'dbp_invoice';

	public static $found_items = 0;
    
    function __construct()
	{
        parent::__construct( array(
            'singular'  => 'factura',
            'plural'    => 'facturas',
            'ajax'      => false
        ) );
    }
    
	function get_columns()
	{
		return array(
			"cb"		=> "<input type=\"checkbox\" />",
			"invoice_number"	=> 'N&uacute;mero de factura',
			"title"		=> "Cliente",
			"date"		=> "Fecha",
			"rif"		=> "C&eacute;dula/RIF",
			"seller"	=> "Vendedor",
			"ammount"	=> "Monto",
			"post_status"	=> "Estado"
			);
	}
	
	function get_sortable_columns()
	{
		return array(
			'invoice_number'	=> array('invoice_number'),
			"title"	=> array("title", false),
			"date"	=> array("date", false),
			"seller"	=> array("seller", false)
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
				foreach($_GET[$this->_args['singular']] as $invoice)
				{
					wp_delete_post( $invoice, true );
				}
				
				echo '<div id="message" class="updated ui-state-highlight"><strong>Se han eliminado las facturas</strong></div>';
			}
        }
        
    }
	
	function column_default($item, $column_name)
	{
		switch($column_name)
		{
			case "post_status":
				switch($item->post_status)
				{
					case "unpaid": $the_value = "<strong class=\"dbp-sold\">Pendiente</strong>"; break;
					case "paid": $the_value = "<strong class=\"dbp-available\">Pagada</strong>"; break;
					case "unpaiable": $the_value = "<strong class=\"dbp-unpaiable\">Incobrables</strong>"; break;
				}
			break;
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
	
	function column_invoice_number($item)
	{
		$url = admin_url( 'admin.php?page=dbp_invoices' );
		$view_url = add_query_arg(array("view" => $item->ID), $url);
		$edit_link = add_query_arg(array("edit" => $item->ID), $url);

		$actions = array('edit' => '<a href="' . $edit_link . '">Editar</a>');
		
		$invoice_number = get_post_meta($item->ID, "dbp_invoice_number", true);
		
		$a = sprintf( '<a class="row-title" href="' . $view_url . '" title="%2$s">%3$s</a>',
			$actions,
			esc_attr( sprintf( 'Editar', $item->post_title ) ),
			esc_html( "#" . $invoice_number ) );

		return '<strong>' . $a . '</strong> ' . $this->row_actions( $actions );
	}
	
	function column_title($item)
	{
		$url = admin_url( 'admin.php?page=dbp_invoices' );
		$view_url = add_query_arg(array("view" => $item->ID), $url);

		$actions = array();
		
		$client_id = get_post_meta($item->ID, "dbp_client_id", true);
		$client = get_userdata($client_id);

		$a = sprintf( '<a class="row-title" href="' . $view_url . '" title="%2$s">%3$s</a>',
			$actions,
			esc_attr( sprintf( 'Editar', $client->first_name . " " . $client->last_name ) ),
			esc_html( $client->first_name . " " . $client->last_name ) );

		return '<strong>' . $a . '</strong> ' . $this->row_actions( $actions );
	}
	
	function column_rif($item)
	{
		$url = admin_url( 'admin.php?page=dbp_clients' );
		$view_url = add_query_arg(array("view" => $item->ID), $url);

		$actions = array();
		
		$client_id = get_post_meta($item->ID, "dbp_client_id", true);
		$client = get_userdata($client_id);

		$a = sprintf( '<a class="row-title" href="' . $view_url . '" title="%2$s">%3$s</a>',
			$actions,
			esc_attr( sprintf( 'Editar', $client->user_login ) ),
			esc_html( $client->user_login ) );

		return '<strong>' . $a . '</strong> ' . $this->row_actions( $actions );
	}
	function column_date($item)
	{
		return date("d-m-Y", strtotime($item->post_date));
	}
	
	function column_seller($item)
	{
		$url = admin_url( 'admin.php?page=dbp_sellers' );
		$view_link = add_query_arg(array("view"	=> $item->post_author ), $url);
		
		$actions = array();
		
		$seller = get_userdata( $item->post_author );
		
		$a = sprintf( '<a class="row-title" href="' . $view_link . '" title="%2$s">%3$s</a>',
			$actions,
			esc_attr( sprintf( 'Editar', $seller->first_name . " " . $seller->last_name ) ),
			esc_html( $seller->first_name . " " . $seller->last_name ) );

		return '<strong>' . $a . '</strong> ' . $this->row_actions( $actions );
	}
	
	function column_ammount($item)
	{
		$items = get_post_meta($item->ID, "dbp_invoice_item", true);
		$ammount = 0;
		$tax = 0;
		$items = unserialize($items);
		foreach($items as $item)
		{
			
			$the_price = $item["price"]*$item["quantity"];
			$the_tax = ($item["tax"]=="true") ? ($the_price*str_replace('%', '', get_option('dbp_shop_tax_v')))/100 : 0;
			
			$ammount += $the_price;
			$tax += $the_tax;
		}
		
		$the_ammount = number_format(($ammount + $tax), 2, ',', '.');
		
		$currency_symbol = get_option("dbp_currency_symbol");
		
		return "<strong class=\"dbp-currency-symbol\">$currency_symbol. $the_ammount";
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
			'post_status'	=> 'unpaid, paid, unpaiable',
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
		
		$this->process_bulk_action();
		
		$this->items = $this->find( $args );
	}
	
	function find( $args = '' ) {
		$defaults = array(
			'post_status' => 'any',
			'posts_per_page' => -1,
			'offset' => 0,
			'orderby' => 'ID',
			'order' => 'ASC' );

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