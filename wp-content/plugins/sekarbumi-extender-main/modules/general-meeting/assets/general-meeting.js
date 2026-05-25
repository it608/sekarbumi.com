(function ($) {
  "use strict"
  var currentPage = 1;

  const generalMeeting = async () => {
    $('.skbm-cpt-tax-switcher__inner li').eq(0).addClass('uk-active');
    $('.skbm-cpt-tax-switcher__inner li a').on('click', function (e) {
      e.preventDefault();
      let catId = $(this).data('category-id');
      let outerHeight = $('.general-meeting').outerHeight();
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
          action: 'general_meeting_get_post',
          catId: catId
        },
        beforeSend: function () {
          $('.general-meeting').html('');
          $('.general-meeting').css('min-height', outerHeight);
          $('.general-meeting').append('<div class="loader">Loading...</div>');
        },
        success: function (res) {
          $('.general-meeting').append(res.html);
          if (res.hasContent === false) {
            $('#load-more').hide();
          }
          $('.loader').remove();
          $('.general-meeting').css('min-height', '0');
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
          action: 'general_meeting_more_post',
          paged: currentPage,
          catId: catId
        },
        success: function (res) {
          if (res.html === '') {
            button.hide();
          }
          $('.general-meeting').append(res.html);
        }
      });
    });
  }

  $(document).on('ready', function () {
    generalMeeting();
  });
})(jQuery);