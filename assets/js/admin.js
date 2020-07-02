(function ($) {
	var load = "<div class='loading'></div>";
	
    $('#event').change(() => {
		var e = v.getInput('#event');
		var adm = '<div class="Admission">\
							<div class="form-group">\
								<label for="date">Start</label>\
								<input type="date" id="start" name="start[]" class="form-control date">\
							</div>\
							<div class="form-group">\
								<label for="end">End</label>\
								<input type="date" id="end" name="start[]" class="form-control date">\
							</div>\
						</div>';
		var job = '<div class="Vacancy">\
							<div class="form-group">\
								<label for="job-type">Job type</label>\
								<input name="job" placeholder="Enter job type..." id="job-type" class="form-control">\
							</div>\
						</div>';
		var exam = '<div class="Exams">\
							<div class="form-group">\
								<label for="ex-session">Select session</label>\
								<select name="session" id="ex-session" class="form-control">\
									<option value="">Select session</option>\
									<option>1st term</option>\
									<option>2nd term</option>\
									<option>3rd term</option>\
								</select>\
							</div>\
						</div>';
		if (!v.empty(e, 'c')) {
			$('.' + e).show().siblings().hide();
			$('.event-info').html('');

			if (e == "Admission") {
				var to = adm;
			} else if (e == "Exams") {
				to = exam;
			} else if (e == 'Vacancy') {
				to = job;
			}
			$('.items-to-add').html(to);
		} else {
			$('#event').parent().next('div').children().hide();
		}
	});
    
    $('#create-event').submit(function (e) {
		e.preventDefault();
		var info = $('.event-info');
		v.validator({
			'#event': {
				'require': true,
				'error': 'Select an event!',
				'field': 'event',
			}
		});

		if (!v.check()) {
			info.html(v.thrower());
		} else {
			var input = v.getInput('#event');
			if (input == 'Vacancy') {
				var i = '#job-type';
			} else if (input == 'Admission') {
				i = '.date'
			} else if (input == 'Exams') {
				i = 'ex-session';
			}

			v.validator({
                [i]: {
					'multiple': true,
					'error': ' cannot be empty',
					'field': 'date'
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
							info.html('Event created');
							v.redirect();
						} else {
							info.html(e);
						}
					}
				});
			}
		}
	});

	$('.delete-event').click(function (e) {
		var cond = confirm("You are about to delete this event");
		var info = $('.event-del-info');
		if (cond) {
			$.ajax({
				data: 'event-id=' + $(this).attr('id'),
				beforeSend: () => {
					info.html(load + 'Please wait...');
				},
				success: e => {
					if (e == 'ok') {
						info.html('Event deleted!');
						v.redirect();
					} else {
						info.html(e);
					}
				}
			})
		}
	})

	$('#set').keyup(() => {
		$('.form-question').empty();
		var num = v.getInput('#set');
		var c = 1;
		for (var i = 0; i < num; i++) {
			$('.form-question').append('<div class="h4 my-3">Question ' + c + '</div><div class="form-group">\
						<label for="question-' + c + '">Question</label>\
						<input type="text" name="question[]" id="question-' + c + '" class="form-control" placeholder="Enter question..." autocomplete="off">\
					</div>\
					<div class="form-group">\
						<label for="ans-' + c + '">Answer</label>\
						<select name="ans[]" id="ans-' + c + '" class="form-control">\
							<option value="">Select answer</option>\
							<option>A</option>\
							<option>B</option>\
							<option>C</option>\
							<option>D</option>\
						</select>\
					</div></div>\
					<div class="form-group">\
						<label for="opt-a-' + c + '">Option a</label>\
						<input type="text" name="opt-a[]" id="opt-a-' + c + '" class="form-control" placeholder="Enter option..." autocomplete="off">\
					</div>\
					<div class="form-group">\
						<label for="opt-b-' + c + '">Option b</label>\
						<input type="text" name="opt-b[]" id="opt-b-' + c + '" class="form-control" autocomplete="off" placeholder="Enter option...">\
					</div>\
					<div class="form-group">\
						<label for="opt-c-' + i + '">Option c</label>\
						<input type="text" name="opt-c[]" id="opt-c-' + c + '" class="form-control" placeholder="Enter option..." autocomplete="off">\
					</div>\
					<div class="form-group">\
						<label for="opt-d-' + c + '">Option d</label>\
						<input type="text" name="opt-d[]" id="opt-d-' + c + '" class="form-control" placeholder="Enter option..." autocomplete="off">\
					</div><hr/>');
			if (c == num) {
				$('.form-question').append('<div class="form-group"><div class="question-info"></div><button class="btn btn-success">Submit</button></div>');
			}
			c++;
		}
	});

	$('#exam-sub').change(() => {
		v.uniqueData(['exam', 'subject', '#exam-sub'], '.subject-info', 'In this case, this will be an update to subject.');
	});

	$('.form-question').submit(function (e) {
		e.preventDefault();

		v.validator({
			'#exam-sub': {
				'require': true,
				'error': 'Kindly select a subject',
				'field': 'subject'
			}
		});
		var info = $('.question-info');

		if (!v.check()) {
			info.html(v.thrower());
		} else {
			$.ajax({
				data: $(this).serialize() + '&subject=' + v.getInput('#exam-sub'),
				beforeSend: () => {
					info.html(load + ' connecting to the server...');
				},
				success: e => {
					info.html(e);
					if (e == 'ok') {
						v.redirect();
					} else {
						info.html(e)
					}
				}
			});
		}
	});

	$('#exam-reset').submit((e) => {
		e.preventDefault();

		var info = $('.reset-info');
		v.validator({
			'#subject': {
				field: 'subject',
				'require': true,
				'error': 'Kindly select a subject'
			}
		});

		if (!v.check()) {
			info.html(v.thrower());
		} else {
			$.ajax({
				data: $('#exam-reset').serialize(),
				beforeSend: () => {
					info.html(load + ' connecting to the server...');
				},
				success: e => {
					if (e == 'ok') {
						info.html('Exam has been reset successfully!');
					} else {
						info.html('Unknown error, try again!');
					}
				}
			});
		}
	});

	//create teacher

	$('#create-teacher').submit(function (e) {
		e.preventDefault();

		v.validator({
			'#pre': {
				'require': true,
				'field': 'prefix'
			},
			'#first': {
				'require': true,
				'wordcount': 1,
				'field': 'firstname'
			},
			'#last': {
				'require': true,
				'field': 'lastname',
				'wordcount': 1
			},
			'#email': {
				'require': true,
				'email': true,
				'field': 'email',
				'wordcount': 1
			},
			'#status': {
				'require': true,
				'field': 'status',
				'wordcount': 1
			},
			'#dob': {
				'require': true,
				'field': 'date of birth'
			},
			'#hadd': {
				'require': true,
				'wordcount': 5,
				'field': 'address'
			},
			'#cls': {
				'require': true,
				'field': 'clas'
			}
		});

		if (!v.check()) {
			$('.teacher-info').html(v.thrower());
		} else {
			$.ajax({
				data: $(this).serialize(),
				beforeSend: () => {
					$('.teacher-info').html(load + 'Connecting, please wait!');
				},
				success: e => {
					if (e == 'ok') {
						$('.teacher-info').html('Teacher added successfully!');
						v.redirect();
					} else {
						$('.teacher-info').html(e);
					}

				}
			});
		}
	});

	$('#create-subject').submit(function (e) {
		e.preventDefault();

		v.validator({
			'#name': {
				'require': true
			}
		});
		var info = $('.subject-info');

		if (!v.check()) {
			info.html(v.thrower());
		} else {
			$.ajax({
				data: $(this).serialize(),
				beforeSend: () => {
					info.html(load + 'Connecting to the server...');
				},
				success: e => {
					alert(e)
					if (e == 'ok') {
						info.html('Subject added successfully!');
						v.redirect();
					} else {
						info.html(e);
					}
				}
			});
		}
	});

	//step_2

	$('#img').submit(function (x) {
		x.preventDefault();

		v.validator({
			'.file-input': {
				'file': true,
				'fileMax': 1,
				'error': 'error!',
				'field': 'Unknow '
			}
		});
		var info = $('.img-info');

		if (!v.check() || v.thrower()) {
			info.html(v.thrower());
		} else {
			$.ajax({
				data: new FormData(this),
				beforeSend: () => {
					info.html(load + 'Sending file to the server...');
				},
				success: (e) => {
					
					if (e == 'ok') {
						if ($('.file-input').hasClass('student')) {
							info.html('Picture uploaded successfully!');
							v.redirect();
						} else {
							v.redirect();
						}
					} else {
						info.html(e);
					}
				},
				cache: false,
				processData: false,
				contentType: false
			});
		}
	});
	
	$('#create-student').submit(function (e) {
		e.preventDefault();

		v.validator({
			'#first': {
				'require': true,
				'field': 'firstname',
				'wordcount': 1,
			},
			'#last': {
				'require': true,
				'wordcount': 1,
				'field': 'lastname'
			},
			'#gender': {
				'require': true,
				'wordcount': 1,
				'field': 'gender'
			},
			'#dob': {
				'require': true,
				'field': 'date of birth'
			},
			'#hadd': {
				'require': true,
				'wordcount': 4,
				'field': 'first'
			},
			'#pre': {
				'require': true,
				'wordcount': 1,
				'field': 'prefix'
			},
			'#p-first': {
				'require': true,
				'wordcount': 1,
				'field': 'firstname'
			},
			'#p-last': {
				'require': true,
				'wordcount': 1,
				'field': 'lastname'
			},
			'#email': {
				'require': true,
				'email': true,
				'wordcount': 1,
				'field': 'email'
			}
		});
		info = $('.student-info');

		if (!v.check()) {
			info.html(v.thrower());
		} else {
			$.ajax({
				data: $(this).serialize(),
				beforeSend: () => {
					info.html(load + 'Connecting to the server...');
				},
				success: e => {
					if (e == 'ok') {
						info.html('Data submitted successfully!');
						setTimeout(() => {
							$(this).next('form').show().siblings().hide();
						}, 2000);
					} else {
						info.html(e);
					}
				}
			});
		}
	});
	
	$('a.remove-teacher').click(function(e) {
		e.preventDefault()
		
		var c = confirm('Are you sure to remove this teacher');
		
		if(c) {
			if(v.empty($(this).attr('id'), 'ccc')) {
				alert('Cannot delete delete this data, its been compromised!');
			} else {
				$.ajax({
					data: 'remove-teacher=' + $(this).attr('id'),
					success: e => {
						if(e == 'ok') {
							alert('Teacher removed successfully!');
							v.redirect();
						} else {
							alert(e);
						}
					}
				});
			}
		}
	});
	
	$('.remove-student').click(function(e) {
		e.preventDefault()
		
		var c = confirm('Are you sure to remove this student!');
		
		if(c) {
			if(v.empty($(this).attr('id'), true)) {
				alert('Cannot delete delete this data, its been compromised!');
			} else {
				$.ajax({
					data: 'remove-student=' + $(this).attr('id'),
					success: e => {
						if(e == 'ok') {
							alert('Student removed successfully!');
							v.redirect();
						} else {
							alert(e);
						}
					}
				});
			}
		}
	});
	
	$('.select-student').click(function(e) {
		if ($(this).children('div').children('input').prop('checked')) {
			$(this).children('div').children('input').prop('checked', false);
			$(this).children('div').removeClass('active');
		} else {
			$(this).children('div').children('input').prop('checked', true);
			$(this).children('div').addClass('active');
		}
	})
	
	$('#promotion').submit(function(e) {
		e.preventDefault();
		
		var val = {
			'input': {
				field: 'select',
				checkBox: true
			},
			'#promote-class': {
				field: 'class',
				require: true
			}
		};
		
		v.back(this, val, '.promote-info', load);
	})
	
	$('#report-student').change(() => {
		if(v.empty('#student-report')) {
			$.ajax({
				data: 'student-report=' + v.getInput('#report-student'),
				dataType: 'json',
				beforeSend: () => {
					$('.report thead').empty();
					$('.report tbody').empty().html(load + 'Getting report, please wait...');
				},
				success: e => {
					$('.report tbody').empty().append(e.report);
					$('.report thead').empty().append('<th>Names</th>' + e.subject);
				}
			})
		}
	})
	
})(jQuery);