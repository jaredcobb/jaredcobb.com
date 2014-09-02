// example pattern of loose augmentation of the SiteJS object
// breaking page specific javascript into their own files
var SiteJS = (function(siteJS, $) {

	// a local object for internal logic only
	var local = {

		someLocalFunction : function() {
		}

	};

	siteJS.samplePageInit = function() {
		var self = this;
		// some custom logic here for page specific functionality
	};

	return siteJS;

})(SiteJS || {}, jQuery);
