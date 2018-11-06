/**
 * App common events.
 *
 * @author 	Alberto Vian - alberto@reaccionestudio.com
 * @website reaccionestudio.com
 */

 $(document).ready(function()
 {
 	// Bootstrap tooltips events
  	$('[data-toggle="tooltip"]').tooltip();

  	// Remove record confirm
  	$('[data-detele-confirm]').on('click', function(e)
  	{
  		e.preventDefault();

  		if(confirm(translations["remove_item_confirm_message"]))
  		{
  			var removeUrl = $(this).attr("href");

  			if( ! removeUrl.length) return;

  			window.location = removeUrl;
  		}
  	});
 });
