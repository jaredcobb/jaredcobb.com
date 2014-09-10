var SiteJS = (function(siteJS, $) {

	siteJS.bindAnimateClicks = function() {
		if (Modernizr.history) {
			$("#menu-primary-menu a").on("click", function(e) {
				if (this.hash.length === 0) {
					return;
				}
				e.preventDefault();
				var stateObj = { hash : this.hash };
				history.pushState(stateObj, '');
				siteJS.animateClicks(stateObj);
			});

			$(window).on("popstate", function(e) {
				stateObj = e.originalEvent.state;
				siteJS.animateClicks(stateObj);
			});
		}

		$(".nav-wrapper").on("click", ".top-bar.expanded .top-bar-section a", function() {
			$(".toggle-topbar a").click();
		});
	};

	siteJS.animateClicks = function(stateObj) {

		if (typeof stateObj === 'object') {
			var $page = $('html, body');

			if (stateObj !== null && stateObj.hash.length > 0) {

				var anchor = $(stateObj.hash);
				$page.animate({
					scrollTop: anchor.offset().top
				}, 1000, "easeInOutCirc" );
				return true;

			}
			else {
				$page.animate({
					scrollTop: 0
				}, 1000, "easeInOutCirc" );
				return true;
			}
		}
	};

	return siteJS;

})(SiteJS || {}, jQuery);
