$(document).ready(function() {

    showEditDialog = function(data) {
        $('#user_modal .modal-title').text("Edit user");
        $('#userId').val(data.userId);
        $('#username').val(data.username);
        $('#fName').val(data.fName);
        $('#midName').val(data.midName);
        $('#lName').val(data.lName);
        $('#gender').val(data.gender);
        $('#active').val(data.active);
        $('#role').val(data.role);
    }

    var $userId = $('#userId'),
        $username = $('#username'),
        $fName = $('#fName'),
        $midName = $('#midName'),
        $lName = $('#lName'),
        $gender = $('#gender'),
        $active = $('#active'),
        $role = $('#role'),
        $formUser = $('#form_user');

    var thisRow;

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
                },
            },
            {
                "targets" : 7,
                "data": "role",
                "render": function(data, type, full, meta) {
                    return (data == '0') ? 'Administrator' : 'User';
                },
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
                    var rowData = {};

                    rowData.username = $username.val();
                    rowData.fName = $fName.val();
                    rowData.midName = $midName.val();
                    rowData.lName = $lName.val();
                    rowData.gender = $gender.val();
                    rowData.active = $active.val();
                    rowData.role = $role.val();

                    if (value == "") { //add
                        rowData.userId = response.newUserId;
                        var json = JSON.parse(JSON.stringify(rowData));
                        table.row.add(json).draw();
                    } else if (value.toLowerCase() == "put") {    //edit here
                        rowData.userId = $userId.val();
                        table.row(thisRow).data(rowData).draw();
                    } else if (value.toLowerCase() == "delete") {
                        console.log("deleting...");
                    } else {
                        console.log("Unknown method.");
                    }

                    $('#form_user').find('input[type=text]').val('');
                    $('#cancel').click();
                } else {
                    alert(response.statusMessage + ' ' + response.statusDesc);
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

 
    //for update cmd
    var table = $('#userTable').DataTable();
    $('#userTable tbody').on( 'click', 'tr', function () {        
        thisRow = this;
        showEditDialog(table.row(this).data());

        $('#userId').val(table.row(this).data().userId);

        $formUser.attr("method", "POST");
        $formUser.attr("data-x-http-method", 'PUT');
    });
});