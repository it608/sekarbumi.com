function YpmAdminJs() {

	this.enableDisable();
	this.colorpicker();
	this.accordion();
	this.upgradePro();
	this.select2();
	this.tabEvenetsListener();
	this.copySortCode();
	this.initCountdownDateTimePicker();
	this.editor();

	this.livePreview();
	this.multipleChoiceButton();
	this.resetViewCount();
	this.fixedPositionSelection();
	this.themesOptions();
	this.buttonImageUpload();
	this.buttonImageRemove();

	this.typesCreation();
	this.imageUpload();
	this.closeButtonPositions();
	this.promotionalVideo();
	this.openAnimationEffects();
	this.fixedPositionEvents();
	this.minicolors();
	this.backgroundImageUpload();
	this.backgroundImageRemove();
	this.initCountdownDateTimePicker()
	this.featureSuggestion();
	this.proOptions();
	this.subscriptionColors();
	this.checkAPIKey();
	this.dateTimePickerSeconds();
	this.soundUpload();
}

YpmAdminJs.prototype.soundUpload = function() {
	var custom_uploader;
	jQuery('.js-upload-sound-button').click(function(e) {
		e.preventDefault();
		var button = jQuery(this);

		/* If the uploader object has already been created, reopen the dialog */
		if (custom_uploader) {
			custom_uploader.open();
			return;
		}

		/* Extend the wp.media object for audio files */
		custom_uploader = wp.media.frames.file_frame = wp.media({
			title: 'Choose Sound',
			button: {
				text: 'Choose Sound'
			},
			multiple: false,
			library: {
				type: 'audio' // Restrict to audio files
			}
		});

		/* When a file is selected, grab the URL and set it as the text field's value */
		custom_uploader.on('select', function() {
			var attachment = custom_uploader.state().get('selection').first().toJSON();
			var soundURL = jQuery(button.data('element'));
			soundURL.val(attachment.url);
			soundURL.trigger('change');
		});

		/* Open the uploader dialog */
		custom_uploader.open();
	});

	/* Audio uploader complete */
    jQuery('#js-reset-to-click-sound').bind('click', function() {
        jQuery('#ypm-wheel-sound-url').val(jQuery(this).data('default-song'))
    })
    this.soundPreview();
};

YpmAdminJs.prototype.soundPreview = function()  {
	var songValue = 1;
	var lastSong;
	var isPlaying = false;

	jQuery('.js-preview-button-click-sound').bind('click', function() {
		var el = jQuery(this).data('element');
		var uploadFile = jQuery(el).val();

		// Initialize lastSong only if it's not already created
		if (!lastSong) {
			lastSong = new Audio(uploadFile);
		}

		// Toggle play and pause based on songValue
		if (songValue === 1 && !isPlaying) {
			isPlaying = true;
			lastSong.play().then(() => {
				songValue = 2;
			}).catch(error => {
				console.error("Playback failed: ", error);
			});
			console.log("start play");
		} else if (songValue === 2 && isPlaying) {
			lastSong.pause();
			isPlaying = false;
			songValue = 1;
		}

		// When the song ends, reset songValue and lastSong
		lastSong.onended = function() {
			isPlaying = false;
			lastSong = null;
			songValue = 1;
		};
	});
};

YpmAdminJs.prototype.dateTimePickerSeconds = function() {
	var secondsDate = jQuery('.js-datetimepicker-seconds');

	if(!secondsDate.length) {
		return false;
	} 

	secondsDate.ypmdatetimepicker({
		format: 'H:i',
		datepicker: false
	});
};

