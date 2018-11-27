/**
 * Menu class
 *
 * @author  Alberto Vian - alberto@reaccionestudio.com
 * @website reaccionestudio.com
 */

import CommonFunctions from './CommonFunctions.js';

class Menu
{
	/**
   	* Constructor
   	*/
  	constructor()
  	{
	    this.commonFunctions = new CommonFunctions();
  	}

  	/**
   	 * User form events
   	 */
  	formEvents()
  	{
    	this.commonFunctions.appendRequiredFormFieldsAsterisks("menu");
    	this._toggleFormFieldsEvent();
	}

	/**
	 * Toggle form fields event
	 */
	_toggleFormFieldsEvent()
	{
		let _self = this;
		let type  = $("select#menu_type").val();

		// hide field on load
		if( ! type.length )
		{
			$("select#menu_pageValue").parent().addClass("d-none");
			$("input#menu_urlValue").parent().addClass("d-none");
		}
		else
		{
			this._toggleFormFields(type);
		}

		// on change event
		$("select#menu_type").on("change", function()
		{
			let type = $(this).val();
			_self._toggleFormFields(type);
		});
	}

	/**
	 * Toggle form fields when type field value changes
	 */
	_toggleFormFields(type)
	{
		if(type == "url")
		{
			$("select#menu_pageValue").parent().addClass("d-none");
			$("input#menu_urlValue").parent().removeClass("d-none");
		}
		else if(type == "page")
		{
			$("input#menu_urlValue").parent().addClass("d-none");
			$("select#menu_pageValue").parent().removeClass("d-none");
		}
	}
}

export default Menu;