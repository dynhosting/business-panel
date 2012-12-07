	$("#dbp-create-client").submit(function(){
		var data = $(this).serialize();
		var client_error = 0;
		
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
			else
			{
				return true;
			}
		
		});
		
				
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
		
	$("#dbp_search_client").autocomplete({
		source: function( request, response ) {
			var matcher = new RegExp( $.ui.autocomplete.escapeRegex( request.term ), "i" );
			response( $.grep( clients, function( value ) {
				value = value.label || value.value || value;
				return matcher.test( value ) || matcher.test( normalize( value ) );
			}) );
		},
		select: function(event, ui){
			window.location.href=theUrl + ui.item.id;
		}
	});