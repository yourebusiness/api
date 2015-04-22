$(document).ready(function() {
	var $customers = $('#customers');
	var companyId = $('#companyId').val();

	var customersTemplate = $('#customers-template').html();

	function customersList(customer) {
		$customers.append(Mustache.render(customersTemplate, customer));
	}

	$.ajax({
		type: 'GET',
		url: 'http://yourspa.com/admin/getAllCustomersByCompanyId',
		data: {companyId: companyId},
		success: function(customers) {
			$.each(customers, function(i, customer) {
				customersList(customer);
			});
		},
		error: function() {
			alert("Error loading customers.");
		}
	});

	// listener for the add customer
	$('#addCustomer').on('click', function(event) {
		$('#dangerAlert').slideUp();
		$('#successAlert').slideUp();
		event.preventDefault();
		var form = $('#form'),
			url = form.attr('action'),
            type = form.attr('method'),
            data = {};

		$custType = $('input[name="custType"]');
		$fName = $('#fName');
		$midName = $('#midName');
		$lName = $('#lName');

		data = {fName: $fName.val(),
				midName: $midName.val(),
				lName: $lName.val(),
				custType: $custType.val()};

		if (jQuery.trim($fName.val()).length < 2) {
            $('#dangerAlert').slideDown();
            $("div.alert span#errorMessage").html("Invalid first name.");
            event.preventDefault();
            return;
		}
		if (jQuery.trim($lName.val()).length < 2) {
            $('#dangerAlert').slideDown();
            $("div.alert span#errorMessage").html("Invalid last name.");
            event.preventDefault();
            return;
		}

		$.ajax({
			url: url,
			type: type,
			data: data,
			success: function() {
				console.log("Success.");
				$('.alert-success').slideDown();
			}
		});
	});
});