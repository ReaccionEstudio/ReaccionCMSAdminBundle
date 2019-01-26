/**
 * Image picker component events class
 *
 * @author 	Alberto Vian - alberto@reaccionestudio.com
 * @website reaccionestudio.com
 */

class ImagePickerComponentEvents
{
	constructor(){ }

	/**
	 * Set filename value in the input label for the selected file
	 */
	setFilenameValueInInputLabelEvent()
	{
	    $("input.image-picker").on("change", function()
	    {
	      var files = $("input.image-picker")[0].files;
	      
	      if(files[0].name)
	      {
	        var filename = files[0].name;
	        $("label.custom-file-label").html(filename);
	      }
	    });
	}
}

export default ImagePickerComponentEvents;