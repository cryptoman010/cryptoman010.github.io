/*Copyright (c) 2017 */
(function ($) {
	"use strict";
	var Zoo = {
		initialised: false,
		version: 1.0,
		mobile: false,
		init: function () {
			if(!this.initialised) {
				this.initialised = true;
			} else {
				return;
			}
			/*-------------- Zoo Functions Calling ----------*/
			this.Banner_Slider();
			this.Ticket_Slider();
			this.Image_Grid();
			this.Popup();
			this.Image_popup();
			this.Video_slider();
			this.Client_slider();
			this.Gallery();
			this.Ajax();
			this.Wow();
		},
		/*-------------- Zoo Functions definition ------------*/
		Banner_Slider: function () {
			// slider section
			if($('.zoo_slider_wrapper .owl-carousel').length > 0){		
			    var owl = $('.zoo_slider_wrapper .owl-carousel');

				// Carousel initialization
				owl.owlCarousel({
				  loop:true,
				  margin:0,
				  autoplay:true,
				  mouseDrag: false,
				  nav:false,
				  dots:false,
				  items:1
				});
				// add animate.css class(es) to the elements to be animated
				function setAnimation ( _elem, _InOut ) {
				// Store all animationend event name in a string.
				// cf animate.css documentation
				var animationEndEvent = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
				_elem.each ( function () {
				  var $elem = $(this);
				  var $animationType = 'animated ' + $elem.data( 'animation-' + _InOut );
				  $elem.addClass($animationType).one(animationEndEvent, function () {
				    $elem.removeClass($animationType); // remove animate.css Class at the end of the animations
				  });
				});
				}
				// Fired before current slide change
				owl.on('change.owl.carousel', function(event) {
				  var $currentItem = $('.owl-item', owl).eq(event.item.index);
				  var $elemsToanim = $currentItem.find("[data-animation-out]");
				  setAnimation ($elemsToanim, 'out');
				});
				// Fired after current slide has been changed
				owl.on('changed.owl.carousel', function(event) {
				  var $currentItem = $('.owl-item', owl).eq(event.item.index);
				  var $elemsToanim = $currentItem.find("[data-animation-in]");
				  setAnimation ($elemsToanim, 'in');
				})
			}
  		},
  		Ticket_Slider: function(){
  			if($('.zoo_ticket_wrapper .owl-carousel').length > 0){
				$('.zoo_ticket_wrapper .owl-carousel').owlCarousel({
				    loop:true,
				    margin:10,
				    nav:false,
				    autoplay:false,
				    mouseDrag: false,
				    responsive:{
				        0:{
				            items:1
				        },
				        992:{
				            items:2
				        },
				        1000:{
				            items:2
				        }
				    }
				});
			}
		},
		Image_Grid: function(){
			// for masonary
			if($('#grid').length > 0){
				new AnimOnScroll(document.getElementById( 'grid' ), {
				    minDuration : 0.4,
				    maxDuration : 0.7,
				    viewportFactor : 0.2
				});
			}
		},
		Popup: function(){
			if($('.popup-video').length > 0){
				$('.popup-video').magnificPopup({
				    delegate: 'a',
				    type: 'iframe',
				    tLoading: 'Loading image #%curr%...',
				    mainClass: 'mfp-with-zoom',
				    gallery: {
				      enabled: true,
				      navigateByImgClick: true,
				      preload: [0,1] // Will preload 0 - before current, and 1 after the current image
				    },
				    image: {
				      tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
				      titleSrc: function(item) {
				        return item.el.attr('title') + '<small></small>';
				      }
				    }
				});
			}
		},
		Image_popup: function(){
			if($('.popup-gallery').length > 0){
		       $('.popup-gallery').magnificPopup({
				    delegate: 'a',
				    type: 'image',
				    tLoading: 'Loading image #%curr%...',
				    mainClass: 'mfp-with-zoom',
				    gallery: {
				      enabled: true,
				      navigateByImgClick: true,
				      preload: [0,1] // Will preload 0 - before current, and 1 after the current image
				    }
				});
		   }
		},
		Video_slider: function(){
			if($('.zoo_video_wrapper .owl-carousel').length > 0){
				$('.zoo_video_wrapper .owl-carousel').owlCarousel({
				    loop:true,
				    margin:10,
				    nav:false,
				    autoplay:false,
				    mouseDrag: false,
				    responsive:{
				        0:{
				            items:1
				        },
				        600:{
				            items:2
				        },
				        1000:{
				            items:4
				        }
				    }
				})
			}
		},
		Client_slider: function(){
			if($('.zoo_client_wrapper .owl-carousel').length > 0){
				$('.zoo_client_wrapper .owl-carousel').owlCarousel({
				    loop:true,
				    margin:10,
				    nav:false,
				    dots:false,
				    autoplay:true,
				    mouseDrag: false,
				    responsive:{
				        0:{
				            items:1
				        },
				        600:{
				            items:2
				        },
				        1000:{
				            items:4
				        }
				    }
				})
			}
		},
		Gallery: function(){
			if($('#photostack-3').length > 0){
				new Photostack( document.getElementById( 'photostack-3' ), {
				callback : function( item ) {
					//console.log(item)
				}
				} );
			}
		},
		Ajax: function(){
				function checkRequire(formId , targetResp){
				targetResp.html('');
				var email = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/;
				var url = /(http|ftp|https):\/\/[\w-]+(\.[\w-]+)+([\w.,@?^=%&amp;:\/~+#-]*[\w@?^=%&amp;\/~+#-])?/;
				var image = /\.(jpe?g|gif|png|PNG|JPE?G)$/;
				var mobile = /^[\s()+-]*([0-9][\s()+-]*){6,20}$/;
				var facebook = /^(https?:\/\/)?(www\.)?facebook.com\/[a-zA-Z0-9(\.\?)?]/;
				var twitter = /^(https?:\/\/)?(www\.)?twitter.com\/[a-zA-Z0-9(\.\?)?]/;
				var google_plus = /^(https?:\/\/)?(www\.)?plus.google.com\/[a-zA-Z0-9(\.\?)?]/;
				var check = 0;
				$('#er_msg').remove();
				var target = (typeof formId == 'object')? $(formId):$('#'+formId);
				target.find('input , textarea , select').each(function(){
					if($(this).hasClass('require')){
						if($(this).val().trim() == ''){
							check = 1;
							$(this).focus();
							targetResp.html('You missed out some fields.');
							$(this).addClass('error');
							return false;
						}else{
							$(this).removeClass('error');
						}
					}
					if($(this).val().trim() != ''){
						var valid = $(this).attr('data-valid');
						if(typeof valid != 'undefined'){
							if(!eval(valid).test($(this).val().trim())){
								$(this).addClass('error');
								$(this).focus();
								check = 1;
								targetResp.html($(this).attr('data-error'));
								return false;
							}else{
								$(this).removeClass('error');
							}
						}
					}
				});
				return check;
			}
			$(".submitForm").on("click", function() {
				var _this = $(this);
				var targetForm = _this.closest('form');
				var errroTarget = targetForm.find('.response');
				var check = checkRequire(targetForm , errroTarget);
				if(check == 0){
					var formDetail = new FormData(targetForm[0]);
					formDetail.append('form_type' , _this.attr('data-form-type'));
					$.ajax({
						method : 'post',
						url : './assets/ajax.php',
						data:formDetail,
						cache:false,
						contentType: false,
						processData: false
					}).done(function(resp){
						if(resp == 1){
							targetForm.find('input').val('');
							targetForm.find('textarea').val('');
							errroTarget.html('<p style="color:green;">Mail has been sent successfully.</p>');
						}else{
							errroTarget.html('<p style="color:red;">Something went wrong please try again latter.</p>');
						}
					});
				}
			});
		},
		Wow: function(){
			new WOW().init();
		}
	};
Zoo.init();
// Preloader 
	jQuery(window).on('load', function() {
		jQuery("#status").fadeOut(1800);
		jQuery("#preloader").delay(1000).fadeOut("slow");
	});
/*-------- Fixed Menu -------*/
$(window).scroll(function(){
		var window_top = $(window).scrollTop() + 1; 
		if (window_top > 400) {
			$('.zoo_navigation_wrapper').addClass('menu_fixed animated fadeInDown');
		} else {
			$('.zoo_navigation_wrapper').removeClass('menu_fixed animated fadeInDown');
		}
	});
/*-------- scroll top -------*/
$(window).scroll(function() {
    if ($(this).scrollTop() >= 500) {
        $('.go_top').fadeIn(200);
    } else {
        $('.go_top').fadeOut(200);
    }
});
$('.go_top').click(function() {
    $('body,html').animate({
        scrollTop : 0
    }, 500);
});
}(jQuery));