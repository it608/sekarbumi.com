(function ($) {
  "use strict"
  var currentPage = 1;

  const sustainsReports = async () => {

    $('#load-more').on('click', function (e) {
      e.preventDefault();
      let button = $(this);
      currentPage++; // Do currentPage + 1, because we want to load the next page

      $.ajax({
        type: 'POST',
        url: skbmPublicAjax.ajaxUrl,
        dataType: 'JSON',
        data: {
          action: 'sustains_report_more_post',
          paged: currentPage,
        },
        success: function (res) {
          if (res.html === '') {
            button.hide();
          }
          $('.sustains-report').append(res.html);
        }
      });
    });
  }

  $(document).ready(function () {
    sustainsReports();
  });
})(jQuery);