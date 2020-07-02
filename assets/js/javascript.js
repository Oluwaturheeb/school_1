(function ($) {
	// ajaxsetup 

	$.ajaxSetup({
		type: 'post',
		url: 'request.php'
	});

	//loader

	var load = "<div class='loading'></div>";

	//search

	$('.search-btn').click(function (e) {
		e.preventDefault();

		$('.hidden-sm-up').hide();
		$('#search-field').show();
	});

	//bar click

	$('.bar-container').click(function (x) {
		if ($(this).hasClass('open')) {
			$('.links').css({
				'margin-right': '-50rem'
			});
			$('.global').hide();
			$(this).removeClass('open');
		} else {
			$('.links').css({
				'margin-right': '0'
			});
			$(this).addClass('open');
			$('.global').show();
		}
	});

	$('.global').click(() => {
		$('.links').css({
			'margin-right': '-50rem'
		});
		$('.global').hide();
		$('.bar-container').removeClass('open');
	});

	$('.toggler-menu').click(() => {
		$('.menu').css({
			display: 'grid'
		});
	});

	$('.menu .close').click(() => {
		$('.menu').css({
			display: 'none'
		});
	});

	$('.menu a').click(function (e) {
		var id = $(this).attr('id');
		$('.links').css({
			'margin-right': '-50rem'
		});
		$('.global').hide();
		$(this).addClass('active').siblings('a').removeClass('active');
		if ($(window).width() < 700) {
			$('.menu').css({
				display: 'none'
			});
		}

		if (!v.empty(id, 'string')) {
			e.preventDefault();
			$('.options .' + id).show().siblings().hide();
		}
	});

	

	// main page swich 

	if (true/*$(window.location).attr('pathname').indexOf('index') != -1*/) {
		var rev = $('.reviews .rev-each');
		var r = $('.reviews')
		var h = '<div class="header my-3">Reviews</div>';
		r.empty().html(h).append(rev[0]);
		
		if (rev.length > 1) {
			var i = 1;
			setInterval(() => {
				if (rev.length == i) {
					r.html(h).append(rev[0]);
					i = 0;
				} else {
					r.html(h).append(rev[i]);
				}
				i++;
			}, 5000);
		}
	}


	

	//step_2 and fpass

	$('#fpass').submit(function (e) {
		e.preventDefault();

		v.validator({
			'#fpass-email': {
				'require': true,
				'field': 'email',
				'email': true,
				'wordcount': 1
			}
		});
		var info = $('.fpass-info');
		if (!v.check()) {
			info.html(v.thrower());
		} else {
			$.ajax({
				data: $(this).serialize(),
				beforeSend: () => {
					info.html(load + 'Connecting please wait...');
				},
				success: e => {
					if (e == 'ok') {
						info.html('Email verified!');
						setTimeout(() => {
							$(this).parent('div').hide().siblings().show();
						}, 2000);
					} else {
						info.html(e);
					}
				}
			});
		}
	});

	$('#chpwd').submit(function (e) {
		e.preventDefault();

		v.validator({
			'#password': {
				'require': true,
				'field': 'password',
				'wordcount': 1,
				'min': 8,
				'max': 64
			},
			'#v-pass': {
				'require': true,
				'field': 'verify password',
				'wordcount': 1,
				'min': 8,
				'max': 64,
				'match': '#password'
			}
		});

		if (!v.check() || v.thrower()) {
			$('.chpwd-info').html(v.thrower());
		} else {
			$.ajax({
				data: $(this).serialize(),
				beforeSend: () => {
					$('.chpwd-info').html(load + 'Connecting, please wait');
				},
				success: (e) => {
					if (e == 'ok') {
						$('.chpwd-info').html('Password created!');
						if ($(this).hasClass('fpass')) {
							v.redirect('admin.php');
						} else {
							setTimeout(() => {
								$('.item-b').show().siblings().hide();
							}, 2000);
						}
					} else {
						$('.chpwd-info').html(e);
					}
				}
			});
		}
	});

	//adding student 

	

	//login 

	$('#auth').submit(function (e) {
		e.preventDefault();

		v.validator({
			'#email': {
				'require': true,
				'field': 'email',
			},
			'#password': {
				'require': true,
				'field': 'password',
				'wordcount': 1,
				'min': 8,
				'max': 30
			}
		});

		if (!v.check()) {
			$('.admin-info').html(v.thrower());
		} else {
			$.ajax({
				data: $(this).serialize(),
				dataType: 'json',
				beforeSend: () => {
					$('.admin-info').html(load + ' connecting to the server!');
				},
				success: e => {
					if (e.stat == 'ok') {
						$('.admin-info').html('Welcome!');
						if (e.res === 1) {
							v.redirect('admin');
						} else if (e.res === 2) {
							v.redirect('student');
						}
					} else {
						$('.admin-info').html('Credentials does not match any account, try again!');
					}
				}
			});
		}
	});

	$('#review-form').submit(function (e) {
		e.preventDefault();
		var info = $('.review-info');

		v.validator({
			'#fullname': {
				'require': true,
				'wordcount': 2,
				'field': 'Fullname',
				'error': ' field cannot be empty!'
			},
			'#email': {
				'require': true,
				'email': true,
				'field': 'Email',
				'error': ' field cannot be empty'
			},
			'#rating': {
				'require': true,
				'number': true,
				'field': 'Rating',
				'error': ' field cannot be empty'
			},
			'#review': {
				'require': true,
				'wordcount': 4,
				'field': 'review',
				'error': ' field cannot be empty!'
			}
		});

		if (!v.check()) {
			info.html(v.thrower());
		} else {
			$.ajax({
				data: $(this).serialize(),
				beforeSend: () => {
					info.html(load + 'Please wait...');
				},
				success: e => {
					if (e == 'ok') {
						info.html('Review submitted successfully!');
						v.redirect('index');
					} else {
						info.html(e);
					}
				}
			});
		}
	});

}(jQuery));