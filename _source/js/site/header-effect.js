var SiteJS = (function(siteJS, $) {

	siteJS.headerEffect = function() {
		var self = this;

		/*
		http://bassta.bg/2013/12/medium-com-like-blurred-header-effect/
		*/

		$window = $(window);
		$body  = $("body");
		$bgBlur = $(".header-image-effect");

		var bgBlurHeight = $bgBlur.height();
		var scrollFlag = false;
		var scrollThreshold  = 0.25; //used to debounce pointer events
		var blurWhenReach = 3; //blur factor, 3 means the imahe will be blurred when you scroll 1/3 of the div

		$window.on("scroll", function(event){

		  var scrollTop = $window.scrollTop();

		  if(!scrollFlag){
			  scrollFlag = true;
			$body.addClass("disable-pointer-events");
		  }

		//	  debouncePointerEvents();

		  if(scrollTop < bgBlurHeight){
			var _alpha = (scrollTop / bgBlurHeight) * blurWhenReach;
			if(_alpha > 1){ _alpha = 1 }
			  $bgBlur.css('opacity', _alpha);
		  }

		});

		// Speed up things by disabling pointer events. I use TweenMax delayedCall instead JS native setInterval just for the sake of showing how to use this method. See more at http://www.thecssninja.com/javascript/pointer-events-60fps

		function debouncePointerEvents(){
		  TweenMax.killDelayedCallsTo(addPointerEvents);
			TweenMax.delayedCall(scrollThreshold, addPointerEvents);
		}

		function addPointerEvents(){
		  scrollFlag = false;
			$body.removeClass("disable-pointer-events");
		}
	};

	return siteJS;

})(SiteJS || {}, jQuery);
