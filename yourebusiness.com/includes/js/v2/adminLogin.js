$(document).ready(function(){

	$('#login').on('click', function(event) {
		event.preventDefault();

		var action = $("#adminLogin").attr("action");
		$username = $('#username');
		$password = $('#password');		
		$alert = $('.alert');

		if (jQuery.trim($username.val()).length < 5) {
			$alert.slideDown();
			return false;
		}
		if (jQuery.trim($password.val()).length < 4) {
			$alert.slideDown();
			return false;
		}

		var data = {
			username: $username.val(),
			password: $password.val(),
			v: "companyProfile",
		};

		$.ajax({
			type: 'POST',
			url: action,
			data: data,
			success: function(response) {
				window.location.href = 'http://yourspa.com/index.php/admin/companyProfile';
			},
			error: function() {
				window.location.href = 'http://yourspa.com/index.php/admin/adminLogin?v=companyProfile';
				console.log("Error has occured.");
			}
		});
	});	
});