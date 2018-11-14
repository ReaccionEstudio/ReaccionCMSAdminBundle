/**
 * Page class
 *
 * @author 	Alberto Vian - alberto@reaccionestudio.com
 * @website reaccionestudio.com
 */

import ImageContentType from "./page/imageContentType.js";

class Page
{
	/**
	 * Constructor
	 */
	constructor()
	{

	}

	/**
	 * Page form events
	 */
	formEvents()
	{
		var imageContentType = new ImageContentType();
			imageContentType.events();
	}

}

export default Page;