function YpmPopup() {

	this.ypmCount = 0;
	this.popupData = [];
	this.popupId = 0;
}

YpmPopup.prototype.setPopupData = function(popupData) {

	popupData = this.filterPopupData(popupData);
	this.popupData = popupData;
};

YpmPopup.prototype.getPopupData = function() {

	return this.popupData;
};

YpmPopup.prototype.setPopupId = function(popupId) {

	this.popupId = popupId;
};

YpmPopup.prototype.getPopupId = function() {

	return this.popupId;
};

YpmPopup.prototype.varToBool = function (optionName) {

	var returnValue = (optionName) ? true : false;
	return returnValue;
};

YpmPopup.prototype.init = function () {

	var that = this;
	jQuery(".ypm-open-popup").each(function () {
		var currentPopup = jQuery(this);
		var popupEvent = jQuery(this).attr("data-popup-event");
		if (typeof popupEvent == 'undefined') {
			popupEvent = 'click';
		}

		that.ypmCount = 0;
		var callBack = function () {

			that.ypmCount += 1;
			var status = that.ypmCount > 1;
			if (status) {
				return;
			}
			var popupId = currentPopup.attr("data-ypm-popup-id");
			var data = YPM_DATA[popupId];
			if(typeof data == 'undefined') {
				return false;
			}
			that.setPopupId(popupId);
			that.setPopupData(data);
			that.openPopup();
		};

		if (popupEvent == 'click') {
			jQuery(this).bind(popupEvent, function () {
				callBack();
			});
		}
		else if (popupEvent == 'hover') {
			jQuery(this).hover(function () {
				callBack();
			}, function () {
				
			});
		}
	});
};

YpmPopup.prototype.fomSubmition = function () {
	var that = this;

	document.addEventListener( 'wpcf7submit', function( event ) {

		var data = that.getPopupData();
		if (data['ypm-popup-special-events-settings'] && event.detail.status == 'mail_sent') {
			var specialEvents = data['ypm-popup-special-events-settings'];
			for (var i in specialEvents) {
				var current = specialEvents[i];
				if (current['key1'] === 'Cf7') {
					switch (current['key2']) {
						case "redirectToUrl":
							window.location.href = current['key3'];
							break;
						case "openAnotherPopup":
							YpmObserver.openPopupById(current['key3']);
							break;
						case "closePopup":
							setTimeout(function () {
								YpmPopup.closePopup();
							}, parseInt(current['key3'])*1000)
					}
				}
			}
		}


	}, false );

	jQuery(document).on('wpformsAjaxSubmitSuccess', '.wpforms-ajax-form', function (event, details) {
		var data = that.getPopupData();
		var specialEvents = data['ypm-popup-special-events-settings'];
		if (data['ypm-popup-special-events-settings']) {
			for (var i in specialEvents) {
				var current = specialEvents[i];
				if (current['key1'] === 'wpform') {
					switch (current['key2']) {
						case "wpformRedirectToUrl":
							window.location.href = current['key3'];
							break;
						case "wpformOpenAnotherPopup":
							YpmObserver.openPopupById(current['key3']);
							break;
						case "wpformClosePopup":
							setTimeout(function () {
								YpmPopup.closePopup();
							}, parseInt(current['key3'])*1000)
					}
				}
			}
		}
	})
}

YpmPopup.prototype.customEvents = function() {
	
	var data = this.getPopupData();

	jQuery('#ypmcolorbox').on('ypmOnOpen', function() {
		
	});
};

YpmPopup.prototype.openByPopupEvent = function() {

	var popupId = this.getPopupId();
	
	if(typeof YPM_DATA[popupId] == 'undefined') {
		return;
	}
	var data = YPM_DATA[popupId];
	this.setPopupData(data);

	if(typeof data['ypm-popup-exit-enable'] != 'undefined' && data['ypm-popup-exit-enable'] == 'on') {
		YpmPopup.prototype = new YpmExit();
	}
	else {
		this.onLoad();
	}
};

YpmPopup.prototype.openPopup = function (popupId) {

	this.setPopupId(popupId);

	this.openByPopupEvent();
};

YpmPopup.prototype.onLoad = function() {

	var data = this.getPopupData();
	var that = this;

	var isAllowToLoad = that.allowToLoad();
	if (!isAllowToLoad) return isAllowToLoad;

	setTimeout(function () {
		that.openPopup();

	}, data['ypm-delay']*1000);
};

