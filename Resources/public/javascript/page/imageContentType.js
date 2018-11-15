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
		var _self = this;

		document.addEventListener('selectedItemFromMediaGallery', function(e)
		{
			_self.selectedMedia = e.detail;

			// Generate image preview
			_self._previewSelectedImage();

			// Close modal
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

		let selectedImagePreviewSelector = "div#selected_image_preview";
		let previewImagePath = this.selectedMedia.path;
		let imagePrefix = appUrl + '/uploads/';

		if(this.selectedMedia.mediumPath)
		{
			previewImagePath = this.selectedMedia.mediumPath;
		}
		else if(this.selectedMedia.largePath)
		{
			previewImagePath = this.selectedMedia.largePath;
		}

		// add image prefix
		previewImagePath = imagePrefix + previewImagePath;
		this.selectedMedia.path = imagePrefix + this.selectedMedia.path;

		// convert bytes to kb
		this.selectedMedia['size'] = this._convertBytesToKilobytes(this.selectedMedia['size']) + ' Kb';
		this.selectedMedia.largeSize = this._convertBytesToKilobytes(this.selectedMedia.largeSize) + ' Kb';
		this.selectedMedia.mediumSize = this._convertBytesToKilobytes(this.selectedMedia.mediumSize) + ' Kb';
		this.selectedMedia.smallSize = this._convertBytesToKilobytes(this.selectedMedia.smallSize) + ' Kb';

		// set original image
		$(selectedImagePreviewSelector + " a.card-aside-column").css('background-image', 'url(' + previewImagePath + ')' );
		$(selectedImagePreviewSelector + " div#image_quality_original_option div.image-quality-option img").attr("src", this.selectedMedia.path);
		$(selectedImagePreviewSelector + " div#image_quality_original_option small.media-size").html('(' + this.selectedMedia['size'] + ')');

		if(this.selectedMedia.largePath)
		{
			this.selectedMedia.largePath = imagePrefix + this.selectedMedia.largePath;
			$(selectedImagePreviewSelector + " div#image_quality_large_option div.image-quality-option img").attr("src", this.selectedMedia.largePath);
			$(selectedImagePreviewSelector + " div#image_quality_large_option small.media-size").html('(' + this.selectedMedia.largeSize + ')');
		}

		if(this.selectedMedia.mediumPath)
		{
			this.selectedMedia.mediumPath = imagePrefix + this.selectedMedia.mediumPath;
			$(selectedImagePreviewSelector + " div#image_quality_medium_option div.image-quality-option img").attr("src", this.selectedMedia.mediumPath);
			$(selectedImagePreviewSelector + " div#image_quality_medium_option small.media-size").html('(' + this.selectedMedia.mediumSize + ')');
		}

		if(this.selectedMedia.smallPath)
		{
			this.selectedMedia.smallPath = imagePrefix + this.selectedMedia.smallPath;
			$(selectedImagePreviewSelector + " div#image_quality_small_option div.image-quality-option img").attr("src", this.selectedMedia.smallPath);
			$(selectedImagePreviewSelector + " div#image_quality_small_option small.media-size").html('(' + this.selectedMedia.smallSize + ')');
		}

		$("div#selected_image_preview").removeClass("d-none");
	}

	/**
	 * Converts bytes to kilobytes
	 */
	_convertBytesToKilobytes(bytes)
	{
		if( isNaN(bytes) || ! bytes ) return '';
		return Math.round(bytes / 1024);
	}

}

export default ImageContentType;