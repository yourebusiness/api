/*
Use: For Masseur pages
Author:
Updated:
*/

$(document).ready(function() {
	
	// listner for the add button
	$('#add-masseur').on('click', function(event) {
		var $fName = $('#fName');
		var $midName = $('#midName');
		var $lName = $('#lName');
		var $nickname = $('#nickname');

		$('#successAlert').slideUp();
		$('#dangerAlert').slideUp();
		if (jQuery.trim($fName.val()).length < 2) {
			event.preventDefault();
			$('#dangerAlert').slideDown();
			return;
		}
		if (jQuery.trim($lName.val()).length < 2) {
			event.preventDefault();
			$('#dangerAlert').slideDown();
			return;
		}
		if (jQuery.trim($nickname.val()).length < 2) {
			event.preventDefault();
			$('#dangerAlert').slideDown();
			return;
		}
			
		var masseur = {
			fName: $fName.val(),
			midName: $midName.val(),
			lName: $lName.val(),
			nickname: $nickname.val()
		};

		$.ajax({
			type: 'GET',
			url: 'http://yourspa.com/admin/masseuradd',
			data: masseur,
			success: function(response) {
				$fName.val('');
				$midName.val('');
				$lName.val('');
				$nickname.val('');
				$('#successAlert').slideDown();
			},
			error: function() {
				console.log("Error.");
			}
		});
	});

	// listener for the 'active'
	$('a.masseurchangestatus').on('click', function(e) {
		e.preventDefault();
		var href = $(this).attr('href');
		var lastSlashIndex = href.lastIndexOf("/");
		var newHref = href.substring(0, lastSlashIndex);
		var record = $(this);

		if (record.text() == 'N')
			var newStatus = 'Y';
		else
			var newStatus = 'N';

		newHref = newHref + '/' + newStatus;

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
		}
	});

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

	});

	/* Event: change text in the table. */
	//var $nickname = '';

	$('.changeable').focusout(function() {
		var column = $(this).attr("data-col");

		if (column == 'nickname') {
			var $id = $(this).parent().prev().text();
			var $nickname = $(this).text();
			var $fName = $(this).parent().next().text();
			var $midName = $(this).parent().next().next().text();
			var $lName = $(this).parent().next().next().next().text();
		}
		if (column == 'fName') {
			var $id = $(this).parent().prev().prev().text();
			var $nickname = $(this).parent().prev().text();
			var $fName = $(this).text();
			var $midName = $(this).parent().next().text();
			var $lName = $(this).parent().next().next().text();
		}
		if (column == 'midName') {
			var $id = $(this).parent().prev().prev().prev().text();
			var $nickname = $(this).parent().prev().prev().text();
			var $fName = $(this).parent().prev().text();
			var $midName = $(this).text();
			var $lName = $(this).parent().next().text();	
		}
		if (column == 'lName') {
			var $id = $(this).parent().prev().prev().prev().prev().text();
			var $nickname = $(this).parent().prev().prev().prev().text();
			var $fName = $(this).parent().prev().prev().text();
			var $midName = $(this).parent().prev().text();
			var $lName = $(this).text();	
		}

		var $nicknameLength = jQuery.trim($nickname).length;
		var $fNameLength = jQuery.trim($fName).length;
		var $lNameLength = jQuery.trim($lName).length;

		if ($nicknameLength <= 0 || $nicknameLength > 18) {
			alert("Nickname must be provided and not greater than 18 characters.");
			return;
		}
		if ($fNameLength <= 0 || $fNameLength > 60) {
			alert("First name must be provided and not greater than 60 characters.");
			return;
		}
		if ($lNameLength <= 0 || $lNameLength > 60) {
			alert("Last name must be provided and not greater than 60 characters.");
			return;
		}

		var url = 'http://yourspa.com/admin/masseurEdit?id=' + $id + '&nickname=' + $nickname + '&fName=' + $fName + '&midName=' + $midName + '&lName=' + $lName;
			url = encodeURI(url);

		$.ajax({
			url: url,
			type: 'GET',
		});

	});	
});