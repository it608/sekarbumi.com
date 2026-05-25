(function ($) {
  "use strict"
  var currentPage = 1;

  const pressRelease = async () => {
    $('.skbm-cpt-tax-switcher__inner li').eq(0).addClass('uk-active');
    $('.skbm-cpt-tax-switcher__inner li a').on('click', function () {
      let catId = $(this).data('category-id');
      let outerHeight = $('.press-release').outerHeight();
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
          action: 'press_release_get_post',
          catId: catId
        },
        beforeSend: function () {
          $('.press-release').html('');
          $('.press-release').css('min-height', outerHeight);
          $('.press-release').append('<div class="loader">Loading...</div>');
        },
        success: function (res) {
          $('.press-release').append(res.html);
          if (res.hasContent === false) {
            $('#load-more').hide();
          }
          $('.loader').remove();
          $('.press-release').css('min-height', '0');
        }
      });
    });

    $('#load-more').on('click', function (e) {
      let button = $(this);
      let catId = button.attr('data-categories-id');
      e.preventDefault();
      currentPage++; // Do currentPage + 1, because we want to load the next page

      $.ajax({
        type: 'POST',
        url: skbmPublicAjax.ajaxUrl,
        dataType: 'JSON',
        data: {
          action: 'press_release_more_post',
          paged: currentPage,
          catId: catId
        },
        success: function (res) {
          if (res.html === '') {
            button.hide();
          }
          $('.press-release').append(res.html);
        }
      });
    });
  }

  $(document).ready(function () {
    pressRelease();
  });
})(jQuery);