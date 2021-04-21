/* ------------------------------------------------------ /
	Magentech jQuery
	Created by Magentech
	All rights reserved.
	+----------------------------------------------------+
		TABLE OF CONTENTS
	+----------------------------------------------------+
	
	[1]		Quickview - jQuery elevateZoom
	[2]		Quantity plus minus - Product Detail
	[3]		Social Widgets Accounts - Slidebar
	[4]		Back To Top 
	[5]		Language and Currency Dropdowns
	[6]		Preloading Screen
	[7]		Simple Blog - Magnific Popup
	[8]		Preloader Function
/ ---------------------------------------------------- */

/* Preloading Screen
 ========================================================*/
$(window).load(function() {
	// Animate loader off screen
	setTimeout(function () {
        $('body').addClass('loaded');
    }, 1500);
	
});


/* Button Collapse  
 ========================================================*/
$(function ($) {
    "use strict";
	$('body').delegate('.btn-collapse','click',function(){
		$(this).toggleClass('active');
	});
});	

/* Quantity plus minus - Product Detail
 ========================================================*/
$(function ($) {
    "use strict";
    
	$('.contentslider').each(function () {
		var $slider = $(this),
			$panels = $slider.children('div'),
			data = $slider.data(),
			$totalItem = $panels.length;
		// Apply Owl Carousel
		$slider.on("initialized.owl.carousel2", function () {
			setTimeout(function() {
			   $slider.parent().find('.loading-placeholder').hide();
			}, 1000);

		});
		$slider.owlCarousel2({
			responsiveClass: true,
			mouseDrag: true,
			video:true,
			autoWidth: (data.autowidth == 'yes') ? true : false,
			rtl: (data.rtl == 'yes') ? true : false,
			animateIn: data.transitionin,
    		animateOut: data.transitionout,
    		lazyLoad: (data.lazyload == 'yes') ? true : false,
			autoplay: (data.autoplay == 'yes') ? true : false,
			autoHeight: (data.autoheight == 'yes') ? true : false,
			autoplayTimeout: data.delay * 1000,
			smartSpeed: data.speed * 1000,
			autoplayHoverPause: (data.hoverpause == 'yes') ? true : false,
			center: (data.center == 'yes') ? true : false,
			loop: (data.loop == 'yes') ? true : false,
            dots: (data.pagination == 'yes') ? true : false,
            nav: (data.arrows == 'yes') ? true : false,
			dotClass: "owl2-dot",
			dotsClass: "owl2-dots",
            margin: data.margin,
			navText: ['<i class="fa fa-chevron-left"></i>', '<i class="fa fa-chevron-right"></i>'],
			navClass: ["owl2-prev", "owl2-next"],
			responsive: {
				0: {
					items	: data.items_column4,
					nav		: ($totalItem > data.items_column4 && data.arrows == 'yes') ? true : false
				},
				370: {
					items	: data.items_column3,
					nav		: ($totalItem > data.items_column3 && data.arrows == 'yes') ? true : false
				},
				768: {
					items	: data.items_column2,
					nav		: ($totalItem > data.items_column2 && data.arrows == 'yes') ? true : false
				},
				992: { 
					items	: data.items_column1,
					nav		: ($totalItem > data.items_column1 && data.arrows == 'yes') ? true : false
				}
			}
		});
		

	});
});

/* Quantity plus minus - Product Detail
 ========================================================*/
$(function ($) {
    "use strict";
    $.initQuantity = function ($control) {
        $control.each(function () {
            var $this = $(this),
                data = $this.data("inited-control"),
                $plus = $(".input-group-addon:last", $this),
                $minus = $(".input-group-addon:first", $this),
                $value = $(".form-control", $this);
            if (!data) {
                $control.attr("unselectable", "on").css({
                    "-moz-user-select": "none",
                    "-o-user-select": "none",
                    "-khtml-user-select": "none",
                    "-webkit-user-select": "none",
                    "-ms-user-select": "none",
                    "user-select": "none"
                }).bind("selectstart", function () {
                    return false
                });
                $plus.click(function () {
                    var val =
                        parseInt($value.val()) + 1;
                    $value.val(val);
                    return false
                });
                $minus.click(function () {
                    var val = parseInt($value.val()) - 1;
                    $value.val(val > 0 ? val : 1);
                    return false
                });
                $value.blur(function () {
                    var val = parseInt($value.val());
                    $value.val(val > 0 ? val : 1)
                })
            }
        })
    };
    $.initQuantity($(".quantity-control"));
    $.initSelect = function ($select) {
        $select.each(function () {
            var $this = $(this),
                data = $this.data("inited-select"),
                $value = $(".value", $this),
                $hidden = $(".input-hidden", $this),
                $items = $(".dropdown-menu li > a", $this);
            if (!data) {
                $items.click(function (e) {
                    if ($(this).closest(".sort-isotope").length >
                        0) e.preventDefault();
                    var data = $(this).attr("data-value"),
                        dataHTML = $(this).html();
                    $this.trigger("change", {
                        value: data,
                        html: dataHTML
                    });
                    $value.html(dataHTML);
                    if ($hidden.length) $hidden.val(data)
                });
                $this.data("inited-select", true)
            }
        })
    };
    $.initSelect($(".btn-select"))
});


/* Resonsive Sidebar aside
 ========================================================*/
$(document).ready(function(){
	$(".open-sidebar").click(function(e){
        e.preventDefault();
        $(".sidebar-overlay").toggleClass("show");
        $(".sidebar-offcanvas").toggleClass("active");
    });
      
    $(".sidebar-overlay").click(function(e){
        e.preventDefault();
        $(".sidebar-overlay").toggleClass("show");
        $(".sidebar-offcanvas").toggleClass("active");
    });
    $('#close-sidebar').click(function() {
        $('.sidebar-overlay').removeClass('show');
        $('.sidebar-offcanvas').removeClass('active');
        
    }); 
});

	
	