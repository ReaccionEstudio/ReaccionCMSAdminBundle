/**
 * Mailer class
 *
 * @author 	Alberto Vian - alberto@reaccionestudio.com
 * @website reaccionestudio.com
 */

class Mailer
{
	/**
	 * Constructor
	 */
	constructor() { }

	/**
	 * Events
	 */
	events()
	{
		this._sendTestEmailEvent();
	}

	/**
	 * Send test email
	 */
	_sendTestEmailEvent()
	{
		let sendingTestEmail = false;

		$("button#send_test_email").on("click", function(e)
		{
			e.preventDefault();

			if(sendingTestEmail) return;

			let url = Routing.generate("reaccion_cms_admin_preferences_mailer_sent_test_email");
			window.location = url;

			sendingTestEmail = true;
		});
	}
}

export default Mailer;