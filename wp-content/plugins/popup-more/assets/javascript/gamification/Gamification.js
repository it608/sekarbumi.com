function ypmgamification() {
	this.extraOpions = {};
}

ypmgamification.prototype.init = function()
{
	this.animationAfterOpenPopup();
};

ypmgamification.expTime = 365;
ypmgamification.popupHeight = 0;

ypmgamification.prototype.animationAfterOpenPopup = function() {
	var that = this;
	var init = function (args) {
		var popupId = that.popupId = args.popupId;
		setTimeout(function () {
			that.validateForms(popupId);
		}, 0)

		ypmgamification.popupHeight = jQuery('.ypm-content-'+popupId).height();
		var gifts = jQuery('.ypm-gifts .ypm-gift');
		gifts.hide();
		var i = 0;
		gifts.each(function () {
			var current = jQuery(this);
			setTimeout(function () {
				current.show();
				current.addClass('ypm-animated ypm-bounceInUp');
			}, i*100);
			++i;
		});

		setTimeout(function(){
			window.dispatchEvent(new Event('resize'));
		}, 600);
	}
	jQuery('#ypmcolorbox').bind('ypmOnComplete', function (e, args) {
		init(args)
	});

	init({})
};

ypmgamification.prototype.validateForms = function(popupId)
{
	var forms = jQuery('.ycf-gamification-form');

	if (!forms.length) {
		return false;
	}
	var that = this;



	forms.each(function () {
		var form = jQuery(this);
		var validateObj = form.data('validate');
		var popupId = form.data('id');

		var message = jQuery(this).data('required-message');
		var emailMessage = jQuery(this).data('email-message');

		that.shakeForm(jQuery(this));
		// validateObj['ypm-subs-email'] = {
		// 	required: message,
		// 	email: emailMessage
		// };
		validateObj.submitHandler = function(e) {
			that.submitForm(form, popupId);
		};
		form.validate(validateObj);
	});
};

ypmgamification.prototype.getRandomPercentage = function () {
	return Math.random()*100;
};

ypmgamification.prototype.playGame = function(popupId)
{
	var randomPercentage = this.getRandomPercentage();
	jQuery('#ypm-popup-content-wrapper-'+popupId+' .ypm-gifts').addClass('ypm-bigger');
	var that = this;
	var i = 0;
	var gifts = jQuery('.ypm-gifts-'+popupId+' .ypm-gift');
	gifts.removeClass('ypm-bounceInUp');

	gifts.each(function () {
		var current = jQuery(this);
		setTimeout(function () {
			current.show();
			current.addClass('ypm-tada');
		}, i*100);
		++i;
	});

	jQuery('.ypm-gifts-'+popupId).animate({
		marginTop: '-15%'
	}, 1000);
	jQuery('.ypm-gift').bind('click', function() {
		if (jQuery(this).hasClass('ypm-animate-double')) {
			return false;
		}
		if (randomPercentage <= that.extraOpions['ypm-gamification-win-chance']) {
			var giftsWrapper = jQuery('.ypm-gifts-'+popupId);
			var selectedGift = jQuery(this);
			jQuery('.ypm-gamification-play-text').animate(1000, function () {
				// jQuery('.ypm-gamification-play-text').hide();
				// jQuery("#ycf-gamification-form").hide()
				jQuery(this).css('visibility', 'hidden');
				var notSelectedGifts = jQuery('.ypm-gifts-'+popupId+' .ypm-gift').not(selectedGift);

				jQuery('.ypm-gifts').css({'margin-top': 0});

				/*where 20 is static margin from top*/
				var top = giftsWrapper.position().top+parseInt(giftsWrapper.css('margin-top'))-selectedGift.height()/2 - 200;
				selectedGift.addClass('ypm-animate-double');

				var wrapper = jQuery('.ypm-gamification-content-wrapper').width();
				/*Half width of gifts wrapper*/
				var wrapperWidth = wrapper/2;

				/*Initial position center of the current scaled gift*/
				var positionCenter = selectedGift.position().left+parseInt(selectedGift.css('margin-left'))+selectedGift.width()/2;

				notSelectedGifts.animate({ opacity: 0 }, 0);
				giftsWrapper.removeClass('ypm-bigger');

				selectedGift.animate({
					'left': (wrapperWidth - positionCenter),
					'top': 0
				}, 1000, function () {
					setTimeout(function() {
						selectedGift.parent().after(jQuery('.ypm-gamification-win-text'));
						selectedGift.parent().next('.ypm-gamification-win-text').fadeIn(500);
					}, 500);
				});
				jQuery("#ycf-gamification-form").css({'display': 'none'});
				jQuery('.ypm-gamification-start-header').css({'padding-top': '50px;'});
				jQuery(this).css({'position': 'relative'}).css({'display': 'none'});
			});
		}
		else {
			/*Lose*/
			jQuery('.ypm-gamification-play-text').fadeOut(1000);
			jQuery('.ypm-gifts').fadeOut(1000, function () {
				jQuery('.ypm-gamification-lose-text').fadeIn(800);
			});
		}
	});
};

