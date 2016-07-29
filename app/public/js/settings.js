$(document).ready(function() {

	// show edit options for name
	$('.name-setting').on('click', '.editBtn', function(e) {
		e.preventDefault();
		$('.password-editContainer').hide();
		$('.password-setting').show();
		$('.name-setting').hide();
		$('.name-editContainer').fadeIn('slow');
	});

	// hide edit options for name
	$('.name-commit-section').on('click', '.cancel', function(e) {
		e.preventDefault();
		$('.name-editContainer').find('input').val('');
		$('.name-editContainer').hide();
		$('.name-setting').show();
	});

	// show edit options for email
	$('.password-setting').on('click', '.editBtn', function(e) {
		e.preventDefault();
		$('.name-editContainer').hide();
		$('.name-setting').show();
		$('.password-setting').hide();
		$('.password-editContainer').fadeIn('fast');

	});

	// hide edit options for password
	$('.password-editContainer').on('click', '.cancel', function(e) {
		e.preventDefault();
		$('.password-editContainer').find('input').val('')
		$('.password-editContainer').hide();
		$('.password-setting').show();
	});

	// get first and last name and update it
	$('.name-editContainer').on('click', '.save', function(e) {
		e.preventDefault();
		var fname = $.trim($('#fname').val());
		var lname = $.trim($('#lname').val());

		if (fname && lname) {
			$.ajax({
				url		: 'account_settings.php',
				method	: 'POST',
				dataType: 'json',
				data 	: {
					fname : fname,
					lname : lname
				}
			})
			.done(function(response) {
				if (response.status == 'success') {
					$('.name-commit-section').addClass('hidden');
					$('.name-response').html('Name Updated!').fadeIn('slow', function() {
						setTimeout(function() {
							$('.name-editContainer').find('input').val('');
							$('.name-editContainer').slideUp();
							$('.name-setting').fadeIn();
							$('.name-response').hide();
							$('.name-commit-section').removeClass('hidden');
							$('.fname').text(response.name.fname);
							$('.lname').text(response.name.lname);
						}, 1000);
					});
				} else {
					$('.name-commit-section').addClass('hidden');
					$('.name-response').html(response.message).fadeIn('slow');
				}
			})
			.fail(function() {
				alert('Something went wrong!');
			});
		} else {
			alert('Please enter first and last name before updating.');
		}
	});
	
	// get password and update it
	$('.password-editContainer').on('click', '.save', function(e){
		e.preventDefault();
		var pword = $.trim($('.pword').val());

		if (pword) {
			$.ajax({
				url		: 'account_settings.php',
				method	: 'POST',
				dataType: 'json',
				data	: {pword}
			})
			.done(function(response) {
				if (response.status == 'success') {
					$('.password-editContainer').hide();
					$('.pword-response').html(response.message).fadeIn('slow', function() {
						setTimeout(function() {
							$('.password-editContainer').find('input').val('');
							$('.pword-response').hide();
							$('.password-setting').show();
						}, 1000);
					});
				} else {
					$('.password-editContainer').hide();
					$('.pword-response').html(response.message).fadeIn();
				}
			})
			.fail(function() {
				alert('Something went wrong!');
			});
		} else {
			alert('Please enter a password!');
		}
	});

	// delete all photos
	$('.delete-all-photos').on('click', 'a', function(e) {
		e.preventDefault();
		var result = confirm('This action will delete all photos!\nPress OK to continue or Cancel to stop.');

		if (result) {
			$.ajax({
				url		: 'account_settings.php',
				method	:'POST',
				dataType: 'json',
				data	: {deleteAll : true}
			})
			.done(function(response) {
				$('.general-response').html(response.message).fadeIn('slow', function() {
					setTimeout(function() {
						$('.general-response').slideUp();
					}, 2000);
				});
			})
			.fail(function() {
				alert('Something went wrong!');
			});
		}
	});

	// delete account
	$('.delete-account').on('click', 'a', function(e) {
		e.preventDefault();
		var result = confirm('This action will delete your account!\nPress OK to confirm or Cancel to stop.');

		if (result) {
			$.ajax({
				url		: 'account_settings.php',
				method	: 'POST',
				dataType: 'json',
				data	: {deleteAcc : true}
			})
			.done(function(response) {
				if (response.status == 'success') {
					$('.general-response').html(response.message).fadeIn('slow', function() {
						setTimeout(function() {
							$('.general-response').slideUp('slow', function() {
								location.reload();
							});
						}, 2000);
					})
				} else {
					alert(response.message);
				}
			})
			.fail(function() {
				alert('Something went wrong!');
			});
		}
	});
});