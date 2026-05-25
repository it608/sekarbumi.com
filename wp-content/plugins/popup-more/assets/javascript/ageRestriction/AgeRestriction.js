function ypmagerestriction() {
	this.logic();
}
ypmagerestriction.cookieName = 'ypm-age-restriction-';
ypmagerestriction.cookieexpiration = 365;

ypmagerestriction.prototype.logic = function () {
	var that = this;
	var renderData = function (popupOptions) {
		var dataOptions = jQuery(".ypm-restriction-options").data('options');
		var type = dataOptions['ypm-age-restriction-type'];
		if (type === 'yesNo') {
			that.yesNo(jQuery(this), popupOptions, dataOptions);
		}
		else if (type === 'ageVerification') {
			that.ageVerification(jQuery(this), popupOptions, dataOptions);
		}
	}
	jQuery('#ypmcolorbox').on('ypmOnComplete', function (e, popupOptions) {
		renderData(popupOptions)
	});
	renderData({})
}

ypmagerestriction.prototype.ageVerification = function (popup, popupOptions, options) {
	var values = {
		day: '',
		month: '',
		year: ''
	}
	var that = this;
	jQuery(".ypm-restriction-button-wrapper button").bind('click', function () {
		jQuery('.ypm-restriction-error').addClass('ypm-hide');
		var callBack = function () {
			var val = jQuery(this).val();
			var type = jQuery(this).data('type');

			if (!val) {
				jQuery('.ypm-restriction-'+type).removeClass('ypm-hide');
			}
			else {
				values[type] = val;
				jQuery('.ypm-restriction-'+type).addClass('ypm-hide')
			}
		};

		jQuery('.restriction-option').each(callBack);
		jQuery('.restriction-option').bind('change', callBack)
		if (values.day && values.month && values.year) {
			var age = that.calculateAge(values.day, values.month, values.year);
			if (age < options['ypm-restriction-min-age']) {
				window.location.href = options['ypm-restriction-button-url'];
			}
			else {
				YpmPopup.closePopup();
				YpmPopup.setCookie(ypmagerestriction.cookieName + popupOptions.popupId, 1, ypmagerestriction.cookieexpiration)
			}
		}
	})
}

ypmagerestriction.prototype.calculateAge = function (birthDay, birthMonth, birthYear) {
	const today = new Date();
	const birthDate = new Date(birthYear, birthMonth - 1, birthDay);

	let age = today.getFullYear() - birthDate.getFullYear();
	const monthDiff = today.getMonth() - birthDate.getMonth();

	if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
		age--;
	}

	return age;
}

ypmagerestriction.prototype.yesNo = function (popup, popupOptions, options) {
	jQuery('.ypm-yes-button').bind('click', function () {
		var id = popup.data('id');
		if (popupOptions.popupId) {
			YpmPopup.closePopup();
			YpmPopup.setCookie(ypmagerestriction.cookieName + popupOptions.popupId, 1, ypmagerestriction.cookieexpiration)
		}
	})
	jQuery('.ypm-no-button').bind('click', function () {
		var id = jQuery(this).data('id');

		window.location.href = options['ypm-restriction-deny-url'];
	});
}

ypmagerestriction.allowToLoad = function (popup) {
	if (YpmPopup.getCookie(ypmagerestriction.cookieName+popup.popupId)) {
		return false;
	}

	return true;
}

jQuery(window).load(function () {
	new ypmagerestriction();
})