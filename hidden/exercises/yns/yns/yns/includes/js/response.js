$(document).ready(function() {

	function alertMe() {
		alert("All fields are  required.");
	}

	$('#send').on('click', function(event) {
		var name = $('#name').val(),
			email = $('#email').val(),
			message = $('#message').val();

			if (jQuery.trim(name).length < 1) {
				alertMe();
				event.preventDefault();
				return;
			}
			if (jQuery.trim(email).length < 1) {
				alertMe();
				event.preventDefault();
				return;
			}
			if (jQuery.trim(message).length < 1) {
				alertMe();
				event.preventDefault();
				return;
			}
	});

	$('#clear').on('click', function() {
		$('#name').val('');
		$('#email').val('');
		$('#message').val('');
	});
});