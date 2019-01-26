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
		this._showFormMessageParams();
	}

	/**
	 * Show message param form inputs
	 */
	_showFormMessageParams()
	{
		let messageParams = $("textarea#messageParams").val();

		if( ! messageParams.length) return;
		
		messageParams = JSON.parse(messageParams);

		for(var i in messageParams)
		{
			let inputCount = i + 1;
			let inputField = this._getCustomParamHtml(inputCount, messageParams[i].name, messageParams[i].value);
			$("div#custom_email_message_params").append(inputField);
		}
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

			let inputField = _self._getCustomParamHtml(inputCount);
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

	/**
	 * Get custom param input html code
	 *
	 * @param  Int 		inputCount 	Input number
	 * @param  String 	name 		Input name value
	 * @param  String 	value 		Input value
	 * @return String 	[type]		Input HTML
	 */
	_getCustomParamHtml(inputCount, name, value)
	{
		if(typeof name == "undefined")  name = "";
		if(typeof value == "undefined") value = "";

		return '<div class="custom-email-param-group">\
					<hr />\
					<div class="form-group">\
						<label>' + this.translator.trans('email_message_param_name') + '</label>\
						<input type="text" id="custom_param_name_' +  inputCount + '" name="custom_params[' +  inputCount + '][name]" class="form-control" value="' + name + '" />\
					</div>\
					<div class="form-group">\
						<label>' + this.translator.trans('email_message_param_value') + '</label>\
						<textarea id="custom_param_value_' +  inputCount + '" name="custom_params[' +  inputCount + '][value]" class="form-control">' + value + '</textarea>\
					</div>\
					<a href="#" class="text-danger remove-mssg-param"><i class="fe fe-trash-2"></i>&nbsp;&nbsp;' + this.translator.trans('remove') + '</a>\
			  	</div>';
	}
}

export default EmailTemplates;