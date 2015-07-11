YUI().use('datatable', function(Y) {
	$.ajax({
		type: "GET",
		url: "http://yourspa.com/index.php/admin/getAllUsersExceptCurrent",
		success: function(data) {
				
			},
		error: function() {
				console.log("Error.");
			}
	});
	
	var data = 

	var table = new Y.DataTable({
	    columns: ["Id", "Username", "First name", "Last name", "Address", "Gender", "Active", "Access rights"],
	    data: data,

	    caption: "System users list",

	    // and/or a summary (table attribute)
	    summary: "Example DataTable showing basic instantiation configuration"
	});

	table.render("#table-users-list");
});