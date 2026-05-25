(function($){
	$(window).on('elementor/frontend/init', () => {

		const animatedHeadline = async () => {
			//set animation timing
			var animationDelay = $('.zod-headline').data('animation-delay'),
				//loading bar effect
				barAnimationDelay = 3800,
				barWaiting = barAnimationDelay - 3000, //3000 is the duration of the transition on the loading bar - set in the scss/css file
				//letters effect
				lettersDelay = 50,
				//type effect
				typeLettersDelay = 150,
				selectionDuration = 500,
				typeAnimationDelay = selectionDuration + 800,
				//clip effect 
				revealDuration = 600,
				revealAnimationDelay = 1500;

			function initHeadline() {
				//insert <i> element for each letter of a changing word
				singleLetters($('.cd-headline.letters').find('b'));
				//initialise headline animation
				animateHeadline($('.zod-headline'));
			}

			function singleLetters($words) {
				$words.each(function(){
					var word = $(this),
						letters = word.text().split(''),
						selected = word.hasClass('is-visible');
					for (i in letters) {
						if(word.parents('.rotate-2').length > 0) letters[i] = '<em>' + letters[i] + '</em>';
						letters[i] = (selected) ? '<i class="in">' + letters[i] + '</i>': '<i>' + letters[i] + '</i>';
					}
					var newLetters = letters.join('');
					word.html(newLetters).css('opacity', 1);
				});
			}

			function animateHeadline($headlines) {
				var duration = animationDelay;
				$headlines.each(function(){
					var headline = $(this);
					
					if(headline.hasClass('loading-bar')) {
						duration = barAnimationDelay;
						setTimeout(function(){ headline.find('.zod-headline-dynamic-wrapper').addClass('is-loading') }, barWaiting);
					} else if (headline.hasClass('clip')){
						var spanWrapper = headline.find('.zod-headline-dynamic-wrapper'),
							newWidth = spanWrapper.width() + 10
						spanWrapper.css('width', newWidth);
					} else if (!headline.hasClass('type') ) {
						//assign to .zod-headline-dynamic-wrapper the width of its longest word
						var words = headline.find('.zod-headline-dynamic-text'),
							width = 0;
						words.each(function(){
							var wordWidth = $(this).width();
							if (wordWidth > width) width = wordWidth;
						});
						headline.find('.zod-headline-dynamic-wrapper').css('width', width);
					};

					//trigger animation
					setTimeout(function(){ hideWord( headline.find('.zod-headline-text-active').eq(0) ) }, duration);
				});
			}

			function hideWord($word) {
				var nextWord = takeNext($word);
				
				if($word.parents('.zod-headline').hasClass('type')) {
					var parentSpan = $word.parent('.zod-headline-dynamic-wrapper');
					parentSpan.addClass('selected').removeClass('waiting');	
					setTimeout(function(){ 
						parentSpan.removeClass('selected'); 
						$word.removeClass('is-visible').addClass('is-hidden').children('i').removeClass('in').addClass('out');
					}, selectionDuration);
					setTimeout(function(){ showWord(nextWord, typeLettersDelay) }, typeAnimationDelay);
				
				} else if($word.parents('.zod-headline').hasClass('letters')) {
					var bool = ($word.children('i').length >= nextWord.children('i').length) ? true : false;
					hideLetter($word.find('i').eq(0), $word, bool, lettersDelay);
					showLetter(nextWord.find('i').eq(0), nextWord, bool, lettersDelay);

				}  else if($word.parents('.zod-headline').hasClass('clip')) {
					$word.parents('.zod-headline-dynamic-wrapper').animate({ width : '2px' }, revealDuration, function(){
						switchWord($word, nextWord);
						showWord(nextWord);
					});

				} else if ($word.parents('.zod-headline').hasClass('loading-bar')){
					$word.parents('.zod-headline-dynamic-wrapper').removeClass('is-loading');
					switchWord($word, nextWord);
					setTimeout(function(){ hideWord(nextWord) }, barAnimationDelay);
					setTimeout(function(){ $word.parents('.zod-headline-dynamic-wrapper').addClass('is-loading') }, barWaiting);

				} else {
					switchWord($word, nextWord);
					setTimeout(function(){ hideWord(nextWord) }, animationDelay);
				}
			}

			function showWord($word, $duration) {
				if($word.parents('.zod-headline').hasClass('type')) {
					showLetter($word.find('i').eq(0), $word, false, $duration);
					$word.addClass('is-visible').removeClass('is-hidden');

				}  else if($word.parents('.zod-headline').hasClass('clip')) {
					$word.parents('.zod-headline-dynamic-wrapper').animate({ 'width' : $word.width() + 10 }, revealDuration, function(){ 
						setTimeout(function(){ hideWord($word) }, revealAnimationDelay); 
					});
				}
			}

			function hideLetter($letter, $word, $bool, $duration) {
				$letter.removeClass('in').addClass('out');
				
				if(!$letter.is(':last-child')) {
					setTimeout(function(){ hideLetter($letter.next(), $word, $bool, $duration); }, $duration);  
				} else if($bool) { 
					setTimeout(function(){ hideWord(takeNext($word)) }, animationDelay);
				}

				if($letter.is(':last-child') && $('html').hasClass('no-csstransitions')) {
					var nextWord = takeNext($word);
					switchWord($word, nextWord);
				} 
			}

			function showLetter($letter, $word, $bool, $duration) {
				$letter.addClass('in').removeClass('out');
				
				if(!$letter.is(':last-child')) { 
					setTimeout(function(){ showLetter($letter.next(), $word, $bool, $duration); }, $duration); 
				} else { 
					if($word.parents('.zod-headline').hasClass('type')) { setTimeout(function(){ $word.parents('.zod-headline-dynamic-wrapper').addClass('waiting'); }, 200);}
					if(!$bool) { setTimeout(function(){ hideWord($word) }, animationDelay) }
				}
			}

			function takeNext($word) {
				return (!$word.is(':last-child')) ? $word.next() : $word.parent().children().eq(0);
			}

			function takePrev($word) {
				return (!$word.is(':first-child')) ? $word.prev() : $word.parent().children().last();
			}

			function switchWord($oldWord, $newWord) {
				$oldWord.removeClass('zod-headline-text-active').addClass('zod-headline-text-inactive');
				$newWord.removeClass('zod-headline-text-inactive').addClass('zod-headline-text-active');
				setDynamicWrapperWidth( $newWord );
			}

			function setDynamicWrapperWidth($word) {
				$($word).parents('.zod-headline-dynamic-wrapper').css('width', Math.round( $word.width() ) );
			}

			initHeadline();
		}
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/zod-core-animated-headline.default',
			animatedHeadline
		);

	});
})(jQuery);