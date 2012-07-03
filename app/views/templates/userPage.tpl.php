<div id="logo"><img src="<?=WEBROOT?>images/logo.png" alt="BetterYoo Logo" /></div>

<div id="controlPanel">
	
	<!-- Reminder List -->
	<table>
		<thead>
			<tr>
				<th class="active">Status</th>
				<th class="reminder">Reminder Message</th>
				<th class="interval">Next Alert</th>
				<th class="buttons"></th>
			</tr>
		</thead>
		<tbody>
		<?php for ($i=0; $i<count($tmpl['start_time']);$i++) : ?>
			<tr id="row-<?=$tmpl['reminder_id'][$i]?>" <? if ($tmpl['active'][$i] == 0) : ?>class="off"<? endif; ?>>
				<td class="status">
					
					<? if ($tmpl['active'][$i] == 1) : ?>
						<div class="deactivate" id="deactivate-<?=$tmpl['reminder_id'][$i]?>"></div>
					<? else: ?>
						<div class="activate" id="activate-<?=$tmpl['reminder_id'][$i]?>"></div>
					<? endif; ?>
				
				</td>
				
				<td class="reminder"><?=$tmpl['reminder_message'][$i]?> <?=$tmpl['phonetic_interval'][$i]?></td>
				<? if ($tmpl['active'][$i] == 1) : ?>
					<td class="interval small"><?=$tmpl['next_push'][$i]?></td>
				<? else: ?>
					<td class="interval small">Reminder is off</td>
				<? endif; ?>
				<td class="buttons">
					<div class="removeReminder" id="remove-<?=$tmpl['reminder_id'][$i]?>"></div>
				</td>
			</tr>
		<? endfor; ?>
		</tbody>
		
	</table>
	<div class="underShadow"></div><!-- END Reminder List -->
	
	
	<!-- ADD NEW REMINDER -->
	<div class="fauxTableContainer">
		<div class="fauxTableHead">Add New Reminder</div>
		<div class="fauxTableBody newReminder">
			<form action="<?=WEBROOT?>add-reminder/" method="POST" id="newReminder">
				<textarea id="reminderText" name="reminder" placeholder="Your Reminder Message"></textarea>
				<input id="intervalText" type="text" name="interval" placeholder="How Often" value="How Often" required>
				<input type="text" name="startingOn" id="startingOn" value="" placeholder="Starting Now" /><br />
				
				<!-- Hidden Input -->
				<input type="text" name="userId" style="display:none;" value="<?=$tmpl['id']?>">
				
				<!-- Submit -->
				<input type="submit" value="Set It" id="addReminderButton" class="submitButton">
			</form>
		</div>
	</div>
	<div class="underShadow"></div><!-- END ADD NEW REMINDER -->
	
	<!-- Account Settings -->
	<div class="fauxTableContainerSmall">
		<div class="fauxTableHead">Account Settings</div>
		<div class="fauxTableBody">
			<ul class="ctrlPanelList">
				<li><label>Phone Number:</label> <?=$tmpl['phone_number']?></li>
				<li><label>E-Mail:</label> <?=$tmpl['email']?></li>
				<li><label>Carrier:</label> <?=$tmpl['carrier']?></li>
				<li><label>Active Reminders: </label> <?=$tmpl['active_reminders']?> / <?=$tmpl['reminder_limit']?></li>
			</ul>
			<a href="edit" id="editSettings"><img src="<?=WEBROOT?>images/gear.png" class="gear" alt="gear" /> edit</a>
				
		</div>
	<div class="underShadowSmall"></div>
	</div><!-- END Account Settings -->
	
	<!-- Ways To Upgrade -->
	<div class="fauxTableContainerSmall">
		
		<div class="fauxTableHead">
			<? if ($tmpl['pro_user'] == 0) : ?>Upgrade Today: First Month Free
			<? else: ?>Account Level: Pro
			<? endif; ?>
		</div>
			<div class="fauxTableBody" style="font-size: 16px; line-height: 19px;">
			
			<? if ($tmpl['pro_user'] == 0) : ?>
			
				<a href="https://betteryoo.pintpay.com/?identifier=<?=$tmpl['phone_number']?>" title="Upgrade for $1.99/month." id="upgrade">
					<img src="images/unlimited-reminders.png" id="upgradeButton" alt="Upgrade To Unlimited Reminders" />
				</a>
				I built BetterYoo to provide a simple service
				for a simple price. Your business enables me
				to build more cool stuff and I wholeheartedly appreciate it!
			
			<? else: ?>
			
				<div class="fade" style="width: 230px; height: 85px; float: left; margin: 15px;">
					<object type="application/x-shockwave-flash" data="https://clients4.google.com/voice/embed/webCallButton" width="230" height="85"><param name="movie" value="https://clients4.google.com/voice/embed/webCallButton" /><param name="wmode" value="transparent" /><param name="FlashVars" value="id=c27eaf66abca27dba50ae0cbe07f6c015d552bb6&style=0" /></object>
				</div>
				<p style="padding-top: 5px;">Thank you for your continued support. Feel free to <a href="http://blog.betteryoo.com/contact/" title="Contact Page">contact me</a> if you have any feeback, questions, or comments!</p>
				
			<? endif; ?>

		</div>
	<div class="underShadowSmall"></div>
	</div>
</div><!-- END Control Panel -->


<!-- Account Settings | HIDDEN -->
<div id="editSettingsWindow" class="stepTwo hidden">
	<div class="s2Container">
		<h1>Account Settings</h1>
		<hr />
		<form action="<?=WEBROOT?>update-settings/" method="POST">
			<label>Carrier</label><br />
			<input type="text" name="carrier" id="carrierInput" placeholder="<?=$tmpl['carrier']?>" /><br />
			<hr />
			
			<label>E-Mail</label><br />
			<input type="text" name="email" id="email" placeholder="<?=$tmpl['email']?>" /><br />
			<hr />

			<a href="" id="changePassword">Change Password</a>
			<div id="changePasswordWidget" class="hidden"><br />
				<label>Old Password</label><br />
				<input type="password" name="oldPassword" /><br />
				
				<label>New Password</label><br />
				<input type="password" name="newPassword" /><br />
				
				<label>New Password Again</label><br />
				<input type="password" name="newPassword2" />
			</div>
			<hr />
			<div class="calign">
				<a href="" class="no" id="settingsCancel">Cancel</a>
				<input type="submit" value="save" class="submitButton" />
			</div>
		</form>
	</div>
</div><!-- end Account Settings -->