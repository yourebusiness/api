$(document).ready(function(){

	obj = JSON.parse($profileDetails);

	var $userId = $('#userId'), $username = $('#username'), $fName = $('#fName'), $midName = $('#midName'), $lName = $('#lName'), $address = $('#address'), $gender = $('#gender');

	$username.val(obj[0]["username"]);
	$fName.val(obj[0]["fName"]);
	$midName.val(obj[0]["midName"]);
	$lName.val(obj[0]["lName"]);
	$address.val(obj[0]["address"]);
	$gender.val(obj[0]["gender"]);
	
	// listener for the update button
	$('#update_profile').on('click', function() {
		$dangerAlert = $('#dangerAlert');
		$successAlert = $('#successAlert');
		$dangerAlert.slideUp();
		$successAlert.slideUp();

		if (jQuery.trim($userId.val()).length < 1) {
			$dangerAlert.slideDown();
			return;
		}
		if (jQuery.trim($username.val()).length < 10) {
			$dangerAlert.slideDown();
			return;
		}
		if (jQuery.trim($fName.val()).length < 2) {
			$dangerAlert.slideDown();
			return;
		}
		if (jQuery.trim($lName.val()).length < 2) {
			$dangerAlert.slideDown();
			return;
		}
		if (jQuery.trim($gender.val()).length < 1) {
			$dangerAlert.slideDown();
			return;
		}

		$data = { userId: $userId.val(),
					fName: $fName.val(),
					midName: $midName.val(),
					lName: $lName.val(),
					address: $address.val(),
					gender: $gender.val() };
		$.ajax({
			type: 'GET',
			url: 'http://yourspa.com/admin/updateProfile',
			data: $data,
			success: function(response) {
				$successAlert.slideDown();
			},
			error: function() {
				console.log("Error.");
			}
		});
	});
});