YpmAdminJs.prototype.checkAPIKey = function() {
	jQuery('.ypm-check-api-key').bind('click', function(e) {
		e.preventDefault();
		var apiBtn = jQuery(this);
		var data = {
			api_key: jQuery("#ypm-ai-api-key").val(),
			action: "ypm_check_AI_KEY",
			beforeSend: function() {
				apiBtn.prop('disabled', true);
			},
			nonce: YpmAdminParams.ajaxNonce
		};
	
		jQuery.post(ajaxurl, data, function(response) {
			apiBtn.prop('disabled', false);
			var html = jQuery('.ypm-api-checker-wrapper');
			html.html('');
			html.removeClass('ypm-hide');
			html.append('<p class="ypm-api-key-status-'+response.success+'">'+response.data+'</p>');
			setTimeout(function() {
				html.addClass('ypm-hide')
			}, 4000)
		})
	});
}
YpmAdminJs.prototype.subscriptionColors = function() {
	if (!jQuery(' #ypm-subscription-input-color').length) {
		return;
	}
	jQuery('#ypm-subscription-labels-color, #ypm-subscription-input-color').minicolors();

}
YpmAdminJs.prototype.proOptions = function() {
	var proOptions = jQuery('label .ypm-pro-span');

	if(!proOptions.length) {
		return false;
	}

    proOptions.parent().bind('click', function(e) {
		e.preventDefault();
		window.open(YpmAdminParams.proURL);
    });
};

YpmAdminJs.prototype.featureSuggestion = function() {
	var sendButton = jQuery("#ypm-feature-suggestion-button");
	if (!sendButton.length) {
		return '';
	}
	sendButton.bind('click', function() {
		jQuery('.ypm-fsend-successs').addClass('ypm-hide')
		jQuery('.ypm-fsend-error').addClass('ypm-hide')
		var data = {
			message: jQuery("#ypm-feature-suggestions").val(),
			action: "ypm_send_feature_suggestion",
			nonce: YpmAdminParams.ajaxNonce
		};

		jQuery.post(ajaxurl, data, function(response) {
			if (response.success) {
				jQuery('.ypm-fsend-successs').removeClass('ypm-hide')
				jQuery("#ypm-feature-suggestions").val('')	
			}
			else {
				jQuery('.ypm-fsend-error').removeClass('ypm-hide')
			}
			
		});
	});

	jQuery(".ypm-hide-suggestion").bind('click', function () {
		var data = {
			action: "ypm_hide_suggestion",
			nonce: YpmAdminParams.ajaxNonce
		}
		jQuery.post(ajaxurl, data, function(response) {
			window.location.reload()
		})
	});
	
}

YpmAdminJs.prototype.initCountdownDateTimePicker = function() {
	var countdown = jQuery('.ypm-date-time-picker');

	if(!countdown.length) {
		return false;
	}

	countdown.ycddatetimepicker({
		format: 'Y-m-d H:i',
		minDate: 0
	});
};

YpmAdminJs.prototype.backgroundImageUpload = function() {
	if (jQuery('#js-background-upload-image').val()) {
		jQuery('.ypm-show-background-image-container').html('');
		jQuery('.ypm-show-background-image-container').css({
			'background-image': 'url("' + jQuery("#js-background-upload-image").val() + '")'
		});
	}
	var supportedImageTypes = ['image/bmp', 'image/png', 'image/jpeg', 'image/jpg', 'image/ico', 'image/gif'];
	var custom_uploader;
	jQuery('#js-background-upload-image-button').click(function(e) {
		e.preventDefault();
		if (custom_uploader) {
			custom_uploader.open();
			return;
		}
		custom_uploader = wp.media.frames.file_frame = wp.media({
			titleFF: 'Choose Image',
			button: {
				text: 'Choose Image'
			},
			multiple: false,
			library: {
				type: 'image'
			}
		});
		custom_uploader.on('select', function() {
			var attachment = custom_uploader.state().get('selection').first().toJSON();
			if (supportedImageTypes.indexOf(attachment.mime) === -1) {
				alert("Not supported");
				return;
			}
			jQuery('.ypm-show-background-image-container').css({
				'background-image': 'url("' + attachment.url + '")'
			});
			jQuery('.ypm-show-background-image-container').html('');
			jQuery('#js-background-upload-image').val(attachment.url);
			jQuery('input[name="ypm-background-image"]').attr('value', attachment.url);
			jQuery('.js-ypm-remove-background-image').removeClass('ypm-hide');
		});
		custom_uploader.open();
	});
};
YpmAdminJs.prototype.backgroundImageRemove = function() {
	jQuery('#js-background-upload-image-remove-button').click(function() {
		jQuery('.ypm-show-background-image-container').html('<span class="ypm-no-image">(No image selected)</span>');
		jQuery('.ypm-show-background-image-container').removeAttr('style');
		jQuery('#js-background-upload-image').attr('value', '');
		jQuery('.js-ypm-remove-background-image').addClass('ypm-hide');
	});
};

