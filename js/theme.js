jQuery(function($) {
	$(window).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addFilter('frontend/handlers/menu_anchor/scroll_top_distance', function(scrollTop) {
			return scrollTop-$('.elementor-location-header').height();
		});
	});
})

function check_email(email) {
	var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
	return re.test(email);
}