/**
 * Main app javascript file
 *
 * @author 	Alberto Vian - alberto@reaccionestudio.com
 * @website reaccionestudio.com
 */


require('./commonEvents.js');

import Media from './media.js';
import Page from './page.js';

const currentRoute 	= window.location.pathname;

$(document).ready(function()
{
	if(currentRoute == "/admin/media/add")
	{
		let media = new Media();
			media.formEvents();
	}

	if(currentRoute == "/admin/pages/2/content/add") // TODO: regex
	{
		let page = new Page();
			page.formEvents();
	}
});