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
			v: "companyProfile",
		};


		$.ajax({
			type: 'POST',
			url: 'http://yourspa.com/admin/checkLogin',
			data: data,
			success: function(response) {
				window.location.href = 'http://yourspa.com/admin/companyProfile';
			},
			error: function() {
				console.log("Error.");
			}
		});
	});
	
});