(function( $ ) {
	'use strict';

	$(function() {
	 	$(".load-info").click(function() {

            $("#loader").show();            
            var id = $(this).closest('tr').attr("data-id");

            var data = {
		        'action' : 'load_user_info',
		        'id': id,
		        'nonce': settings.nonce
		    };

		    $.get( settings.ajaxurl, data, function( response ) {

		    	if(response.success == false) {
		    		$("#user_info_div").html('<span class="f-w-600 m-t-25 m-b-10 text-center text-danger"><h3>Error!</h3> <h4>Something went wrong!</h4></span>');	
		    	} else {	
		        	$('#user_info_div').html(response);
		        }
		        $("#loader").hide();

		    });
        });
	});

})( jQuery );
