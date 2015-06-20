$(function () {
    $("#province").change(function() {
        $("#city").find("option").remove().end().append('<option value="0">-- select --</option>');

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

    $('.btn-primary').on('click', function(event) {

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
        var province = $('#province').val();
        var city = $('#city').val();
        var gender = $('#gender').val();
        var password = $('#password').val();
        var confirmPassword = $('#confirmPassword').val();
        var captcha = $('#captcha').val();

        if (province == 0) {
            var styles = {display : "block"};
            $('div.alert').css(styles);
            $("div.alert span#errorMessage").html("Please choose province.");
            return false;
        }
        if (city == 0) {
            var styles = {display : "block"};
            $('div.alert').css(styles);
            $("div.alert span#errorMessage").html("Please choose city.");
            return false;
        }
        if (gender === 0) {
            var styles = {display : "block"};
            $('div.alert').css(styles);
            $("div.alert span#errorMessage").html("Please choose your gender.");
            return false;
        }

        if(jQuery.trim(company).length < 2 ) {
            var styles = {display : "block"};
            $('div.alert').css(styles);
            $("div.alert span#errorMessage").html("Invalid company name.");
            return false;
        }
        if(jQuery.trim(phoneNo).length < 7) {
            var styles = {display : "block"};
            $('div.alert').css(styles);
            $("div.alert span#errorMessage").html("Invalid phone number.");
            return false;
        }
        if (jQuery.trim(tin).length < 12) {
            var styles = {display : "block"};
            $('div.alert').css(styles);
            $("div.alert span#errorMessage").html("Invalid company TIN.");
            return false;
        }
        if (jQuery.trim(fName).length < 2) {
            var styles = {display : "block"};
            $('div.alert').css(styles);
            $("div.alert span#errorMessage").html("Invalid First name.");
            return false;
        }
        if (jQuery.trim(lName).length < 2) {
            var styles = {display : "block"};
            $('div.alert').css(styles);
            $("div.alert span#errorMessage").html("Invalid Last name.");
            return false;
        }
        if (jQuery.trim(password).length < 6) {
            var styles = {display : "block"};
            $('div.alert').css(styles);
            $("div.alert span#errorMessage").html("Password should be at least 6 alphanumeric characters.");
            return false;
        }
        if (jQuery.trim(confirmPassword).length < 6) {
            var styles = {display : "block"};
            $('div.alert').css(styles);
            $("div.alert span#errorMessage").html("Password should be at least 6 alphanumeric characters.");
            return false;
        }
        if (password !== confirmPassword) {
            var styles = {display : "block"};
            $('div.alert').css(styles);
            $("div.alert span#errorMessage").html("Password and confirm password are not the same.");
            return false;
        }
        if(jQuery.trim(captcha).length < 5) {
            var styles = {display : "block"};
            $('div.alert').css(styles);
            $("div.alert span#errorMessage").html("Invalid captcha.");
            return false;
        }

        that.find('[name]').each(function(index, value) {
            var that = $(this),
                name = that.attr("name"),
                value = that.val();
                data[name] = value;
        });

        

    });
});