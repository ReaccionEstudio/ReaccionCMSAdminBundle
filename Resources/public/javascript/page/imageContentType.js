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
		this._selectImageQualityEvent();
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
		$(this.galleryComponent + ", div#selected_image_preview").addClass("d-none");
		$("textarea#page_content_value").parent().removeClass("d-none");
		this._resetImagePreview();
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
			_self._resetImagePreview();
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
		$(this.formSelector + ' textarea#page_content_value').val(this.selectedMedia.path);

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
		let originalPath = imagePrefix + this.selectedMedia.path;

		// set values
		this._setValuesForSelectedPreviewImage('div#image_quality_original_option', 'original', selectedImagePreviewSelector, imagePrefix);
		this._setValuesForSelectedPreviewImage('div#image_quality_large_option', 'large', selectedImagePreviewSelector, imagePrefix);
		this._setValuesForSelectedPreviewImage('div#image_quality_medium_option', 'medium', selectedImagePreviewSelector, imagePrefix);
		this._setValuesForSelectedPreviewImage('div#image_quality_small_option', 'small', selectedImagePreviewSelector, imagePrefix);

		$("div#selected_image_preview").removeClass("d-none");
	}

	/**
	 * Set values for selected image in the preview template
	 *
	 * @param 	String 	selector 						Image quality DOM element selector
	 * @param 	String 	qualityPrefix 					Prefix for each image quality type
	 * @param 	String 	selectedImagePreviewSelector 	Selector for preview image container div 
	 * @param 	String 	imagePrefix 					Image prefix with full upload url path
	 * @return  [void]
	 */
	_setValuesForSelectedPreviewImage(selector, qualityPrefix, selectedImagePreviewSelector, imagePrefix)
	{
		let key = (qualityPrefix == "original") ? "path" : qualityPrefix + 'Path';
		let sizeKey = (qualityPrefix == "original") ? "size" : qualityPrefix + 'Size';

		if(this.selectedMedia[key])
		{
			let path = imagePrefix + this.selectedMedia[key];
			
			// convert bytes to kb
			this.selectedMedia[sizeKey] = this._convertBytesToKilobytes(this.selectedMedia[sizeKey]) + ' Kb';

			// set values to image preview template
			$(selectedImagePreviewSelector + " " + selector + " div.image-quality-option img").attr("src", path);
			$(selectedImagePreviewSelector + " " + selector + " div.image-quality-option a").attr("href", path);
			$(selectedImagePreviewSelector + " " + selector + " small.media-size").html('(' + this.selectedMedia[sizeKey] + ')');

			// display image quality option
			$(selector).removeClass("d-none");
		}
		else
		{
			$(selector).addClass("d-none");
		}
	}

	/**
	 * Reset image preview values
	 */
	_resetImagePreview()
	{
		let selectedImagePreviewSelector = "div#selected_image_preview";
		let imageTypes = ['original', 'large', 'medium', 'small'];

		// reset form value
		$("textarea#page_content_value").val('');

		// other types
		for(let i in imageTypes)
		{
			$(selectedImagePreviewSelector + " div#image_quality_" + imageTypes[i] + "_option div.image-quality-option img").attr("src", '');
			$(selectedImagePreviewSelector + " div#image_quality_" + imageTypes[i] + "_option div.image-quality-option a").attr("href", '#');
			$(selectedImagePreviewSelector + " div#image_quality_" + imageTypes[i] + "_option small.media-size").html('');
		}
	}

	/**
	 * Convert bytes to kilobytes
	 *
	 * @param 	Float 	bytes 		Bytes number
	 * @return	Float 	[type] 		Kilobytes number
	 */
	_convertBytesToKilobytes(bytes)
	{
		if( isNaN(bytes) || ! bytes ) return '';
		return Math.round(bytes / 1024);
	}

	/**
	 * Event for image quality selection
	 */
	_selectImageQualityEvent()
	{
		let _self = this;

		$('input[name="imageQuality"]').on("change", function()
		{
			let selectedQuality = $(this).val();
			let key = (selectedQuality == 'original') ? 'path' : selectedQuality + 'Path';

			if(_self.selectedMedia[key])
			{
				$("textarea#page_content_value").val(_self.selectedMedia[key]);
			}
			else
			{
				$("textarea#page_content_value").val('');
			}
		});
	}

}

export default ImageContentType;