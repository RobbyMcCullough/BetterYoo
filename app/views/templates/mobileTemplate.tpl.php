<!DOCTYPE html> 
<html> 
	<head> 
	<title>Page Title</title> 
	<link rel="stylesheet" type="text/css" href="<?=WEBROOT?>css/custom-theme/jquery-ui-1.8.12.custom.css">
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0a4.1/jquery.mobile-1.0a4.1.min.css" />
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.5.2.min.js"></script>
	<script type="text/javascript" src="<?=WEBROOT?>js/libs/jquery-ui-1.8.12.custom.min.js"></script>
	<script type="text/javascript" src="http://code.jquery.com/mobile/1.0a4.1/jquery.mobile-1.0a4.1.min.js"></script>
	<style>
		.ui-content { background: url('<?=WEBROOT?>images/background.png') repeat-x; }
		#logo { text-align: center; }
		.hidden { display: none; }
		.ui-autocomplete { max-height: 100px; overflow-y: auto; }
		#splashPage { text-align: center; }
		
	</style>
	<script>
		$(function() {
				
			// Available autocomplete options
			var availableTags = [
				"every day", "every week", "every month", "every year",
				"every two days" , "every two weeks", "every two months",
				"every three days" , "every three weeks", "every three months",
				"every four days", "every four weeks", "every four months",
				"every five days", "every five weeks", "every five months",
				"every six days", "every six weeks", "every six months",
				"every seven days", "every seven weeks", "every seven months",
				"every eight days", "every eight weeks", "every eight months",
				"every nine days", "every nine weeks", "every nine months",
				"every ten days", "every ten weeks", "every ten months",
				
				"every 2 days" , "every 2 weeks", "every 2 months" ,
				"every 3 days" , "every 3 weeks", "every 3 months" ,
				"every 4 days" , "every 4 weeks", "every 4 months" ,
				"every 5 days" , "every 5 weeks", "every 5 months" ,
				"every 6 days" , "every 6 weeks", "every 6 months" ,
				"every 7 days" , "every 7 weeks", "every 7 months" ,
				"every 8 days" , "every 8 weeks", "every 8 months" ,
				"every 9 days" , "every 9 weeks", "every 9 months" ,
				"every 10 days" , "every 10 weeks", "every 10 months"
			];	

		
			// Load time offset
			var date = new Date();
			$("#timeOffset").val(date.getTimezoneOffset());
			
			$( "#interval" ).focus(function() {this.value = 'every '; });
			// Set autocomplet feture
			$( "#interval" ).autocomplete({
				source: availableTags,
				minLength: 0
			});
			
			// Setup hidden phone number
			$("#phone").change(function() {
				$("#phone2").val($(this).val());
			});
		});
	</script>
</head> 
<body> 

<!-- Splash Page  -->
<div data-role="page" id="userPage" data-theme="c">
	<div data-role="header" data-theme="d"><img src="<?=WEBROOT?>images/logo.png" alt="BetterYoo Logo" style="zoom: 65%;" /></div>
	<div data-role="content">
		<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="d"> 
			<li data-role="list-divider">Overview</li>
			<li> 
				<select name="slider2" id="slider2" data-role="slider"> 
					<option value="off">Off</option> 
					<option value="on">On</option> 
				</select> 
	        	<h2>Call Mom every week. next alert: 03/04/2001 09:23</h2>
	        	<a href="index.html" data-role="button" data-icon="delete" style="width: 20px;"></a>
			</li> 
		</ul>
	</div>
</div>


<!-- Splash Page -->
<div data-role="page" id="splashPage" data-theme="c">
	<div data-role="header" data-theme="d"><img src="<?=WEBROOT?>images/logo.png" alt="BetterYoo Logo" style="zoom: 65%;" /></div>
	<div data-role="content">	
		<h3>BetterYoo is a free text message reminder service.</h3>
		<form>
			<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="d">
				<li data-role="list-divider">Sign Up</li>
				<li>
					<h1>Your Phone:</h1>
					<p>We'll <strong>never</strong> release your phone number.</p>
					<input type="tel" name="phone" id="phone">
					<input type="text" id="timeOffset" name="timeOffset" style="display:none;">
				</li>
				<li>
					<h1>Verify your number:</h1>
					<input type="submit" value="Send Verification" id="setItButton">
				</li>
			</ul>
		</form>
		
		<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="d">
			<li data-role="list-divider">More Information</li>
			<li><a href="http://blog.betteryoo.com/about/">About BetterYoo</a></li>
			<li><a href="http://blog.betteryoo.com/registration-faq/">Registration FAQ</a></li>
			<li><a href="http://blog.betteryoo.com/contact/">Contact</a></li>
		</ul>

	</div><!-- /content -->
</div><!-- /page -->

<!-- Second Step -->
<div data-role="page" id="secondStep"  data-theme="c">
	<div id="logo"><img src="<?=WEBROOT?>images/logo.png" alt="BetterYoo Logo" style="zoom: 50%;" /></div>
	<div data-role="content">	
		<form action="<?=WEBROOT?>register/" method="POST" id="registerForm">
			<input type="tel" name="phone" id="phone2" style="display:none;">
			
			<label for="confirmation">Confirmation:</label>
			<input type="text" name="confirmation" id="confirmation" placeholder="Confirmation Code">
			
			<label for="reminder">Reminder:</label>
			<input type="text" name="reminder" id="reminder" placeholder="Visit the dentist">
			
			<label for="interval">How often:</label>
			<input type="text" name="interval" id="interval" placeholder="How Often" value="every 6 months">
			
			<label for="password">Password:</label>
			<input type="password" name="password" id="password" placeholder="password" required>
			
			<label for="password">Confirm password:</label>
			<input type="password" name="password2" id="password2" placeholder="password again" required>
			<input type="submit" value="Finish" id="completeRegistrationButton" class="submitButton">
			
	</div>
</div><!-- /page -->

</body>
</html>