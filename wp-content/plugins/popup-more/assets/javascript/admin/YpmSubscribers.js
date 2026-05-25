function YpmSubscribers() {
	this.init();
}

YpmSubscribers.prototype.init = function() {
	this.toggleCheckedSubscribers();
	this.deleteSubscribers();
	this.exportSubscribers();
	this.openExportModal();
	this.openDetailModal();
	this.exportSubscription();
};

YpmSubscribers.prototype.exportSubscribers = function() {
	var exportLink = jQuery('.ypm-export-subscriber');

	if (!exportLink.length) {
		return false;
	}
	var that = this;

	exportLink.bind('click', function(e) {
		e.preventDefault();
		var searchInput = jQuery('#search_id-search-input');
		var dates = jQuery('#ypm-subscribers-dates');
		var subscription = jQuery('#ypm-subscription-id');
		var searchName = searchInput.attr('name');
		var dateName = dates.attr('name');
		var subscriptionName = subscription.attr('name');
		var urlParam = that.getUrlVars();

		var args = {
		};
		args[searchName] = urlParam[searchName];
		args[dateName] = urlParam[dateName];
		args[subscriptionName] = urlParam[subscriptionName];
		var paramsString = '';

		for(var i in args) {
			var value = args[i];
			if (value == undefined) {
				continue;
			}
			paramsString += '&'+i+'='+value;
		}

		var url = ypm_admin_localized.adminUrl+'admin-post.php?action=ypm_export_csv'+paramsString;
		window.location.href = url;
	});
};

YpmSubscribers.prototype.getUrlVars = function() {
	var vars = {};
	var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi,
		function(m,key,value) {
			vars[key] = value;
		});

	return vars;
};

YpmSubscribers.prototype.deleteSubscribers = function() {
	var deleteSubs = jQuery('.ypm-delete-subscribers');

	if (!deleteSubs.length) {
		return false;
	}
	var that = this;

	deleteSubs.bind('click', function(e) {
		e.preventDefault();
		var bulkValue = jQuery(this).prev().val();

		if (bulkValue == 'delete') {
			that.deleteSubscribersAjax()
		}
	});
};

YpmSubscribers.prototype.deleteSubscribersAjax = function()
{
	var checkedSubscribersList = [];

	jQuery('.ypm-delete-checkbox').each(function() {
		if (jQuery(this).is(':checked')) {
			checkedSubscribersList.push(jQuery(this).val())
		}
	});

	var data = {
		action: 'ypm_subscribers_delete',
		nonce: YpmAdminParams.nonce,
		subscribersId: checkedSubscribersList,
		beforeSend: function() {
		}
	};

	jQuery.post(ajaxurl, data, function(response) {
		jQuery('.ypm-delete-checkbox').prop('checked', '');
		window.location.reload();
	});
};

YpmSubscribers.prototype.toggleCheckedSubscribers = function() {
	var subsBulk = jQuery('.subs-bulk');

	if (!subsBulk.length) {
		return false;
	}
	var that = this;

	subsBulk.each(function() {
		jQuery(this).bind('click', function() {
			var bulkStatus = jQuery(this).is(':checked');
			subsBulk.each(function() {
				jQuery(this).prop('checked', bulkStatus);
			});
			that.changeCheckedSubscribers(bulkStatus);
		});
	})
};

YpmSubscribers.prototype.changeCheckedSubscribers = function(bulkStatus) {
	jQuery('.ypm-delete-checkbox').each(function() {
		jQuery(this).prop('checked', bulkStatus);
	});
};

YpmSubscribers.prototype.openExportModal = function () {

	var that = this;
	jQuery(".ypm-export-link").bind("click", function () {
		that.openPopup("#ypm-subscription-content")
	});
}

YpmSubscribers.prototype.openDetailModal = function () {
	var that = this;
	jQuery(".view-subscription-details").bind("click", function () {
		var id = jQuery(this).data('id');
		that.openPopup("#ypm-subscription-details-"+id)
	});
}

YpmSubscribers.prototype.exportSubscription = function () {
	var button = jQuery('.ypm-export-subscriptions');
	button.bind('click', function () {
		var value = jQuery('#ypm-subscription-id').val()
		jQuery.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'ypm_export_subscription',
				nonce: YpmAdminParams.nonce,
				value: value
				// Additional data if needed
			},
			success: function(response) {
				// Assuming the server returns the CSV data in the response
				// You can create a Blob or data URL to initiate a download
				var blob = new Blob([response], { type: 'text/csv' });
				var link = document.createElement('a');
				link.href = window.URL.createObjectURL(blob);
				link.download = 'popup_more_subscribers.csv';
				link.click();
			}
		});
	})
}

YpmSubscribers.prototype.openPopup = function(hrefLink = '#ypm-welcome-panel-wrapper')
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

YpmSubscribers.prototype.varToBool = function(optionName)
{
	var returnValue = (optionName) ? true : false;
	return returnValue;
};

jQuery(document).ready(function() {
	var obj = new YpmSubscribers();
});