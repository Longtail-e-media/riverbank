
$(document).on("scroll", function(){
    if($(document).scrollTop() >100){
       $(".site-header").addClass("scroll-fixed");
    }else{
        $(".site-header").removeClass("scroll-fixed");
    }
})

var base_url = jQuery('base').attr('url');
! function(e) {
    "use strict";

    function t() {
        e(window).scrollTop() > 40 && l > 767 ? e(a).fadeIn() : e(a).fadeOut()
    }

    function i() {
        e(s).delay(100).fadeOut("slow")
    }
    var l = e(window).innerWidth(),
        o = e(window).innerHeight(),
        a = e(".site-backtop"),
        s = e(".site-loading");
    if (e(".site-backtop").on("click", function(t) {
            t.preventDefault(), e("body, html").animate({
                scrollTop: 0
            }, 800)
        }), e(".site-header .header-nav li.sub > a").on("click", function(t) {
            if (1200 > l) {
                t.preventDefault();
                var i = e(this).parent("li"),
                    o = e(this).siblings("ul");
                i.hasClass("active") ? (o.hide(), i.removeClass("active")) : (o.show(), i.addClass("active"))
            }
        }), e(".site-header .header-toggle").on("click", function(t) {
            t.preventDefault();
            var i = e(".site-header"),
                l = e(".site-header .header-nav");
            l.is(":visible") ? i.removeClass("nav-open") : i.addClass("nav-open")
        }), e("[data-background]").each(function() {
            var t = e(this).data("background");
            t && e(this).css("background-image", "url(" + t + ")")
        }), e(".popup-photo").length && e(".popup-photo").magnificPopup({
            type: "image"
        }), e(".popup-gallery").length && e(".popup-gallery").magnificPopup({
            type: "image",
            gallery: {
                enabled: !0
            }
        }), e(".popup-video").length && e(".popup-video").magnificPopup({
            type: "iframe"
        }), e("input.datepicker").length) {
        var r = e("input.datepicker").outerWidth();
        e("input.datepicker").datepicker(), e("body").append("<style>.ui-datepicker{width:auto; min-width: " + r + "px !important;}</style>")
    }
    if (e(".video-full").length && e(".video-full").fitVids(), e(".widget-slider").length && e(".widget-slider .widget-carousel").owlCarousel({
            items: 1,
            nav: !0,
            navText: ["", ""],
            dots: !0,
            autoHeight: !0,
            animateOut: "fadeOut",
            animateIn: "fadeIn",
            onInitialized: function() {
                e(".site-header").addClass("header-over"), e(".widget-rooms-carousel.top-over").length && e(".widget-slider").addClass("has-rooms")
            }
        }), e(".widget-gallery-grid").length && e(".widget-gallery-grid .gallery-item a").imagesLoaded({
            background: !0
        }, function() {
            var t = e(".widget-gallery-grid .row");
            t.on("arrangeComplete", function() {
                e(".widget-gallery-grid").magnificPopup({
                    delegate: ".isotope-item:visible a",
                    type: "image",
                    gallery: {
                        enabled: !0
                    }
                })
            }), t.isotope({
                itemSelector: ".isotope-item"
            }), e(".widget-filter-top ul li a").on("click", function(i) {
                i.preventDefault();
                var l = e(this).attr("data-filter");
                t.isotope({
                    filter: l
                }), e(".widget-filter-top ul li").removeClass("active"), e(this).parent("li").addClass("active")
            })
        }), e(".widget-gallery-carousel").length && e(".widget-gallery-carousel .widget-carousel").owlCarousel({
            center: !0,
            loop: !0,
            nav: !0,
            navText: ["", ""],
            dots: !1,
            mouseDrag: !1,
            responsive: {
                0: {
                    items: 1
                },
                768: {
                    items: 3
                }
            }
        }), e(".widget-rooms-carousel").length && e(".widget-rooms-carousel .widget-carousel").owlCarousel({
            autoplay:true,
            responsive: {
                0: {
                    items: 1
                },
                991: {
                    items: 2
                },
                1200: {
                    items: 3
                }
            }
        }), e(".widget-rooms-detail").length) {
        var n = e(".widget-rooms-detail .room-slider .owl-carousel"),
            g = e(".widget-rooms-detail .room-thumbnails .owl-carousel");
        n.owlCarousel({
            items: 1,
            nav: !0,
            navText: ["", ""],
            dots: !1,
            mouseDrag: !1
        }).on("changed.owl.carousel", function(e) {
            g.trigger("to.owl.carousel", [e.item.index, 250, !0])
        }), g.owlCarousel({
            margin: 20,
            dots: !1,
            responsive: {
                0: {
                    items: 1
                },
                768: {
                    items: 2
                },
                991: {
                    items: 3
                }
            }
        }).on("click", ".owl-item a", function(t) {
            t.preventDefault(), n.trigger("to.owl.carousel", [e(this).parent().index(), 250, !0])
        })
    }
    if (e(".widget-blog-list").length && e(".widget-blog-list .media-gallery .media-carousel").owlCarousel({
            items: 1,
            navText: ["", ""]
        }), e(".widget-blog-carousel").length && (e(".widget-blog-carousel .widget-carousel").owlCarousel({
            responsive: {
                0: {
                    items: 1
                },
                768: {
                    items: 2
                },
                992: {
                    items: 3
                },
                1200: {
                    items: 4
                }
            },
            onRefreshed: function() {
                var t = e(".widget-blog-carousel .widget-carousel .blog-item"),
                    i = 0;
                t.removeAttr("style"), t.each(function() {
                    e(this).height() > i && (i = e(this).height())
                }), t.css("height", i)
            }
        }), e(".widget-blog-carousel .media-gallery .media-carousel").owlCarousel({
            items: 1,
            mouseDrag: !1,
            navText: ["", ""]
        })), e(".widget-blog-single").length && e(".widget-blog-single .media-gallery .media-carousel").owlCarousel({
            items: 1,
            nav: !0,
            dots: !1,
            navText: ["", ""],
            mouseDrag: !1,
            autoplay: !0
       }), 
          e(".widget-testimonials-carousel").length && e(".widget-testimonials-carousel .widget-carousel").owlCarousel({
            margin: 40,
            responsive: {
                0: {
                    items: 1
                },
                768: {
                    items: 2
                },
                992: {
                    items: 2
                }
            }
        }),
        e(".widget-article-carousel").length && e(".widget-article-carousel .widget-carousel").owlCarousel({
            margin: 40,
            autoplay: !0,
            loop: !0,
            responsive: {
                0: {
                    items: 1
                },
                768: {
                    items: 1
                },
                992: {
                    items: 1
                }
            }
        }), e(".widget-features-carousel").length && e(".widget-features-carousel .widget-carousel").owlCarousel({
            margin: 40,
            responsive: {
                0: {
                    items: 1
                },
                768: {
                    items: 2
                },
                992: {
                    items: 3
                },
                1200: {
                    items: 5
                }
            }
        }), e(".widget-team-carousel").length && e(".widget-team-carousel .widget-carousel").owlCarousel({
            margin: 50,
            responsive: {
                0: {
                    items: 1
                },
                768: {
                    items: 2
                },
                992: {
                    items: 3
                },
                1200: {
                    items: 4
                }
            }
        }), e(".widget-google-map").length) try {
        
    } catch (d) {
        console.log(d)
    }
    e(window).scroll(function() {
        t()
    }), e(window).load(function() {
        i()
    }), e(window).resize(function() {
        l = e(window).innerWidth(), o = e(window).innerHeight()
    })

    
    $(document).on('click', '.map-google', function() {
        $('.full-map').removeClass('point-block');
    });
    $(document).mouseup(function (e) { $('.full-map').addClass('point-block'); })

    if(jQuery('#frm-contact')[0]) {
        jQuery("#frm-contact")[0].reset();
        jQuery("#frm-contact").validate({
            errorElement: 'span',
            errorClass: 'validate-has-error',
            rules: {
                fullname: { required: true },
                mailaddress: { required: true, email: true },
                phoneno: { required: true },
                message: { required: true },             
                userstring:{
                    required:true,
                    minlength:5,
                    remote: {
                        url: base_url+"captcha/checkcaptcha.php",
                        type: "post"
                    }
                }
            },
            messages:{  
                fullname: { required: "Enter your Fullname", },            
                mailaddress: { required: "Enter your email address", email: "Enter a VALID email address" },
                phoneno: { required: "Enter your Phone No." },
                message: { required: "Enter your Message" },
                userstring:{
                    required: 'Enter Security Code',
                    minlength: 'Security Code must be at least 5 characters',
                    remote: "Security Code Not match"     
                }                       
            },      
            submitHandler:function(form){    
                   var recaptcha = $("#g-recaptcha-response").val();
                if (recaptcha === "") {
                    event.preventDefault();
                    alert("Please check the recaptcha");
                    return false;
                }
                var Frmval = jQuery("#frm-contact").serialize();  
                jQuery("#btn-contact").attr("disabled","true").val('Processing... <i class="icon ion-ios-arrow-right"></i>');
                jQuery.ajax({
                    type: "POST",
                    dataType:"JSON",
                    url: base_url+"enquery_mail.php",
                    data:"action=forcoment&"+Frmval,
                    success:function(data){
                        var msg=eval(data); 
                        jQuery("#btn-booking").removeAttr("disabled").val('Send us Enquiry <i class="icon ion-ios-arrow-right"></i>');    
                        alert(msg.message);
                        jQuery("#frm-contact")[0].reset();
                    }               
                });
                return false;
            }
        });
    }

    // My Scripts
    // Default Reservation
    // if(jQuery('#default-form')[0]) {
    //     jQuery('#checkin').datepicker({
    //         changeMonth: true,
    //         changeYear: true,
    //         dateFormat: 'yy-mm-dd',
    //         minDate: '0',
    //         maxDate: '+2Y',
    //         onSelect: function(dateStr) {
    //             var d1 = jQuery(this).datepicker("getDate");
    //             d1.setDate(d1.getDate() + 1); // change to + 1 if necessary
    //             var d2 = jQuery(this).datepicker("getDate");
    //             d2.setDate(d2.getDate() + 180); // change to + 180 if necessary   
    //             jQuery("#checkout").datepicker("option", "minDate", d1);
    //             jQuery("#checkout").datepicker("option", "maxDate", d2);
    //             var start = jQuery("#checkin").datepicker("getDate");                
    //             var end   = jQuery("#checkout").datepicker("getDate");
    //             var days   = (end - start)/1000/60/60/24;
    //             if(end!=null)
    //                 var dd = jQuery(this).datepicker("getDate");
    //                 jQuery('#checkout').datepicker('setDate', d1);
    //         }
    //     });

    //     jQuery('#checkout').datepicker({
    //         changeMonth: true,
    //         changeYear: true,
    //         dateFormat: 'yy-mm-dd',
    //         minDate: jQuery("#checkin").datepicker("getDate"),
    //         maxDate: '+2Y'
    //     });

    //     jQuery("#default-form").validate({
    //         errorElement: 'span',
    //         errorClass: 'validate-has-error',
    //         rules: {
    //             check_in: { required: true, },
    //             check_out: { required: true }
    //         },
    //         messages:{              
    //             check_in: { required: '', },
    //             check_out: { required: '' }
    //         },      
    //         submitHandler:function(form){    
    //             form.submit();
    //             return false;
    //         }
    //     });
    // }

    // Nepal Hotel Reservation
    if(jQuery('#nepalhotel-form')[0]) {
        jQuery('#checkin').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            minDate: '0',
            maxDate: '+2Y',
            onSelect: function(dateStr) {
                var d1 = jQuery(this).datepicker("getDate");
                d1.setDate(d1.getDate() + 1); // change to + 1 if necessary
                var d2 = jQuery(this).datepicker("getDate");
                d2.setDate(d2.getDate() + 180); // change to + 180 if necessary   
                jQuery("#checkout").datepicker("option", "minDate", d1);
                jQuery("#checkout").datepicker("option", "maxDate", d2);
                var start = jQuery("#checkin").datepicker("getDate");                
                var end   = jQuery("#checkout").datepicker("getDate");
                var days   = (end - start)/1000/60/60/24;
                if(end!=null)
                    var dd = jQuery(this).datepicker("getDate");
                    jQuery('#checkout').datepicker('setDate', d1);
            }
        });

        jQuery('#checkout').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            minDate: jQuery("#checkin").datepicker("getDate"),
            maxDate: '+2Y'
        });

        jQuery("#nepalhotel-form").validate({
            errorElement: 'span',
            errorClass: 'validate-has-error',
            rules: {
                nepalhotel_check_in: { required: true, },
                nepalhotel_check_out: { required: true },
                nepalhotel_code: { required: true }
            },
            messages:{              
                nepalhotel_check_in: { required: '', },
                nepalhotel_check_out: { required: '' },
                nepalhotel_code: { required: '' }
            },      
            submitHandler:function(form){    
                form.submit();
                // window.open('about:blank', 'booking_popup', 'width=1000,height=800');
                return false;
            }
        });
    }

    // Fastbooking
    if(jQuery('#fastbooking-form')[0]) {
        jQuery('#checkin').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            minDate: '0',
            maxDate: '+2Y',
            onSelect: function(dateStr) {
                var d1 = jQuery(this).datepicker("getDate");
                d1.setDate(d1.getDate() + 1); // change to + 1 if necessary
                var d2 = jQuery(this).datepicker("getDate");
                d2.setDate(d2.getDate() + 180); // change to + 180 if necessary   
                jQuery("#checkout").datepicker("option", "minDate", d1);
                jQuery("#checkout").datepicker("option", "maxDate", d2);
                var start = jQuery("#checkin").datepicker("getDate");                
                var end   = jQuery("#checkout").datepicker("getDate");
                var days   = (end - start)/1000/60/60/24;
                if(end!=null)
                    var dd = jQuery(this).datepicker("getDate");
                    jQuery('#checkout').datepicker('setDate', d1);
            }
        });

        jQuery('#checkout').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            minDate: jQuery("#checkin").datepicker("getDate"),
            maxDate: '+2Y'
        });

        jQuery("#fastbooking-form").validate({
            errorElement: 'span',
            errorClass: 'validate-has-error',
            rules: {
                arrival: { required: true, },
                departure: { required: true },
                Clusternames: { required: true },
                Hotelnames: { required: true }
            },
            messages:{              
                arrival: { required: '', },
                departure: { required: '' },
                Clusternames: { required: '' },
                Hotelnames: { required: '' }
            },      
            submitHandler:function(form){
                hhotelDispoprice(form);
                return false;
            }
        });
    }

    // Booking Reservation
    if(jQuery('#booking-form')[0]) {
        jQuery('#checkin').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            minDate: '0',
            maxDate: '+2Y',
            onSelect: function(dateStr) {
                var d1 = jQuery(this).datepicker("getDate");
                d1.setDate(d1.getDate() + 1); // change to + 1 if necessary
                var d2 = jQuery(this).datepicker("getDate");
                d2.setDate(d2.getDate() + 180); // change to + 180 if necessary   
                jQuery("#checkout").datepicker("option", "minDate", d1);
                jQuery("#checkout").datepicker("option", "maxDate", d2);
                var start = jQuery("#checkin").datepicker("getDate");                
                var end   = jQuery("#checkout").datepicker("getDate");
                var days   = (end - start)/1000/60/60/24;
                if(end!=null)
                    var dd = jQuery(this).datepicker("getDate");
                    jQuery('#checkout').datepicker('setDate', d1);
            },
            onClose: function(dateText, inst) {
                if($('.checkin-monthday')[0]) {
                    var cout = jQuery('#checkout').val();
                    jQuery('.checkout-monthday').val(cout.split('-')[2]);
                    jQuery('.checkout-year-month').val(cout.split('-')[0]+'-'+cout.split('-')[1]);
                    jQuery('.checkin-monthday').val(dateText.split('-')[2]);
                    jQuery('.checkin-year-month').val(dateText.split('-')[0]+'-'+dateText.split('-')[1]);
                }
            }
        });

        jQuery('#checkout').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            minDate: jQuery("#checkin").datepicker("getDate"),
            maxDate: '+2Y',
            onClose: function(dateText, inst) {
                if($('.checkout-monthday')[0]) {
                    $('.checkout-monthday').val(dateText.split('-')[2]);
                    $('.checkout-year-month').val(dateText.split('-')[0]+'-'+dateText.split('-')[1]);
                }
            }
        });

        jQuery("#booking-form").validate({
            errorElement: 'span',
            errorClass: 'validate-has-error',
            rules: {
                check_in: { required: true, },
                check_out: { required: true },
                hotel_id: { required: true }
            },
            messages:{              
                check_in: { required: '', },
                check_out: { required: '' },
                hotel_id: { required: '' }
            },      
            submitHandler:function(form){    
                form.submit();
                window.open('about:blank', 'booking_popup', 'width=1000,height=800');
                return false;
            }
        });
    }


    // Room reservation
    if(jQuery('#roombooking')[0]) {        

        jQuery('#checkin').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            minDate: '0',
            maxDate: '+2Y',
            onSelect: function(dateStr) {
                var d1 = jQuery(this).datepicker("getDate");
                d1.setDate(d1.getDate() + 1); // change to + 1 if necessary
                var d2 = jQuery(this).datepicker("getDate");
                d2.setDate(d2.getDate() + 180); // change to + 180 if necessary   
                jQuery("#checkout").datepicker("option", "minDate", d1);
                jQuery("#checkout").datepicker("option", "maxDate", d2);
                var start = jQuery("#checkin").datepicker("getDate");                
                var end   = jQuery("#checkout").datepicker("getDate");
                var days   = (end - start)/1000/60/60/24;
                if(end!=null)
                    var dd = jQuery(this).datepicker("getDate");
                    jQuery('#checkout').datepicker('setDate', d1);
            }
        });

        jQuery('#checkout').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            minDate: jQuery("#checkin").datepicker("getDate"),
            maxDate: '+2Y'
        });
        
        jQuery("#roombooking")[0].reset();
        jQuery("#roombooking").validate({
            errorElement: 'span',
            errorClass: 'validate-has-error',
            rules: {
                fullname: { required: true },
                mailaddress: { required: true, email: true },
                phone: { required: true },
                address: { required: true },
                country: { required: true },
                checkin: { required: true, date:true },
                checkout: { required: true, date:true },               
                userstring:{
                    required:true,
                    minlength:5,
                    remote: {
                        url: base_url+"captcha/checkcaptcha.php",
                        type: "post"
                    }
                }
            },
            messages:{  
                fullname: { required: "Enter your Fullname", },            
                mailaddress: { required: "Enter your email address", email: "Enter a VALID email address" },
                phone: { required: "Enter your Phone No." },
                address: { required: "Enter your Address" },
                country: { required: "Choose your Country" },
                checkin: { required: "Choose your Check-In Date", date:"Date Format Not Match (yy-mm-dd)" },
                checkout: { required: "Choose your Check-Out Date", date:"Date Format Not Match (yy-mm-dd)" },               
                userstring:{
                    required: 'Enter Security Code',
                    minlength: 'Security Code must be at least 5 characters',
                    remote: "Security Code Not match"     
                }                       
            },      
            submitHandler:function(form){               
                var Frmval = jQuery("#roombooking").serialize();  
                jQuery("#btn-booking").attr("disabled","true").val('Processing...');
                jQuery.ajax({
                    type: "POST",
                    dataType:"JSON",
                    url: base_url+"booking_action.php",
                    data:"action=forbooking&"+Frmval,
                    success:function(data){
                        var msg=eval(data); 
                        jQuery("#btn-booking").removeAttr("disabled").val('Send');    
                        alert(msg.message);
                        jQuery("#roombooking")[0].reset();
                    }               
                });
                return false;
            }
        });
    }
    
}(jQuery);

