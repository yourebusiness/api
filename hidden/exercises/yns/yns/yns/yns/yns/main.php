<html>
<head><title>Main</title>
<link rel="stylesheet" type="text/css" href="includes/css/styles.css">
</head>
<body>
	<div class="wrapper">
		<form method="post" action="response.php">
			<label for="name"><b>Name</b></label> <br />
			<input type="text" id="name" name="name" placeholder="Your name here..." /> <br />
			<label for="email"><b>Mail_Address</b></label> <br />
			<input type="email" id="email" name="email" placeholder="Your email add here..." /> <br />
			<label for="message"><b>Message</b></label> <br />
			<textarea id="message" name="message" placeholder="Message here..."></textarea> <br />

			<button type="button" id="clear">Clear</button>
			<button type="submit" id="submit" name="submit">Confirm</button>
		</form>
	</div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script type="text/javascript" src="includes/js/main.js"></script>
</body>
</html>