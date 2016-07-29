$(document).ready(function() {

	$('#loginForm').on('submit', function(e) {
		validateLogin(e);
	});

	function validateLogin(e) {

		// prevent default submit
		e.preventDefault();

		// field mapping
		var form_fields = {
			'email' : 'email',
			'pword' : 'password',
			'login'	: 'login'
		};

		// ajax data
		var ajaxData = {};


		for (var field in form_fields) {

			if (!$('#' + field).val()) {

				$('.error').fadeIn();

			} else if ($('#' + field).val()) {

				ajaxData[field] = $('#' + field).val();
			}
		}
		
		// send data if it is all there
		if (Object.keys(ajaxData).length === 3) {

			var request = $.ajax({
				url			: 'validate_login.php',
				method		: 'POST',
				data		: ajaxData,
				dataType	: 'html'
			});

			request.done(function(response) {

				if (response !== 'Invalid Email or Password') {

					window.location = 'account.php';

				} else {

					$('.error').html(response).fadeIn(1000);
					
				}

				$("input[name=email], input[name=pword]").val('');
			});

			request.fail(function() {
				alert('Your request could not be processed.');
			});

		}
	}

});