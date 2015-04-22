<html>
<head><title>Response</title>
<link rel="stylesheet" type="text/css" href="includes/css/styles.css">
</head>
<body>
	<div class="wrapper">
		<form method="post" action="confirm.php">
			<?php
				$name = $_POST["name"];
				$email = $_POST["email"];
				$message = $_POST["message"];
			?>
			<label><b>Name</b></label><br />
				<input type="hidden" id="name" name="name" value="<?php echo $name; ?>"><?php echo $name; ?> <br />

			<label><b>Mail_address</b></label><br />
				<input type="hidden" id="email" name="email" value="<?php echo $email; ?>" /><?php echo $email; ?> <br />

			<label><b>Message</b></label> <br />
			    <input type="hidden" id="message" name="message" value="<?php echo $message; ?>" />	<?php echo $message, " I am ", $name, "." ?>

			<br />
			<button type="button" id="back" onclick="window.history.back()">Back</button>
			<button type="submit" id="send">Send</button>
		</form>
	</div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script type="text/javascript" src="includes/js/response.js"></script>
</body>
</html>