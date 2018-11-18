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

    // Selectize inputs
    $('select#user_language').selectize(
    {
      render: {
        option: function (data, escape) {
            return '<div>' +
                      '<span class="image"><i class="flag flag-' + data.icon + '"></i></span>' +
                      '<span class="title">' + escape(data.text) + '</span>' +
                    '</div>';
        },
        item: function (data, escape) {
            return '<div>' +
                      '<span class="image"><i class="flag flag-' + data.icon + '"></i></span>' +
                      escape(data.text) +
                    '</div>';
        }
      }
    });
  }

}

export default User;