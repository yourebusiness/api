$(document).ready(function () {
    $("#province").change(function() {
        $("#city").find("option").remove().end().append('<option value="0">-- select --</option>');

        var provinceId = $("#province").val();
        var city = $("#city");

        $.ajax({
            type: "GET",
            url:"http://yourspa.com/api/getCity/" + provinceId,
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

    $('.btn-primary').on('click', function(event) {
        event.preventDefault();

        var styles = {display : "none"},
            that = $('#form'),
            url = that.attr('action'),
            type = that.attr('method'),
            data = {};

        $('div.alert').css(styles);

        var company = $('#company').val();
        var phoneNo = $('#phoneNo').val();
        var companyWebsite = $('#companyWebsite').val();
        var tin = $('#tin').val();
        var fName = $('#fName').val();
        var lName = $('#lName').val();
        var userEmail = $('#userEmail').val();

        if(jQuery.trim(company).length < 2 ) {
            var styles = {display : "block"};
            $('div.alert').css(styles);
            $("div.alert span#errorMessage").html("Invalid company name.");
            event.preventDefault();
            return;
        }
        if(jQuery.trim(phoneNo).length < 7) {
            var styles = {display : "block"};
            $('div.alert').css(styles);
            $("div.alert span#errorMessage").html("Invalid phone number.");
            event.preventDefault();
            return;
        }
        if (jQuery.trim(tin).length < 12) {
            var styles = {display : "block"};
            $('div.alert').css(styles);
            $("div.alert span#errorMessage").html("Invalid company TIN.");
            event.preventDefault();
            return;
        }
        if (jQuery.trim(fName).length < 2) {
            var styles = {display : "block"};
            $('div.alert').css(styles);
            $("div.alert span#errorMessage").html("Invalid First name.");
            event.preventDefault();
            return;
        }
        if (jQuery.trim(lName).length < 2) {
            var styles = {display : "block"};
            $('div.alert').css(styles);
            $("div.alert span#errorMessage").html("Invalid Last name.");
            event.preventDefault();
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
                
            },
            error: function() { 
                alert("Error found on request.");
            }
        });

    });
});