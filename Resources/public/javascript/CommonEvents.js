/**
 * App common events.
 *
 * @author 	Alberto Vian - alberto@reaccionestudio.com
 * @website reaccionestudio.com
 */

 import Translator from './Translator.js';

 $(document).ready(function()
 {
 	  // Bootstrap tooltips events
  	$('[data-toggle="tooltip"]').tooltip();

  	// Remove record confirm
  	$('[data-detele-confirm]').on('click', function(e)
  	{
  		e.preventDefault();

      let translator = new Translator();

  		if(confirm(translator.trans("remove_item_confirm_message")))
  		{
  			var removeUrl = $(this).attr("href");

  			if( ! removeUrl.length) return;

  			window.location = removeUrl;
  		}
  	});

    // Show loading spinner when button is pressed
    $('button[data-spinner="true"], a[data-spinner="true"]').on('click', function()
    {
      let form = $(this).parents("form");

      if( ! form.length ) return;

      if( form[0].checkValidity() == false ) return;

      if( ! $(this).hasClass('btn-loading'))
      {
        $(this).addClass('btn-loading');
      }

      $(this).attr('disabled', 'disabled');
    });
 });
