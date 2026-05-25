function YpmSubscriptionForm()
{

}

YpmSubscriptionForm.init = function ()
{
	var forms = jQuery('.ycf-subscription-form');

	if (!forms.length) {
		return false;
	}

	forms.each(function () {
		var current = jQuery(this);
		var id = current.data('id');
		var validateObj = current.attr('data-validate');

		var obj = new YpmSubscriptionForm;
		obj.setId(id);
		obj.setValidateObj(validateObj);
		obj.setForm(current);
		obj.start();
	});


};

YpmSubscriptionForm.prototype.setId = function (id)
{
	this.id = parseInt(id);
};

YpmSubscriptionForm.prototype.getId = function ()
{
	return parseInt(this.id);
};

YpmSubscriptionForm.prototype.setForm = function (from)
{
	this.form = from;
};

YpmSubscriptionForm.prototype.getForm = function ()
{
	return this.form;
};

YpmSubscriptionForm.prototype.setValidateObj = function (validateObj)
{
	var validateObj = jQuery.parseJSON(validateObj);
	validateObj.errorPlacement = function(error, element) {
		if (jQuery(element).data('type') === 'gdpr') {
			jQuery(element).parent().next().append(error);
		}
		else {
			error.insertAfter(element);
		}
	}
	this.validateObj = validateObj;
};


YpmSubscriptionForm.prototype.getValidateObj = function ()
{
	return this.validateObj;
};

YpmSubscriptionForm.prototype.validate = function ()
{
	var validateObj = this.getValidateObj();
	var form = this.getForm();
	var formId = form.data('id');

	validateObj.submitHandler = function() {
		var formData = form.serialize();

		var data = {
			nonce: ypmFormLocalization.ajaxNonce,
			action: 'ypm_subscribed',
			formData: formData,
			beforeSend: function () {
				jQuery('.ypm-spinner', form).removeClass('ycf-hide');

			},
			formId: formId
		};
		jQuery.post(ypmFormLocalization.ajaxurl, data, function (response) {
			jQuery('.ypm-spinner', form).addClass('ycf-hide');
			jQuery(window).trigger('YpmSubscriptionFormSend', {form: form, expirationData: form.data('expiration-options')})
		})
	};
	form.validate(validateObj);
};

YpmSubscriptionForm.prototype.eventListener = function()
{
	jQuery(window).bind('YpmSubscriptionFormSend', function (e, options) {
		var expirationData = options['expirationData'];
		switch (expirationData['ypm-popup-subscription-behavior']) {
			case "message":
				jQuery(options['form']).replaceWith("<div class='ypm-success-text'>"+expirationData['ypm-popup-subscription-expiration-message']+'</div>');
				jQuery(".ypm-success-text").css({color: "green"})
				if (expirationData['ypm-popup-subscription-enable-redirect']) {
					window.open(expirationData['ypm-popup-subscription-text-redirect-url']);
				}
				break;
			case "redirect":
				var redirectUrl = expirationData['ypm-popup-subscription-redirect-url'];
				if (expirationData['ypm-popup-subscription-redirect-url-tab']) {
					window.open(redirectUrl)
				}
				else {
					window.location = redirectUrl;
				}
				break;
			case "hide":
				options['form'].hide();
				break;
			case "openPopup":
				YpmPopup.closePopup();
				YpmPopup.openPopupById(expirationData['ypm-popup-subscription-popup']);

		}
	});
};

YpmSubscriptionForm.prototype.start = function ()
{
	this.validate();
	this.eventListener();
};

jQuery(document).ready(function () {
	YpmSubscriptionForm.init();
});