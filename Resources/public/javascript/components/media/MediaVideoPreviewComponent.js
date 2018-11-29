/**
 * Media video preview form component class
 *
 * @author 	Alberto Vian - alberto@reaccionestudio.com
 * @website reaccionestudio.com
 */

class MediaVideoPreviewComponent
{
	/**
	 * Constructor
	 *
	 * @param  String 	galleryComponent 	Gallery component DOM selector
	 * @return void 	[type]
	 */
	constructor(galleryComponent, formSelector)
	{
		this.galleryComponent = galleryComponent;
		this.formSelector = formSelector;
	}

	/**
	 * Hide gallery form component
	 *
	 * @param Boolean 	resetImagePreview	Indicates if resetImagePreview() method has to be executed
	 */
	hideGallery(resetImagePreview)
	{
		if(typeof resetImagePreview == "undefined") resetImagePreview = true;

		$(this.galleryComponent + ", div#selected_image_preview").addClass("d-none");
		$("textarea#page_content_value").parent().removeClass("d-none");

		if(resetImagePreview) this.resetImagePreview();
	}

	/**
	 * Show image preview
	 */
	showGallery()
	{
		$(this.galleryComponent).removeClass("d-none");
		$("textarea#page_content_value").parent().addClass("d-none");

		if( $("textarea#page_content_value").val().length )
		{
			// this._previewVideoByPath();
		}
	}

	// TODO ...
}

export default MediaVideoPreviewComponent;