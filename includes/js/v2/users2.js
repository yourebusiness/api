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

    var $username = $('#username'),
        $fName = $('#fName'),
        $midName = $('#midName'),
        $lName = $('#lName'),
        $gender = $('#gender'),
        $active = $('#active'),
        $role = $('#role');

    var mode = '';

    var tableOptions = {
        "paging": false,
        "searching": true,
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
                    return '<a href="#" onclick="window.showEditDialog('+full.userId+')";" data-toggle="modal" data-target="#user_modal">'+data+'</a>';
                }
            },
        ]
    };

    var $table = $('#userTable');
    $table.DataTable(tableOptions);
 
    $('#save').on('click', function() {
        var action = $("#form_user").attr("action"),

        if (mode == 'add')
            method = 'POST';
        if (mode == 'retrieve')
            method = 'GET';
        if (mode == 'update')
            mode = 'PUT';

        var data = {
            username: $username.val(),
            fName: $fName.val(),
            midName: $midName.val(),
            lName: $lName.val(),
            gender: $gender.val(),
            active: $active.val(),
            role: $role.val(),
        };

        $.ajax({
            type: method,
            url: action,
            data: data,
            success: function(o) {
                if(parseInt(o.newUserId) > 0) {
                    data.userId = o.newUserId;
                    var json = JSON.parse(JSON.stringify(data));
                    $table.row.add(json).draw();

                    $('#form_user').find('input[type=text]').val('');
                    $('#cancel').click();
                } else {
                    alert(o.statusMessage);
                }
            },
            error: function() {
                console.log("Error has occured.");
            }
        });
    });

    $('#addRow').on('click', function() {
        $('#user_modal .modal-title').text("Add user");
        $('#form_user').find('input[type=text]').val('');
    });

    var table = $('#userTable').DataTable();
 
    $('#userTable tbody').on( 'click', 'tr', function () {
        showEditDialog(table.row( this ).data());
    });
});