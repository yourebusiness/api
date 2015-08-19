window.showEditDialog = function(data) {
    $('#user_modal .modal-title').text("Edit user");
    $('#username').val(data.username);
    $('#fName').val(data.fName);
    $('#midName').val(data.midName);
    $('#lName').val(data.lName);
    $('#gender').val(data.gender);
    $('#active').val(data.active);
    $('#role').val(data.role);
}

$(document).ready(function() {

    var $userId = $('#userId'),
        $username = $('#username'),
        $fName = $('#fName'),
        $midName = $('#midName'),
        $lName = $('#lName'),
        $gender = $('#gender'),
        $active = $('#active'),
        $role = $('#role'),
        $formUser = $('#form_user');

    var mode = '';

    var tableOptions = {
        "paging": false,
        "searching": false,
        "ordering": true,
        "ajax": {
            url: 'http://yourspa.com/index.php/admin/users?current-user=false',
            dataSrc: '',
        },
        columns: [
            { data: "userId" },
            { data: "username" },
            { data: "fName" },
            { data: "midName" },
            { data: "lName" },
            { data: "gender" },
            { data: "active" },
            { data: "role" },
        ],
        "columnDefs": [
            {
                "targets": 0,
                "data": "userId",
                "render": function(data, type, full, meta) {
                    return '<a href="#" data-toggle="modal" data-target="#user_modal">'+data+'</a>';
                }
            },
        ]
    };

    var $table = $('#userTable');
    $table.DataTable(tableOptions);     // initialize it
 

    // when save is clicked.
    $('#save').on('click', function() {
        var action = $formUser.attr("action");

        var data = {
            userId: $userId.val(),
            username: $username.val(),
            fName: $fName.val(),
            midName: $midName.val(),
            lName: $lName.val(),
            gender: $gender.val(),
            active: $active.val(),
            role: $role.val(),
        };

        $.ajax({
            type: $formUser.attr("method"),
            url: action,
            data: data,
            beforeSend: function(xhr) {
                if ($formUser.attr("data-x-http-method") != "")
                    xhr.setRequestHeader('X-HTTP-METHOD', $formUser.attr("data-x-http-method"));
            },
            success: function(response) {
                if(parseInt(response.statusCode) == 0) {
                    var value = $formUser.attr("data-x-http-method");

                    if (value == "") { //meaning add
                        var row = {};

                        row.userId = response.newUserId;
                        row.username = $username.val();
                        row.fName = $fName.val();
                        row.midName = $midName.val();
                        row.lName = $lName.val();
                        row.gender = $gender.val();
                        row.active = $active.val();
                        row.role = $role.val();

                        var json = JSON.parse(JSON.stringify(row));
                        table.row.add(json).draw();
                    } else if (value.toLowerCase() == "put") {    //edit here
                        console.log("Edit here.");
                    } else if (value.toLowerCase() == "delete") {
                        console.log("deleting...");
                    } else {
                        console.log("Unknown method.");
                    }

                    $('#form_user').find('input[type=text]').val('');
                    $('#cancel').click();
                } else {
                    alert(response.statusMessage);
                }
            },
            error: function() {
                console.log("Error has occured.");
            }
        });
    });

    // for add cmd
    $('#addRow').on('click', function() {
        $('#user_modal .modal-title').text("Add user");
        $formUser.find('input[type=text]').val('');
        $('#userId').val('0');
        $formUser.attr("method", "POST");
        $formUser.attr("data-x-http-method", '');
    });

    var table = $('#userTable').DataTable();
 
    //for update cmd
    $('#userTable tbody').on( 'click', 'tr', function () {
        showEditDialog(table.row(this).data());
        $('#userId').val(table.row(this).data().userId);

        $formUser.attr("method", "POST");
        $formUser.attr("data-x-http-method", 'PUT');
    });
});