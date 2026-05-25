(function ($) {
  "use strict"
  $(document).on('ready', () => {
    $('.skbm-cpt-tax-switcher__inner').slick({
      infinite: false,
      slidesToShow: 10,
      arrows: true,
      prevArrow: $('.cpt-prev'),
      nextArrow: $('.cpt-next'),
      responsive: [
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 1,
            touchMove: false,
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 4,
          }
        }
      ]
    });
  });
})(jQuery);