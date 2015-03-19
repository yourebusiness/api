$(document).ready(function(){

	$('#signin').on('click', function(event) {
		event.preventDefault();
		$('#dangerAlert').slideUp();

		$username = $('#username');
		$password = $('#password');
		
		$dangerAlert = $('#dangerAlert');

		if (jQuery.trim($username.val()).length < 5) {
			$dangerAlert.slideDown();
			return;
		}
		if (jQuery.trim($password.val()).length < 5) {
			$dangerAlert.slideDown();
			return;
		}

		var data = {
			username: $username.val(),
			password: $password.val(),
		};


		$.ajax({
			type: 'POST',
			url: 'http://yourspa.com/admin/addService',
			data: data,
			success: function(response) {
				$('#successAlert').slideDown();
			},
			error: function() {
				console.log("Error.");
			}
		});
	});
	
});