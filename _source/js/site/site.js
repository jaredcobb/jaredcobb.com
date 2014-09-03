var SiteJS = (function(siteJS, $) {

	// a local object for internal logic only
	var local = {

		// executed automatically on all pages (not posts or archives)
		pageInit : function() {
		},

		// executed automatically on all single pages
		singleInit : function() {
		},

		// executed automatically on home page only
		homeInit : function() {
			siteJS.bindAnimateClicks();
		},

		// executed on everything (used for more global initilizations)
		allTheThings : function() {

			// transition one image to another on scroll
			siteJS.headerEffect();

			// initialize foundation
			$(document).foundation({
				topbar : {
					scrolltop: true
				}
			});

			/*
			* Webkit doesn't support viewport units in calc() expressions:
			* https://bugs.webkit.org/show_bug.cgi?id=94158 and
			* https://code.google.com/p/chromium/issues/detail?id=168840;
			* Blink added support with Revision 172971
			*
			* When Safari gets its crap together I'll get rid of this
			* Mobile Safari is also buggy with vh & vw units, but js will help until then. THE FUTURE IS NOW!
			*/

			// i know, i know, ua sniffing is the debil!
			var ua = navigator.userAgent.toLowerCase();
			var isSafari = false;

			if(/safari/.test(ua) && !/chrome/.test(ua)) {
				isSafari = true;
			}

			if (isSafari) {
				var topbarHeight = 90; // change this if i ever resize the nav bar
				var navWrapper = $(".nav-wrapper");
				var content = $(".content");
				var feature = $(".feature");

				$(window).on("resize", function() {
					var vh = $(window).innerHeight();
					navWrapper.css("top", vh - topbarHeight);
					content.css("top", vh - topbarHeight);
					feature.css("height", vh);
				}).resize();
			}
		}

	};

	siteJS.init = function() {
		var self = this;

		// run this code ON ALL THE THINGS! (no matter what kind of page/post it is)
		local.allTheThings();

		// SITE_JSON
		// this is a php generated global object that should exist on every page in the site. it's used
		// to store global data which helps define settings or conditional logic for page execution

		// execute for all pages in general
		if (SITE_JSON.isPage === "true") {
			local.pageInit();
		}

		// execute for all single posts in general
		if (SITE_JSON.isSingle === "true") {
			local.singleInit();
		}

		// execute for home page only
		if (SITE_JSON.isFrontPage === "true") {
			local.homeInit();
		}

		// check for page specific initializations
		// define public methods for this module that match the pattern of `postNameInit()` where `postName` is the actual WordPress post name
		// this code essentially asks, "Hey, did you define an init function for this page specifically? If so, call it"
		if (typeof(self[SITE_JSON.postNameInit]) == typeof(Function)) {
			self[SITE_JSON.postNameInit].apply();
		}

	};

	return siteJS;

})(SiteJS || {}, jQuery);

(function($) {
	$(function() {
		SiteJS.init();
	});
})(jQuery);
