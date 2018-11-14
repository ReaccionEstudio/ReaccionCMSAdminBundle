/**
 * Image content type class
 *
 * @author 	Alberto Vian - alberto@reaccionestudio.com
 * @website reaccionestudio.com
 */

import Media from '../media.js';

class ImageContentType
{
	/** 
	 * Constructor
	 */
	constructor()
	{
		this.selectedMedia = {};
		this.mediaObj = new Media();
		this.galleryComponent = "div#image_gallery_component";
		this.formSelector = 'form[name="page_content"]';
	}

	/**
	 * Image content type form events
	 */
	events()
	{
		this._toggleGalleryEvent();
		this._handleSelectedItemFromMediaGalleryEvent();
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

	/**
	 * Toggle gallery in page content form
	 */
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

	/**
	 * Hide gallery
	 */
	_hideGallery()
	{
		$(this.galleryComponent).addClass("d-none");
		$("textarea#page_content_value").parent().removeClass("d-none");
	}

	/**
	 * Show gallery
	 */
	_showGallery()
	{
		$(this.galleryComponent).removeClass("d-none");
		$("textarea#page_content_value").parent().addClass("d-none");
	}

	/**
	 * Open gallery
	 */
	_openMediaGallery()
	{
		this.mediaObj._showMediaGallery();
	}

	/**
	 * Listen to selectedItemFromMediaGallery event
	 */
	_handleSelectedItemFromMediaGalleryEvent()
	{
		document.addEventListener('selectedItemFromMediaGallery', function(e)
		{
			this.selectedMedia = e.detail;
			$("div#modal").modal("hide");
		}, 
		false);
	}

	/**
	 * Generate image preview for selected gallery image
	 */
	_previewSelectedImage()
	{
		$(this.formSelector).find('[data-media-value="true"]').val(this.selectedMedia.path);

		// TODO ...
	}

}

export default ImageContentType;