$(document).ready(function() {
	
	$("#services").change(function() {
		var $price = $('#price');
	    var serviceId = $('#services').val();
		var customerId = $('#customers').val();
		var companyId = $('#companyId').val();

        $.ajax({
            type: "GET",
            url:"http://yourspa.com/admin/getPriceForCustomer?serviceId=" + serviceId + "&customerId=" + customerId + "&companyId=" + companyId ,
            success:function(data) {
                var json_price = jQuery.parseJSON(data);
                json_price = json_price.toFixed(2);
                $price.text(json_price);
            },
            error: function() {
                console.log("Error.");
            }
        });

    });

	$("#customers").change(function() {
		var $price = $('#price');
	    var serviceId = $('#services').val();
		var customerId = $('#customers').val();
		var companyId = $('#companyId').val();

        $.ajax({
            type: "GET",
            url:"http://yourspa.com/admin/getPriceForCustomer?serviceId=" + serviceId + "&customerId=" + customerId + "&companyId=" + companyId ,
            success:function(data) {
                var json_price = jQuery.parseJSON(data);
                json_price = json_price.toFixed(2);
                $price.text(json_price);
            },
            error: function() {
                console.log("Error.");
            }
        });

    });

    $("#amountPaid").focusout(function() {
    	var amountPaid = $('#amountPaid').val();
    	var price = $('#price').text();
    	var change = parseFloat(amountPaid) - parseFloat(price);
    	change = change.toFixed(2);
    	$('#change').text(change);
    });

    $('#save').on('click', function(event) {

    	var employeeId = $('#masseur').val();
	    var serviceId = $('#services').val();
		var customerId = $('#customers').val();
		var companyId = $('#companyId').val();

		var serviceName = $('#services option:selected').text();
		var customerName = $('#customers option:selected').text();

		var amountPaid = $('#amountPaid').val();
    	var price = $('#price').text();
    	var change = parseFloat(amountPaid) - parseFloat(price);
    	$('#change').text(change);

		if (isNaN(price)) {
			event.preventDefault();
		}
		if (isNaN(amountPaid)) {
			event.preventDefault();
		}
		if (isNaN(change)) {
			event.preventDefault();
		}
		
		var data = { serviceId: serviceId,
			serviceName: serviceName,
			customerId: customerId,
			customerName: customerName,
			employeeId: employeeId,
			price: price,
			discount: 0,
			total: price };

		var url = $('#form').attr('action');
		var type = $('#form').attr('method');

		$.ajax({
			type: type,
			url: url,
			data: data,
			success: function() {
				window.location.href = 'http://yourspa.com/admin/successaddtransaction';
				console.log("Success.");
			},
			error: function() {
				console.log("Error.");
			}
		});

    });

});