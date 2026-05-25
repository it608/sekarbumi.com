function YcfFormBackend()
{
}

YcfFormBackend.prototype.init = function()
{
	this.addNewFieldInit();
	this.changeElementValueFromList();
	this.ysfRemoveElement();
	/*sub options*/
	this.crudSubOptions();
	/*end sub option*/
	this.addSortableColumn();
	this.ycfElementOptions();
	this.confButtonInit();
	this.addTypElementsWrapper();
	this.deleteContactFormFormDb();
	this.initTab();
	this.addToHiddenContent();
	this.disableFreeFields();
};

YcfFormBackend.prototype.crudSubOptions = function()
{
	this.changeElementSubOption();
	this.addElementSubOption();
	this.deleteElementSubOption();
};

YcfFormBackend.prototype.disableFreeFields = function () {
	var input = jQuery("#ypm-field-types").val();
	var typesList = JSON.parse(input);
	jQuery(".ypm-disabledbutton").bind('click', function (e) {
		e.preventDefault();
	})
	jQuery('.subs-free-fields-wrapper .sortable-custom-element').removeClass('ypm-disabledbutton');
	for(var i in typesList) {
		var typeName = typesList[i];
		jQuery('.subs-free-fields-wrapper [data-element-type="'+typeName+'"]').addClass('ypm-disabledbutton')
	}
}

YcfFormBackend.prototype.addFieldTypes = function (fieldType) {
	var input = jQuery("#ypm-field-types").val();
	var typesList = JSON.parse(input);
	typesList.push(fieldType);
	jQuery("#ypm-field-types").val(JSON.stringify(typesList))
	this.disableFreeFields();
}

YcfFormBackend.prototype.removeTypeFromFieldTypes = function (fieldType) {
	var input = jQuery("#ypm-field-types").val();
	var typesList = JSON.parse(input);
	var index = typesList.indexOf(fieldType);
	if (index !== -1) {
		typesList.splice(index, 1);
	}
	jQuery("#ypm-field-types").val(JSON.stringify(typesList))
	this.disableFreeFields();
}

YcfFormBackend.prototype.addNewFieldInit = function()
{
	var that = this;

	jQuery(".sortable-custom-element").unbind('click').bind("click", function() {
		that.addFieldTypes(jQuery(this).data('element-type'));
		that.addToFormElementsList(jQuery(this));
	});
};

YcfFormBackend.prototype.addToFormElementsList = function(element)
{
	var that = this;
	var formType = element.attr('data-form-type') || 'contact';
	var id = this.getRandomName();
	var type = element.attr('data-element-type');
	var label = element.find("span").text();
	var name = element.attr('data-element-name') || "ycf-" + id;

	var orderNumber = this.getNewFieldOrderNumber();

	var formElement = {};
	formElement.id = id;
	formElement.type = type;
	formElement.name = name;
	formElement.label = label;
	formElement.orderNumber = orderNumber;
	formElement.value = '';
	formElement.options = '';

	if(jQuery('#ycf-mailchimp-selectbox').length) {
		formElement.listId = jQuery('#ycf-mailchimp-selectbox option:selected').val();
	}

	if(type == 'select') {
		formElement.options = this.selectiveOptions(element);
	}

	var data = {
		action: 'ypm-shape-form-element',
		ajaxNonce: ycfBackendLocalization.ajaxNonce,
		modification: 'add-element',
		formElements: formElement,
		contactFormId: jQuery("#ycf-form-id").val(),
		formType: formType,
		beforeSend: function() {
		}
	};

	this.doAjaxShapeFormList(data);
};

YcfFormBackend.prototype.doAjaxShapeFormList = function(data)
{
	var that = this;

	jQuery.post(ajaxurl, data, function(response) {

		if(response != '') {
			jQuery("#ycf-submit-wrapper").parent().before(response);
			jQuery(window).trigger('ycfRefreshFields', data);
			that.ycfElementOptions();
			that.confButtonInit();
			that.addCurrentOrdering();
			that.crudSubOptions();
		}

	});
};

YcfFormBackend.prototype.changeElementValueFromList = function()
{
	var that = this;
	jQuery('.ycf-element-sub-option').bind('input', function() {

		var editElementData = {};
		var currentValue = jQuery(this).val();
		editElementData.formCurrentId = jQuery("#ycf-form-id").val();
		editElementData.changedElementValue = currentValue;
		if(jQuery(this).is(':checkbox')) {
			editElementData.changedElementValue = jQuery(this).is(':checked');
		}
		editElementData.changedElementId = jQuery(this).attr('data-id');
		editElementData.changedElementKey = jQuery(this).attr('data-key');
		var data = {
			action: 'ypm-change-element-data',
			ajaxNonce: ycfBackendLocalization.ajaxNonce,
			isSettings: jQuery(this).data('is-settings'),
			editElementData: editElementData,
			formKey: jQuery('#form-key-val').val()
		};

		jQuery.post(ajaxurl, data, function(response) {
			console.log(response);
		});
	})
};

