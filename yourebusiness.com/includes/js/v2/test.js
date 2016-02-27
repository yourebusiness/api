$(function() {
    $('[data-toggle="popover"]').popover();
});

YUI().use('datatable-mutable', 'datatable-sort', 'datatable-message', 'io-base', 'io-form', 'node-event-simulate', 'yui2-imagecropper', function(Y) {
    // on initial load

    var uri = "?current-user=false";

    var table;
    var my_data = null;

    var cfg = {
        on: {
            complete: function(id, xhr, arguments) {
                my_data = xhr.response;

                table = new Y.DataTable({
                    columns: [
                        {
                            key: "userId",
                            label: "User Id",
                            allowHTML: true,
                            formatter: '<a id="{value}" href="#" class="tableRow" data-toggle="modal" data-target="#addUser_modal">{value}</a>',
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
                    data: JSON.parse(my_data),
                    caption: "List of other users",
                    sortable: true,
                    scrollable: true,
                });

                table.render("#users-table");
            }
        }
    };

    var io = new Y.IO();
    io.send(uri, cfg);


    /**********/
    var username = Y.one('#username'),
        fName = Y.one('#fName'),
        midName = Y.one('#midName'),
        lName = Y.one('#lName'),
        address = Y.one('#address'),
        gender = Y.one('#gender'),
        active = Y.one('#active'),
        role = Y.one('#role');

    // function to add row from modal form
    function addItem(data) {
        table.addRow({
            userId: data["newUserId"],
            username: data["username"],
            fName: data["fName"],
            midName: data["midName"],
            lName: data["lName"],
            gender: data["gender"],
            active: data["active"],
            role: data["role"],
        });
    }


    //Save on add
    Y.one('#addUser').on("click", function(e) {
        e.preventDefault();

        if (Y.Lang.trim(username.get('value')).length < 5) return false;
        if (Y.Lang.trim(fName.get('value')).length < 2) return false;
        if (Y.Lang.trim(lName.get('value')).length < 2) return false;
        if (Y.Lang.trim(gender.get('value')).length < 1) return false;
        if (Y.Lang.trim(active.get('value')).length < 1) return false;
        if ((role.get('value') != 0) && (role.get('value') != 1)) return false;

        var form = Y.one('#form_addUser'),
        method = form.get('method'),
        action = form.get('action');

        var cfg = {
            method: method,
            form: {
                id: form,
                useDisabled: true,
            },
            on: {
                complete: function(id, o) {
                    var response = JSON.parse(o.response);

                    if (parseInt(response.newUserId) <= 0) {
                        alert(response.statusMessage);
                    } else {
                        data = [];
                        data["newUserId"] = response.newUserId;
                        data["username"] = username.get('value');
                        data["fName"] = fName.get('value');
                        data["midName"] = midName.get('value');
                        data["lName"] = lName.get('value');
                        data["address"] = address.get('value');
                        data["gender"] = gender.get('value');
                        data["active"] = active.get('value');
                        data["role"] = role.get('value');

                        addItem(data);

                        Y.one('#form_addUser').all('input[type=text]').set('value', '');

                        Y.one('#cancel').simulate('click');
                    }
                },
            }
        };

        Y.io(action, cfg);

    }); // #addUser


    function showModal(e) {
        console.log(e);
        username.set('value', this._node.id);

        
    };

    //listener on the rows
    Y.on('click', showModal, '.tableRow');
});