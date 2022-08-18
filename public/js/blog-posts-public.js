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
	})
	 
})( jQuery );


 