(function($){
  "use strict"
  var currentPage = 1;

  const publicDisclosure = async () => {
    $('.skbm-cpt-tax-switcher__inner li').eq(0).addClass('uk-active');
    $('.skbm-cpt-tax-switcher__inner li a').on( 'click', function(e){
      e.preventDefault();
      let catId = $(this).data('category-id');
      let outerHeight = $('.public-disclosure').outerHeight();
      $('#load-more').attr('data-categories-id', catId);
      $('#load-more').show();
      $('.skbm-cpt-tax-switcher__inner li a').not($(this)).parents('li').removeClass('uk-active');
      $(this).parents('li').addClass('uk-active');

      currentPage = 1;
      $.ajax({
        type: 'POST',
        url: skbmPublicAjax.ajaxUrl,
        dataType: 'JSON',
        data: {
          action: 'public_disclosure_get_post',
          catId: catId
        },
        beforeSend: function(){
          $('.public-disclosure').html('');
          $('.public-disclosure').css('min-height', outerHeight);
          $('.public-disclosure').append('<div class="loader">Loading...</div>');
        },
        success: function( res ) {
          $('.public-disclosure').append(res.html);
          if( res.hasContent === false ){
            $('#load-more').hide();
          }
          $('.loader').remove();
          $('.public-disclosure').css('min-height', '0');
        }
      });
    });

    $('#load-more').on('click', function(e) {
      let button = $(this);
      let catId = button.attr('data-categories-id');
      e.preventDefault();
      currentPage++; // Do currentPage + 1, because we want to load the next page

      $.ajax({
        type: 'POST',
        url: skbmPublicAjax.ajaxUrl,
        dataType: 'JSON',
        data: {
          action: 'public_disclosure_more_post',
          paged: currentPage,
          catId: catId
        },
        success: function (res) {
          if( res.html === ''){
            button.hide();
          }
          $('.public-disclosure').append(res.html);
        }
      });
    });
  }

  $(document).on( 'ready', function(){
    publicDisclosure();
  });
})(jQuery);