function YpmObserver() {

	this.list = [];
	this.currentId = 0;
}

YpmObserver.prototype.init = function () {

	if(typeof YPM_IDS == 'undefined' || !YPM_IDS.length) {
		return;
	}

	this.list = YPM_IDS;
	this.currentId = this.list[0];

	this.openCurrent();
	this.nextListener();
};

YpmObserver.prototype.openCurrent = function () {

	this.openPopup(this.currentId);
};

YpmObserver.prototype.nextListener = function () {

	var that = this;
	jQuery('#ypmcolorbox').on("ypmPopupClose", function () {
		that.prepareNextOpen();
	});
};

YpmObserver.prototype.prepareNextOpen = function () {

	this.list.shift();
	this.currentId = this.list[0];
	this.openCurrent();
};

YpmObserver.openPopupById = function (popupId, event = 'Onload') {
	var data = YPM_DATA[popupId];

	var currentPopup = new YpmPopup();
	currentPopup.setPopupId(popupId);
	currentPopup.setPopupData(data);
	currentPopup['Onload']({key2: 0})
}

YpmObserver.prototype.openPopup = function(popupId) {

	if(typeof YPM_DATA[popupId] == 'undefined') {
		return;
	}

	var data = YPM_DATA[popupId];
	var eventsSettings = data['ypm-events-settings'];

	for (var current in eventsSettings) {
		var currentEvent = eventsSettings[current];
		var currentPopup = new YpmPopup();
		currentPopup.setPopupId(popupId);
		currentPopup.setPopupData(data);
		if (currentPopup[currentEvent['key1']]) {
			currentPopup[currentEvent['key1']](currentEvent);
		}
	}
};

jQuery(document).ready(function () {
		var obs = new YpmObserver();
		obs.init();
});
