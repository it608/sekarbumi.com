function YpmYoutube()
{
	this.init();
}

YpmYoutube.embededUrl = 'https://www.youtube.com/embed/';

YpmYoutube.prototype.init = function ()
{
	YpmVideo.renderVideos();
	this.previewUrl();
	this.diemnsion();
};

YpmYoutube.prototype.diemnsion = function ()
{
	var dimension = jQuery('.ypm-youtube-dimension');

	if (!dimension.length) {
		return false;
	}

	dimension.bind('change', function () {
		var val = parseInt(jQuery(this).val())+'px';
		var dataType = jQuery(this).data('type');
		var styleObj = {};
		styleObj[dataType] = val;
		jQuery('.ypm-iframe-div').css(styleObj);
	});
};

YpmYoutube.prototype.previewUrl = function ()
{
	var urlInput = jQuery('#ypm-youtube-url');

	if (!urlInput.length) {
		return false;
	}
	var getLocation = function (href) {
		var match = href.match(/^(https?\:)\/\/(([^:\/?#]*)(?:\:([0-9]+))?)([\/]{0,1}[^?#]*)(\?[^#]*|)(#.*|)$/);
		return match && {
				href: href,
				protocol: match[1],
				host: match[2],
				hostname: match[3],
				port: match[4],
				pathname: match[5],
				search: match[6],
				hash: match[7]
			}
	};
	urlInput.bind('change', function () {
		var url = jQuery(this).val();
		var youtubeId = '';
		var parseUrl = getLocation(url);
		if (parseUrl && typeof parseUrl['search'] != 'undefined' && parseUrl['search'] != '') {
			var splitData = parseUrl['search'].split('?v=');
			youtubeId = splitData[1].split('&')[0];
		}
		else if (url.indexOf('embed')) {
			var searchData = url.split('embed')[1].replace(/\//, '');
			youtubeId = searchData.split('&')[0];

		}
		setTimeout(function () {
			jQuery('.ypm-iframe-div').attr('src', YpmYoutube.embededUrl+youtubeId);
		}, 0)
	});
};

jQuery(document).ready(function () {
	new YpmYoutube();
});

function YpmVideo(id, options)
{
	this.id = id;
	this.options = options;
	this.render();
}


YpmVideo.renderVideos = function ()
{
	var videos = jQuery('.ypm-iframe-div');
	if (!videos.length) {
		return false;
	}

	videos.each(function (current) {
		var id = jQuery(this).data('video-id');
		var options = jQuery(this).data('options');
		new YpmVideo(id, options);
	})
};

YpmVideo.uuidv4 = function() {
	return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
		var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
		return v.toString(16);
	});
};

YpmVideo.prototype.render = function ()
{
	var id = this.id;
	var that = this;

	jQuery('[data-video-id="'+id+'"]').each(function () {
		var id = jQuery(this).data('video-id');
		var attrId = 'ypm-video-id-'+id+'-'+YpmVideo.uuidv4();
		jQuery(this).attr('id', attrId);
		that.start(attrId);
	});
};

YpmVideo.prototype.start = function(attrId)
{
	var options = this.options;
	options['attrId'] = attrId;
	var player = new YT.Player(attrId, {
		height: options['ypm-youtube-height'],
		width: options['ypm-youtube-width'],
		videoId: options['videoId'],
		playerVars: options['playerVars']
	});
	this.behavior(player, options);
};

YpmVideo.prototype.behavior = function(player, options)
{

	player.addEventListener("onStateChange", function(state){

		if(state.data === 0){
			if (typeof YpmYoutubePro == 'function') {
				var ypmYtPro = new YpmYoutubePro();
				ypmYtPro.endOfYoutube(options);
			}
		}
	});

};

function onYouTubeIframeAPIReady() {
	YpmVideo.renderVideos();
}