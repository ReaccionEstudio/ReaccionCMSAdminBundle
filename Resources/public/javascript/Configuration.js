/**
 * Configuration class
 *
 * @author 	Alberto Vian - alberto@reaccionestudio.com
 * @website reaccionestudio.com
 */

import ImagePickerComponent from './components/image/ImagePickerComponent.js';

class Configuration
{
	/**
	 * Configuration form events
	 */
	formEvents()
	{
		this._initComponents();
	}

	_initComponents()
	{
		let configType = $("input#config_type").val();

		if(configType == "image")
		{
			let imagePickerComponent = new ImagePickerComponent();

			// hide value input
			$("textarea#config_value").parent().addClass('d-none');
		}
	}
}

export default Configuration;