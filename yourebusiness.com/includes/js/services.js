$(document).ready(function(){
	var $services = $('#services');

	var servicesTemplate = $('#services-template').html();

	function serviceList(service) {
		$services.append(Mustache.render(servicesTemplate, service));
	}

	$.ajax({
		type: 'GET',
		url: 'http://yourspa.com/admin/getAllServices',
		success: function(services) {
			$.each(services, function(i, service) {
				serviceList(service);
			});
		},
		error: function() {
			alert("Error loading services.");
		}
	});

	/* listener for delete button */
	$services.delegate('.remove', 'click', function() {
		var result = confirm("Are you sure you want to delete the record?");
		if (result == true) {
			$record = $(this).closest('tr');
			$id = $(this).attr("data-id");
			$data = {id: $id};

			$.ajax({
				type: 'GET',
				url: 'http://yourspa.com/admin/deleteService',
				data: $data,
				success: function() {
					$record.fadeOut(300, function() {
						$(this).remove();
					});
				}
			});
		}
	});

	/* listener for add button */
	$('#add-service').on('click', function() {
		$('#dangerAlert').slideUp();

		$serviceName = $('#serviceName');
		$description = $('#description');
		$regPrice = $('#regPrice');
		$memberPrice = $('#memberPrice');

		$dangerAlert = $('#dangerAlert');

		if (jQuery.trim($serviceName.val()).length < 5) {
			$dangerAlert.slideDown();
			return;
		}
		if (jQuery.trim($description.val()).length < 5) {
			$dangerAlert.slideDown();
			return;
		}
		if (jQuery.trim($regPrice.val()).length < 1) {
			$dangerAlert.slideDown();
			return;
		}
		if (jQuery.trim($memberPrice.val()).length < 1) {
			$dangerAlert.slideDown();
			return;
		}

		var data = {
			serviceName: $serviceName.val(),
			description: $description.val(),
			regPrice: $regPrice.val(),
			memberPrice: $memberPrice.val()
		};


		$.ajax({
			type: 'GET',
			url: 'http://yourspa.com/admin/addService',
			data: data,
			success: function(response) {
				$('#successAlert').slideDown();
			},
			error: function() {
				console.log("Error.");
			}
		});
	});


	$('#edit-service').on('click', function() {
		$('#dangerAlert').slideUp();
		$('#successAlert').slideUp();

		$serviceId = $('#editServiceDetails');
		$serviceName = $('#serviceName');
		$description = $('#description');
		$regPrice = $('#regPrice');
		$memberPrice = $('#memberPrice');

		$dangerAlert = $('#dangerAlert');

		if (jQuery.trim($serviceName.val()).length < 5) {
			$dangerAlert.slideDown();
			return;
		}
		if (jQuery.trim($description.val()).length < 5) {
			$dangerAlert.slideDown();
			return;
		}
		if (jQuery.trim($regPrice.val()).length < 1) {
			$dangerAlert.slideDown();
			return;
		}
		if (jQuery.trim($memberPrice.val()).length < 1) {
			$dangerAlert.slideDown();
			return;
		}

		function getId() {
			var $href = $serviceId.attr('action');
			var $lastIndexOfSlash = $href.lastIndexOf("/");
			return $href.substring($lastIndexOfSlash + 1);
		}

		var data = {
			serviceId: getId(),
			serviceName: $serviceName.val(),
			description: $description.val(),
			regPrice: $regPrice.val(),
			memberPrice: $memberPrice.val()
		};

		$.ajax({
			type: 'GET',
			url: 'http://yourspa.com/admin/editService',
			data: data,
			success: function(response) {
				$('#successAlert').slideDown();
			},
			error: function() {
				console.log("Error.");
			}
		});
	});

});