YpmPopup.prototype.allowToLoad = function() {
	var popupHasLimit = this.isSatisfyForShowingLimitation();
	if (popupHasLimit === false) {
		return popupHasLimit;
	}
	var extensionsStatus = true;
	var data = this.getPopupData();

	if (data['extensionsClasses']) {
		for (var extensionIndex in data['extensionsClasses']) {
			var extension = data['extensionsClasses'][extensionIndex];
			var className = extension['name'];
			if (typeof className != undefined) {
				className = eval(className);
				var currentObj = new className();
				var currentStatus = currentObj[extension['method']](this);

				if (currentStatus === false) {
					extensionsStatus = false;
					break;
				}
			}
		}

		if (!extensionsStatus) {
			return false;
		}
	}

	var popupType = data['ypm-popup-type'];
	try {
		if (extensionsStatus) {
			var className = eval(popupType);
			var status = className.allowToLoad(this);

			if (status === false) {
				return  false;
			}
		}
	}
	catch (e) {

	}
	return true;
};

YpmPopup.prototype.isSatisfyForShowingLimitation = function()
{
	var data = this.getPopupData();
	var popupLimitation = data['ypm-popup-showing-limitation'];

	if (!popupLimitation) {
		return null;
	}

	var cookieData = this.getPopupShowLimitationCookie();

	/*when there is not*/
	if (!cookieData.cookie) {
		return true;
	}
	var limitationCount = parseInt(data['ypm-limitation-shwoing-count']);

	return  cookieData.cookie.openingCount < limitationCount;
};

YpmPopup.prototype.getPopupShowLimitationCookie = function()
{
	var savedCookie = this.getPopupShowLimitationCookieDetails();
	savedCookie = this.filterPopupLimitationCookie(savedCookie);

	return savedCookie;
};

YpmPopup.prototype.filterPopupLimitationCookie = function(cookie)
{
	var result = {};
	result.cookie = '';
	if (cookie.isPageLevel) {

		result.cookieName = cookie.pageLevelCookieName;
		if (cookie.pageLevelCookie) {
			result.cookie = jQuery.parseJSON(cookie.pageLevelCookie);
		}

		YpmPopup.deleteCookie(cookie.domainLevelCookieName);

		return result;
	}
	result.cookieName = cookie.domainLevelCookieName;
	if (cookie.domainLevelCookie) {
		result.cookie = jQuery.parseJSON(cookie.domainLevelCookie);
	}
	var currentUrl = window.location.href;

	YpmPopup.deleteCookie(cookie.pageLevelCookieName, currentUrl);

	return result;
};

YpmPopup.prototype.getPopupShowLimitationCookieDetails = function()
{
	var result = false;
	var data = this.getPopupData();
	var currentUrl = window.location.href;
	var currentPopupId = this.getPopupId();

	/*Cookie names*/
	var popupLimitationCookieHomePageLevelName = 'YPMShowingLimitationHomePage' + currentPopupId;
	var popupLimitationCookiePageLevelName = 'YPMShowingLimitationPage' + currentPopupId;
	var popupLimitationCookieDomainName = 'YPMShowingLimitationDomain' + currentPopupId;

	var pageLevelCookie = data['ypm-show-popup-same-user-page-level'] || false;

	/*check if current url is home page*/
	if (currentUrl === YpmParams.homePageUrl) {
		popupLimitationCookiePageLevelName = popupLimitationCookieHomePageLevelName;
	}
	var popupLimitationPageLevelCookie = YpmPopup.getCookie(popupLimitationCookiePageLevelName);
	var popupLimitationDomainCookie = YpmPopup.getCookie(popupLimitationCookieDomainName);

	result = {
		'pageLevelCookieName': popupLimitationCookiePageLevelName,
		'domainLevelCookieName': popupLimitationCookieDomainName,
		'pageLevelCookie': popupLimitationPageLevelCookie,
		'domainLevelCookie': popupLimitationDomainCookie,
		'isPageLevel': pageLevelCookie
	};

	return result;
};

YpmPopup.closePopup = function () {
	jQuery.ypmcolorbox.close();
}

