$(document).ready(function(){
	// drop down suggestions
	$('#search-field').keyup(ajaxAutocomplete);

	// enable use of keys for selection
	$('.datalistPlaceholder').on('keydown', '.tag', down);

	$('.datalistPlaceholder').on('click', 'a', function(e) {
		e.preventDefault();
		$('#search-field').val($(this).text());
		$('#searchForm').submit();
	});
});

function ajaxAutocomplete(e) {
	var hash_tag = $.trim($(this).val());

	$.ajax({
		url		: 'autocomplete.php',
		method	: 'GET',
		dataType: 'html',
		data 	: {tag : hash_tag}
	})
	.done(function(response) {
		if (response) {
			$('.datalistPlaceholder').html(response).show();

			if (e.keyCode === 40) { // down key
				$('.tag:first').focus();
			}
		} else {
			$('.datalistPlaceholder').hide();
		}
	})
	.fail(function() {
		alert('Something went wrong');
	});
}

function down(e)
{
	if (e.keyCode === 40) { // key down
		$(this).parent('li').next().find('.tag').focus();
	} else if (e.keyCode === 38) { // key up
		$(this).parent('li').prev().find('.tag').focus();

		// focus on input if no previous element
		if (!$(this).parent('li').prev().find('.tag').length) {
			$('#search-field').focus();
		}
	} else if (e.keyCode === 27) { // escape key
		$('.datalistPlaceholder').hide();
	} else if (e.keyCode === 13) { // enter
		$('#search-field').val($(this).text());
		$('#searchForm').submit();
	}

	e.preventDefault();
}