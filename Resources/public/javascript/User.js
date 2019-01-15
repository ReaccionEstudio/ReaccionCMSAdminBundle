/**
 * User class
 *
 * @author  Alberto Vian - alberto@reaccionestudio.com
 * @website reaccionestudio.com
 */

import CommonFunctions from './CommonFunctions.js';
import LanguageIcons from './collections/language_icons.json';

class User
{
  /**
   * Constructor
   */
  constructor()
  {
    this.commonFunctions = new CommonFunctions();
  }

  /**
   * User form events
   */
  formEvents()
  {
    this.commonFunctions.appendRequiredFormFieldsAsterisks("user");

    // Add lang value to all select options
    $('select#user_language option').each(function()
    {
      let lang = $(this).val();
      let data = {
        'icon' : LanguageIcons.language_icons[lang]
      };

      $(this).attr("data-data", JSON.stringify(data));
    });
  }

}

export default User;