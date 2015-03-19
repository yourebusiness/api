$(document).ready(function(){
	$dangerAlert = $('#dangerAlert');

	var $userId = $('#userId');
	var $oldPassword = $('#oldPassword');
	var $newPassword = $('#newPassword');
	var $confirmPassword = $('#confirmPassword');

	$('#updatePassword').on('click', function(e){
		e.preventDefault();
		$dangerAlert.slideUp();

		if (jQuery.trim($userId.val()).length < 1) {
			alert("User Id is not found.");
			return;
		}
		if (jQuery.trim($oldPassword.val()).length < 4) {
			$dangerAlert.slideDown();
			return;
		}
		if (jQuery.trim($newPassword.val()).length < 4) {
			$dangerAlert.slideDown();
			return;
		}
		if (jQuery.trim($confirmPassword.val()).length < 4) {
			$dangerAlert.slideDown();
			return;
		}

		$data = {oldPassword: $oldPassword.val(),
				newPassword: $newPassword.val(),
				confirmPassword: $confirmPassword.val(),
				userId: $userId.val()}

		$.ajax({
			type: 'POST',
			url: 'http://yourspa.com/admin/changePassword',
			data: $data,
			success: function() {
				window.location.reload();
			},
			error: function() {
				console.log("Error.");
			}
		});
	});
});