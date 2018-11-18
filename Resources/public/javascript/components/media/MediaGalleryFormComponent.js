/**
 * Media gallery form component class
 *
 * @author 	Alberto Vian - alberto@reaccionestudio.com
 * @website reaccionestudio.com
 */

 import MediaImagePreviewComponent from './MediaImagePreviewComponent.js';

class MediaGalleryFormComponent
{
 	/** 
	 * Constructor
	 */
	constructor()
	{
		this.selectedMedia = {};
		this.galleryComponent = "div#open_gallery_button";
		this._getFormSelectorValue();

		// Media image preview component
		this.mediaImagePreviewComponent = new MediaImagePreviewComponent(this.galleryComponent, this.formSelector);
		
		// Media gallery events
		this.events();
	}

	/**
	 * Media gallery form component events
	 */
	events()
	{
		this._navigateThroughtMediaGalleryEvent();
		this._selectMediaItemFromGalleryEvent();
	}

	/**
	 * Get form selector value defined as 'data-form-name' attribute in the 'Open gallery form button'
	 */
	_getFormSelectorValue()
	{
		let formName = $(this.galleryComponent).attr("data-form-name");

		if( ! formName)
		{
			throw new Error("Attribute 'data-form-name' does not exist.");
		}

		this.formSelector = 'form[name="' + formName + '"]';
	}

	/**
	 * Show media gallery
	 */
	showMediaGallery()
	{
		var mediaListRoute = Routing.generate('reaccion_cms_admin_media_index');
		mediaListRoute = mediaListRoute + '?modal=1';

		$("div#modal").modal("show");
		$("div#modal div.modal-body div.dimmer-content").load(mediaListRoute, function(result)
		{
			$("div#modal div.modal-body .dimmer").removeClass("active");
		});
	}

	/**
	 * Handles navigation event for media gallery
	 */
	_navigateThroughtMediaGalleryEvent()
	{
		$("body").on("click", "div#modal div.modal-body ul.pagination a.page-link", function(e)
		{
			e.preventDefault();
			
			$("div#modal div.modal-body .dimmer").addClass("active");
			$("div#modal div.modal-body").load($(this).attr("href"));
		});
	}

	/**
	 * Select media item from gallery
	 */
	_selectMediaItemFromGalleryEvent()
	{
		$("body").on("click", "div#modal div.card a[data-media-id]", function(e)
		{
			e.preventDefault();

			let mediaId = $(this).attr("data-media-id");

			// Get image details
			let detailMediaRoute = Routing.generate('reaccion_cms_admin_media_detail', { 'media' : mediaId });

			$.get(detailMediaRoute + '?json=1', function(result)
			{
				this.selectedMedia = result;
				
				// create and dispatch event
				var event = new CustomEvent(
									'selectedItemFromMediaGallery', 
									{ 
										'detail' : {
											'image' : this.selectedMedia,
											'reset' : true
										}
									}
								);

				document.dispatchEvent(event);
			});

		});
	}
}

export default MediaGalleryFormComponent;