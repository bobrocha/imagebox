// JavaScript to handle photo operations

$(document).ready(function() {

	$(document).ajaxStart(function() {
		$('.commit-section').hide();
		$('.ajax-loading').fadeIn();
	});

	$(document).ajaxComplete(function() {
		setTimeout(function() {
			$('.ajax-loading').hide();
			$('.response').fadeIn();
		}, 350);
	});

	// show/hide edit option
	$('.editContainer').hover(function() {
		$(this).find('.btn').addClass('visible');
	}, function() {
		$(this).find('.btn').removeClass('visible');
	});

	// show ok/cancel and input controls and store original value for later use
	$('.photo-section').on('click', '.editBtn', function(e) {
		e.preventDefault();

		// hide response from previous edit
		$('.response').hide();

		// get original value
		var $container = $(this).closest('.editContainer');
		var $editValue = $container.find('.editValue');
		var value = $editValue.text();

		// store original value
		$editValue.data('originalValue', value);

		// replace elemnt with an input element and original value
		$editValue.html($("<input type='text'>").val(value));
		$editValue.find('input').focus();

		// change/replace edit control with cancel and ok control
		var $btn = $container.find('.btn');
		$btn.html("<a href='#' class='okBtn'>ok</a> <a href='#' class='cancelBtn'>cancel</a>");
	});

	// revert to original value and 'edit' control upon 'cancel'
	$('.photo-section').on('click', '.cancelBtn', function(e) {
		e.preventDefault();

		// put back original value
		var $container = $(this).closest('.editContainer');
		var $editValue = $container.find('.editValue');
		$editValue.html($editValue.data('originalValue'));

		// toggle to edit control
		var $btn = $container.find('.btn');
		$btn.html("<a href='#' class='editBtn'>edit</a>");
	});

	// commit value upon editing and show 'edit' control again
	$('.photo-section').on('click', '.okBtn', function(e) {
		e.preventDefault();

		var $container = $(this).closest('.editContainer');
		var $editValue = $container.find('.editValue');

		$editValue.html($editValue.find('input').val());

		var $btn = $container.find('.btn');
		$btn.html("<a href='#' class='editBtn'>edit</a>");

		$('.commit-section').fadeIn();
	});

	// go back
	$('.backBtn').on('click', function(e) {
		e.preventDefault();
		history.back();
	});

	// save
	$('.save').on('click', function(e) {
		e.preventDefault();

		// collect data
		var regex = /[#]+[A-Za-z0-9-_]+/g;
		var title = $.trim($('.title-value').text());
		var tags = $.trim($('.tags-value').text()).match(regex);
		var filename = $('img').attr('src').substring(14);
		
		// send it
		$.ajax({
			url		: 'edit_photo.php',
			method	: 'POST',
			dataType: 'html',
			data	: {
				title: title,
				hash_tags: tags,
				filename: filename
			}
		})
		.done(function(response) {
			if (response == 'Photo updated!') {
				$('.response').html(response);
			}
		})
		.fail(function() {
			alert('Something went wrong!');
		});
	});

	// cancel
	$('.cancel').on('click', function(e) {
		e.preventDefault();
		location.reload();
	});

	// delete photo
	$('.delBtn').on('click', function(e) {
		e.preventDefault();

		var filename = $.trim($('.photo img').attr('src').substring(14));
		
		$.ajax({
			url		: 'edit_photo.php',
			method	: 'GET',
			dataType: 'json',
			data	: {
				'delete'	: true,
				'filename'	: filename
			}
		})
		.done(function(response) {
			if (response.status == 'success') {
				$('.response').html(response.message);
				setTimeout(function() {
					$('.photo-section').html('');
				}, 2000);
			} else {
				$('.response').html(response.message);
			}
		})
		.fail(function() {
			alert('Something went wrong!');
		});
	});
});
