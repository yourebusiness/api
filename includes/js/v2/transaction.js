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