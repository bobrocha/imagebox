$(document).ready(function() {

	$('#signUpForm').on('submit', function(e) {
		validateSignUp(e);
	});

	function validateSignUp(e) {

		// prevent default submit
		e.preventDefault();

		// field mapping
		var form_fields = {
			'fname'	: 'first name',
			'lname'	: 'last name',
			'email'	: 'email',
			'pword'	: 'password',
			'signup': 'submit'
		};

		// ajax data
		var ajaxData = {};

		// go over   fields
		for (var field in form_fields) {

			if (!$('#' + field).val()) {

				$('#' + field).next().fadeIn('slow');

			} else if ($('#' + field).val()) {

				$('#' + field).next('span').hide();
				ajaxData[field] = $('#' + field).val();
			}
		}

		// send data if all fields are present
		if (Object.keys(ajaxData).length == 5) {

			var request = $.ajax({
				url		: 'validate_signup.php',
				method	: 'POST',
				data	: ajaxData,
				dataType: 'html'
			});

			request.done(function(response) {
				
				if (response == 'Sign up complete.') {
					$('.repsonse').html("<p style='text-align:center; margin-top: 10px'>" + response + " <a href='index.php'>Sign in</a></p>").fadeIn();

					$("input[name=email], input[name=pword], input[name=fname], input[name=lname]").val('');

				} else {

					$('.repsonse').html("<p style='text-align:center; margin-top: 10px; color: red'>" + response + "</p>");

				}

			});

			request.fail(function() {
				alert('Your request could not be processed.');
			});

		}
	}
});