YpmPopup.prototype.openPopup = function () {

	this.popupEvents();
	this.fomSubmition();
	var popupId = this.getPopupId();
	var data = this.getPopupData();

	var href = '#ypm-popup-content-wrapper-'+popupId;
	var photo = false;
	var inline = true;
	if (data['ypm-popup-type'] === 'ypmimage') {
		href = data['ypm-image-popup-url'];
		inline = false;
		photo = true;
	}

	var that = this;

	var title = data['title'];
	var showTitle = this.varToBool(data['ypm-popup-title']);
	if(!showTitle) {
		title = false;
	}
	var width = data['ypm-popup-width'];
	var height = data['ypm-popup-height'];
	var maxWidth = data['ypm-popup-max-width'] || '100%';
	var maxHeight = data['ypm-popup-max-height'] || '100%';
	var theme = data['ypm-popup-theme'];
	var ypmOverlayOpacity = data['ypm-overlay-opacity'];
	var escKey = this.varToBool(data['ypm-esc-key']);
	var closeButton = this.varToBool(data['ypm-close-button']);
	var overlayClick = this.varToBool(data['ypm-overlay-click']);
	var closeText = data['ypm-popup-theme-close-text'];
	var content = data['content'];
	var initialWidth = 300;
	var initialHeight = 100;
	var positionsObj = this.getPositionData(data);

	var ypmConfig = {
		popupId: popupId,
		closeDelay: data['ypm-close-delay'],
		title: title,
		html: false,
		inline: inline,
		href: href,
		photo: photo,
		closeButton: closeButton,
		overlayClose: overlayClick,
		opacity: ypmOverlayOpacity,
		className: 'ypm'+theme,
		escKey: escKey,
		fixed: positionsObj.fixed,
		top: positionsObj.top,
		bottom: positionsObj.bottom,
		left: positionsObj.left,
		right: positionsObj.right,
		close: closeText,
		overlayId: '#ypmcboxOverlay',
		disableOverlay: this.varToBool(data['ypm-disable-overlay']),
		popupOptions: data,
		onOpen: function() {
			jQuery(ypmConfig.overlayId).addClass("ypmcboxOverlayBg");
			jQuery(ypmConfig.overlayId).addClass(data['ypm-overlay-custom-class']);
			jQuery('#ypmcolorbox').addClass(data['ypm-content-custom-class']);

			if(data['ypm-overlay-color']) {
				jQuery('.ypmcboxOverlayBg').css({
					'background': 'none',
					'background-color':
					data['ypm-overlay-color']
				})
			}

			if (data['ypm-enable-bg-image']) {
				jQuery("#ypmcboxContent").css({
					'background-image': 'url(' + data['ypm-background-image'] + ')',
					'background-size': data['ypm-background-image-mode'],
					'background-repeat': 'no-repeat',
					'background-attachment': 'fixed',
					'background-position': 'center'
				})
			}

			jQuery('#ypm-popup-content-wrapper-'+popupId).css({padding: data['ypm-content-padding']})
			jQuery('.ypm-popup-content-'+popupId+' #ypmcboxContent, .ypm-popup-content-'+popupId+' #ypmcboxLoadedContent').css({'border-radius': data['ypm-content-border-radius']})
			if(data['ypm-content-click-status']) {
				var clickCount = 0;
				jQuery('#ypmcboxContent').bind('click', function () {
					clickCount += 1;
					if (clickCount == data['ypm-content-click-count']) {
						jQuery.ypmcolorbox.close();
						if (data['ypm-content-click-redirect-enable']) {
							var url = data['ypm-content-click-redirect-url'];
							if (data['ypm-content-click-redirect-tab']) {
								window.open(url);
							}
							else {
								window.location.href = url;
							}
						}
					}
				});
			}
			var popupEffectDuration = data['ypm-popup-opening-animation-speed'];
			jQuery('.ypm-popup-content-'+popupId).addClass('ypm-animated '+data['ypm-popup-opening-animation'])
			jQuery('.ypm-popup-content-'+popupId).css('animation-duration', popupEffectDuration + "s");
			jQuery('.ypm-popup-content-'+popupId).css('-webkit-animation-duration', popupEffectDuration + "s");
			jQuery(ypmConfig.overlayId).css('zIndex', data['ypm-z-index']);
			jQuery('#ypmcolorbox').trigger('ypmOnOpen', {popupId: popupId, data: data});
		},
		onCleanup: function () {
			that.ypmCount = 0;
			jQuery('#ypmcolorbox').trigger("ypmPopupCleanup", {popupId: popupId, data: data});
		},
		onComplete: function () {
			jQuery("#ypmcboxClose").bind('click', function() {

				if (data['ypm-close-button-click-sound']) {
					const audio = new Audio(YpmParams.soundsURL+"/click.mp3");
					audio.play();
				}
			});
			if (data['ypm-enable-close-delay'] && data['ypm-close-button']) {
				var time = data['ypm-close-button-delay'];
				if (data['ypm-show-close-delay']) {
					var styles = window.getComputedStyle(document.getElementById("ypmcboxClose"));

					jQuery("#ypmcboxClose").after("<span id='ypm-close-text'>"+time+"</span>");
					jQuery("#ypm-close-text").css({
						position: styles.position,
						top: styles.top,
						left: styles.left,
						right: styles.right,
						bottom: styles.bottom,
						color: data['ypm-close-delay-color'],
						fontSize: data['ypm-close-delay-font-size'],
					})
				}

				var intervalId = setInterval(function () {
					time -= 1;
					jQuery("#ypm-close-text").text(time);
					if (time <= 0) {
						clearInterval(intervalId);
						jQuery("#ypm-close-text").remove();
						jQuery("#ypmcboxClose").removeClass('ypm-hide');
					}
				}, 1000);
			}
			jQuery('.ypm-popup-content-'+popupId).removeClass('ypm-animated '+data['ypm-popup-opening-animation'])
			jQuery('.ypm-popup-content-'+popupId+' #ypmcboxContent, .ypm-popup-content-'+popupId+' #ypmcboxLoadedContent').css({'border-radius': data['ypm-content-border-radius']})
			jQuery(window).trigger('ypmOnComplete', {popupId: popupId, data: data});
			jQuery('#ypmcolorbox').trigger('ypmOnComplete', {popupId: popupId, data: data});
		},
		onClosed: function () {
			jQuery('#ypmcolorbox').trigger("ypmPopupClose", {popupId: popupId, data: data});
			switch (data['ypm-popup-close-behavior']) {
				case 'redirect': {
					var URL = data['ypm-popup-close-redirection-url'];
					if (data['ypm-popup-close-redirection-url-tab']) {
						window.open(URL)
					}
					else {
						window.location.href = URL;
					}
					break;
				}
				case 'openPopup':
					YpmPopup.openPopupById(data['ypm-popup-close-popup']);
					break;
			}
		},
		onClosing: function () {
			var popupCloseEffectDuration = data['ypm-popup-close-animation-speed'];
			jQuery('.ypm-popup-content-'+popupId).addClass('ypm-animated '+data['ypm-popup-close-animation'])
			jQuery('.ypm-popup-content-'+popupId).css('animation-duration', popupCloseEffectDuration + "s");
			jQuery('.ypm-popup-content-'+popupId).css('-webkit-animation-duration', popupCloseEffectDuration + "s");
		}
	};

	if(width) {
		ypmConfig.width = width;
	}
	if(height) {
		ypmConfig.height = height;
	}
	if(maxWidth) {
		ypmConfig.maxWidth = maxWidth;
	}
	if(maxHeight) {
		ypmConfig.maxHeight = maxHeight;
	}
	if(initialWidth) {
		ypmConfig.initialWidth = initialWidth;
	}
	if(initialHeight) {
		ypmConfig.initialHeight = initialHeight;
	}

	if (data['ypm-popup-type'] === 'ypmlink') {
		this.dynamicLink(ypmConfig);
	}
	else {
		jQuery.ypmcolorbox(ypmConfig);
	}

	this.customEvents();
};

