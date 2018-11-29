/**
 * Page class
 *
 * @author 	Alberto Vian - alberto@reaccionestudio.com
 * @website reaccionestudio.com
 */

import ImageContentType from "./page/ImageContentType.js";
import VideoContentType from "./page/VideoContentType.js";

class Page
{
	/**
	 * Constructor
	 */
	constructor(){ }

	/**
	 * Page form events
	 */
	formEvents()
	{
		let imageContentType = new ImageContentType();
			imageContentType.events();

		let videoContentType  = new VideoContentType();
			videoContentType.events();
	}

}

export default Page;