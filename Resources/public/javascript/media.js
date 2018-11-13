/**
 * Media class
 *
 * @author 	Alberto Vian - alberto@reaccionestudio.com
 * @website reaccionestudio.com
 */

class Media
{
	constructor(){ }

	formEvents()
	{
		var submitted = false;

		// Show loading button on form submit
		$("button#send_media_btn").on("click", function(e)
		{
			if(submitted) e.preventDefault();

			if($("input.custom-file-input")[0].files.length)
			{
				submitted = true;

				$(this).html('<div class="fe fe-refresh-cw spin"></div>');
				$(this).attr("disabled", "disabled");
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
	}
}

export default Media;