YpmAdminJs.prototype.fixedPositionEvents = function () {
	var checkValueDisabled = function () {
		var styleVal = jQuery('.ypm-popup-floating-style option:selected').val();
		if (styleVal === 'corner') {
			jQuery('.ypm-button-options').addClass('ypm-hide')
			jQuery('.ypm-position-button-center').prop('disabled', true);
		}
		else {
			jQuery('.ypm-button-options').removeClass('ypm-hide')
			jQuery('.ypm-position-button-center').prop('disabled', false);
		}
	};
	checkValueDisabled();

	jQuery('.ypm-popup-floating-style').bind('change', function () {
		checkValueDisabled();
	});

	jQuery(".ypm-position-button").click(function () {
		jQuery(".ypm-position-button").removeClass('ypm-position-active');
		jQuery(this).addClass('ypm-position-active');
		jQuery("#ypm-popup-floating-position").val(jQuery(this).data('value'))
	});
	jQuery(".ypm-position-button").hover(
		function() {
			jQuery(this).addClass('ypm-position-active');
		}, function() {
			if (jQuery(this).data('value') !== jQuery("#ypm-popup-floating-position").val()) {
				jQuery(this).removeClass('ypm-position-active');
			}
		}
	);
	if (!jQuery("#ypm-popup-floating-hover-bg-color").length) {
		return;
	}
	jQuery("#ypm-popup-floating-hover-bg-color").minicolors({});
	jQuery("#ypm-popup-floating-hover-text-color").minicolors({});

	if (jQuery("#ypm-popup-floating-text-color").length) {
		jQuery("#ypm-popup-floating-text-color").minicolors({
			format: 'rgb',
			opacity: 1,
			change: function() {

			}
		});
	}
	if (jQuery("#ypm-popup-floating-bg-color").length) {
		jQuery("#ypm-popup-floating-bg-color").minicolors({
			format: 'rgb',
			opacity: 1,
			change: function() {

			}
		});
	}
	if (jQuery("#ypm-popup-floating-border-color").length) {
		jQuery("#ypm-popup-floating-border-color").minicolors({
			format: 'rgb',
			opacity: 1,
			change: function() {

			}
		});
	}
}

YpmAdminJs.prototype.openAnimationEffects = function () {
	jQuery('.ypm-animation-preview').bind('click', function() {
		var type = jQuery(this).data('type');
		var animation = jQuery('.ypm-popup-'+type+'-animation').val();
		var openAnimationDiv = jQuery('.ypm-js-'+type+'-animation-effect');
		var speedSeconds = jQuery("#ypm-popup-"+type+"-animation-speed").val()*1000;

		setTimeout(function() {
			openAnimationDiv.hide();
		}, speedSeconds);
		openAnimationDiv.removeClass();
		openAnimationDiv.css({
			'animationDuration': speedSeconds + 'ms',
			display: 'block'
		});
		openAnimationDiv.addClass('js-open-animation-effect ypm-js-'+type+'-animation-effect ypm-animated '+animation)
	})
}

YpmAdminJs.prototype.promotionalVideo = function() {
	var target = jQuery('.ypm-play-promotion-video');

	if(!target.length) {
		return false;
	}

	target.bind('click', function(e) {
		e.preventDefault();
		var href = jQuery(this).data('href');
		window.open(href);
	});
};

YpmAdminJs.prototype.copySortCode = function() {
	jQuery('.ypm-shortcode-input').bind('click', function() {
		var currentId = jQuery(this).data('id');
		var copyText = document.getElementById('ypm-shortcode-input-'+currentId);
		copyText.select();
		document.execCommand('copy');

		var tooltip = document.getElementById('ypm-tooltip-'+currentId);
		tooltip.innerHTML = YpmAdminParams.copied;
	});

	jQuery(document).on('focusout', '.countdown-shortcode',function() {
		var currentId = jQuery(this).data('id');
		var tooltip = document.getElementById('ypm-tooltip-'+currentId);
		tooltip.innerHTML = YpmAdminParams.copyToClipboard;
	});
};

