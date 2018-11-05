/**
 * App common events.
 *
 * @author 	Alberto Vian - info@reaccionestudio.com
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

  		if(confirm(lang[currentLang]["remove_item_confirm_message"]))
  		{
  			var removeUrl = $(this).attr("href");

  			if( ! removeUrl.length) return;

  			window.location = removeUrl;
  		}
  	});
 });
