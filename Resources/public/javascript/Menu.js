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
    	this._toggleFormFields();
	}

	/**
	 * Toggle form fields when type field value changes
	 */
	_toggleFormFields()
	{
		// hide field on load
		$("select#menu_pageValue").parent().addClass("d-none");
		$("input#menu_urlValue").parent().addClass("d-none");

		// on change event
		$("select#menu_type").on("change", function()
		{
			let type = $(this).val();

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
		});
	}
}

export default Menu;