YpmAdminJs.prototype.tabEvenetsListener = function() {

	if (!jQuery('.nav-tabs a').length) {
		return false;
	}

	jQuery('.nav-tabs a').bind('click', function (e) {
		e.preventDefault();
		jQuery('.nav.nav-tabs li').removeClass('active');
		jQuery(this).parent().addClass('active');
		jQuery('.tab-pane.fade').removeClass('in active');
		var id = jQuery(this).attr('href');
		jQuery(id).addClass('in active');
		jQuery(".ypm-section-tabs").val(id.slice(1));
	});
};

YpmAdminJs.prototype.select2 = function () {
	if (!jQuery('.js-basic-select').length) {
		return false;
	}
	jQuery('.js-basic-select').select2();
};

YpmAdminJs.prototype.colorpicker = function() {

	if (!jQuery(".ypm-range").length) {
		return false;
	}
	jQuery('.ypm-color-picker').wpColorPicker();

	jQuery(".ypm-range").ionRangeSlider({
		hide_min_max: true,
		keyboard: true,
		min: 0,
		max: 1,
		type: 'single',
		step: 0.1,
		prefix: "",
		grid: true
	});
};

YpmAdminJs.prototype.accordion = function () {

	var that = this;
	var element = jQuery(".js-ypm-accordion");
	element.each(function() {
		that.checkboxAccordion(jQuery(this));
	});

	element.click(function() {
		var elements = jQuery(this);
		that.checkboxAccordion(jQuery(this));
	});
};

YpmAdminJs.prototype.checkboxAccordion = function (element) {

	if(!element.is(':checked')) {
		element.parents('.row').first().removeClass('ypm-margin-bottom-0');
		element.parents('.row').first().nextAll("div").first().css({'display': 'none'});
	}
	else {
		element.parents('.row').first().addClass('ypm-margin-bottom-0');
		element.parents('.row').first().nextAll("div").first().css({'display':'block'});
	}
};

YpmAdminJs.prototype.upgradePro = function () {
	jQuery('.ypm-pro-options').on('click', function() {
		window.open(YpmAdminParams.proURL);
	});
};

YpmAdminJs.prototype.editor = function() {
	(function($){
		$(function(){

			if( $('#ypm-editor-js').length ) {
				var editorSettings = wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings ) : {};
				editorSettings.codemirror = _.extend(
					{},
					editorSettings.codemirror,
					{
						mode: 'javascript',
					}
				);
				var editor = wp.codeEditor.initialize( $('#ypm-editor-js'), editorSettings );
			}

			if( $('#ypm-editor-css').length ) {
				var editorSettings = wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings ) : {};
				editorSettings.codemirror = _.extend(
					{},
					editorSettings.codemirror,
					{
						indentUnit: 2,
						tabSize: 2,
						mode: 'css',
					}
				);
				var editor = wp.codeEditor.initialize( $('#ypm-editor-css'), editorSettings );
			}
		});
	})(jQuery);
};

YpmAdminJs.prototype.livePreview = function ()
{
	var previewHeader = jQuery('.ypm-live-preview-text');

	if (!previewHeader.length) {
		return false;
	}
	previewHeader.bind('click', function () {
		var toggleIcon = jQuery('.ypm-toggle-icon');
		if (toggleIcon.hasClass('ypm-toggle-icon-open')) {
			toggleIcon.removeClass('ypm-toggle-icon-open').addClass('ypm-toggle-icon-close');
		}
		else {
			toggleIcon.removeClass('ypm-toggle-icon-close').addClass('ypm-toggle-icon-open');
		}
		jQuery('.ypm-live-preview-content').toggle();
	});

};

YpmAdminJs.prototype.multipleChoiceButton = function() {
	var choiceOptions = jQuery('.ypm-choice-option-wrapper input');
	if(!choiceOptions.length) {
		return false;
	}

	var that = this;

	choiceOptions.each(function() {

		if(jQuery(this).is(':checked')) {
			that.buildChoiceShowOption(jQuery(this));
		}

		jQuery(this).on('change', function() {
			that.hideAllChoiceWrapper(jQuery(this).parents('.ypm-multichoice-wrapper').first());
			that.buildChoiceShowOption(jQuery(this));
		});
	})
};