YpmPopup.prototype.popupEvents = function ()
{
	var that = this;
	var colorBox = jQuery('#ypmcolorbox');
	colorBox.bind('ypmOnOpen', function (e, args) {

	});

	colorBox.bind("ypmPopupCleanup", function (e, args) {

	});

	colorBox.bind("ypmOnComplete", function (e, args) {

		var data = args.data;
		var popupId = args.popupId;
		var titleColor = data['ypm-title-color'];
		var titleFontSize = data['ypm-popup-title-font-size'];
		var disablePageScrolling = data['ypm-disable-page-scrolling'];

		if(disablePageScrolling) {
			jQuery('body').addClass('ypm-disable-page-scrolling');
		}
		jQuery('.ypm-popup-content-'+popupId+' #ypmcboxTitle').css({color: titleColor});
		if (titleFontSize) {
			jQuery('.ypm-popup-content-'+popupId+' #ypmcboxTitle').css({fontSize: titleFontSize});
		}

		/*Popup open count*/
		that.openCount(args);
		that.setPopupLimitation(args);
	});

	colorBox.bind("ypmPopupClose", function (e, args) {

		var data = args.data;
		var disablePageScrolling = data['ypm-disable-page-scrolling'];

		if(disablePageScrolling) {
			jQuery('body').removeClass('ypm-disable-page-scrolling');
		}
	});
};