// captcha
function updateCaptcha(c){
    var d = new Date();
    c.src= base_url+'captcha/imagebuilder.php?rand='+d.getTime();
}

 $('.owl-slider').owlCarousel({
    items: 1,
    autoplay:true,
    autoplayTimeout:5000,
    dots: false,
    nav:false,
  });
  
  
//=== Switcher panal slide function	=====================//

	jQuery(window).load(function(){
		jQuery('.styleswitcher').animate({
					'right': '-212px'
				});
		jQuery('.switch-btn').addClass('closed');
	});
	
	jQuery(document).ready(function () {		
		jQuery('.switch-btn').on('click', function () {	
			if (jQuery(this).hasClass('open')) {
				jQuery(this).addClass('closed');
				jQuery(this).removeClass('open');
				jQuery('.styleswitcher').animate({
					'right': '-212px'
				});
			} else {
				if (jQuery(this).hasClass('closed')) {
				jQuery(this).addClass('open');
				jQuery(this).removeClass('closed');
				jQuery('.styleswitcher').animate({
					'right': '0'
				});
				}
			}	
		});
	});
// 	

 $(document).ready(function() {
        var checkinDate = $('#checkin');
        var checkoutDate = $('#checkout');
  
        // Initialize the datepickers
        checkinDate.datepicker({
          format: 'yyyy-mm-dd',
          startDate: 'today',
          autoclose: true,
          todayHighlight: true
        }).datepicker('setDate', 'today');
  
        checkoutDate.datepicker({
          format: 'yyyy-mm-dd',   
                 startDate: 'tomorrow',
          autoclose: true,
          todayHighlight: true
        }).datepicker('setDate', '+1d');
  
        // Set the minimum date for checkout based on the selected check-in date
        checkinDate.on('changeDate', function() {
          var selectedDate = new Date(checkinDate.datepicker('getDate'));
          selectedDate.setDate(selectedDate.getDate() + 1); // Add one day
          checkoutDate.datepicker('setStartDate', selectedDate);
  
          // Check if the current selected date in checkout is before the new minimum date
          var checkoutSelectedDate = new Date(checkoutDate.datepicker('getDate'));
          if (checkoutSelectedDate < selectedDate) {
            checkoutDate.datepicker('setDate', selectedDate);
          }
        });
      });


//=== Switcher panal slide function END	=====================//