YpmAdminJs.prototype.hideAllChoiceWrapper = function(choiceOptionsWrapper) {
	choiceOptionsWrapper.find('input').each(function() {
		var choiceInputWrapperId = jQuery(this).attr('data-attr-href');
		jQuery('#'+choiceInputWrapperId).addClass('ypm-hide');
	})
};

YpmAdminJs.prototype.buildChoiceShowOption = function(currentRadioButton)  {
	var choiceOptions = currentRadioButton.attr('data-attr-href');
	var currentOptionWrapper = currentRadioButton.parents('.ypm-choice-wrapper').first();
	var choiceOptionWrapper = jQuery('#'+choiceOptions).removeClass('ypm-hide');
	currentOptionWrapper.after(choiceOptionWrapper);
};

YpmAdminJs.prototype.resetViewCount = function()  {
	var resetButton = jQuery('.ypm-reset-count-btn');

	if (!resetButton.length) {
		return false;
	}

	resetButton.bind('click', function () {

		if (!confirm(YpmAdminParams.areYouSure)) {
			return false;
		}

		var data = {
			postId: jQuery(this).data('id'),
			action: "ypm_reset_view_count",
			nonce: YpmAdminParams.ajaxNonce
		};

		jQuery.post(ajaxurl, data, function(response) {
			window.location.reload();
		});
	});
};

YpmAdminJs.prototype.enableDisable = function()  {
	var resetButton = jQuery('.ypm-popup-enable');

	if (!resetButton.length) {
		return false;
	}

	resetButton.bind('change', function () {

		var data = {
			postId: jQuery(this).data('switch-id'),
			value: jQuery(this).is(":checked"),
			action: "ypm_change_popup_status",
			ajaxNonce: YpmAdminParams.ajaxNonce
		};

		jQuery.post(ajaxurl, data, function(response) {
		});
	});
};

YpmAdminJs.prototype.fixedPositionSelection = function() {
	var fixedPossitionEl = jQuery(".js-ypm-fixed-position-style");
	var fixPosition = jQuery(".js-ypm-fixed-position");
	var visitedColor = "rgba(33, 150, 243,0.5)";
	var hoverColor = "rgb(33, 150, 243)";
	var defaultColor = "#FFFFFF";
	var valueAttr = 'data-ypm-value';
	
	fixedPossitionEl.bind("click", function() {
		var ypmElement = jQuery(this);
		var ypmPoss = ypmElement.attr(valueAttr);
		fixedPossitionEl.css("backgroundColor", defaultColor);
		jQuery(this).css("backgroundColor", visitedColor);
		fixPosition.val(ypmPoss);
	});
	fixedPossitionEl.bind("mouseover", function() {
		fixedPossitionEl.css("backgroundColor", defaultColor);
		jQuery(this).css("backgroundColor", hoverColor);
		fixedPossitionEl.each(function() {
			if (jQuery(this).attr(valueAttr) == fixPosition.val())
				jQuery(this).css("backgroundColor", visitedColor);
		});
	});
	fixedPossitionEl.bind("mouseout", function() {
		if (fixedPossitionEl.attr(valueAttr) !== fixPosition.val() || fixPosition.val() == 1) {
			jQuery(this).css("backgroundColor", defaultColor);
		}
		fixedPossitionEl.each(function() {
			if (jQuery(this).attr(valueAttr) == fixPosition.val()) {
				jQuery(this).css("backgroundColor", visitedColor);
			}
		});
	});
	if (fixPosition.val() != '') {
		fixedPossitionEl.each(function() {
			if (jQuery(this).attr(valueAttr) == fixPosition.val()) {
				jQuery(this).css("backgroundColor", visitedColor);
			}
		});
	}
};

YpmAdminJs.openPopupByHref = function (href) {
    jQuery.ypmcolorbox({
        html: false,
        inline: true,
        className: 'ypmcolorbox1',
        opacity: "0.8",
        innerWidth: "480px",
        innerHeight: "320px",
        href: href,
        onOpen: function() {
            jQuery("#ypmcboxOverlay").addClass("ypmcboxOverlayBg");
        }
    })
};

YpmAdminJs.prototype.initCountdownDateTimePicker = function() {
	var countdown = jQuery('.ypm-date-time-picker');

	if(!countdown.length) {
		return false;
	}

	countdown.ypmdatetimepicker({
		format: 'Y-m-d H:i',
		minDate: 0
	});
};

