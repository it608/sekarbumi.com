(function($){

	const languageSwitcher = async () => {

		$('#primary-menu').append('<li class="zod-tp-switcher"><ul class="zod-tp-switcher__desktop"></ul></li>');
		$('#mobile-menu').append('<li class="zod-tp-switcher"><ul class="zod-tp-switcher-mobile"></ul></li>');

		let languageItemDesktop = $('#primary-menu .trp-language-switcher-container'),
			  languageItemMobile	= $('#mobile-menu .trp-language-switcher-container');

		languageItemDesktop.each( (i, el) => {
			$(el).appendTo('.zod-tp-switcher__desktop');
      if( i === 0 ){
        $('.zod-tp-switcher__desktop').append('<span class="divider"></span>');
      }
		});

		languageItemMobile.each( (i, el) => {
			$(el).appendTo('.skbm-switcher-mobile');
		});
		
	}

    const sharePost = async () => {
      $('.share-popup').each( (i, el) => {
          $(el).on( 'click', () => {
              window.open( $(el).attr('href'), 'ShareWindow', 'height=450, width=550, top=' + ($(window).height() / 2 - 275) + ', left=' + ($(window).width() / 2 - 225) + ', toolbar=0, location=0, menubar=0, directories=0, scrollbars=0');
          });
          
      });
    }

	$( document ).on( 'ready', () => {
	  languageSwitcher();
    sharePost();
	});
})(jQuery);