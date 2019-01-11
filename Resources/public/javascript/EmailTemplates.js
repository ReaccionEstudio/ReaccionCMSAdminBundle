/**
 * Email templates class
 *
 * @author 	Alberto Vian - alberto@reaccionestudio.com
 * @website reaccionestudio.com
 */

import Translator from './Translator.js';

class EmailTemplates
{
	/**
	 * Constructor
	 */
	constructor() 
	{
		this.translator = new Translator();
	}

	/**
	 * Events
	 */
	events()
	{
		this._addEmailMessageParam();
	}

	/**
	 * Add email message param
	 */
	_addEmailMessageParam()
	{
		let _self = this;
		let inputCount = 1;

		$("button#add_custom_email_message_param").on("click", function(e)
		{
			e.preventDefault();

			let inputField = 	'<div class="custom-email-param-group">\
									<hr />\
									<div class="form-group">\
										<label>' + _self.translator.trans('email_message_param_name') + '</label>\
										<input type="text" id="custom_param_name_' +  inputCount + '" name="custom_params[' +  inputCount + '][name]" class="form-control" />\
									</div>\
									<div class="form-group">\
										<label>' + _self.translator.trans('email_message_param_value') + '</label>\
										<input type="text" id="custom_param_value_' +  inputCount + '" name="custom_params[' +  inputCount + '][value]" class="form-control" />\
									</div>\
									<a href="#" class="text-danger remove-mssg-param"><i class="fe fe-trash-2"></i>&nbsp;&nbsp;' + _self.translator.trans('remove') + '</a>\
							  	</div>';

			$("div#custom_email_message_params").append(inputField);

			inputCount++;
		});

		$("body").on("click", "a.remove-mssg-param", function(e)
		{
			e.preventDefault();
			$(this).parent().remove();
			inputCount--;
		});
	}
}

export default EmailTemplates;