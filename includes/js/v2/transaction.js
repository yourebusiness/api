$.ajax({
	type: "get",
	url: "http://yourspa.com/index.php/admin/transactions/resources",
	success: function(response) {
		$.each(response[0].masseurs, function(key, value) {   
	     	$('#masseur')
				.append(
					$("<option></option>")
					.attr("value", value["masseurId"])
					.text(value["name"])
				);
		});

		$.each(response[0].services, function(key, value) {
			$('#service')
				.append(
					$("<option></option>")
					.attr("value", value["serviceId"])
					.text(value["serviceName"])
				);
		});

		$.each(response[0].customers, function(key, value) {
			$('#customer')
				.append(
					$("<option></option>")
					.attr("value", value["customerId"])
					.text(value["customerFullName"])
				);
		});
	},
	error: function(jqXHR, textStatus, errorThrown) {
        console.log(textStatus);
        console.log(errorThrown)
    }
});

var $services = $("#services");
var $yesButton = $('#yesButton');

$services.change(function() {
		var $price = $('#price');
	    var serviceId = $('#services').val();
		var customerId = $('#customers').val();
		var companyId = $('#companyId').val();

        $.ajax({
            type: "GET",
            url:"http://yourspa.com/admin/getPriceForCustomer?serviceId=" + serviceId + "&customerId=" + customerId,
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


$yesButton.on('click', function() {
	$.ajax({
		type: "post",
		url: "http://yourspa.com/index.php/admin/transactions/resources",
		success: function(response) {
			
		},
		error: function(jqXHR, textStatus, errorThrown) {
	        console.log(textStatus);
	        console.log(errorThrown)
	    }
	});
});