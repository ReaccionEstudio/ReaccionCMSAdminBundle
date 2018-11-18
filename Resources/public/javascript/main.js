/**
 * Main app javascript file
 *
 * @author 	Alberto Vian - alberto@reaccionestudio.com
 * @website reaccionestudio.com
 */

require('./commonEvents.js');

import Media from './media.js';
import Page from './page.js';
import User from './User.js';

const currentRoute 	= window.location.pathname;

$(document).ready(function()
{
	let pageAddContentRegex = /admin\/pages\/\d\/content\/add/gi;
	let pageEditContentRegex = /admin\/pages\/content\/\d/gi;
	
	if(currentRoute == "/admin/media/add")
	{
		let media = new Media();
			media.formEvents();
	}

	if(pageAddContentRegex.test(currentRoute) || pageEditContentRegex.test(currentRoute) )
	{
		let page = new Page();
			page.formEvents();
	}

	if(currentRoute == "/admin/users/create")
	{
		let user = new User();
			user.formEvents();
	}
});