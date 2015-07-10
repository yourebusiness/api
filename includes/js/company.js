$(document).ready(function () {
    $("#province").change(function() {
        $("#city").find("option").remove().end().append('<option value="0">-- City --</option>');

        var provinceId = $("#province").val();
        var city = $("#city");

        $.ajax({
            type: "GET",
            url:"http://yourspa.com/index.php/api/getCity/" + provinceId,
            success:function(data) {
                var json = jQuery.parseJSON(data);
                $.each(json, function (i, obj) {
                    city.append('<option value="' + obj.id + '">' + obj.cityName + '</option>');
                });
            },
            error: function() {
                console.log("Error.");
            }
        });
    });

    var companyError = addressError = phoneNoError = tinError = false;

    $('.btn-primary').on('click', function(event) {
        event.preventDefault();

        // let's clean up the error on click to refresh it
        

        var styles = {display : "none"},
            that = $('#form'),
            url = that.attr('action'),
            type = that.attr('method'),
            data = {};

        $('div.alert').css(styles);

        var $company = $('#company'),
            $address = $('#address'),
            $phoneNo = $('#phoneNo'),
            $companyWebsite = $('#companyWebsite'),
            $tin = $('#tin');

        if(jQuery.trim($company.val()).length < 2 ) {
            $company.parent().parent().addClass("has-error has-feedback");
            if (!companyError) {
                $company.after('<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>');
                $company.after('<span id="inputError2Status" class="sr-only">(error)</span>');
                companyError = true;
            }

            return;
        }

        if(jQuery.trim($address.val()).length < 2 ) {
            $address.parent().parent().addClass("has-error has-feedback");
            if (!addressError) {
                $address.after('<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>');
                $address.after('<span id="inputError2Status" class="sr-only">(error)</span>');
                addressError = true;
            }

            return;
        }

        if(jQuery.trim($phoneNo.val()).length < 7) {
            $phoneNo.parent().parent().addClass("has-error has-feedback");
            if (!phoneNoError) {
                $phoneNo.after('<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>');
                $phoneNo.after('<span id="inputError2Status" class="sr-only">(error)</span>');
                phoneNoError = true;
            }

            return;
        }

        if (jQuery.trim($tin.val()).length > 0 && jQuery.trim($tin.val()).length != 12) {
            $tin.parent().parent().addClass("has-error has-feedback");
            if (!tinError) {
                $tin.after('<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>');
                $tin.after('<span id="inputError2Status" class="sr-only">(error)</span>');
                tinError = true;
            }

            return;
        }

        that.find('[name]').each(function(index, value) {
            var that = $(this),
                name = that.attr("name"),
                value = that.val();
                data[name] = value;
        });

        $.ajax({
            url: url,
            type: type,
            data: data,
            success: function (response) {
                window.location.href='http://yourspa.com/index.php/admin';
            },
            error: function() { 
                console.log("Error found on request.");
            }
        });

    });

    jQuery.fn.extend({
        removeadminLoginCookie: function() {
            $.cookie('yourspaFunc_CompanyProfile', null, {path: '/'});
        }
    });
});