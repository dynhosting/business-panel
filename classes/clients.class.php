<?php
if ( ! class_exists( 'WP_List_Table' ) )
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	
class dbp_clients extends WP_List_Table
{
    
    const post_type = 'db_invoice';

	public static $found_items = 0;
    
    function __construct()
	{
        parent::__construct( array(
            'singular'  => 'cliente',
            'plural'    => 'clientes',
            'ajax'      => false
        ) );
    }
    
	function get_columns()
	{
		return array(
			"cb"		=> "<input type=\"checkbox\" />",
			"user_login"		=> "RIF",
			"user_firstname"		=> "Raz&oacute;n social",
			"address"		=> "Direcci&oacute;n",
			"phone"	=> "Tel&eacute;fono"
			);
	}
	
	function get_sortable_columns()
	{
		return array(
			"user_login"	=> array("user_login", false),
			"user_firstname"	=> array("user_nicename", false)
			);
	}
	
	function get_bulk_actions()
	{
        $actions = array(
            'trash'    => 'Papelera'
        );
        return $actions;
    }
	
	function process_bulk_action() 
	{
        
        //Detect when a bulk action is being triggered...
        if( 'trash'===$this->current_action() ) {
            wp_die('Se enviaron a la papelera las facturas seleccionadas');
        }
        
    }
	
	function column_default($item, $column_name)
	{
		switch($column_name)
		{
			case "client":
				$the_value = "N/A";
			break;
		}
		return $the_value;
	}
	
	function column_cb( $item )
	{
		return sprintf(
			'<input type="checkbox" name="%1$s[]" value="%2$s" />',
			$this->_args['singular'],
			$item->id );
	}
	
	function column_user_login($item)
	{
		$user = get_user_meta($item->ID);
		return '<strong>' . $item->user_login . '</strong>';
	}
	
	function column_user_firstname($item)
	{
		$url = admin_url( 'admin.php?page=dbp_clients' );
		$view_url = add_query_arg(array("view" => $item->ID), $url);
		$edit_link = add_query_arg(array("edit" => $item->ID), $url);

		$actions = array(
			'edit' => '<a href="' . $edit_link . '">Editar</a>');
		
		$user = get_user_meta($item->ID);
		
		#print_r($user);
		
		$name = $user["first_name"][0] . " " . $user["last_name"][0];

		$a = sprintf( '<a class="row-title" href="' . $view_url . '" title="%2$s">%3$s</a>',
			$actions,
			esc_attr( sprintf( 'Editar', $name) ),
			esc_html( $name ) );

		return '<strong>' . $a . '</strong> ' . $this->row_actions( $actions );
	}
	
	function column_address($item)
	{
		$user = get_user_meta($item->ID);
		
		return '<strong>' . $user["description"][0] . '</strong>';
	}
	
	function column_phone($item)
	{
		$user = get_user_meta($item->ID);
		
		return '<strong>' . $user["dbp_client_phone"][0] . '</strong>';
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
		
		$this->process_bulk_action();
		
		$query = "SELECT * FROM " . $wpdb->users;
		
		if ( ! empty( $_REQUEST['s'] ) )
			$args['s'] = $_REQUEST['s'];

		if ( ! empty( $_REQUEST['orderby'] ) )
			$query .= " ORDER BY " . $_REQUEST["orderby"];

		if ( ! empty( $_REQUEST['order'] ) )
			$query .=  " " . strtoupper($_REQUEST["order"]);
		
		
		$this->items = $wpdb->get_results($query);
	}
}