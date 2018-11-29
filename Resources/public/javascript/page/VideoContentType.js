/**
 * Video content type class
 *
 * @author 	Alberto Vian - alberto@reaccionestudio.com
 * @website reaccionestudio.com
 */

import MediaGalleryFormComponent from '../components/media/MediaGalleryFormComponent.js';

class VideoContentType
{
	/** 
	 * Constructor
	 */
	constructor()
	{
		this.mediaGalleryFormComponent = new MediaGalleryFormComponent();
		this.mediaVideoPreviewComponent = this.mediaGalleryFormComponent.mediaVideoPreviewComponent;
		this.formSelector = this.mediaGalleryFormComponent.formSelector;
	}

	/**
	 * Image content type form events
	 */
	events()
	{
		this._toggleGalleryEvent();

		let _self = this;

		$("select#page_content_type").on("change", function()
		{
			_self._toggleGalleryEvent();
		});

		$('[data-event="open-media-gallery"]').on("click", function(e)
		{
			_self.mediaGalleryFormComponent.showMediaGallery();
		});
	}

	/**
	 * Toggle gallery in page content form
	 */
	_toggleGalleryEvent()
	{
		let selectedValue = $("select#page_content_type").val();
		
		if(selectedValue == "video")
		{
			this.mediaVideoPreviewComponent.showGallery();
		}
		else if(selectedValue != "img")
		{
			this.mediaVideoPreviewComponent.hideGallery(false);
		}
	}
}

export default VideoContentType;