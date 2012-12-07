<?php
	if($_REQUEST["invoice"]=="" OR !is_numeric($_REQUEST["invoice"]))
	{
		$testListTable = new dbp_products();
		$testListTable->prepare_items();
		
		$heading_args = array(
		"heading"	=> "Productos",
		"page"	=> "dbp_products",
		"id"	=> "icon-products-page",
		"url"	=> admin_url('admin.php?page=dbp_products&edit=new')
		);
		dbp_header($heading_args);?>
		<!--<ul class="subsubsub">
			<li class="all"><a href="<?php echo admin_url("admin.php?page=dbp_products")?>"<?php echo ($_REQUEST["post_status"] == "") ? " class=\"current\"" : ""?>>Todos <span class="count"></span></a> |</li>
			<li class="publish"><a href="<?php echo admin_url("admin.php?page=dbp_products&post_status=paid")?>"<?php echo ($_REQUEST["post_status"] == "paid") ? " class=\"current\"" : ""?>>Pagadas <span class="count"></span></a> |</li>
			<li class="trash"><a href="<?php echo admin_url("admin.php?page=dbp_products&post_status=unpaid")?>"<?php echo ($_REQUEST["post_status"] == "unpaid") ? " class=\"current\"" : ""?>>Pendientes <span class="count"></span></a></li>
		</ul>-->
			<!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
			<form id="movies-filter" method="get">
				<?php $testListTable->search_box( "Buscar factura", "dbp_search_invoice" );?>
				<!-- For plugins, we also need to ensure that the form posts back to our current page -->
				<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
				<!-- Now we can render the completed list table -->
				<?php $testListTable->display() ?>
			</form>
		</div>
		<script>
			$("#icon-invoice-page-new, .dbp-invoice-edit-link").click(function(event){
				event.preventDefault();
				var url = $(this).attr("href");
				var dialog = $("#dialog");
				if ($("#dialog").length == 0) {
					dialog = $('<div id="dialog" style="display:hidden"></div>').appendTo('body');
				} 

				// load remote content
				dialog.load(
						url,
						{},
						function(responseText, textStatus, XMLHttpRequest) {
							dialog.dialog({
								height: 550,
								width : "60%",
								modal: true,
								resizable: false,
								draggable: true,
								title: "Facturas"
							});
						}
					);});
		</script>
	<?php }
	elseif(!isset($_REQUEST["invoice"]) && $_REQUEST["action"] == "new")
		dbp_edit_invoice();
	elseif(($_REQUEST["invoice"] != "" && is_numeric($_REQUEST["invoice"])) && $_REQUEST["action"] == "")
		dbp_view_invoice($_REQUEST["invoice"]);
	elseif(($_REQUEST["invoice"] != "" && is_numeric($_REQUEST["invoice"])) && $_REQUEST["action"] == "edit")
		dbp_edit_invoice($_REQUEST["invoice"]);