YpmAdminJs.prototype.themesOptions = function () {
	var themes = jQuery('.ypm-popup-theme');
	var hideClassName = 'ypm-hide';
	var that = this;
	var callBack = function (initial) {

		jQuery('.ypm-themes-sub-options').addClass(hideClassName);

		if (initial === 0) {
			var selectedValue = jQuery('.ypm-popup-theme:checked').val();
		}
		else {
			var selectedValue = jQuery(this).val();
			jQuery('.js-ypm-remove-close-button-image').click();
		}
		jQuery("#js-button-upload-image").attr('value', '');
		jQuery('.ypm-themes-sub-options-'+selectedValue).removeClass(hideClassName)
	}

	themes.bind('change', callBack)
	callBack(0)
}

YpmAdminJs.prototype.buttonImageUpload = function() {
	var supportedImageTypes = ['image/bmp', 'image/png', 'image/jpeg', 'image/jpg', 'image/ico', 'image/gif', 'image/x-icon', 'image/x-png']
	var custom_uploader;
	jQuery('.js-button-upload-image-button').click(function(e) {
		e.preventDefault();
		if (custom_uploader) {
			custom_uploader.open();
			return;
		}
		custom_uploader = wp.media.frames.file_frame = wp.media({
			titleFF: 'Choose Image',
			button: {
				text: 'Choose Image'
			},
			multiple: false,
			library: {
				type: 'image'
			}
		});
		custom_uploader.on('select', function() {
			var attachment = custom_uploader.state().get('selection').first().toJSON();
			if (supportedImageTypes.indexOf(attachment.mime) === -1) {
				alert(YpmAdminParams.imageSupportAlertMessage);
				return;
			}
			jQuery(".ypm-close-image-wrapper").css({
				'background-image': 'url("' + attachment.url + '")'
			});
			jQuery(".ypm-close-image-wrapper").html("");
			jQuery('#ypm-theme-close-image-url').val(attachment.url);
			jQuery('.js-ypm-remove-close-button-image').removeClass('ypm-hide');
		});
		custom_uploader.open();
	});
};

YpmAdminJs.prototype.buttonImageRemove = function() {
	var closeButton = jQuery('.js-ypm-remove-close-button-image');
	closeButton.click(function() {
		var selectedTheme = jQuery('.ypm-popup-theme:checked').val();
		if (typeof selectedTheme == 'undefined') {
			selectedTheme = 'colorbox6';
		}
		jQuery('#ypm-default-preview-style-'+selectedTheme).remove();
		jQuery(".ypm-show-button-image-container").html("");
		jQuery("#js-button-upload-image").attr('value', '');
		jQuery('.ypm-close-image-wrapper').attr('style', 'background-image: url("' + YpmAdminParams.imagesURL + '/' + selectedTheme + '/close.png")');
		closeButton.addClass('ypm-hide');
	});
};

YpmAdminJs.prototype.typesCreation = function () {
	this.popupsCreation();
	this.createPopup();
}

YpmAdminJs.prototype.createPopup = function () {
	var button = jQuery('.ypm-create-popup-button');

	if (!button.length) {
		return false;
	}

	button.bind('click', function (e) {
		e.preventDefault();
		var moduleId = jQuery("#ypmcboxLoadedContent #ypm-popup-type-value option:selected").val();
		var type = jQuery(this).data('type');
		var link = jQuery(this).data('href');
		if (!moduleId) {
			alert('Please create a type, then you can create a popup.')
			return;
		}
		var popupCreationLink = link+'&ypm_module_id='+parseInt(moduleId);
		window.location.href = popupCreationLink;
	})
}

YpmAdminJs.prototype.popupsCreation = function () {
	var popupsLink = jQuery('.create-popups-link');
	if (!popupsLink.length) return  false;
	var that = this;

	popupsLink.bind('click', function (e) {
		if (!jQuery(this).data('is-available')) {
			return ;
		}
		if (jQuery(this).data('hastype')) {
			e.preventDefault();
			var type = jQuery(this).data('type');
			that.openPopup('#popup-options-'+type);
		}
	})
}

