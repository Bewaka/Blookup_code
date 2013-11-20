 
 
 $(function() {
            function launch() {
                 $('#tabs').lightbox_me({centered: true, onLoad: function() { $('#tabs').find('input:first').focus()}});
            }
            
            $('#try-1').click(function(e) {
                $("#tabs").lightbox_me({centered: true, onLoad: function() {
					$("#sign_up").find("input:first").focus();
				}});
				
                e.preventDefault();
            });
            
            
            $('table tr:nth-child(even)').addClass('stripe');
        });
		
	$(function() {
		$( "#tabs" ).tabs();
	});