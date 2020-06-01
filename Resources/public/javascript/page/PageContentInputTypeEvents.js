/**
 * Page content input type events class
 *
 * @author 	Alberto Vian - alberto@reaccionestudio.com
 * @website reaccionestudio.com
 */

class PageContentInputTypeEvents
{
	/** 
	 * Constructor
	 */
	constructor(mediaGalleryFormComponent) 
	{
		this.mediaGalleryFormComponent = mediaGalleryFormComponent;
	}

	/**
	 * Events
	 */
	events()
	{
		let _self = this;

		// 'Open gallery' button click event
		$('[data-event="open-media-gallery"]').on("click", function(e)
		{
			// Get current type input value
			let typeInput = $("select#page_content_type").val();
			typeInput = (typeInput === "img") ? "image" : typeInput;

			// Show media gallery modal box
			_self.mediaGalleryFormComponent.show(typeInput);
		});

		
	}
}

export default PageContentInputTypeEvents;
