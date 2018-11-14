/**
 * Image content type class
 *
 * @author 	Alberto Vian - alberto@reaccionestudio.com
 * @website reaccionestudio.com
 */

import Media from '../media.js';

class ImageContentType
{
	constructor()
	{
		this.mediaObj = new Media();
		this.galleryComponent = "div#image_gallery_component";
	}

	events()
	{
		this._toggleGalleryEvent();
		this.mediaObj.mediaGalleryEvents()

		let _self = this;

		$("select#page_content_type").on("change", function()
		{
			_self._toggleGalleryEvent();
		});

		$('[data-event="open-media-gallery"]').on("click", function(e)
		{
			_self._openMediaGallery();
		});
	}

	_toggleGalleryEvent()
	{
		let selectedValue = $("select#page_content_type").val();
			
		if(selectedValue == "img")
		{
			this._showGallery();
		}
		else
		{
			this._hideGallery();
		}
	}

	_hideGallery()
	{
		$(this.galleryComponent).addClass("d-none");
		$("textarea#page_content_value").parent().removeClass("d-none");
	}

	_showGallery()
	{
		$(this.galleryComponent).removeClass("d-none");
		$("textarea#page_content_value").parent().addClass("d-none");
	}

	_openMediaGallery()
	{
		this.mediaObj._showMediaGallery();
	}

}

export default ImageContentType;