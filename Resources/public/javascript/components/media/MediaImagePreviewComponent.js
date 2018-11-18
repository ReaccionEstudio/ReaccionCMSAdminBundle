/**
 * Media image preview form component class
 *
 * @author 	Alberto Vian - alberto@reaccionestudio.com
 * @website reaccionestudio.com
 */

class MediaImagePreviewComponent
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
		this._handleSelectedItemFromMediaGalleryEvent();
		this._selectImageQualityEvent();
	}

	/**
	 * Hide gallery form component
	 */
	hideGallery()
	{
		$(this.galleryComponent + ", div#selected_image_preview").addClass("d-none");
		$("textarea#page_content_value").parent().removeClass("d-none");
		this.resetImagePreview();
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
			this._previewImageByPath();
		}
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

	/**
	 * Listen to selectedItemFromMediaGallery event
	 */
	_handleSelectedItemFromMediaGalleryEvent()
	{
		var _self = this;

		document.addEventListener('selectedItemFromMediaGallery', function(e)
		{
			_self.selectedMedia = e.detail.image;

			if( typeof e.detail.reset == "undefined" ) e.detail.reset = false;

			if(e.detail.reset)
			{
				_self.resetImagePreview();
			}

			// Generate image preview
			_self.previewSelectedImage(e.detail.reset);

			// Close modal
			$("div#modal").modal("hide");
		}, 
		false);
	}

	/**
	 * Reset image preview values
	 */
	resetImagePreview()
	{
		let selectedImagePreviewSelector = "div#selected_image_preview";
		let imageTypes = ['original', 'large', 'medium', 'small'];

		// reset form values
		$("textarea#page_content_value").val('');
		$(this.formSelector + ' div#selected_image_preview input[type="checkbox"]').prop('checked', false);
		$(this.formSelector + ' div#selected_image_preview input#original_image_quality').prop('checked', true);


		// other types
		for(let i in imageTypes)
		{
			$(selectedImagePreviewSelector + " div#image_quality_" + imageTypes[i] + "_option div.image-quality-option img").attr("src", '');
			$(selectedImagePreviewSelector + " div#image_quality_" + imageTypes[i] + "_option div.image-quality-option a").attr("href", '#');
			$(selectedImagePreviewSelector + " div#image_quality_" + imageTypes[i] + "_option small.media-size").html('');
		}
	}

	/**
	 * Generate image preview for selected gallery image
	 *
	 * @param  Boolean 	reset 	Indicate if form image path value has to be reset
	 * @return void 	[type]
	 */
	previewSelectedImage(reset)
	{
		if(reset)
		{
			$(this.formSelector + ' textarea#page_content_value').val(this.selectedMedia.path);
		}

		let selectedImagePreviewSelector = "div#selected_image_preview";
		let imagePrefix = appUrl + '/uploads/';

		// set values
		this._setValuesForSelectedPreviewImage('div#image_quality_original_option', 'original', selectedImagePreviewSelector, imagePrefix);
		this._setValuesForSelectedPreviewImage('div#image_quality_large_option', 'large', selectedImagePreviewSelector, imagePrefix);
		this._setValuesForSelectedPreviewImage('div#image_quality_medium_option', 'medium', selectedImagePreviewSelector, imagePrefix);
		this._setValuesForSelectedPreviewImage('div#image_quality_small_option', 'small', selectedImagePreviewSelector, imagePrefix);

		// hide loader
		$("div#selected_image_preview div.dimmer").removeClass("active");

		// show preview component
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
	 * Preview image from saved content image path value.
	 */
	_previewImageByPath()
	{
		console.log("_previewImageByPath");

		let imagePath = $("textarea#page_content_value").val();
		let mediaDetailRoute = Routing.generate('reaccion_cms_admin_media_detail_by_path');

		$("div#selected_image_preview").removeClass("d-none");

		// load media data
		$.post(mediaDetailRoute, { 'path' : imagePath }, function(response)
		{
			// create and dispatch event
			var event = new CustomEvent(
								'selectedItemFromMediaGallery', 
								{ 
									'detail' : {
										'image' : response
									}
								}
							);

			document.dispatchEvent(event);

			// check media option for current image in the image preview component
			for(var key in response)
			{
				if(imagePath == response[key]) 
				{
					$('input[data-path-key="' + key + '"]').attr('checked', 'checked');
				}
			}

			// hide loader
			$("div#selected_image_preview div.dimmer").removeClass("active");
		}, 
		"json");
	}
}

export default MediaImagePreviewComponent;