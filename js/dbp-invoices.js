				
var accentMap = {
			"á": "a",
			"ö": "o"
		};
var normalize = function( term ) {
	var ret = "";
	for ( var i = 0; i < term.length; i++ ) {
		ret += accentMap[ term.charAt(i) ] || term.charAt(i);
	}
	return ret;
};
		
	$("#client_username").autocomplete({
		source: function( request, response ) {
			var matcher = new RegExp( $.ui.autocomplete.escapeRegex( request.term ), "i" );
			response( $.grep( clients, function( value ) {
				value = value.label || value.value || value;
				return matcher.test( value ) || matcher.test( normalize( value ) );
			}) );
		},
		select: function(event, ui){
			$("#client_id").val(ui.item.id);
			$("#client_firstname").val(ui.item.first_name);
			$("#client_lastname").val(ui.item.last_name);
			$("#client_email").val(ui.item.email);
			$("#client_address").val(ui.item.address);
			$("#client_phone").val(ui.item.phone);
			$("#client_company_name").val(ui.item.company_name);
		}
	});


		
	$("#shopping_autocomplete").autocomplete({
		source: function( request, response ) {
			var matcher = new RegExp( $.ui.autocomplete.escapeRegex( request.term ), "i" );
			response( $.grep( products, function( value ) {
				value = value.label || value.value || value;
				return matcher.test( value ) || matcher.test( normalize( value ) );
			}) );
		},
		select: function(event, ui){
			if(ui.item.quantity > 0)
			{
				if($("#get-the-item-" + ui.item.id).length == 0)
				{
					var deletable = $("#dbp-deletable");
					if(deletable.length > 0)
						deletable.remove();
					var item_holder = $(".dbp-item-holder");
					var counter = item_holder.length;
					app = '<div id="get-the-item-' + ui.item.id + '" class="dbp-item-holder"><div class="left">' + ui.item.label + '</div>';
					app += '<div class="left first"><input id="get-the-q-' + ui.item.id + '" type="text" class="dbp_invoice_item" size="3" value="1" name="item[' + counter + '][quantity]" /></div>';
					app += '<div class="left second"><input type="checkbox" class="dbp_invoice_item" name="item[' + counter + '][tax]" value="true" checked="checked" /></div>';
					app += '<div class="right third"><a id="delete-item-' + counter + '" href="#">Borrar</a></div>';
					app += '<input type="hidden" name="item[' + counter + '][post_id]" value="' + ui.item.id + '" class="dbp_invoice_item" />';
					app += '<input type="hidden" name="item[' + counter + '][price]" value="' + ui.item.price + '" class="dbp_invoice_item" />';
					app += '<div class="clr"></div><hr /></div>';
					
					$("#shopping_invoice_items").append(app);
					$("#shopping_invoice_items, #shopping_invoice_items_header").show("fast");
					
					$("#get-the-q-" + ui.item.id).keyup(function(){
						var the_q_val = $(this).val();
						var the_q_max = ui.item.quantity;
						
						if(parseInt(the_q_val) > the_q_max) {
							$(this).val(the_q_max);
							alert("No puede vender mas de la cantidad disponible");
							}
						});
					
					$("#delete-item-" + counter).click(function(e){
						e.preventDefault();
						$(this).parent().parent().remove();
						});
				}
				else
				{
					var re = $("#get-the-q-" + ui.item.id).val();
					
					if(re == ui.item.quantity)
					{
						alert("No se puede vender mas " + ui.item.label);
					}
					else
					{
						var valor = 1;
						
						valor += parseInt(re);
						$("#get-the-q-" + ui.item.id).val(valor.toFixed());
					}
				}
			}
			else
			{
				alert("No existe disponibilidad de este producto actualmente!");
			}
		}
	});
	
	$("a.dbp-delete").each(function(){
	$(this).click(function(e){
		e.preventDefault();
		if(confirm("Desea eliminar este producto de la factura guardada?"))
			$(this).parent().parent().remove();
		});
	});
	
	$("#dbp-create-invoice").submit(function(){
		var data = $(this).serialize();
		var client_error = 0;
		var product_catcher = $(".dbp_invoice_item");
		
			if($("#client_company_name").val() != "")
				$("#client_firstname, #client_lastname").removeClass("required");
			else if($("#client_firstname, #client_lastname").val() != "")
				$("#client_company_name").removeClass("required");
			else if($("#client_company_name, #client_firstname, #client_lastname").val() == "")
				$("#client_company_name, #client_firstname, #client_lastname").addClass("required");
				
			$(".required").each(function(){
				if($(this).val() == "") {
					$(this).addClass("ui-state-error");
					client_error++;
					}
				});

			if(client_error > 0)
			{
				$("#message").text("Los campos marcados en rojo son requeridos para el cliente").addClass("ui-state-error").show("fast");
				$("input.ui-state-error").each(function(){
					$(this).focus(function(){
						$(this).removeClass("ui-state-error");
						$("#message").removeClass("ui-state-error").hide("slow");
					});
				});
				return false;
			}
			else if(product_catcher.length < 3)
			{
				$("#message").text("Debe agregar al menos un porducto para crear una factura").addClass("ui-state-error").show("fast");
				$("#shopping_autocomplete").addClass("ui-state-error").focus(function(){
						$(this).removeClass("ui-state-error");
						$("#message").removeClass("ui-state-error").hide("slow");
					});
				return false;
			}
			else
			{
				return true;
			}
		
		});
		
function set_dialog_payment(dbp_invoice_id, dbp_insert_payment_url)
{
	var url = dbp_insert_payment_url;
	var dialog = $("#dialog");
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
				title: "Nueva factura"
			});
		}
	);
}