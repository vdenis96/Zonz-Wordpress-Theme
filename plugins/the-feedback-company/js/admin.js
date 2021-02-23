jQuery( document ).ready(function() {
	// initialize fancy tooltips
	jQuery(document).tooltip();

	// update on changes of formfields
	jQuery('.feedbackcompany-formfield').change(feedbackcompany_updateform);
	feedbackcompany_updateform();
});

function feedbackcompany_updateform()
{
	// update GUI

	// disable non-required fields

	if (jQuery('#feedbackcompany_invitation_enabled').val() == 0)
	{
		jQuery('#feedbackcompany_invitation_orderstatus').prop('disabled', true);
		jQuery('#feedbackcompany_invitation_delay').prop('disabled', true);
		jQuery('#feedbackcompany_invitation_delay_unit').prop('disabled', true);
		jQuery('#feedbackcompany_invitation_reminder_enabled').prop('disabled', true);
		jQuery('#feedbackcompany_invitation_reminder').prop('disabled', true);
		jQuery('#feedbackcompany_invitation_reminder_unit').prop('disabled', true);
	}
	else
	{
		jQuery('#feedbackcompany_invitation_orderstatus').prop('disabled', false);
		jQuery('#feedbackcompany_invitation_delay').prop('disabled', false);
		jQuery('#feedbackcompany_invitation_delay_unit').prop('disabled', false);
		jQuery('#feedbackcompany_invitation_reminder_enabled').prop('disabled', false);

		if (jQuery('#feedbackcompany_invitation_reminder_enabled').val() == 0)
		{
			jQuery('#feedbackcompany_invitation_reminder').prop('disabled', true);
			jQuery('#feedbackcompany_invitation_reminder_unit').prop('disabled', true);
		}
		else
		{
			jQuery('#feedbackcompany_invitation_reminder').prop('disabled', false);
			jQuery('#feedbackcompany_invitation_reminder_unit').prop('disabled', false);
		}
	}

	// determine main reviews widget preview image url
	var mainwidget_img = 'main-';
	mainwidget_img += jQuery('#feedbackcompany_mainwidget_size').val() + '-';
	mainwidget_img += jQuery('#feedbackcompany_mainwidget_amount').val() + '.png';

	// determine bar reviews widget preview image url
	var barwidget_img = 'bar.png';

	// determine sticky reviews widget preview image url
	var stickywidget_img = 'sticky.gif';

	// update gui with new image urls
	jQuery('#feedbackcompany_mainwidget_preview').attr('src', feedbackcompany_admin_javascript.plugins_url + mainwidget_img);
	jQuery('#feedbackcompany_barwidget_preview').attr('src', feedbackcompany_admin_javascript.plugins_url + barwidget_img);
	jQuery('#feedbackcompany_stickywidget_preview').attr('src', feedbackcompany_admin_javascript.plugins_url + stickywidget_img);
}

function feedbackcompany_formerror(message)
{
	// make labels red
	jQuery('#feedbackcompany_invitation_delay').parent().prev('th').addClass('feedbackcompany-error');
	jQuery('#feedbackcompany_invitation_reminder').parent().prev('th').addClass('feedbackcompany-error');
	// make inputs red
	jQuery('#feedbackcompany_invitation_delay').addClass('feedbackcompany-error');
	jQuery('#feedbackcompany_invitation_delay_unit').addClass('feedbackcompany-error');
	jQuery('#feedbackcompany_invitation_reminder').addClass('feedbackcompany-error');
	jQuery('#feedbackcompany_invitation_reminder_unit').addClass('feedbackcompany-error');

	// display error message
	jQuery('#feedbackcompany_invitation_reminder_unit').next('p').text(message);
	// make it red
	jQuery('#feedbackcompany_invitation_reminder_unit').next('p').addClass('feedbackcompany-error');

	// scroll there
	jQuery('html, body').animate({
		scrollTop: jQuery('#feedbackcompany_invitation_delay').offset().top - 200
	}, 500);
}
function feedbackcompany_validateform()
{
	// if
	if (jQuery('#feedbackcompany_invitation_enabled').val() == 1
		&& jQuery('#feedbackcompany_invitation_reminder_enabled').val() == 1)
	{
		// none or both fields should be weekdays - not just one
		if (jQuery('#feedbackcompany_invitation_delay_unit').val() == 'weekdays'
			^ jQuery('#feedbackcompany_invitation_reminder_unit').val() == 'weekdays')
		{
			feedbackcompany_formerror('When selecting weekdays as the delay, both the invitation delay and the reminder delay need to use weekdays');
			return false;
		}

		// calculcate if the delay is long enough
		var units = {'minutes': 1, 'hours': 60, 'days': 1440, 'weekdays': 1440};
		var invitation = jQuery('#feedbackcompany_invitation_delay').val()
			* units[jQuery('#feedbackcompany_invitation_delay_unit').val()];
		var reminder = jQuery('#feedbackcompany_invitation_reminder').val()
			* units[jQuery('#feedbackcompany_invitation_reminder_unit').val()];

		if ((reminder - invitation) < 1440)
		{
			feedbackcompany_formerror('The amount of time between the invitation delay and the reminder delay should be at least a full day');
			return false;
		}
	}

	// validated!
	return true;
}

function feedbackcompany_copytoclipboard(event, element_id)
{
	event.preventDefault();
	element = document.getElementById(element_id);
	element.select();
	document.execCommand('copy');
}
