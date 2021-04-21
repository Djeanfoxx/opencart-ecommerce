
/* Top panel Fixed
 ========================================================*/
 $(function ($) {
    "use strict";
	var $window = $('#wrapper .content'),
	$document = $('#content'),
	$header = $("header"),
	headerStaticHeight = $header.outerHeight();
	
	$window.scroll(function(event) {
		var scrTop = $(this).scrollTop();
		
		if( scrTop >= headerStaticHeight ) {
			$header.addClass('topnav-menu');
			//$window.css("margin-top", headerStaticHeight + "px");
		} else if (scrTop < headerStaticHeight) {
			$header.stop().removeClass("topnav-menu");
			//$window.css("margin-top", "0px");
		}
		
		
	});
	
});