ypmgamification.prototype.shakeForm = function(form)
{
	jQuery('.ypm-gift').bind('click', function () {
		jQuery(form).removeClass('ypm-animated ypm-shake');
		setTimeout(function () {
			jQuery(form).addClass('ypm-animated ypm-shake');
		}, 0)
	});
};

ypmgamification.allowToLoad = function(popup)
{
	var cookieObject = ypmgamification.getCookie('ypmgamification' + popup.popupId);

	if (cookieObject) {
		return false;
	}

	return true;
};

ypmgamification.prototype.submitForm = function(form, popupId)
{
	var that = this;
	jQuery('.ypm-content-'+popupId).css('height', ypmgamification.popupHeight+'px');

	var formData = form.serialize();
	var submitButton = jQuery(form).find('.ycf-submit input');
	var ajaxData = {
		action: 'ypm_subscribed',
		nonce: YPM_GAMIFICATION_PARAMS.nonce,
		beforeSend: function () {
			submitButton.val(submitButton.attr('data-progress-title'));
			submitButton.prop('disabled', true);
		},
		formData: formData,
		popupPostId: popupId
	};
	var cookieName = 'ypmgamification' + that.popupId;
	var popupData = jQuery(form).data('expiration-options');
	that.extraOpions = popupData;

	var alreadySubscribed = popupData['ypm-gamification-already-subscribed'];
	jQuery.post(YPM_GAMIFICATION_PARAMS.ajaxUrl, ajaxData, function (res) {
		if (jQuery('.ypm-hide-form').length) {
			jQuery('.ypm-gamification-win-text .ypm-gamification-start-header').css('margin-top', '43px');
		}
		submitButton.prop('disabled', false);
		jQuery(form).animate({ opacity: 0 },1000);

		jQuery('.ypm-gamification-gdpr-text').animate({ opacity: 0 },1000);
		jQuery('.ypm-gamification-start-text').fadeOut(1000, function () {
			jQuery('.ypm-gamification-play-text').fadeIn(1000);
		});

		jQuery(".ypm-gifts-content-wrapper").nextAll('.ypm-gifts').first().addClass('ypm-bigger');
		jQuery('#ypm-popup-content-wrapper-'+popupId).addClass('ypm-overflow-hidden');

		if (typeof alreadySubscribed != 'undefined' && alreadySubscribed) {
			ypmgamification.setCookie(cookieName, 1, ypmgamification.expTime);
		}

		that.playGame(popupId);
	});
};


ypmgamification.getCookie = function (cName) {

	var name = cName + "=";
	var ca = document.cookie.split(';');
	for (var i = 0; i < ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) == ' ') {
			c = c.substring(1);
		}
		if (c.indexOf(name) == 0) {
			return c.substring(name.length, c.length);
		}
	}
	return "";
};

ypmgamification.deleteCookie = function (name) {

	document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
};

ypmgamification.setCookie = function (cName, cValue, exDays, cPageLevel) {

	var expirationDate = new Date();
	var cookiePageLevel = '';
	var cookieExpirationData = 1;
	if (!exDays || isNaN(exDays)) {
		exDays = 365 * 50;
	}
	if (typeof cPageLevel == 'undefined') {
		cPageLevel = false;
	}
	expirationDate.setDate(expirationDate.getDate() + exDays);
	cookieExpirationData = expirationDate.toUTCString();
	var expires = 'expires='+cookieExpirationData;

	if (exDays == -1) {
		expires = '';
	}

	if (cPageLevel) {
		cookiePageLevel = 'path=/;';
	}

	var value = cValue + ((exDays == null) ? ";" : "; " + expires + ";" + cookiePageLevel);
	document.cookie = cName + "=" + value;
};

jQuery(document).ready(function() {
	var obj = new  ypmgamification();
	obj.init();
});
