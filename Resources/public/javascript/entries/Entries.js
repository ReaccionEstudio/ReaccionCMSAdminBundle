/**
 * Entries class
 *
 * @author 	Alberto Vian - alberto@reaccionestudio.com
 * @website reaccionestudio.com
 */

import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

class Entries
{
	formEvents()
	{
		this._initEditor("textarea#entry_content");
	}

	_initEditor(editorId)
	{
		ClassicEditor
		    .create( 
		    	document.querySelector(editorId),
		    	{
		    		
		    	}
		    )
		    .then( editor => {
		        console.log( editor );
		    } )
		    .catch( error => {
		        console.error( error );
		    } );

	}
}

export default Entries;