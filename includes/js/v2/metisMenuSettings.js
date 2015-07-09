$(function() {
	$(document).ready(function() {
		var metisMenu_options = {
			toggle: false,
		};

		$("#menu").metisMenu(metisMenu_options);
	});
	
	$(".sidebar .nav li a").on("click", function() {
		var last_i_tag = $(this).children(":last");

		if (last_i_tag.hasClass("fa-plus")) {
			last_i_tag.removeClass("fa-plus");
			last_i_tag.addClass("fa-minus");
		} else {
			// this assumes that it always has fa-minus
			last_i_tag.removeClass("fa-minus");
			last_i_tag.addClass("fa-plus");
		}
	});
});