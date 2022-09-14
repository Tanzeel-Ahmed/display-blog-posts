(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	
// custom ajax for load more posts
$(document).ready(function(){
		if($('.load-more-btn').data('totalpages') == '1'){
			$('.load-more-btn').remove();
		}
		var currentPage = 1;
		$('.load-more-btn').click(function(){
			var total_pages = $('.load-more-btn').data('totalpages');
		
			currentPage	++;
			var data = {
				'action'    : 'wpb_load_more_posts',
				'paged'     : currentPage,
			};
	
			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			jQuery.post(jquery_object.ajaxurl, data, function(response) {
				if(total_pages == currentPage){
					$('.load-more-btn').remove();
				}
				console.log(response);
				
				$('.wpb-sub-container').append(response);
			});
		})

		// custom js for category base post
	$(".investors-content .tab-pane").each(function(){
		$(this).hide();
		$('.investors-content .tab-pane:first-child()').show();
	});
	$('.nav-tabs li >span').on( "click", function(e) {
		e.preventDefault();
		var id = $(this).attr('data-href');
		$(".investors-content .tab-pane").each(function(){
			$(this).hide();
			if($(this).attr('id') == id) {
				$(this).show();
			}
		});
	});
	// Display All Posts When open blog page 
	// if ($(".all-nav-tabs-list")) {
	// 	$(".wpb-sub-container").each(function(){
	// 		$(this).show();
	// 	});
	// }

	// Displayed All Posts When Click on All nav tab list
	$('.all-nav-tabs-list').on( "click", function(e) {
		e.preventDefault();
		$(".wpb-all-content-container").each(function(){
			$(this).show();
			$('.investors-content .tab-pane').remove();
		});
	});
	
	// Display Posts When Click on specific category tab
	$('.single-nav-tabs-list').on( "click", function(e) {
		e.preventDefault();
		$(".wpb-all-content-container").each(function(){
			$(this).hide();
		});
	});
	
	// Add active class on All nav tab list
	$(".all-nav-tabs-list").addClass("active");
	$(".all-nav-tabs-list").click(function () {
		if($(".all-nav-tabs-list").hasClass("active")){
			$(".all-nav-tabs-list").removeClass("active");
			$(".single-nav-tabs-list").removeClass("active");
		}
			$(this).addClass("active");
	});
	// Added active class when click on specific nav tab list
	$(".single-nav-tabs-list").click(function () {
		if($(".single-nav-tabs-list").hasClass("active")){
			$(".single-nav-tabs-list").removeClass("active");
			$(".all-nav-tabs-list").removeClass("active");
		}
			$(this).addClass("active");
	});
	
})

})( jQuery );


 