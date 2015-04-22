/*
Use: For users pages
Author: Jhunex
Updated:
*/

$(document).ready(function() {

	// add listener to the add button
	$('#add-user').on('click', function(event) {
		var $username = jQuery.trim($('#username').val());
		var $password = jQuery.trim($('#password').val());
		var $fName = jQuery.trim($('#fName').val());
		var $midName = jQuery.trim($('#midName').val());
		var $lName = jQuery.trim($('#lName').val());
		var $address = jQuery.trim($('#address').val());
		var $gender = jQuery.trim($('#gender').val());
		//var $role = jQuery.trim($('#role').val());
		var $role = $('input:radio[name=role]:checked').val();

		$('#successAlert').slideUp();
		$('#dangerAlert').slideUp();

		if ($username.length <= 0) {
			event.preventDefault();
			$('#dangerAlert').slideDown();
			return;
		}
		if ($password.length <= 0) {
			event.preventDefault();
			$('#dangerAlert').slideDown();
			return;
		}
		if ($fName.length <= 0) {
			event.preventDefault();
			$('#dangerAlert').slideDown();
			return;
		}
		if ($lName.length <= 0) {
			event.preventDefault();
			$('#dangerAlert').slideDown();
			return;
		}
		if ($gender.length <= 0) {
			event.preventDefault();
			$('#dangerAlert').slideDown();
			return;
		}
		if ($role.length <= 0) {
			event.preventDefault();
			$('#dangerAlert').slideDown();
			return;
		}

		var user = { username: $username,
			password: $password, fName: $fName, midName: $midName, lName: $lName, address: $address, gender: $gender, role: $role
		};

		$.ajax({
			type: 'GET',
			url: 'http://yourspa.com/admin/usersadd',
			data: user,
			success: function(response) {
				$('#username').val('');
				$('#password').val('');
				$('#fName').val('');
				$('#midName').val('');
				$('#lName').val('');
				$('#address').val('');
				$('#gender').val('0');
				$('#role').val('1');
				
				$('#successAlert').slideDown();
			},
			error: function() {
				console.log("Error.");
			}
		});

	}); // end of add-user

	// for delete record
	$('a.deleteRecord').on('click', function(e) {
		e.preventDefault();
		var href = $(this).attr("href");

		var result = confirm("Are you sure you want to delete this record?");
		if (result == true) {
			$.ajax({
				type: 'GET',
				url: href,
				success: function() {
					location.reload();
				},
				error: function() {
					console.log("Error.");
				}
			});
		}

	}); // end of delete record.

	// listener for the 'active'
	$('a.changeStatus').on('click', function(e) {
		e.preventDefault();
		var href = $(this).attr('href');
		var newHref = href.substring(0, href.length - 1);
		var record = $(this);

		if (record.text() == 'N')
			var newStatus = 'Y';
		else
			var newStatus = 'N';

		newHref = newHref + newStatus;

		var result = confirm("Are you sure you want to change the status?");
		if (result == true) {
			$.ajax({
				type: 'GET',
				url: href,
				success: function() {
					record.attr("href", newHref);
					record.text(newStatus);
				},
				error: function() {
					console.log("Error.");
				}
			});
		} // end of if 
	}); // end of a#changeStatus

	$('a.changeUserRights').on('click', function(e) {
		e.preventDefault();
		var href = $(this).attr('href');

		var lastSlashIndex = href.lastIndexOf("/");
		var newHref = href.substring(0, lastSlashIndex);
		var record = $(this);

		if (jQuery.trim(record.text()) == 'Administrator')
			var newUserRights = 'User';
		else
			var newUserRights = 'Administrator';

		newHref = newHref + '/' + newUserRights;

		var result = confirm("Are you sure you want to change the status?");
		if (result == true) {
			$.ajax({
				type: 'GET',
				url: href,
				success: function() {
					record.attr("href", newHref);
					record.text(newUserRights);
				},
				error: function() {
					console.log("Error.");
				}
			});
		} // end of if 
	});

}); /* end of $(document).ready() */