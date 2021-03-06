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
      let buttonType = $(this).attr("type");
      let parent = $(this).parents().eq(3);
      
      if(buttonType == "submit")
      {
        let form = $(this).parents("form");
        if( ! form.length || form[0].checkValidity() == false ) return;
        parent = form;
      }

      if( ! $(this).hasClass('btn-loading'))
      {
        $(this).addClass('btn-loading');
      }

      parent.find("button").attr('disabled', 'disabled');
    });

    // change language
    $('div#language_picker_widget div.dropdown-menu a').on('click', function(e)
    {
      e.preventDefault();

      let language = $(this).attr('data-language');

      if( ! language) return;

      let route = Routing.generate('change_language', { 'language' : language });

      window.location = route;

    });

    // data-click="href"
    $('[data-click="href"]').on('click', function(e)
    {
      e.preventDefault();

      if( ! $("[data-url]").length ) return;

      let url = $(this).attr('data-url');
      window.location = url;
    });

 });
