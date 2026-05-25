(function($){

  $(window).on('elementor/frontend/init', () => {

    const imageTabs = () => {
      $('.zod-image-tabs__title').each( (idx,item) => {
        let _this = $(item);
        let dataCarousel = _this.data('carousel');
        let dataTabs = _this.data('tabs');

        $('.zod-image-tabs__wrapper', _this).slick({
          slidesToShow: dataCarousel.column,
          slidesToScroll: 1,
          infinite: false,
          dots: false,
          arrows: false,
          responsive: [
            {
              breakpoint: 768,
              settings: {
                slidesToShow: dataCarousel.columnTablet,
                slidesToScroll: 1,
                touchMove: false,
              }
            },
            {
              breakpoint: 480,
              settings: {
                slidesToShow: dataCarousel.columnMobile,
                slidesToScroll: 1,
              }
            }
          ]
        });
        
        UIkit.switcher($('.slick-track', _this), {
          connect: `.${String(dataTabs)}`,
          animation: 'uk-animation-fade',
          active: 0
        });
      });
    }

    elementorFrontend.hooks.addAction(
      'frontend/element_ready/zod-core-image-tabs.default',
      imageTabs
    );
  });

})(jQuery);
