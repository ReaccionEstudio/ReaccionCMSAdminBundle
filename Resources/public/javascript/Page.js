/**
 * Page class
 *
 * @author 	Alberto Vian - alberto@reaccionestudio.com
 * @website reaccionestudio.com
 */

import MediaGalleryFormComponent from './components/media/MediaGalleryFormComponent.js';
import CommonFunctions from './CommonFunctions.js';

class Page
{
	/**
	 * Constructor
	 */
	constructor()
	{ 
	}

	/**
	 * Page form events
	 */
	formEvents()
	{
		this.mediaGalleryFormComponent = new MediaGalleryFormComponent();
		this.mediaImagePreviewComponent = this.mediaGalleryFormComponent.mediaImagePreviewComponent;
		this.mediaVideoPreviewComponent = this.mediaGalleryFormComponent.mediaVideoPreviewComponent;
		
		this._toggleGalleryEvent();
		this._onChangePageContentTypeEvent();
	}

	/**
	 * Page detail event
	 */
	pageDetailEvents()
	{
		let commonFunctions = new CommonFunctions();
		commonFunctions.appendRequiredFormFieldsAsterisks('page');
		commonFunctions.appendRequiredFormFieldsAsterisks('seo_page');
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
		else if(selectedValue == "img")
		{
			this.mediaImagePreviewComponent.showGallery();
		}
		else if(selectedValue == "entry_categories" || selectedValue == "entries_list")
		{
			this.mediaImagePreviewComponent.hideGallery(false);
			$("textarea#page_content_value").parent().addClass("d-none");
		}
		else
		{
			this.mediaImagePreviewComponent.hideGallery(false);
		}
	}

	/**
	 * Handle onChange page content type input event
	 */
	_onChangePageContentTypeEvent()
	{
		let _self = this;

		$("select#page_content_type").on("change", function()
		{
			_self._toggleGalleryEvent();
		});
	}

}

export default Page;