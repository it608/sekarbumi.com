(function($){
    $(window).on('elementor/frontend/init', () => {
      const zodCarouselTabs = async () => {
        let carouselWrapper = $('.zod-project');

        if( carouselWrapper.length ){
          carouselWrapper.each( (i, item) => {
            $('.zod-project-carousel:not(.slick-initialized)', $(item)).slick({
              autoplay: true,
              fade: true,
              arrows: false,
              slidesPerRow: 1,
              slidesToShow: 1,
              slidesToScroll: 1,
              swipe: true,
              asNavFor: $('.zod-project-caption', $(item)),
            });
            $('.zod-project-caption:not(.slick-initialized)', $(item)).slick({
              autoplay: true,
              fade: true,
              arrows: false,
              slidesPerRow: 1,
              slidesToShow: 1,
              slidesToScroll: 1,
              swipe: true,
              asNavFor: $('.zod-project-carousel', $(item)),
            });
          });
        }

        $('.zod-tabs').on('beforeshow', function(){
          $('.zod-project-carousel').slick('refresh');
          $('.zod-project-caption').slick('refresh');
        });
      }
      elementorFrontend.hooks.addAction(
        'frontend/element_ready/zod-tabs.default',
        zodCarouselTabs
      );
    });
})(jQuery);