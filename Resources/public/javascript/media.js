/**
 * Media class
 *
 * @author 	Alberto Vian - alberto@reaccionestudio.com
 * @website reaccionestudio.com
 */

class Media
{
	/**
	 * Constructor
	 */
	constructor()
	{
		this.selectedMedia = {};
	}

	/**
	 * Media form events
	 */
	formEvents()
	{
		var submitted = false;

		// Show loading button on form submit
		$("button#send_media_btn").on("click", function(e)
		{
			if(submitted) e.preventDefault();

			if($("input.custom-file-input")[0].files.length)
			{
				submitted = true;

				$(this).html('<div class="fe fe-refresh-cw spin"></div>');
				$(this).attr("disabled", "disabled");
			}
		});

		// Set filename value in the input label for the selected file
	    $("input.custom-file-input").on("change", function()
	    {
	      var files = $("input.custom-file-input")[0].files;
	      
	      if(files[0].name)
	      {
	        var filename = files[0].name;
	        $("label.custom-file-label").html(filename);
	      }
	    });
	}

	/**
	 * Show media gallery
	 */
	_showMediaGallery()
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
	 * Media gallery events
	 */
	mediaGalleryEvents()
	{
		this._navigateThroughtMediaGalleryEvent();
		this._selectMediaItemFromGalleryEvent();
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
		$("body").on("click", "div#modal div.card a[data-media-path]", function(e)
		{
			e.preventDefault();

			let path = $(this).attr("data-media-path");
			let largePath = $(this).attr("data-media-large-path");
			let mediumPath = $(this).attr("data-media-medium-path");
			let smallPath = $(this).attr("data-media-small-path");

			// TODO: ADD IMAGE SIZE
			this.selectedMedia = {
				"path" : path,
				"largePath" : largePath,
				"mediumPath" : mediumPath,
				"smallPath" : smallPath
			};

			// create and dispatch event
			var event = new CustomEvent('selectedItemFromMediaGallery', { 'detail' : this.selectedMedia });
			document.dispatchEvent(event);
		});
	}
}

export default Media;