YpmAdminJs.prototype.varToBool = function(optionName)
{
	var returnValue = (optionName) ? true : false;
	return returnValue;
};

YpmAdminJs.prototype.openPopup = function(hrefLink = '#ypm-welcome-panel-wrapper')
{
	/* this.popupEvents(); */
	var popupId = 0;

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
		href: hrefLink,
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
	/*
	this.customEvents();
	 */
};

YpmAdminJs.prototype.imageUpload = function() {
	var supportedImageTypes = ['image/bmp', 'image/png', 'image/jpeg', 'image/jpg', 'image/ico', 'image/gif', 'image/x-icon', 'image/x-png'];
	if (jQuery("#ypm-image-popup-url").val()) {
		jQuery(".ypm-show-image-container").html("");
		jQuery(".ypm-show-image-container").css({
			'background-image': 'url("' + jQuery("#ypm-image-popup-url").val() + '")'
		});
	}
	var custom_uploader;
	jQuery('.ypm-upload-button').click(function(e) {
		e.preventDefault();
		if (custom_uploader) {
			custom_uploader.open();
			return;
		}
		custom_uploader = wp.media.frames.file_frame = wp.media({
			titleFF: 'Choose Image',
			button: {
				text: 'Choose Image'
			},
			multiple: false,
			library: {
				type: 'image'
			}
		});
		custom_uploader.on('select', function() {
			var attachment = custom_uploader.state().get('selection').first().toJSON();
			if (supportedImageTypes.indexOf(attachment.mime) === -1) {
				alert('Only image files supported');
				return;
			}
			jQuery(".ypm-show-image-container").css({
				'background-image': 'url("' + attachment.url + '")'
			});
			jQuery(".ypm-show-image-container").html("");
			jQuery('#ypm-image-popup-url').val(attachment.url);
		});
		custom_uploader.open();
	});
}

YpmAdminJs.prototype.closeButtonPositions = function () {
	if (!jQuery('.js-close-button-positions').length) {
		return ;
	}
	var position = function () {
		var positionValue = jQuery('.js-close-button-positions').val();
		jQuery('.ypm-popup-close-button-wrapper').addClass('ypm-hide');
		var positions = positionValue.split('_');

		for(var cur in positions) {
			jQuery('.ypm-close-wrapper-'+positions[cur]).removeClass('ypm-hide');
		}
	}

	jQuery('.js-close-button-positions').bind('change', function () {
		position()
	})

	position();
}

YpmAdminJs.prototype.minicolors = function () {
	var color = jQuery('.ypm-minicolors');
	if (!color.length) {
		return  false;
	}
	color.minicolors({
		format: 'rgb'
	});
}

jQuery(document).ready(function() {
	var obj = new YpmAdminJs();
});


/*Conditions builder*/
function YpmConditionBuilder() {
}

YpmConditionBuilder.prototype.init = function() {
	this.conditionsBuilder();
	this.select2();
};

YpmConditionBuilder.prototype.select2 = function() {
	var select2 = jQuery('.js-ypm-select');

	if(!select2.length) {
		return false;
	}
	select2.each(function() {
        if (!jQuery(this).is('select')) {return true}

		var type = jQuery(this).data('select-type');

		var options = {
			width: '100%'
		};

		if (type == 'ajax') {
			options = jQuery.extend(options, {
				minimumInputLength: 1,
				ajax: {
					url: ajaxurl,
					dataType: 'json',
					delay: 250,
					type: "POST",
					data: function(params) {

						var searchKey = jQuery(this).attr('data-value-param');
						var postType = jQuery(this).attr('data-post-type');

						return {
							action: 'ypm_select2_search_data',
							nonce_ajax: YpmAdminParams.nonce,
							postType: postType,
							searchTerm: params.term,
							searchKey: searchKey
						};
					},
					processResults: function(data) {
						return {
							results: jQuery.map(data.items, function(item) {

								return {
									text: item.text,
									id: item.id
								}

							})
						};
					}
				}
			});
		}

		jQuery(this).select2(options);
	});
};

YpmConditionBuilder.prototype.conditionsBuilder = function() {
	this.conditionsBuilderEdit();
	this.conditionsBuilderAdd();
	this.conditionsBuilderSetting();
	this.conditionsBuilderDelte();
};

