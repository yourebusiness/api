$(function() {
    var url = 'http://yourspa.com/index.php/admin/getAllUsersExceptCurrent';
    
    var getAllUsersExceptCurrent = function(data, status) {
        window.yourSpa = data;
    }

    $.get(url, getAllUsersExceptCurrent);

    YUI().use("datatable", function (Y) {
        table = new Y.DataTable({
            columns: [
                {
                    key: "userId",
                    label: "User Id",
                    allowHTML: true,
                    formatter: '<a href="#">{value}</a>'
                },
                {
                    key: "username",
                    label: "Username"
                },
                {
                    key: "fName",
                    label: "First name"
                },
                {
                    key: "midName",
                    label: "Middle name"
                },
                {
                    key: "lName",
                    label: "Last name"
                },
                {
                    key: "gender",
                    label: "Gender"
                },
                {
                    key: "active",
                    label: "Active"
                },
                {
                    key: "role",
                    label: "Role",
                    formatter: function(o) {
                        if (o.value == 0)
                            return 'Administrator';
                        else
                            return 'User';
                    }
                }
            ],
            data: window.yourSpa,
            caption: "List of other users"
        });

        table.render("#users-table");
    });

});