<?php

# Install functions

add_action('admin_menu', 'dbp_menu_config');
add_action( 'admin_init', 'dbp_init' );

function dbp_init()
{
	# Register Custom CSS
	wp_register_style( 'dbp-stylesheet', plugin_dir_url( __FILE__ ) . 'style.css' );
	wp_register_style( 'dbp-printer', plugin_dir_url( __FILE__ ) . 'printer.css' , false, false, 'print');
	wp_enqueue_style( 'dbp-stylesheet' );
	wp_enqueue_style( 'dbp-printer' );
	
	# Register jquery
    wp_register_script( 'jquery-dbp', plugin_dir_url( __FILE__ ) . 'js/jquery.js');
    wp_enqueue_script( 'jquery-dbp' );
	
	# Register JS jquery UI
    wp_register_script( 'ui', plugin_dir_url( __FILE__ ) . 'js/ui/js/ui.js');
    wp_enqueue_script( 'ui' );
	
	# Register CSS jquery UI
	wp_register_style( 'jqui', plugin_dir_url( __FILE__ ) . 'js/ui/css/smoothness/ui.css');
	wp_enqueue_style( 'jqui' );
	
	# Register image selecter
	wp_register_script( 'jquery-image-selecter', plugin_dir_url( __FILE__ ) . 'js/dbp-select-image.js');
} 


function dbp_menu_config()
{
		$image_url =  plugin_dir_url( __FILE__ ) . "images/menu-icon.png";
        add_menu_page( 'Todas las facturas', 'Dyn Business', 'edit_posts', 'dyn_business', 'dbp_invoices' , $image_url, 6);
				
		add_submenu_page( "dyn_business", "Facturas", "Facturas", "edit_posts", "dbp_invoices", "dbp_invoices" );
		add_submenu_page( "dyn_business", "Productos", "Productos", "publish_pages", "dbp_products", "dbp_products" );
		add_submenu_page( "dyn_business", "Clientes", "Clientes", "edit_posts", "dbp_clients", "dbp_clients" );
		/*add_submenu_page( "dyn_business", "Vendedores", "Vendedores", "upload_files", "dbp_sellers", "dbp_sellers" );
		add_submenu_page( "dyn_business", "Proveedores", "Proveedores", "upload_files", "dbp_suppliers", "dbp_suppliers" );*/
		add_submenu_page( "dyn_business", "Configuraci&oacute;n", "Configuraci&oacute;n", "publish_pages", "dbp_settings", "dbp_settings" );
}

function dbp_registration()
{
	$labels = array(
		'name' => "Factura",
		'singular_name' => "Factura",
		'add_new' => "Nueva factura",
		'add_new_item' => "Nueva factura",
		'edit_item' => "Editar",
		'new_item' => "Nueva factura",
		'all_items' => "Todas las facturas",
		'view_item' => "Ver",
		'search_items' => "Buscar facturas",
		'not_found' =>  "No se encontraron facturas",
		'not_found_in_trash' => "No se encontraron facturas en la papelera", 
		'parent_item_colon' => 'dyn_business',
		'menu_name' => "Facturas"

	);
	
	$args = array(
		'labels' => $labels,
		'public' => false,
		'publicly_queryable' => true,
		'show_ui' => false, 
		'show_in_menu' => false, 
		'query_var' => true,
		'has_archive' => true,
		'supports' => array( 'title', 'excerpt','custom-fields','categories','page-attributes')
		); 
		
	register_post_type('dbp_invoice', $args);
	
	register_post_status( 'paid', array(
		'label'       => 'pagada',
		'public'      => true,
		'label_count' => 'Pagadas <span class="count">(%s)</span>',
		'show_in_admin_all' => true,
		'show_in_admin_status_list'	=> true
	) );
	
	register_post_status( 'unpaid', array(
		'label'       => 'no pagada',
		'public'      => true,
		'label_count' => 'No pagadas <span class="count">(%s)</span>',
		'show_in_admin_all' => true,
		'show_in_admin_status_list'	=> true
	) );
	
	register_post_status( 'unpaiable', array(
		'label'       => 'Incobrable',
		'public'      => true,
		'label_count' => 'Incobrables <span class="count">(%s)</span>',
		'show_in_admin_all' => true,
		'show_in_admin_status_list'	=> true
	) );
}

add_action('init', 'dbp_registration');

function dbp_create_roles()
{
	/*add_role('seller', 'Vendedor', array(
		'read' => false,
		'moderate_comments'	=> false,
		'edit_posts'	=> false,
		'edit_others_posts'	=> false,
		'publish_posts'	=> false,
		'edit_dashboard' => false
	))*/;
	
	add_role('client', 'Cliente', array(
		'read' => true
	));
	
	/*add_role('auditor', 'Auditor', array(
		'read' => true
	));
	
	add_role('manager', 'Gerente', array(
		'read'			=> true, 
		'edit_posts'	=> true,
		'delete_posts'	=> true,
		'upload_files'	=> true,
		'edit_published_posts'	=> true,
		'delete_published_posts'	=> true,
		'edit_users'	=> true,
		'moderate_comments'	=> false
	));*/
}