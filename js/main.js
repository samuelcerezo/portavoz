jQuery(function($) {
	var ajax = false;
	$('.hamburger').on('click', function() {
		$(this).toggleClass('active');
		$('.main-menu').toggleClass('active');
	});
	$('.cookies-link').click(function() {
		$('#cmplz-manage-consent button').click();
	});
	$('#primary, .elementor-location-single, .elementor-location-archive').css('padding-top', $('.elementor-location-header').outerHeight());
	$('form button[type="submit"]').on('click', function(e) {
		var t = $(this),
			f = t.closest('form'),
			g = 0,
			m = [main.messages.errors];
		f.children('.response').remove();
		t.find('.invalid').removeClass('invalid');
		f.find('.elementor-field-required').each(function() {
			var c = $(this);
			c.find('input').each(function() {
				var x = $(this)
				switch (x.attr('type')) {
					case 'text':
						if (x.val() == '') {
							g++;
							m.push(main.messages.fill);
							c.addClass('invalid');
						}
						break;
					case 'radio':
						if (c.find('input:checked').length == 0) {
							g++;
							m.push(main.messages.fill);
							c.addClass('invalid');
						}
						break;
					case 'checkbox':
						if (c.find('input:checked').length == 0) {
							g++;
							if (c.hasClass('elementor-field-type-acceptance')) {
								m.push(main.messages.legal);
							} else {
								m.push(main.messages.fill);
							}
							c.addClass('invalid');
						}
						break;
					case 'email':
						if (x.val() == '') {
							g++;
							m.push(main.messages.fill);
							c.addClass('invalid');
						} else if (check_email(x.val()) == false) {
							g++;
							m.push(main.messages.email);
							c.addClass('invalid');
						}
						break;
					case 'date':
						if (x.val() == '') {
							g++;
							m.push(main.messages.fill);
							c.addClass('invalid');
						}
						break;
				}
			});
			c.find('select').each(function() {
				var x = $(this);
				if (x.val() == null) {
					g++;
					m.push(main.messages.fill);
					c.addClass('invalid');
				}
			});
			c.find('textarea').each(function() {
				var x = $(this);
				if (x.val() == '') {
					g++;
					m.push(main.messages.fill);
					c.addClass('invalid');
				}
			});
		});
		if (g > 0) {
			e.preventDefault();
			var n = [];
			$.each(m, function(i, el) {
				if($.inArray(el, n) === -1) n.push(el);
			});
			if (f.find('.elementor-message').length > 0) {
				f.find('.elementor-message').html(n.join(' '));
			} else {
				$('<div class="elementor-message">'+n.join(' ')+'</div>').appendTo(f);
			}
		}
	});
	$('input, select, label, textarea').on('click focus', function() {
		$(this).closest('.elementor-field-required').removeClass('invalid');
	});
});

jQuery(window).on('scroll load', function() {
	if (jQuery(document).scrollTop() > 200) {
		jQuery('body').addClass('scrolled');
	} else {
		jQuery('body').removeClass('scrolled');
	}
});
