$(document).ready(function() {

    showEditDialog = function(data) {
        $('#masseur_modal .modal-title').text("Edit Masseur record");
        $('#masseurId').val(data.masseurId);
        $('#fName').val(data.fName);
        $('#midName').val(data.midName);
        $('#lName').val(data.lName);
        $('#gender').val(data.gender);
        $('#nickname').val(data.nickname);
        $('#active').val(data.active);
    }

    var $masseurId = $('#masseurId'),
        $fName = $('#fName'),
        $midName = $('#midName'),
        $lName = $('#lName'),
        $gender = $('#gender'),
        $nickname = $('#nickname'),
        $active = $('#active'),
        $formMasseur = $('#form_masseur'),
        $checkboxHeader = $('#checkAll');

    var thisRow;

    var tableOptions = {
        "paging": true,
        "searching": true,
        "ordering": true,
        "ajax": {
            url: 'http://yourspa.com/index.php/admin/masseurs/list',
            dataSrc: '',
        },
        columns: [
            { data: "masseurId" },
            { data: "masseurId" },
            { data: "fName" },
            { data: "midName" },
            { data: "lName" },
            { data: "gender" },
            { data: "nickname" },
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
                "data": "masseurId",
                "render": function(data, type, full, meta) {
                    return '<a href="#" data-toggle="modal" data-target="#masseur_modal">' + data + '</a>';
                },
            },
        ]
    };

    var $table = $('#masseurTable');
    $table.DataTable(tableOptions);     // initialize it


    var table = $('#masseurTable').DataTable();

    //if (table.rows().data().length == 0)
    //    $checkboxHeader.attr('disabled', true);
 

    // when save is clicked.
    $('#save').on('click', function() {
        var action = $formMasseur.attr("action");

        var data = {
            masseurId: $masseurId.val(),
            fName: $fName.val(),
            midName: $midName.val(),
            lName: $lName.val(),
            gender: $gender.val(),
            nickname: $nickname.val(),
            active: $active.val(),
        };

        $.ajax({
            type: $formMasseur.attr("method"),
            url: action,
            data: data,
            beforeSend: function(xhr) {
                if ($formMasseur.attr("data-x-http-method") != "")
                    xhr.setRequestHeader('X-HTTP-METHOD', $formMasseur.attr("data-x-http-method"));
            },
            success: function(response) {
                if(parseInt(response.statusCode) == 0) {
                    var value = $formMasseur.attr("data-x-http-method");
                    var rowData = {};

                    rowData.fName = $fName.val();
                    rowData.midName = $midName.val();
                    rowData.lName = $lName.val();
                    rowData.gender = $gender.val();
                    rowData.nickname = $nickname.val();
                    rowData.active = $active.val();

                    if (value == "") { //add
                        rowData.masseurId = response.newMasseurId;
                        var json = JSON.parse(JSON.stringify(rowData));
                        table.row.add(json).draw();
                    } else if (value.toLowerCase() == "put") {    //edit here
                        rowData.masseurId = $masseurId.val();
                        table.row(thisRow).data(rowData).draw();
                    } else {
                        console.log("Unknown method.");
                    }

                    $('#form_masseur').find('input[type=text]').val('');
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
        $('#masseur_modal .modal-title').text("Add masseur record");
        $formMasseur.find('input[type=text]').val('');
        $masseurId.val('0');
        $formMasseur.attr("method", "POST");
        $formMasseur.attr("data-x-http-method", '');
    });

 
    //for update cmd    
    $('#masseurTable tbody').on('click', 'tr', function () {        
        thisRow = this;
        showEditDialog(table.row(this).data());

        $masseurId.val(table.row(this).data().masseurId);

        $formMasseur.attr("method", "POST");
        $formMasseur.attr("data-x-http-method", 'PUT');
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

                    $formMasseur.attr("data-x-http-method", "DELETE");
                    
                    $.ajax({
                        type: "POST",
                        url: $formMasseur.attr("action"),
                        data: {masseurIds: data},
                        dataType: "JSON",
                        beforeSend: function(xhr) {
                            if ($formMasseur.attr("data-x-http-method") != "")
                                xhr.setRequestHeader('X-HTTP-METHOD', $formMasseur.attr("data-x-http-method"));
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