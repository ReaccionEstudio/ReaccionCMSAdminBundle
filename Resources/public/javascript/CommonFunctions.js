/**
 * Common functions class
 *
 * @author 	Alberto Vian - alberto@reaccionestudio.com
 * @website reaccionestudio.com
 */

class CommonFunctions
{
	/**
	 * Append red asterisks indicator for required form fields
	 *
	 * @param  String 	formName 	Form name
	 * @return void		[type]
	 */
	appendRequiredFormFieldsAsterisks(formName)
	{
		if( ! formName) return;

		let formElems = $('form[name="' + formName + '"] [required]');

		formElems.each(function()
		{
			$(this).parent().find('label').append('<span class="form-required">*</span>');
		});
	}
}

export default CommonFunctions;