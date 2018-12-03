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
		this.formSelector  = formSelector;
		this.selectedMedia = {};

		this._handleSelectedItemFromMediaGalleryEvent();
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
			this._previewVideoByPath();
		}
	}

	/**
	 * Listen to selectedItemFromMediaGallery event
	 */
	_handleSelectedItemFromMediaGalleryEvent()
	{
		var _self = this;

		document.addEventListener('selectedItemFromMediaGallery', function(e)
		{
			_self.selectedMedia = e.detail.video;
			
			if( ! _self.selectedMedia ) return;

			if( typeof e.detail.reset == "undefined" ) e.detail.reset = false;

			if(e.detail.reset)
			{
				// _self.resetImagePreview();
			}

			// Generate image preview
			_self.previewSelectedVideo(e.detail.reset);

			// Close modal
			$("div#modal").modal("hide");
		}, 
		false);
	}

	/**
	 * Generate video preview for selected gallery item
	 *
	 * @param  Boolean 	reset 	Indicate if form video path value has to be reset
	 * @return void 	[type]
	 */
	previewSelectedVideo(reset)
	{
		if(reset)
		{
			$(this.formSelector + ' textarea#page_content_value').val(this.selectedMedia.path);
		}

		let fullVideoPath = appUrl + '/uploads/' + this.selectedMedia.path;

		$("div#selected_video_preview div.dimmer-content h4.video-name").html(this.selectedMedia['name']);
		$("div#selected_video_preview div.dimmer-content video").attr('src', fullVideoPath);

		// hide loader
		$("div#selected_video_preview div.dimmer").removeClass("active");

		// show preview component
		$("div#selected_video_preview").removeClass("d-none");
	}

	/**
	 * Preview video from video path value for saved content.
	 */
	_previewVideoByPath()
	{
		let videoPath = $("textarea#page_content_value").val();
		let mediaDetailRoute = Routing.generate('reaccion_cms_admin_media_detail_by_path');

		$("div#selected_video_preview").removeClass("d-none");

		// load media data
		$.post(mediaDetailRoute, { 'path' : videoPath }, function(response)
		{
			// create and dispatch event
			var event = new CustomEvent(
								'selectedItemFromMediaGallery', 
								{ 
									'detail' : {
										'video' : response
									}
								}
							);

			document.dispatchEvent(event);

			// hide loader
			$("div#selected_video_preview div.dimmer").removeClass("active");
		}, 
		"json");
	}
}

export default MediaVideoPreviewComponent;