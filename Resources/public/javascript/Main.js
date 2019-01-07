/**
 * Main app javascript file
 *
 * @author 	Alberto Vian - alberto@reaccionestudio.com
 * @website reaccionestudio.com
 */

// images
require("../images/reaccion_cms_logo.jpg");

import Translator from './Translator.js';

// js
require('./CommonEvents.js');

import Media from './Media.js';
import Page from './Page.js';
import User from './User.js';
import Menu from './Menu.js';
import Configuration from './Configuration.js';
import Entries from './entries/Entries.js';
import Mailer from './Mailer.js';

const currentRoute 	= window.location.pathname;

$(document).ready(function()
{
	let pageAddContentRegex = /admin\/pages\/\d+\/content\/add/gi;
	let pageEditContentRegex = /admin\/pages\/content\/\d+/gi;
	let userEditRegex = /admin\/users\/\d+\/update/gi;
	let menuCreateRegex = /admin\/appearance\/menu\/\d+\/create\/?\d*/gi;
	let menuDetailRegex = /admin\/appearance\/menu\/\d+/gi;
	let entryEditRegex = /admin\/entries\/\d+\/update/gi;
	let configEditRegex = /admin\/preferences\/configuration\/\d+\/update/gi;
	
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

	if( menuCreateRegex.test(currentRoute) || 
		currentRoute == "/admin\/preferences\/menu\/create" || 
		menuDetailRegex.test(currentRoute) 
	)
	{
		let menu = new Menu();
			menu.formEvents();
	}

	if(currentRoute == "/admin\/entries\/create" || entryEditRegex.test(currentRoute))
	{
		let entries = new Entries();
			entries.formEvents();
	}

	if(currentRoute == "/admin\/preferences\/mailer")
	{
		let mailer = new Mailer();
			mailer.events();
	}

	if(configEditRegex.test(currentRoute))
	{
		let config = new Configuration();
			config.formEvents();
	}
});