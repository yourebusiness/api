$(document).ready(function() {

    showEditDialog = function(data) {
        console.log(data);
        $('#customer_modal .modal-title').text("Edit Customer record");
        $('#customerType').val(data.custType);
        $('#customerId').val(data.customerId);
        $('#fName').val(data.fName);
        $('#midName').val(data.midName);
        $('#lName').val(data.lName);
        $('#gender').val(data.gender);
        $('#active').val(data.active);
    }

    var $customerId = $('#customerId'),
        $customerType = $('#customerType'),
        $fName = $('#fName'),
        $midName = $('#midName'),
        $lName = $('#lName'),
        $gender = $('#gender'),
        $active = $('#active'),
        $formCustomer = $('#form_customer'),
        $checkboxHeader = $('#checkAll');

    var thisRow;

    var tableOptions = {
        "paging": true,
        "searching": true,
        "ordering": true,
        "ajax": {
            url: 'http://yourspa.com/index.php/admin/customers/list',
            dataSrc: '',
        },
        columns: [
            { data: "customerId" },
            { data: "customerId" },
            { data: "custType" },
            { data: "fName" },
            { data: "midName" },
            { data: "lName" },
            { data: "gender" },
            { data: "active" },
        ],
        "columnDefs": [
            {
                "targets": 0,
                "orderable": false,
                "render": function(data, type, full, meta) {
                    return '<input type="checkbox" name="checkboxRow" value = "' + data + '" />';
                }
            },
            {
                "targets": 1,
                "data": "customerId",
                "render": function(data, type, full, meta) {
                    return '<a href="#" data-toggle="modal" data-target="#customer_modal">' + data + '</a>';
                },
            },
            {
                "targets": 2,
                "data": "custType",
                "render": function(data, type, full, meta) {
                    if (data == 0)
                        return "Guest";
                    else if (data == 1)
                        return "Member";
                    else
                        return "";
                },
            },
        ]
    };

    var $table = $('#customersTable');
    $table.DataTable(tableOptions);     // initialize it


    var table = $('#customersTable').DataTable();

    //if (table.rows().data().length == 0)
    //    $checkboxHeader.attr('disabled', true);
 

    // when save is clicked.
    $('#save').on('click', function() {
        var action = $formCustomer.attr("action");

        var data = {
            customerType: $customerType.val(),
            customerId: $customerId.val(),
            fName: $fName.val(),
            midName: $midName.val(),
            lName: $lName.val(),
            gender: $gender.val(),
            active: $active.val(),
        };

        $.ajax({
            type: $formCustomer.attr("method"),
            url: action,
            data: data,
            beforeSend: function(xhr) {
                if ($formCustomer.attr("data-x-http-method") != "")
                    xhr.setRequestHeader('X-HTTP-METHOD', $formCustomer.attr("data-x-http-method"));
            },
            success: function(response) {
                if(parseInt(response.statusCode) == 0) {
                    var value = $formCustomer.attr("data-x-http-method");
                    var rowData = {};

                    rowData.customerType = $customerType.val();
                    rowData.fName = $fName.val();
                    rowData.midName = $midName.val();
                    rowData.lName = $lName.val();
                    rowData.gender = $gender.val();
                    rowData.active = $active.val();

                    if (value == "") { //add
                        rowData.customerId = response.newCustomerId;
                        var json = JSON.parse(JSON.stringify(rowData));
                        table.row.add(json).draw();
                    } else if (value.toLowerCase() == "put") {    //edit here
                        rowData.customerId = $customerId.val();
                        table.row(thisRow).data(rowData).draw();
                    } else {
                        console.log("Unknown method.");
                    }

                    $formCustomer.find('input[type=text]').val('');
                    $('#cancel').click();
                } else {
                    alert(response.statusMessage + ' ' + response.statusDesc);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus);
                console.log(errorThrown)
            }
        });
    });


    // for add cmd
    $('#addRow').on('click', function() {
        $('#customer_modal .modal-title').text("Add customer record");
        $formCustomer.find('input[type=text]').val('');
        $customerId.val('0');
        $formCustomer.attr("method", "POST");
        $formCustomer.attr("data-x-http-method", '');
    });

 
    //for update cmd    
    $('#customersTable tbody').on('click', 'tr', function () {        
        thisRow = this;
        showEditDialog(table.row(this).data());

        $customerId.val(table.row(this).data().customerId);

        $formCustomer.attr("method", "POST");
        $formCustomer.attr("data-x-http-method", 'PUT');
    });


    $btnDelete = $('#btn-delete');

    //for all checkbox
    $('#checkAll').on('click', function() {
        if (table.rows().data().length == 0)
            return;

        if (this.checked == true) {
            var value = true;
            $btnDelete.attr('disabled', false);
        } else {
            var value = false;
            $btnDelete.attr('disabled', true);
        }

        $table.find('input[name="checkboxRow"]').prop('checked', value);
    });


    // for every checkboxRow
    $table.delegate('input[name="checkboxRow"]', 'click', function() {
        if ($(this).prop("checked"))
            $(this).addClass("rowSelected");
        else
            $(this).removeClass("rowSelected");

        var countRowChecked = $('input[name="checkboxRow"]:checked').length;
        var countRowCheckboxes = $('input[type="checkbox"]').length - 1;

        if (countRowChecked > 0)
            $btnDelete.attr('disabled', false);
        else
            $btnDelete.attr('disabled', true);
        
        (countRowChecked == countRowCheckboxes) ? $('#checkAll').prop('checked', true) : "";        
        (this.checked == false) ? $('#checkAll').prop('checked', false) : "";
    });


    // for the delete button
    $btnDelete.on('click', function() {
        var countRowChecked = $('input[name="checkboxRow"]:checked').length;

        if (countRowChecked > 0) {
            $('.confirm').confirm({
                text: "Are you sure you want to delete selected record(s)?",
                title: "Confirmation required",
                confirm: function(button) {
                    var checkboxValues = $('input[name="checkboxRow"]:checked').map(function() {
                        return this.value;
                    }).get();

                    var data = checkboxValues;

                    $formCustomer.attr("data-x-http-method", "DELETE");
                    
                    $.ajax({
                        type: "POST",
                        url: $formCustomer.attr("action"),
                        data: {customerIds: data},
                        dataType: "JSON",
                        beforeSend: function(xhr) {
                            if ($formCustomer.attr("data-x-http-method") != "")
                                xhr.setRequestHeader('X-HTTP-METHOD', $formCustomer.attr("data-x-http-method"));
                            else {
                                alert("No value for X-HTTP-METHOD");
                                return false;
                            }
                        },
                        success: function(response) {
                            if(parseInt(response.statusCode) == 0) {
                                //table.rows('.rowSelected').remove().draw();

                                // we want it reload for now because the above code isn't working.
                                location.reload();
                            } else {
                                alert(response.statusMessage + ' ' + response.statusDesc);
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(textStatus);
                            console.log(errorThrown)
                        }
                    });
                },
                cancel: function(button) {
                    // nothing
                },
                confirmButton: "Yes",
                cancelButton: "No",
            });
        }
    });
    
});