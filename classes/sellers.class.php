<?php
if ( ! class_exists( 'WP_List_Table' ) )
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	
class dbp_sellers extends WP_List_Table
{
    
    const post_type = 'db_invoice';

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
			"title"		=> "T&iacute;tulo",
			"date"		=> "Fecha",
			"client"	=> "Cliente",
			"seller"	=> "Vendedor"
			);
	}
	
	function get_sortable_columns()
	{
		return array(
			"title"	=> array("title", false),
			"date"	=> array("date", false)
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
	
	function column_title($item)
	{
		$url = admin_url( 'admin.php?page=dbp_invoices&invoice=' . absint( $item->ID ) );
		$edit_link = add_query_arg( array( 'action' => 'edit' ), $url );
		$trash_link = add_query_arg( array( 'action' => 'trash' ), $url );

		$actions = array(
			'edit' => '<a href="' . $edit_link . '">Editar</a>',
			'trash' => '<a href="' . $trash_link . '">Papelera</a>');


		$a = sprintf( '<a class="row-title" href="' . $url . '" title="%2$s">%3$s</a>',
			$actions,
			esc_attr( sprintf( 'Editar', $item->post_title ) ),
			esc_html( $item->post_title ) );

		return '<strong>' . $a . '</strong> ' . $this->row_actions( $actions );
	}
	
	function column_date($item)
	{
		return date("d-m-Y", strtotime($item->post_date));
	}
	
	function column_seller($item)
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
		
		$this->items = $wpdb->get_results("SELECT * FROM " . $wpdb->posts . " WHERE post_type = '" . self::post_type . "'");
	}
}