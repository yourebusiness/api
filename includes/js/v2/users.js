$(function() {
    $('[data-toggle="popover"]').popover();

    var url = 'http://yourspa.com/index.php/admin/getAllUsersExceptCurrent';
    
    var getAllUsersExceptCurrent = function(data, status) {
        window.yourSpa = data;
    }

    $.get(url, getAllUsersExceptCurrent);

    YUI().use("datatable-base", "panel", function (Y) {

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

    
    //Save on add
    $('#addUser').on("click", function(e) {
        e.preventDefault();

        var $username = $('#username'),
            $fName = $('#fName'),
            $midName = $('#midName'),
            $lName = $('#lName'),
            $address = $('#address'),
            $gender = $('#gender'),
            $active = $('#active'),
            $role = $('#role');

        if (jQuery.trim($username.val()).length < 5) return false;
        if (jQuery.trim($fName.val()).length < 2) return false;
        if (jQuery.trim($lName.val()).length < 2) return false;
        if (jQuery.trim($gender.val()).length < 1) return false;
        if (jQuery.trim($active.val()).length < 1) return false;
        if (($role.val() != 0) && ($role.val() != 1)) return false;

        var $form = $('#form_addUser'),
            method = $form.attr('method'),
            action = $form.attr('action'),
            data = {
                username: $username.val(),
                fName: $fName.val(),
                midName: $midName.val(),
                lName: $lName.val(),
                address: $address.val(),
                gender: $gender.val(),
                active: $active.val(),
                role: $role.val(),
            }

        $.ajax({
            type: method,
            url: $('#form_addUser').attr('action'),
            data: data,
            success: function(data) {
                // console.log(data);
                $('#form_addUser').find('input[type=text]').val('');
                $('#cancel').click();
            },
            error: function() {
                console.log();
            }
        });
    });

});