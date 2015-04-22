<?php
	
	include_once "includes/php/PHPMailer/PHPMailerAutoload.php";

	$mail = new PHPMailer;

        global $smtp;

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = "smtp.mail.yahoo.com";  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = "jdream_catcher@yahoo.com";                 // SMTP username
        $mail->Password = "";                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 25;                                    // TCP port to connect to

        $mail->From = "jdream_catcher@yahoo.com";
        $mail->FromName = 'yourspa Mailer';
        $mail->addAddress("jdream_catcher@yahoo.com");             // Add a recipient
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = 'Registration';

        $message = "Thank you.";
        $mail->Body    = $message;
        $mail->AltBody = "Thank you.";

        if( ! $mail->send())
            echo "Error sending email.";
?>
<html>
<head><title>Confirmation</title>
<link rel="stylesheet" type="text/css" href="includes/css/styles.css">
</head>
<body>
	<div class="wrapper">
		<h4>Thanks !!</h4>
	</div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
</body>
</html>