YcfFormBackend.prototype.ysfRemoveElement = function()
{
	var that = this;

	jQuery('.ycf-delete-element').bind('click', function() {

		var data = {};
		var removeElementId = jQuery(this).attr('data-id');
		jQuery("#" + removeElementId).parents('.ycf-element-info-wrapper').first().remove();
		data.id = removeElementId;
		that.removeElementViaAjax(data);
		that.removeTypeFromFieldTypes(jQuery(this).data('type'));
	});
};

YcfFormBackend.prototype.addElementSubOption = function()
{
	var that = this;
	jQuery('.ycf-add-sub-option-group').unbind('click');
	jQuery('.ycf-add-sub-option-group').bind('click', function() {
		var lastSubOptionWrapper = jQuery(this).parents('.ycf-sub-options-header').first().find('.ycf-options-data-names').first();
		var dataOptionDiv = lastSubOptionWrapper.find('.data-type-sub-options').last();

		if(dataOptionDiv.length) {
			var elementId = dataOptionDiv.attr("data-id");
			var elementType = dataOptionDiv.attr('data-type');
			var currentLastOrder = dataOptionDiv.attr('data-order');
			var newSubOptionName = dataOptionDiv.find('.sub-option-name').val();
			var newSubOptionLabel = dataOptionDiv.find('.sub-option-value').val();
			var elementOrderId = ++currentLastOrder;
		}
		else {
			var elementId = jQuery(this).attr("data-id");
			var elementType = jQuery(this).attr('data-type');
			var newSubOptionName = 'one';
			var newSubOptionLabel = 'One';
			var elementOrderId = 0;
		}

		var data = {
			action: 'ypm-add-sub-option-option',
			ajaxNonce: ycfBackendLocalization.ajaxNonce,
			contactFormId: jQuery("#ycf-form-id").val(),
			elementId: elementId,
			elementType: elementType,
			newSubOptionName: newSubOptionName,
			newSubOptionLabel: newSubOptionLabel,
			beforeSend: function() {
				that.disableInAjax()
			},
			elementOrderId: elementOrderId
		};

		jQuery.post(ajaxurl, data, function(response, d) {
			lastSubOptionWrapper.after(response);
			that.crudSubOptions();
			that.removeDisableInAjax();
		});
	});
};

YcfFormBackend.prototype.deleteElementSubOption = function()
{
	var that = this;
	jQuery('.delete-sub-option').each(function() {

		jQuery(this).bind('click', function() {
			var dataOption = jQuery(this).parents(".data-type-sub-options").first();
			var elementId = dataOption.attr("data-id");
			var elementType = dataOption.attr('data-type');
			var elementOrderId = dataOption.attr('data-order');
			var elementName = jQuery(this).attr('name');
			var elementValue = jQuery(this).val();

			var data = {
				action: 'ypm-delete-sub-option',
				ajaxNonce: ycfBackendLocalization.ajaxNonce,
				contactFormId: jQuery("#ycf-form-id").val(),
				elementId: elementId,
				elementType: elementType,
				elementName: elementName,
				elementValue: elementValue,
				elementOrderId: elementOrderId,
				modificationType: 'change'
			};

			jQuery.post(ajaxurl, data, function(response, d) {
				dataOption.remove();
				console.log(response);
			})
		});
	});
};

YcfFormBackend.prototype.changeElementSubOption = function()
{
	var that = this;
	jQuery(".data-type-sub-options input").bind("input", function() {
		var dataOption = jQuery(this).parents(".data-type-sub-options").first();
		var elementId = dataOption.attr("data-id");
		var elementType = dataOption.attr('data-type');
		var elementOrderId = dataOption.attr('data-order');
		var elementName = jQuery(this).attr('name');
		var elementValue = jQuery(this).val();

		var data = {
			action: 'ypm-sub-option-change',
			ajaxNonce: ycfBackendLocalization.ajaxNonce,
			contactFormId: jQuery("#ycf-form-id").val(),
			elementId: elementId,
			elementType: elementType,
			elementName: elementName,
			elementValue: elementValue,
			elementOrderId: elementOrderId,
			modificationType: 'change'
		};

		jQuery.post(ajaxurl, data, function(response, d) {
			console.log(response);
		});
	});
};

YcfFormBackend.prototype.removeElementViaAjax = function(removeElementData)
{
	var data = {
		action: 'ypm-remove-element-from-list',
		ajaxNonce: ycfBackendLocalization.ajaxNonce,
		modification: 'delete',
		removeElementData: removeElementData,
		beforeSend: function() {
		}
	};

	jQuery.post(ajaxurl, data, function(response, d) {
		jQuery(window).trigger('ycfRefreshFields', removeElementData);
	});
};

