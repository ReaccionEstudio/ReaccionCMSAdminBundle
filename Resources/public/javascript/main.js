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
	let pageContentAddRegex = /admin\/pages\/\d\/content\/add/gi;
	
	if(currentRoute == "/admin/media/add")
	{
		let media = new Media();
			media.formEvents();
	}

	if(pageContentAddRegex.test(currentRoute))
	{
		let page = new Page();
			page.formEvents();
	}
});