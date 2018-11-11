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

    // Set filename value in the input label for the selected file
    $("input.custom-file-input").on("change", function()
    {
      var files = $("input.custom-file-input")[0].files;
      
      if(files[0].name)
      {
        var filename = files[0].name;
        $("label.custom-file-label").html(filename);
      }
    });
 });
