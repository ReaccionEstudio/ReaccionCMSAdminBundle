import Translations from "./translations/Messages.js";

/**
 * Translator class
 *
 * @author  Alberto Vian - alberto@reaccionestudio.com
 * @website reaccionestudio.com
 */
class Translator
{
	/**
	 * Constructor
	 */
	constructor()
	{
		if(typeof appLanguage == "undefined)")
		{
			throw new Error("Error, appLanguage variable not found.")
		}

		this.language = appLanguage;
	}

	/**
	 * Get the translation for specified key
	 *
	 * @param  String 	key 	Translation key
	 * @return String 	[type]	Translation value
	 */
	trans(key)
	{
		if(Translations[this.language] && Translations[this.language][key])
		{
			return Translations[this.language][key];	
		}

		return '';
	}
}

export default Translator;