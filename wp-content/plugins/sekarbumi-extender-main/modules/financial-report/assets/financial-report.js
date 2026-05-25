(function($){
  "use strict"
  var currentPage = 1;

  const financialReports = async () => {

    $('.skbm-cpt-tax-switcher__inner li').eq(0).addClass('uk-active');
    $('.skbm-cpt-tax-switcher__inner li a').on( 'click', function(){
      let catId = $(this).data('category-id');
      let outerHeight = $('.financial-report').outerHeight();
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
          action: 'financial_report_get_post',
          catId: catId
        },
        beforeSend: function(){
          $('.financial-report').html('');
          $('.financial-report').css('min-height', outerHeight);
          $('.financial-report').append('<div class="loader">Loading...</div>');
        },
        success: function( res ) {
          if( res.hasContent ){
            $('#load-more').show();
          }
          $('.financial-report').append(res.html);
          $('.loader').remove();
          $('.financial-report').css('min-height', '0');
        }
      });
    });

    $('#load-more').on('click', function(e) {
      e.preventDefault();
      let button = $(this);
      let catId = button.attr('data-categories-id');
      currentPage++; // Do currentPage + 1, because we want to load the next page

      $.ajax({
        type: 'POST',
        url: skbmPublicAjax.ajaxUrl,
        dataType: 'JSON',
        data: {
          action: 'financial_report_more_post',
          paged: currentPage,
          catId: catId
        },
        success: function (res) {
          if( res.html === ''){
            button.hide();
          }
          $('.financial-report').append(res.html);
        }
      });
    });
  }

  $(document).on('ready', function(){
    financialReports();
  });
})(jQuery);