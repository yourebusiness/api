$(document).ready(function() {

    showEditDialog = function(data) {
        $('#services_modal .modal-title').text("Edit Masseur record");
        $('#serviceId').val(data.serviceId);
        $('#serviceName').val(data.serviceName);
        $('#serviceDescription').val(data.description);
        $('#regPrice').val(data.regPrice);
        $('#memberPrice').val(data.memberPrice);
        $('#active').val(data.active);
    }

    var $serviceId = $('#serviceId'),
        $serviceName = $('#serviceName'),
        $serviceDescription = $('#serviceDescription'),
        $regPrice = $('#regPrice'),
        $memberPrice = $('#memberPrice'),
        $active = $('#active'),
        $formServices = $('#form_services'),
        $checkboxHeader = $('#checkAll');

    var thisRow;

    var tableOptions = {
        "paging": true,
        "searching": true,
        "ordering": true,
        "ajax": {
            url: 'http://yourspa.com/index.php/admin/services/list',
            dataSrc: '',
        },
        columns: [
            { data: "serviceId" },
            { data: "serviceId" },
            { data: "serviceName" },
            { data: "description" },
            { data: "regPrice" },
            { data: "memberPrice" },
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
                "data": "serviceId",
                "render": function(data, type, full, meta) {
                    return '<a href="#" data-toggle="modal" data-target="#services_modal">' + data + '</a>';
                },
            },
        ]
    };

    var $table = $('#servicesTable');
    $table.DataTable(tableOptions);     // initialize it


    var table = $('#servicesTable').DataTable();


    // when save is clicked.
    $('#save').on('click', function() {
        var action = $formServices.attr("action");

        var data = {
            serviceId: $serviceId.val(),
            serviceName: $serviceName.val(),
            description: $serviceDescription.val(),
            regPrice: $regPrice.val(),
            memberPrice: $memberPrice.val(),
            active: $active.val(),
        };

        $.ajax({
            type: $formServices.attr("method"),
            url: action,
            data: data,
            beforeSend: function(xhr) {
                if ($formServices.attr("data-x-http-method") != "")
                    xhr.setRequestHeader('X-HTTP-METHOD', $formServices.attr("data-x-http-method"));
            },
            success: function(response) {
                if(parseInt(response.statusCode) == 0) {
                    var value = $formServices.attr("data-x-http-method");
                    var rowData = {};

                    rowData.serviceName = $serviceName.val();
                    rowData.description = $serviceDescription.val();
                    rowData.regPrice = $regPrice.val();
                    rowData.memberPrice = $memberPrice.val();
                    rowData.active = $active.val();

                    if (value == "") { //add
                        rowData.serviceId = response.newId;
                        var json = JSON.parse(JSON.stringify(rowData));
                        table.row.add(json).draw();
                    } else if (value.toLowerCase() == "put") {
                        rowData.serviceId = $serviceId.val();
                        table.row(thisRow).data(rowData).draw();
                    } else {
                        console.log("Unknown method.");
                    }

                    $('#form_services').find('input[type=text]').val('');
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
        $('#services_modal .modal-title').text("Add service record");
        $formServices.find('input[type=text]').val('');
        $serviceId.val('0');
        $formServices.attr("method", "POST");
        $formServices.attr("data-x-http-method", '');
    });

 
    //for update cmd    
    $('#servicesTable tbody').on('click', 'tr', function () {        
        thisRow = this;
        showEditDialog(table.row(this).data());

        $serviceId.val(table.row(this).data().serviceId);

        $formServices.attr("method", "POST");
        $formServices.attr("data-x-http-method", 'PUT');
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

                    $formServices.attr("data-x-http-method", "DELETE");
                    
                    $.ajax({
                        type: "POST",
                        url: $formServices.attr("action"),
                        data: {serviceIds: data},
                        dataType: "JSON",
                        beforeSend: function(xhr) {
                            if ($formServices.attr("data-x-http-method") != "")
                                xhr.setRequestHeader('X-HTTP-METHOD', $formServices.attr("data-x-http-method"));
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