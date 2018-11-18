/**
 * Main app javascript file
 *
 * @author 	Alberto Vian - alberto@reaccionestudio.com
 * @website reaccionestudio.com
 */

require('./CommonEvents.js');

import Media from './media.js';
import Page from './page.js';
import User from './User.js';

const currentRoute 	= window.location.pathname;

$(document).ready(function()
{
	let pageAddContentRegex = /admin\/pages\/\d\/content\/add/gi;
	let pageEditContentRegex = /admin\/pages\/content\/\d/gi;
	let userEditRegex = /admin\/users\/\d\/update/gi;
	
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

	if(currentRoute == "/admin/users/create" || userEditRegex.test(currentRoute))
	{
		let user = new User();
			user.formEvents();
	}
});