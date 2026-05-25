(function($){
	$(window).on('elementor/frontend/init', () => {

		const zodCarousel = async () => {
      var slider = $('.zod-carousel');
      slider.each( function(idx, item){
        $('.zod-carousel__inner', $(this)).on('init reInit afterChange', function(event, slick, currentSlide) {
          var cur = $(slick.$slides[slick.currentSlide]),
            next = cur.next(),
            next2 = cur.next().next(),
            prev = cur.prev(),
            prev2 = cur.prev().prev();
            prev.addClass('slick-btprev');
            next.addClass('slick-btnext');  
            prev2.addClass('slick-btprev2');
            next2.addClass('slick-btnext2');  
            cur.removeClass('slick-btnext').removeClass('slick-btprev').removeClass('slick-btnext2').removeClass('slick-btprev2');
            slick.$prev = prev;
            slick.$next = next;

            $('.slick-dots', $(this)).css('grid-template-columns', 'repeat('+slick.$dots[0].children.length+', 1fr)' );
        }).on('beforeChange', function(event, slick, currentSlide, nextSlide) {
    
        var cur = $(slick.$slides[nextSlide]);
            slick.$prev.removeClass('slick-btprev');
            slick.$next.removeClass('slick-btnext');
            slick.$prev.prev().removeClass('slick-btprev2');
            slick.$next.next().removeClass('slick-btnext2');
            next = cur.next(),  
            prev = cur.prev();
            prev.addClass('slick-btprev');
            next.addClass('slick-btnext');
            prev.prev().addClass('slick-btprev2');
            next.next().addClass('slick-btnext2');
            slick.$prev = prev;
            slick.$next = next;
            cur.removeClass('slick-next').removeClass('slick-btprev').removeClass('slick-next2').removeClass('slick-btprev2');
        });
        $('.zod-carousel__inner', $(this)).slick({
          speed: 1000,
          arrows: true,
          dots: true,
          focusOnSelect: true,
          prevArrow: $('.zod-carousel__nav--left', $(this)),
          nextArrow: $('.zod-carousel__nav--right', $(this)),
          infinite: true,
          centerMode: true,
          slidesPerRow: 1,
          slidesToShow: 1,
          slidesToScroll: 1,
          centerPadding: '0',
          swipe: true,
          asNavFor: $('.zod-carousel__caption', $(this)),
          infinite: true,
        });
        $('.zod-carousel__caption', $(this)).slick({
          slidesToShow: 1,
          slidesToScroll: 1,
          arrows: false,
          dots: false,
          asNavFor: $('.zod-carousel__inner', $(this)),
          swipe: true,
          infinite: true,
        });
      });
      
		}
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/zod-carousel.default',
			zodCarousel
		);
	});
})(jQuery);