/**
 * Entries class
 *
 * @author 	Alberto Vian - alberto@reaccionestudio.com
 * @website reaccionestudio.com
 */

import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import ReaccionCMSUploadAdapter from '../ckeditor/imageUpload/ReaccionCMSUploadAdapter.js';

/**
 * ReaccionCMSUploadAdapter plugin
 */
function ReaccionCMSUploadAdapterPlugin( editor ) 
{
    editor.plugins.get( 'FileRepository' ).createUploadAdapter = ( loader ) => {
        
        let ImageUploadRoute = Routing.generate('reaccion_cms_admin_media_image_upload');
        return new ReaccionCMSUploadAdapter( loader, 'http://localhost:8000/admin/media/image/upload' );

    };
}

class Entries
{
	/**
	 * Entry form events
	 */ 
	formEvents()
	{
		this.editor = null;
		this._initEditor("textarea#entry_content");
		this._setEditorContentOnFormSubmit();
		this._toggleFormCategories();
		this._handleLanguageChangeEvent();
	}

	/**
	 * Initialize CKeditor
	 *
	 * @param 	String 		editorId 	Textarea id attribute for CKeditor
	 * @return 	void 		[type] 		
	 */
	_initEditor(editorId)
	{
		ClassicEditor
		    .create( 
		    	document.querySelector(editorId), {
		    		extraPlugins: [ ReaccionCMSUploadAdapterPlugin ]
		    	}
		    )
		    .then( editor => 
		    {
		    	this.editor = editor;
		    })
		    .catch( error => 
		    {
		        console.error( error );
		    });
	}

	/**
	 * Set editor content in textarea when form has been submitted
	 */
	_setEditorContentOnFormSubmit()
	{
		document.querySelector( '#submit' ).addEventListener( 'click', () => {
			let ckeditorContent = this.editor.getData();
			console.log(ckeditorContent);
			$("textarea#entry_content").val(ckeditorContent);

			return true;
		});
	}

	/**
	 * Handle language change event: 
	 *
	 *	 - Show only the categories for selected language
	 */
	_handleLanguageChangeEvent()
	{
		let _self = this;

		$("select#entry_language").on("change", function(e)
		{
			e.preventDefault();

			// unselect all languages
			$('div#entry_categories input[data-language]').prop('checked', false);

			_self._toggleFormCategories();
		});
	}

	/**
	 * Show only the categories for selected language
	 *
	 * @param String  Language  Selected language value
	 */
	_toggleFormCategories(language)
	{
		if(typeof language == "undefined")
		{
			language = $("#entry_language").val();
		}

		if(language)
		{
			$('div#entry_categories input[data-language]').parent().addClass("d-none");
			$('div#entry_categories input[data-language="' + language + '"]').parent().removeClass("d-none");
		}
		else
		{
			$('div#entry_categories input[data-language]').parent().addClass("d-none");
		}
	}
}

export default Entries;