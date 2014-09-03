var SiteJS = (function(siteJS, $) {

	siteJS.headerEffect = function() {
		var self = this;

		$window = $(window);
		$bgBlur = $(".header-image-effect");

		var bgBlurHeight = $bgBlur.height();
		var blurWhenReach = 3; //blur factor, 3 means the image will be blurred when you scroll 1/3 of the div

		$window.on("scroll", function(event){

			var scrollTop = $window.scrollTop();

			if(scrollTop < bgBlurHeight){
				var _alpha = (scrollTop / bgBlurHeight) * blurWhenReach;

				if(_alpha > 1){
					_alpha = 1;
				}

				$bgBlur.css('opacity', _alpha);
			}

		});
	};

	return siteJS;

})(SiteJS || {}, jQuery);
