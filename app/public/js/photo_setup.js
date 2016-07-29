// Javascript to handle photo setup page using ajax

$(document).ready(function() {

	$('.save').on('click', function(e) {
		e.preventDefault();
		save();
	});

	$(document).ajaxStart(function() {
		$('.commit-section .save').hide();
		$('.ajax-loading').fadeIn();
	});

	$(document).ajaxComplete(function() {
		setTimeout(function() {
			$('.ajax-loading, .commit-section').hide();
			$('.response').fadeIn();
		}, 150);
	});

});

// collect all photo data and send to php for database storage
function save() {

	// regex to parse hashtags
	var regex = /[#]+[A-Za-z0-9-_]+/g;
	var name = $.trim($('.photo-name input').val());
	var tags = $.trim($('.tag-section input').val()).match(regex);
	var photo = $.trim($('.photo-section img').attr('src'));

	$.ajax({

		url: 'save_photo.php',
		method: 'POST',
		dataType: 'html',
		data: {
			title: name,
			hash_tags: tags,
			filename: photo
		}

	})
	.done(function(response) {
		if (response == 'Photo saved successfully!') {
			$('.response').html(response + "<br /><a href='upload.php'>upload another</a>");
		}
	})
	.fail(function() {
		alert('Something went wrong!')
	});
}