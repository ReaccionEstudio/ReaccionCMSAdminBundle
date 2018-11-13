
require('./commonEvents.js');

import Media from './media.js';

const currentRoute 	= window.location.pathname;

$(document).ready(function()
{
	if(currentRoute == "/admin/media/add")
	{
		let media = new Media();
			media.formEvents();
	}
});