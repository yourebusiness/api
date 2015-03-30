$(document).ready(function () {
    $("#province").change(function() {
        $("#city").find("option").remove().end().append('<option value="0">-- City --</option>');

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
        var address = $('#address').val();
        var phoneNo = $('#phoneNo').val();
        var companyWebsite = $('#companyWebsite').val();
        var tin = $('#tin').val();

        if(jQuery.trim(company).length < 2 ) {
            var styles = {display : "block"};
            $('div.alert').css(styles);
            $("div.alert span#errorMessage").html("Invalid company name.");
            event.preventDefault();
            return;
        }
        if(jQuery.trim(address).length < 2 ) {
            var styles = {display : "block"};
            $('div.alert').css(styles);
            $("div.alert span#errorMessage").html("Invalid address name.");
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
                window.location.href='http://yourspa.com/admin';
            },
            error: function() { 
                alert("Error found on request.");
            }
        });

    });
});