YcfFormBackend.prototype.ycfElementOptions = function()
{
	jQuery('.ycf-view-element-wrapper').unbind('click');
	jQuery('.ycf-view-element-wrapper').each(function() {

		jQuery(this).bind('click', function() {

			elementOptionsToggle = jQuery(this).attr('data-options');

			if(elementOptionsToggle == "false") {
				jQuery(this).next().removeClass('ycf-hide-element');
				jQuery(this).attr('data-options', "true");
			}
			if(elementOptionsToggle == "true") {
				jQuery(this).next().addClass('ycf-hide-element');
				jQuery(this).attr('data-options', "false");
			}
		});
	});
};

YcfFormBackend.prototype.getCurrentOrdering = function()
{
	var orderingData = [];
	jQuery(".ycf-element-info-wrapper").each(function() {
		var currentId = jQuery(this).find(jQuery('.sub-option-hidden-data')).attr('data-order');
		orderingData.push(currentId);
	});

	return orderingData;
};

YcfFormBackend.prototype.addSortableColumn = function()
{
	var that = this;
	if(!jQuery("#active-elements").length) {
		return;
	}
	var position = {};

	jQuery("#active-elements").sortable({
		connectWith: ".connectedSortable",
		update: function(event, ui) {
			that.addCurrentOrdering();
		}
	});
};

YcfFormBackend.prototype.addCurrentOrdering = function()
{
	var currentData = this.getCurrentOrdering();
	jQuery('.form-element-ordering').val(currentData.join(','));
};

YcfFormBackend.prototype.getRandomName = function()
{
	var randomName = Math.floor(Math.random() * Date.now()).toString().substr(0, 5);

	return randomName;
};

YcfFormBackend.prototype.getNewFieldOrderNumber = function()
{
	var currentData = this.getCurrentOrdering();
	return Math.max.apply(null, currentData) + 1;
};

YcfFormBackend.prototype.confButtonInit = function()
{
	jQuery('.ycf-element-conf-wrapper').hover(function() {
		jQuery(this).find(jQuery('.ycf-conf-element')).removeClass('ycf-hide-element');
	}, function() {
		jQuery('.ycf-conf-edit, .ycf-delete-element').addClass('ycf-hide-element');
	});
};

YcfFormBackend.prototype.addTypElementsWrapper = function()
{
	jQuery('.ycf-add-a-field').bind('click', function() {
		var currentStatus = jQuery('.sortable-all-elements-wrapper').attr('data-toggle-status');

		if(currentStatus == "true") {
			jQuery('.sortable-all-elements-wrapper').attr('data-toggle-status', false);
			jQuery('.sortable-all-elements-wrapper').removeClass('ycf-hide-element');
			jQuery('.ycf-add-a-field').text('Cancel adding a field');
		}
		else {
			jQuery('.sortable-all-elements-wrapper').attr('data-toggle-status', true);
			jQuery('.sortable-all-elements-wrapper').addClass('ycf-hide-element');
			jQuery('.ycf-add-a-field').text("Add A Field");
		}
	})
};

YcfFormBackend.prototype.deleteContactFormFormDb = function()
{
	jQuery('.ycf-delete-form').bind('click', function() {

		var boolData = confirm('Are you sure');

		if(!boolData) {
			return false;
		}

		var formId = jQuery(this).attr('data-id');
		var formType = jQuery(this).attr('data-type');
		var data = {
			action: 'delete_contact_form',
			formId: formId,
			formType: formType
		};

		jQuery.post(ajaxurl, data, function(response) {
			window.location.reload();
		});
	})
};

YcfFormBackend.prototype.initTab = function()
{
	jQuery(".nav-tabs a").click(function() {
		jQuery(this).tab('show');
	});
};

YcfFormBackend.prototype.addToHiddenContent = function()
{
	if(!jQuery('.sortable-all-elements').length) {
		return;
	}
	jQuery('.sortable-all-elements').sortable(
		console.log(jQuery(this))
	);
};

YcfFormBackend.prototype.selectiveOptions = function(createElement)
{
	var data = {};
	data.fieldsOptions = createElement.attr('data-options');
	data.fieldsOrder = createElement.attr('data-order');

	return JSON.stringify(data);
};

YcfFormBackend.prototype.disableInAjax = function()
{
	if(!jQuery('.js-disable-in-ajax').length) {
		return;
	}

	jQuery('.js-disable-in-ajax').each(function() {
		jQuery(this).prop("disabled", true);
	});
};

YcfFormBackend.prototype.removeDisableInAjax = function()
{
	if(!jQuery('.js-disable-in-ajax').length) {
		return;
	}

	jQuery('.js-disable-in-ajax').each(function() {
		jQuery(this).prop("disabled", false);
	});
};

jQuery(document).ready(function() {
	var obj = new YcfFormBackend();
	obj.init();
});