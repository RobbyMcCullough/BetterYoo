<noscript>I am sorry, our service requires Javascript.</noscript>
<div id="innerContainer" class="fade">
	<div id="logo"><img src="images/logo.png" alt="BetterYoo Logo"></div>
	<div id="logoFormContainer">
	<img src="<?=WEBROOT?>images/yoo-the-elephant.png" id="yoo" />
		
		<h2>Send daily, weekly, monthly, &amp; annual reminders to your phone. Forever for free!</h2>
		
		<form action="<?=WEBROOT?>register/" method="POST" id="registerForm">
			<input id="reminderText" type="text" name="reminder" placeholder="<?=$tmpl['reminderValue']?>" required>
			<input id="intervalText" type="text" name="interval" placeholder="<?=$tmpl['intervalValue']?>" value="<?=$tmpl['intervalReminder']?>" required><br />
			<input id="phoneText" type="tel" name="phone" placeholder="Your Phone #" required>
			<input type="text" id="timeOffset" name="timeOffset" class="hidden" />
			
			<div id="stepTwo" class="stepTwo hidden">
				<div class="s2Container">
					<div id="loading">
						<h1>Sending Confirmation</h1>
						<img src="<?=WEBROOT?>images/loading.gif" />
					</div>
				
					<div class="hidden calign" id="stepConfirmation">
						<h1>Excellent</h1>
						<hr />
						<p>I just sent a secret code to your phone. Please enter it below.</p>
						<br />
						<input type="text" name="confirmation" id="confirmationText" placeholder="Confirmation Code">
						<input type="submit" value="Step Two" id="stepPasswordButton" class="submitButton"><br />
						<a href="http://blog.betteryoo.com/didnt-receive-a-confirmation/">Didn't receive a confirmation? It may take a minute or two but if it doesn't show up click here</a>
					</div>
					
					<div class="hidden calign" id="stepPassword">
						<h1>Awesome!</h1>
						<hr />
						<p>Now, just <strong>set a password</strong> and you're ready to go!<p>
						<br />
						<input type="password" name="password" id="password" placeholder="password" required>
						<input type="password" name="password2" id="password2" placeholder="password" required>
						<input type="submit" value="Finish" id="completeRegistrationButton" class="submitButton">
					</div>
				</div>
			</div><!-- END Step Two -->
			
			
			<p id="gettingStarted">
				We'll never disclose your phone number!<br />
				Questions? See the <a href="http://blog.betteryoo.com/registration-faq/" title="BetterYoo Frequently Asked Questions">FAQ page</a>.
			</p>	
			
			<input type="submit" value="Create" id="setItButton">
		
		</form>
	</div><!-- END Logo Form Container -->
</div><!-- END innerContainer -->