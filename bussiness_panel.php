<?php/*Plugin Name: Dyn Bussiness PanelPlugin URI: http://bussinesspanel.dynhosting.netDescription: Administrador de compra, venta y facturaci&oacute;n para Wordpress.Version: 1.0Author: Javier Troya | Dynhosting.netAuthor URI: http://javiertroya.com/Author Email: javiertroya@gmail.com | bussinesspanel@dynhosting.netLicense: GPL3*//*This program is free software: you can redistribute it and/or modifyit under the terms of the GNU General Public License as published bythe Free Software Foundation, either version 3 of the License.This program is distributed in the hope that it will be useful,but WITHOUT ANY WARRANTY; without even the implied warranty ofMERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See theGNU General Public License for more details.You should have received a copy of the GNU General Public Licensealong with this program.  If not, see <http://www.gnu.org/licenses/>. */define ( 'DBP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );define ( 'DBP_PLUGIN_DIR', untrailingslashit( dirname( __FILE__ ) ) );require_once(dirname(__FILE__) . "/settings.php");require_once(dirname(__FILE__) . "/classes/invoices.class.php");require_once(dirname(__FILE__) . "/classes/sellers.class.php");require_once(dirname(__FILE__) . "/classes/clients.class.php");require_once(dirname(__FILE__) . "/classes/products.class.php");require_once(dirname(__FILE__) . "/views/functions.php");register_activation_hook(__FILE__, 'dbp_create_roles' );