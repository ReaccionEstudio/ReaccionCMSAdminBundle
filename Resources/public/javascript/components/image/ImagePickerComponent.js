/**
 * Image picker component class
 *
 * @author 	Alberto Vian - alberto@reaccionestudio.com
 * @website reaccionestudio.com
 */

import ImagePickerComponentEvents from './ImagePickerComponentEvents.js';

class ImagePickerComponent
{
	constructor()
	{
		this._init();
	}

	_init()
	{
		if($(".image-picker").length)
		{
			this._events();
		}
	}

	_events()
	{
		let imagePickerComponentEvents = new ImagePickerComponentEvents();
			imagePickerComponentEvents.setFilenameValueInInputLabelEvent();
	}
}

export default ImagePickerComponent;