YpmPopup.prototype.openCount = function (args)
{
	if (args.data['ypm-popup-disable-statistic']) {
		return;
	}
	var popupId = args.popupId;
	var data = {
		action: 'ypm_increment_popup_count',
		ajaxNonce: YpmParams.ajaxNonce,
		popupId: popupId
	};
	jQuery.post(YpmParams.ajaxurl, data, function(response) {
	});
};

YpmPopup.prototype.setPopupLimitation = function (args)
{
	var data = this.getPopupData();
	var cookieData = this.getPopupShowLimitationCookie();
	var cookie = cookieData.cookie || {};
	var openingCount = cookie.openingCount || 0;
	var currentUrl = window.location.href;

	if (!data['ypm-limitation-shwoing-expiration']) {
		currentUrl = '';
	}
	cookie.openingCount = openingCount + 1;
	cookie.openingPage = currentUrl;
	var popupShowingLimitExpiry = parseInt(data['ypm-limitation-shwoing-expiration']);

	YpmPopup.setCookie(cookieData.cookieName, JSON.stringify(cookie), popupShowingLimitExpiry, currentUrl);
};

YpmPopup.prototype.filterPopupData = function (popupData)
{
	if (popupData['ypm-popup-dimensions-mode'] == 'auto') {
		var width = '';
		if (popupData['ypm-popup-dimensions-auto-size'] != 'auto') {
			width = popupData['ypm-popup-dimensions-auto-size']+'%';
		}
		popupData['ypm-popup-width'] = width;
		popupData['ypm-popup-height'] = '';
	}
	return popupData;
};

YpmPopup.prototype.getPositionData = function (popupData) {
	var positions = {
		fixed: false,
		top: false,
		right: false,
		bottom: false,
		left: false,
	};

	if (!popupData['ypm-popup-location']) {
		return positions;
	}
	var position = popupData['ypm-popup-fixed-position'];

	/*The center Position*/
	positions.fixed = true;
	switch (position) {
		case "1":
			positions.left = "0px";
			positions.top = "0px";
			break;
		case "2":
			positions.top = "0px";
			break;
		case "3":
			positions.top = "0px";
			positions.right = "0px";
			break;
		case "4":
			positions.left = "0px";
			break;
		case "6":
			positions.right = "0px";
			break;
		case "7":
			positions.bottom = "0px";
			positions.left = "0px";
			break;
		case "8":
			positions.bottom = "0px";
			break;
		case "9":
			positions.bottom = "0px";
			positions.right = "0px";
			break;
	}

	return positions;
};

YpmPopup.prototype.Onload = function (eventData) {
	var that = this;
	setTimeout(function () {
        that.onLoad();
    }, parseInt(eventData['key2'])*1000)
};

YpmPopup.prototype.OnClick = function (eventData) {
	var that = this;
	jQuery('.'+eventData['key3']).bind('click', function () {
		that.onLoad();
	});
}

/* Cookie helper start */

YpmPopup.getCookie = function (cName) {

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

YpmPopup.deleteCookie = function (name) {

	document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
};

YpmPopup.setCookie = function (cName, cValue, exDays, cPageLevel) {

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
		expires = '';etCookie
	}

	if (cPageLevel) {
		cookiePageLevel = 'path=/;';
	}

	var value = cValue + ((exDays == null) ? ";" : "; " + expires + ";" + cookiePageLevel);
	document.cookie = cName + "=" + value;
};
/* Cookie helper end */

YpmPopup.getDataById = function (popupId) {
	var data = YPM_DATA[popupId];
	if (!data) {return  false}

	return data;
}

YpmPopup.openPopupById = function (popupId) {
	var data = YpmPopup.getDataById(popupId);

	if (!data) return false;

	var currentPopup = new YpmPopup();
	currentPopup.setPopupId(popupId);
	currentPopup.setPopupData(data);

	currentPopup.openPopup(popupId);
}

jQuery(document).ready(function ($) {
	var ypmObj = new YpmPopup();
	ypmObj.init();
});