(function($){
  "use strict"
  var currentPage = 1;

  const annualReports = async () => {

    $('#load-more').on('click', function(e) {
      e.preventDefault();
      let button = $(this);
      currentPage++; // Do currentPage + 1, because we want to load the next page

      $.ajax({
        type: 'POST',
        url: skbmPublicAjax.ajaxUrl,
        dataType: 'JSON',
        data: {
          action: 'annual_report_more_post',
          paged: currentPage,
        },
        success: function (res) {
          if( res.html === ''){
            button.hide();
          }
          $('.annual-report').append(res.html);
        }
      });
    });
  }

  $(document).ready( function(){
    annualReports();
  });
})(jQuery);