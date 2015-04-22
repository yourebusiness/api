/*

My Custom JS
============

Author:  Jhunex Jun
Updated: March 3, 2015
Notes:

*/

$(function() {

	$('#alertMe').click(function(e) {
		e.preventDefault();

		$('#successAlert').slideDown();
	});

	$('a.pop').click(function(e) {
		e.preventDefault();
	});

	$('a.pop').popover();

	$('[rel="tooltip"]').tooltip();

});