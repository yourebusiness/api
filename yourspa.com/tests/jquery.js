$(document).ready(function() {


	$('a').on('click', function(e) {
		e.preventDefault();
		var href = $(this).attr('href');
		var record = $(this).parent();

		var result = confirm("Are you sure you want to change the status?");
		if (result == true) {

			$(this).text("New text.");
		}
	});

});