YpmConditionBuilder.prototype.conditionsBuilderSetting = function() {
	var settings = jQuery('.ypm-condition-settings');

	if (!settings) {
		return false;
	}

	settings.bind('click', function(e) {
		e.stopPropagation();
		var conditionsId = parseInt(jQuery(this).data('condition-id'));
		var href = '#ypm-setting-'+conditionsId;
		YpmAdminJs.openPopupByHref(href);
	});
};

YpmConditionBuilder.prototype.conditionsBuilderAdd = function() {
	var params = jQuery('.ypm-condition-add');

	if(!params.length) {
		return false;
	}
	var that = this;
	params.unbind('click').bind('click', function() {
		var currentWrapper = jQuery(this).parents('.ypm-condition-wrapper').first();
		var selectedParams = currentWrapper.find('.js-conditions-param').val();

		that.addViaAjax(selectedParams, currentWrapper);
	});
};

YpmConditionBuilder.prototype.conditionsBuilderDelte = function() {
	var params = jQuery('.ypm-condition-delete');

	if(!params.length) {
		return false;
	}

	params.bind('click', function() {
		var currentWrapper = jQuery(this).parents('.ypm-condition-wrapper').first();

		currentWrapper.remove();
	});
};

YpmConditionBuilder.prototype.conditionsBuilderEdit = function() {
	var params = jQuery('.js-conditions-param');

	if(!params.length) {
		return false;
	}
	jQuery('.js-custom-param-change').bind('change', function () {
		params.trigger('change');
	});
	var that = this;
	params.bind('change', function() {
		var selectedParams = jQuery(this).val();
		var currentWrapper = jQuery(this).parents('.ypm-condition-wrapper').first();

		that.changeViaAjax(selectedParams, currentWrapper);
	});
	jQuery('.js-ypm-sub-param').bind('change', function () {
		var selectedParams = jQuery(this).val();
		var currentWrapper = jQuery(this).parents('.ypm-condition-wrapper').first();
		var paramVal = jQuery(currentWrapper).find('.ypm-condition-select').val();
		var params = [];
		params.push(paramVal);
		params.push(selectedParams);

		that.changeViaAjax(params, currentWrapper);
	});
};

YpmConditionBuilder.prototype.addViaAjax = function(selectedParams, currentWrapper) {
	var conditionId = parseInt(currentWrapper.data('condition-id'))+1;
	var conditionsClassName = currentWrapper.parent().data('child-class');

	var that = this;

	var data = {
		action: 'ypm_add_conditions_row',
		nonce: YpmAdminParams.nonce,
		conditionId: conditionId,
		conditionsClassName: conditionsClassName,
		selectedParams: selectedParams,
		popupId: jQuery('#post_ID').val(),
		beforeSend: function () {
			that.beforeSend(conditionId);
		}
	};

	jQuery.post(ajaxurl, data, function(response) {
		currentWrapper.after(response);
		that.afterSend(conditionId);
		that.init();
	});
};

YpmConditionBuilder.prototype.changeViaAjax = function(selectedParams, currentWrapper) {
	var conditionId = currentWrapper.data('condition-id');
	var conditionsClassName = currentWrapper.parent().data('child-class');

	var that = this;

	var data = {
		action: 'ypm_edit_conditions_row',
		nonce: YpmAdminParams.nonce,
		conditionId: conditionId,
		conditionsClassName: conditionsClassName,
		selectedParams: selectedParams,
		popupId: jQuery('#post_ID').val(),
		beforeSend: function () {
			that.beforeSend(conditionId);
		}
	};

	jQuery.post(ajaxurl, data, function(response) {
		currentWrapper.replaceWith(response);
		that.afterSend(conditionId);
		that.init();
	});
};

YpmConditionBuilder.prototype.beforeSend = function(conditionId) {
	jQuery('.ypm-condition-delete, .ypm-condition-add').attr('disabled','disabled');
};

YpmConditionBuilder.prototype.afterSend = function(conditionId) {
	jQuery('.ypm-condition-delete, .ypm-condition-add').removeAttr('disabled');
};

jQuery(document).ready(function() {
	var obj = new YpmConditionBuilder();
	obj.init();
});