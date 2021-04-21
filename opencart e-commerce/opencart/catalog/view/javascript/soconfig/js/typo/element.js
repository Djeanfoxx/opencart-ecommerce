// NOTICE!! DO NOT USE ANY OF THIS JAVASCRIPT
// IT'S ALL JUST JUNK FOR OUR DOCS!
// ++++++++++++++++++++++++++++++++++++++++++

/*!
 * Magentech jQuery
 * Created by Magentech
 * All rights reserved.
 */

/* Accordion Block */
$(document).ready(function($) {
	$("ul.yt-accordion li").each(function() {
		if($(this).index() > 0) {
			$(".yt-accordion-inner").hide();
			$(".enable+.yt-accordion-inner").show();
			$(".enable+.yt-accordion-inner").addClass("active");
		}
		else {
			$(".enable").addClass('active');
		}
		var ua = navigator.userAgent,
		event = (ua.match(/iPad/i)) ? "touchstart" : "click";
		$(this).children(".accordion-heading").bind(event, function() {
			//alert("123");
			if($(this).hasClass("active"))
			{
				$(this).removeClass("active");
				$(this).siblings(".yt-accordion-inner").removeClass("active");
				$(this).siblings(".yt-accordion-inner").slideUp(350);
			}
			else
			{
				$(this).addClass("active");
				$(this).siblings(".yt-accordion-inner").addClass("active");
				$(this).siblings(".yt-accordion-inner").slideDown(350);
			}
			
			$(this).parent().siblings("li").children(".yt-accordion-inner").slideUp(350);
			$(this).parent().siblings("li").find(".active").removeClass("active");
		});
	});
	
});

$(document).ready(function() {
	$('.content--gallery').magnificPopup({
			delegate: '.popup-gallery',
		  type: 'image',
		  tLoading: 'Loading image #%curr%...',
		  mainClass: 'mfp-img-mobile',
		  gallery: {
			enabled: true,
			navigateByImgClick: true,
			preload: [0,1] // Will preload 0 - before current, and 1 after the current image
		  },
		  image: {
			tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
			titleSrc: function(item) {
			  return item.el.attr('title') ;
			}
		  }
    });
});
