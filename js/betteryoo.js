$(function () {

/* GLOBAL VARIABLES
========================================================================	 */


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


// Messagebox Options
var msgBoxOptions = {
	id:              'messageBox',
	position:        'top',
	backgroundColor: '#a1bea5',
	size:		    100,
	delay: 			2500,
	speed: 			500,
	fontSize: 	    '25px'
};

// Pretty container fade in
$(".fade").animate({ opacity: 1.0,}, 1500);


/* USER CONTROL PANEL FUNCTIONS
========================================================================	 */

//Message displayed when user attempts to add reminders
var upgradeMessage = "Oops, all of your free reminders are active. " +
					 "Please deactive one, or upgrade your account!";


// User Page - DateTime Picker
$('#startingOn').datetimepicker({
	ampm: true
});

// User Page - Edit Settings
$("a#editSettings").click(function(e) {
	e.preventDefault();
	$("#editSettingsWindow").fadeIn();
	
});

// User Page - Toggle Password Change
$("#changePassword").click(function(e) {
	e.preventDefault();
	$("#changePasswordWidget").toggle('slide');
});


// Close Settings
$("#settingsCancel").click(function(e) {
	e.preventDefault();
	$("#editSettingsWindow").fadeOut();
});


// User Page - Deactivate Reminder
$("div.deactivate").click(function(e) {
	e.preventDefault();
	
	var remId = this.id.substr(11);
	$.post("deactivate-reminder/", { reminder_id : remId }, function () {
		$("#deactivate-"+remId).removeClass("deactivate").addClass("activate");
		window.location.href=window.location.href;
	});
});


// User Page - Activate Reminder
$("div.activate").click(function(e) {
	e.preventDefault();
	var remId = this.id.substr(9);
	if (activeReminders == reminderLimit) {
		$.showMessage(upgradeMessage, msgBoxOptions);
	} else {
		$.post("activate-reminder/", { reminder_id : remId }, function () {
			$("#activate-"+remId).removeClass("activate").addClass("deactivate");
			window.location.href=window.location.href;
		});
	}
});


// User Page - Add a reminder
$("#addReminderButton").click(function (e) {
	// Make sure interval starts with "Every"
	if ($("#intervalText").val().substr(0,5).toLowerCase() != 'every') {
		$.showMessage('Alert time should start with "Every"', msgBoxOptions);
		$("#intervalText").focus();
		return false;
	}
	
	if ($("#intervalText").val() == 'How Often') {
		$.showMessage("Oops, Change the 'How Often' box to something like 'Every Day' or 'Every Three Weeks'.", msgBoxOptions);
		$("#intervalText").focus();
		return false;
	}
	
	// Make sure we recognize the interval
	if ($("#intervalText").val().toLowerCase().search('day') <= 0 &&
		$("#intervalText").val().toLowerCase().search('week') <= 0 &&
		$("#intervalText").val().toLowerCase().search('month') <= 0 &&
		$("#intervalText").val().toLowerCase().search('year') <= 0) {
		$.showMessage('"How Often" should be in the form "every day" or "every three weeks"', msgBoxOptions);
		$("#intervalText").focus();
		return false;
	}
	
	// Make sure user made a reminder
	if ($("#reminderText").val() == '' || $("#reminderText").val() == 'Your Reminder Message'){
		$.showMessage('Please add a reminder message.', msgBoxOptions);
		$("#reminderText").focus();
		return false;
	}
	
	// Make sure reminder length < 140 chars
	if ($("#reminderText").val().length > 140) {
		$.showMessage('Your reminder must be less than 140 characters.', msgBoxOptions);
		$("#reminderText").focus();
		return false;
	}
	
	// Make sure user hasn't surpassed limit
	if (activeReminders == reminderLimit) {
		$.showMessage(upgradeMessage, msgBoxOptions);
		return false;
	}
});


// Remove a reminder
$("div.removeReminder").click(function (e) {
	e.preventDefault();
	
	var remId = this.id.substr(7);
	$.post("remove-reminder/", { reminder_id : remId }, function(data) {
		$("#row-"+remId).fadeOut('fast', function() { $(this).slideUp(); });
	} );
	
	activeReminders--;
});

// Close Upgrade
$("#notToday").click(function(e) {
	e.preventDefault();
	$("#upgradeWindow").fadeOut();
});


// Fallback for old browsers & placeholder tag
$('input').addPlaceholder();


// Set focus text
$( "#intervalText" ).focus(function() {this.value = 'every '; });


// Set autocomplet feture
$( "#intervalText" ).autocomplete({
	source: availableTags,
	minLength: 0
});


// Set autocomplet feture
$( "#carrierInput" ).autocomplete({
	source: carrierNames,
	minLength: 0
});
	// Carrier Names
	var carrierNames = [
		'Verizon', 'ATT', 'Sprint',
		'tmomail.net', 'Nextel', 'Cingular',
		'Virgin Mobile', 'AllTel', 'Cell One',
		'omnipointpcs.com',	'qwestmp.com'
	];


// Maintain placeholder if no change is made
$( "#intervalText" ).blur(function () {
	if (this.value == 'Every ')
		this.value = 'Every Month';
});



/* REGISTRATION PAGE
========================================================================	 */

// Load time offset
var date = new Date();
$("#timeOffset").val(date.getTimezoneOffset());

// First Step - Set It Button
$("#setItButton").click(function(e) {
	e.preventDefault();
	
	// validate phone number
	var phoneNumber = $("#phoneText").val();
	
	if (phoneNumber == 'Your Phone #') {
		$.showMessage('Oops, it looks like you did not set your phone number..?', msgBoxOptions);
		$("#phoneText").focus();
		return;
	}
	
	// Remove non-numeric chars
	phoneNumber = phoneNumber.replace(/[^0-9]/g, '');
	
	// Verify length
	if (phoneNumber.length < 10) {
		$.showMessage('Oops, we can not recognize your phone number', msgBoxOptions);
		return;
	}
	
	// validate interval
	var interval = $("#intervalText").val();
	if (interval == 'How Often') {
		$.showMessage("Oops, Change the 'How Often' box to something like 'Every Day' or 'Every Three Weeks'.", msgBoxOptions);
		$("#intervalText").focus();
		return;
	}
	
	// Phone # is good so we send out an AJAX call to send a verification and load the next step
	$("#stepTwo").fadeIn(700);
	$.post("/send-verification/", { phone : phoneNumber }, function (data) {
		// Fade out default loading icon div
		$("#loading").fadeOut(1, function() {
			$("#stepConfirmation").fadeIn(200);
		});
	});
});


// Second Step - Verify the confirmation and show the next step
$("#stepPasswordButton").click(function(e) {
	e.preventDefault();
	
	// Send verification AJAX call
	$.post("/confirm-verification/", { confirmation : $("#confirmationText").val().toLowerCase() }, function (data) {
		// On success move next step
		if (data == 1) {
			$("#stepConfirmation").fadeOut(function() { 
				$("#stepPassword").fadeIn();
			});
		} else {
			$.showMessage('Oops, we do not recognize that verification code. Please try again.', msgBoxOptions);
		} 
	});
});


// Final Step -  Validate 
$("#completeRegistrationButton").click(function(e) {
		e.preventDefault();
	
	// Validate password length
	if ($("#password").val().length <= 5) {
		$.showMessage('You password must be 6 characters or more.', msgBoxOptions);
		return false;
	}
	
	// Validate passwords are the same. (No typos)
	if ($("#password").val() != $("#password2").val()) {
		$.showMessage('Oops, your passwords do not match. Please try again.', msgBoxOptions);
		return false;
	}
	
	if ($("#password").val() == 'password') {
		$.showMessage('Please change your password to something more secure.', msgBoxOptions);
		return false;
	}
	
	// Submit the register form
	$('#registerForm').submit();
});



// Go Back Link
$("#goBack").click(function(e) {
	e.preventDefault();
	$("#stepTwo").fadeOut();
});



}); /* End JQuery */

// Fall Back Placeholders For Old Browsers
ie_placeholder(document.getElementsByTagName('input'));
function ie_placeholder (fields) {
    for (var i = 0; i < fields.length; i++) {
        var field = fields[i];
        field.onfocus = function () {
            if (this.value == this.placeholder) {
                this.style.color = '';
                this.value = '';
            }
        };
        field.onblur = function () {
            if (this.value == '' && this.placeholder != null) {
                this.style.color = 'silver';
                this.value = this.placeholder;
            }
        };
        field.onblur();
    }
}