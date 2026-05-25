function YPMGamificationBackend()
{

}

YPMGamificationBackend.tabCookieName = 'YPMGamificationActiveTab';

YPMGamificationBackend.prototype.init = function()
{
	this.buttonImageUpload();
	this.buttonImageRemove();
	this.tabsLinks();
	this.changeGiftImage();
	this.colors();
	jQuery('.ypm-pro-span').bind('click', function () {window.open('https:popup-more.com/')})
};

YPMGamificationBackend.prototype.colors = function () {
	if (!jQuery('.ypm-color-picker').length) {
		return ;
	}
	jQuery('.ypm-color-picker').minicolors({
		format: 'rgb',
		opacity: 1,
		change: function () {
		}
	});
};

YPMGamificationBackend.prototype.buttonImageUpload = function()
{
	var supportedImageTypes = ['image/bmp', 'image/png', 'image/jpeg', 'image/jpg', 'image/ico', 'image/gif'];
	var custom_uploader;
	jQuery('#js-gamification-upload-image-button').click(function(e) {
		e.preventDefault();

		/* If the uploader object has already been created, reopen the dialog */
		if (custom_uploader) {
			custom_uploader.open();
			return;
		}
		/* Extend the wp.media object */
		custom_uploader = wp.media.frames.file_frame = wp.media({
			titleFF: YPM_GAMIFICATION_ADMIN_PARAMS.chooseImage,
			button: {
				text: YPM_GAMIFICATION_ADMIN_PARAMS.chooseImage
			},
			multiple: false,
			library: {
				type: 'image'
			}
		});
		/* When a file is selected, grab the URL and set it as the text field's value */
		custom_uploader.on('select', function() {
			var attachment = custom_uploader.state().get('selection').first().toJSON();
			if (supportedImageTypes.indexOf(attachment.mime) === -1) {
				alert(YPM_JS_LOCALIZATION.imageSupportAlertMessage);
				return false;
			}
			jQuery('.ypm-show-gamification-image-container').css({'background-image': 'url("' + attachment.url + '")'});
			jQuery('#ypm-gamification-gift-image').val(attachment.url);
			jQuery('.js-ypm-remove-gamification-image').removeClass('ypm-hide-remove-button');
			jQuery('.ypm-gift-icon').removeClass('ypm-active-gift');
		});
		/* Open the uploader dialog */
		custom_uploader.open();
	});

	/* its finish image uploader */
};

YPMGamificationBackend.prototype.buttonImageRemove = function()
{
	jQuery('#js-gamification-upload-image-remove-button').click(function() {
		var defaultImageURL = jQuery(this).data('default-image-url');

		jQuery('#ypm-gamification-gift-image').val(defaultImageURL);
		jQuery(".ypm-show-gamification-image-container").attr('style', 'background-image: url("' +defaultImageURL+ '")');
		jQuery('.js-ypm-remove-gamification-image').addClass('ypm-hide-remove-button');
		jQuery('.ypm-gift-icon').removeClass('ypm-active-gift');
		jQuery('.ypm-gift-icon-1').addClass('ypm-active-gift');
	});
};


YPMGamificationBackend.prototype.tabsLinks = function()
{
	var tabs = jQuery('.ypm-options-tab-links');

	if (!tabs) {
		return false;
	}
	var that = this;

	tabs.bind('click', function() {
		var currentKey = jQuery(this).data('key');
		var wrapper = jQuery(this).parents('.ypm-tabs-content-wrapper').first();
		that.changeTab(currentKey, wrapper);
		that.setActiveTab(currentKey);
	});

	var wrapper = tabs.parents('.ypm-tabs-content-wrapper').first();
	var key = jQuery('.ypm-active-tab-name').val();
	if (!key) {
		key = wrapper.find('.ypm-options-tab-links').first().data('key');
	}

	that.changeTab(key, wrapper);
	that.hideShowActiveTab();
};

YPMGamificationBackend.prototype.setActiveTab = function(key)
{
	YpmPopup.setCookie(YPMGamificationBackend.tabCookieName, key);
};

YPMGamificationBackend.prototype.getActiveTab = function()
{
	return YpmPopup.getCookie(YPMGamificationBackend.tabCookieName);
};

YPMGamificationBackend.prototype.hideShowActiveTab = function()
{
	var activeTab = this.getActiveTab();
	if (!activeTab) {
		this.setActiveTab('contents');
		activeTab = 'contents';
	}
	jQuery('.ypm-options-tab-links').each(function(){
		jQuery(this).removeClass('ypm-active-tab');
	});
	jQuery('.ypm-tab-content-wrapper').each(function(){
		jQuery(this).css({display: 'none'});
	});

	jQuery('#ypm-tab-content-wrapper-'+activeTab).css({display: 'block'});
	jQuery('.ypm-option-tab-' + activeTab).addClass('ypm-active-tab');
};

YPMGamificationBackend.prototype.changeTab = function(key, wrapper)
{
	var tabsContent = wrapper.find('.ypm-tab-content-wrapper');
	tabsContent.each(function(){
		jQuery(this).css('display', 'none');
	});
	tablinks = wrapper.find('.ypm-options-tab-links');
	tablinks.each(function(){
		jQuery(this).removeClass('ypm-active-tab');
	});
	var currentLink = wrapper.find('.ypm-option-tab-'+key).first();
	currentLink.css('display', 'block');
	currentLink.addClass('ypm-active-tab');
	jQuery('#ypm-tab-content-wrapper-'+key).css({display: 'block'});
};

YPMGamificationBackend.prototype.changeGiftImage = function()
{
	var giftIcon = jQuery('.ypm-gift-icon');

	if (!giftIcon.length) {
		return false;
	}

	giftIcon.bind('click', function() {
		if (jQuery(this).data('is-free') === 1) {
			window.open("https://popup-more.com/");
			return false;
		}
		jQuery('.ypm-gift-icon').removeClass('ypm-active-gift');
		jQuery(this).addClass('ypm-active-gift');

		var currentImage = jQuery(this).data('image-name');
		if (currentImage != YPM_GAMIFICATION_ADMIN_PARAMS.defaultImagename) {
			jQuery('.js-ypm-remove-gamification-image').removeClass('ypm-hide-remove-button');
		}
		else {
			jQuery('.js-ypm-remove-gamification-image').addClass('ypm-hide-remove-button');
		}
		var currentImageURL = YPM_GAMIFICATION_ADMIN_PARAMS.imgURL+currentImage;
		jQuery('.ypm-show-gamification-image-container').css({'background-image': 'url("' + currentImageURL + '")'});
		jQuery('#ypm-gamification-gift-image').val(currentImageURL);
	});
};

jQuery(document).ready(function () {
	var obj = new  YPMGamificationBackend();
	obj.init();
});
