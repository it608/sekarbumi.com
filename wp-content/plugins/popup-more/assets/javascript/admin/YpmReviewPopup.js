function YpmPopup()
{
	this.ypmCount = 0;
	this.popupData = [];
	this.popupId = 0;
}

YpmPopup.prototype.setPopupData = function(popupData)
{
	this.popupData = popupData;
};

YpmPopup.prototype.getPopupData = function()
{
	return this.popupData;
};

YpmPopup.prototype.setPopupId = function(popupId)
{
	this.popupId = popupId;
};

YpmPopup.prototype.getPopupId = function()
{
	return this.popupId;
};

YpmPopup.prototype.varToBool = function(optionName)
{
	var returnValue = (optionName) ? true : false;
	return returnValue;
};

YpmPopup.prototype.init = function()
{
	var that = this;
	jQuery(".ypm-review-block").each(function() {
		var currentPopup = jQuery(this);
		var popupEvent = 'onload';
		if (typeof popupEvent == 'undefined') {
			popupEvent = 'click';
		}

		that.ypmCount = 0;
		var callBack = function() {

			that.ypmCount += 1;
			var status = that.ypmCount > 1;
			if (status) {
				return;
			}
			var popupId = currentPopup.attr("data-ypm-popup-id");
			var data = [];
			if(typeof data == 'undefined') {
				return false;
			}
			that.setPopupId(popupId);
			that.setPopupData(data);
			that.openPopup();
		};
		callBack();

	});
};

YpmPopup.prototype.customEvents = function()
{
	var data = this.getPopupData();

	jQuery('#ypmcolorbox').on('ypmOnOpen', function() {

	});
};

YpmPopup.prototype.openByPopupEvent = function()
{
	var popupId = this.getPopupId();

	var data = [];
	this.setPopupData(data);

	if(typeof data['ypm-popup-exit-enable'] != 'undefined' && data['ypm-popup-exit-enable'] == 'on') {
		YpmPopup.prototype = new YpmExit();
	}
	else {
		this.onLoad();
	}
};

YpmPopup.prototype.openPopup = function(popupId)
{
	this.setPopupId(popupId);
	this.openByPopupEvent();
};

YpmPopup.prototype.onLoad = function()
{
	var popupId = this.getPopupId();
	var data = this.getPopupData();
	var that = this;

	setTimeout(function() {
		that.openPopup();

	}, data['ypm-delay']*1000);
};

YpmPopup.prototype.openPopup = function()
{
	this.popupEvents();
	var popupId = this.getPopupId();
	var data = this.getPopupData();
	var data = {
		'ypm-popup-width': '',
		'ypm-popup-height': '',
		'ypm-popup-max-width': '100%',
		'ypm-popup-max-height': '',
		'ypm-popup-theme': 'colorbox1',
		'ypm-overlay-opacity': '0.8',
		'ypm-esc-key': true,
		'ypm-close-button': true,
		'ypm-overlay-click': true,
	};

	var href = '#ypm-welcome-panel-wrapper';
	var that = this;

	var title = data['title'];
	var showTitle = this.varToBool(data['ypm-popup-title']);
	if(!showTitle) {
		title = false;
	}
	var width = data['ypm-popup-width'];
	var height = data['ypm-popup-height'];
	var maxWidth = data['ypm-popup-max-width'];
	var maxHeight = data['ypm-popup-max-height'];
	var theme = data['ypm-popup-theme'];
	var ypmOverlayOpacity = data['ypm-overlay-opacity'];
	var escKey = this.varToBool(data['ypm-esc-key']);
	var closeButton = this.varToBool(data['ypm-close-button']);
	var overlayClick = this.varToBool(data['ypm-overlay-click']);
	var content = data['content'];
	var initialWidth = 300;
	var initialHeight = 100;

	var ypmConfig = {
		popupId: popupId,
		title: title,
		html: false,
		inline: true,
		href: href,
		escKey: true,
		closeButton: true,
		overlayClose: true,
		closeButton: closeButton,
		overlayClose: overlayClick,
		opacity: ypmOverlayOpacity,
		className: 'ypm'+theme,
		escKey: escKey,
		onOpen: function() {
			jQuery("#ypmcboxOverlay").addClass("ypmcboxOverlayBg");
			if(data['ypm-overlay-color']) {
				jQuery('.ypmcboxOverlayBg').css({'background': 'none', 'background-color': data['ypm-overlay-color']})
			}
			if(data['ypm-content-click-status']) {

				jQuery('#ypmcboxContent').bind('click', function() {
					jQuery.ypmcolorbox.close();
				});
			}
			jQuery('#ypmcolorbox').trigger('ypmOnOpen', {popupId: popupId, data: data});
		},
		onCleanup: function() {
			that.ypmCount = 0;
			jQuery('#ypmcolorbox').trigger("ypmPopupCleanup", {popupId: popupId, data: data});
		},
		onComplete: function() {
			jQuery('#ypmcolorbox').trigger('ypmOnComplete', {popupId: popupId, data: data});
		},
		onClosed: function() {
			jQuery('#ypmcolorbox').trigger("ypmPopupClose", {popupId: popupId, data: data});
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
	if(initialWidth) {
		ypmConfig.initialWidth = initialWidth;
	}
	if(initialHeight) {
		ypmConfig.initialHeight = initialHeight;
	}

	jQuery.ypmcolorbox(ypmConfig);

	this.customEvents();
};

YpmPopup.prototype.popupEvents = function()
{
	jQuery('#ypmcolorbox').bind('ypmOnOpen', function(e, args) {
		console.log(args);
	});

	jQuery('#ypmcolorbox').bind("ypmPopupCleanup", function(e, args) {

	});

	jQuery('#ypmcolorbox').bind("ypmOnComplete", function(e, args) {

		var data = args.data;
		var popupId = args.popupId;
		var titleColor = data['ypm-title-color'];
		var disablePageScrolling = data['ypm-disable-page-scrolling'];

		if(disablePageScrolling) {
			jQuery('body').addClass('ypm-disable-page-scrolling');
		}
		jQuery('.ypm-popup-content-'+popupId+' #ypmcboxTitle').css({color: titleColor});
	});

	jQuery('#ypmcolorbox').bind("ypmPopupClose", function(e, args) {

		var data = args.data;
		var disablePageScrolling = data['ypm-disable-page-scrolling'];

		if(disablePageScrolling) {
			jQuery('body').removeClass('ypm-disable-page-scrolling');
		}
	});
};

YpmPopup.getCookie = function(cName)
{
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

YpmPopup.deleteCookie = function(name) {

	document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
};

YpmPopup.setCookie = function(cName, cValue, exDays, cPageLevel)
{
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

jQuery(document).ready(function($)
{
	var ypmObj = new YpmPopup();
	ypmObj.init();
});