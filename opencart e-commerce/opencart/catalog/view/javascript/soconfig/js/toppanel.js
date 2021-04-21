$(document).ready(function(){
	// Menu header top 
	var duration = {headerTransform: 500},
	$window = $(window),
	$header = $("header"),
	$wrapper = $("#wrapper"),
	active = false,
	$switcher = $(".navbar-switcher", $header),
	headerStaticHeight = $header.outerHeight(),
	headerHeight = $header.outerHeight(),
	latent = $window.scrollTop() >= headerHeight;
	var $heightNew = $header.outerHeight();
	var windowWidth = window.innerWidth || document.documentElement.clientWidth; 
	var reculcPosHeader = function () {
    var headerCompact = false;
        if (!$header.hasClass("navbar-compact")) {
            headerCompact = true;
            $header.addClass("navbar-compact");
        }
        positionHeader = -$header.height() + 3;
        if (headerCompact) $header.removeClass("navbar-compact");
        if (parseInt($header.css("top")) < -1) $header.css("top", positionHeader + "px");
    };
	
	if (windowWidth > 1200 && !isiPhone()) {
		$window.scroll(function () {
			if (!latent && $window.scrollTop() >= headerStaticHeight) {
				
				$header.addClass("navbar-compact");
				reculcPosHeader();
				$header.css("top", positionHeader + "px");
				//push the header giving it a top-margin
				$wrapper.css("margin-top", headerStaticHeight + "px");
				latent = true;
				if (!$(".navbar-switcher-container").length){
					active = !active;
					$header.animate({
						top: active ? "0" : positionHeader
					}, {
						duration: duration.headerTransform
					})
				}
				
				
			} else if (latent && $window.scrollTop() < headerStaticHeight) {
				
				//push the header giving it a top-margin
				$wrapper.css("margin-top", "0px");
				$header.stop().css("top", "").removeClass("navbar-compact").css("top", "0px");
				
				active = false;
				latent = false;
			}
			
		});
		
		
	
	}
	
});

function isiPhone() {
	return (
		(navigator.userAgent.toLowerCase().indexOf("iphone") > -1) ||
			(navigator.userAgent.toLowerCase().indexOf("ipod") > -1)
		);
}
