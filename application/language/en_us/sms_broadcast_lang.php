<?php

defined('BASEPATH') or exit('No direct script access allowed');

// View Waba
$lang['sms_broadcast_waba_header'] = "SMS Publication";
$lang['sms_broadcast_waba_add'] = "New SMS publication";
$lang['sms_broadcast_waba_edit'] = "Edit SMS publication";

//View 
$lang['sms_broadcast_view'] = "View campaign";
$lang['sms_broadcast_timeline_view'] = "Timeline";
$lang['sms_broadcast_edit_information'] = "Informações da campanha";

$lang['sms_broadcast_segmented_view'] = "Segmented campaign";
$lang['sms_broadcast_segmented_view_yes'] = "Yes";
$lang['sms_broadcast_segmented_view_no'] = "No";
$lang['sms_broadcast_selected_group_view'] = "Selected group";
$lang['sms_broadcast_select_channel_view'] = "Selected channel";
$lang['sms_broadcast_schedule_hour_view'] = "Scheduled time";
$lang['sms_broadcast_description_sent_view'] = "SMS view";

$lang['sms_broadcast_timeline_creation'] = "created the campaign";
$lang['sms_broadcast_timeline_resend'] = "resent the campaign";
$lang['sms_broadcast_timeline_edited'] = "edited the campaign";
$lang['sms_broadcast_timeline_canceled'] = "canceled the campaign";
$lang['sms_broadcast_timeline_send_now'] = "changed to send now";
$lang['sms_broadcast_timeline_creation_api'] = "Campaign created via external system integration";

// View find 
$lang['sms_broadcast_topnav'] = "SMS Publication";
$lang['sms_broadcast_btn_new'] = "New";
$lang['sms_broadcast_btn_filter'] = "Filter";
$lang['sms_broadcast_btn_export'] = "Export";

$lang['sms_broadcast_column_btn_pause'] = "Pause";
$lang['sms_broadcast_column_btn_resume'] = "Resume";
$lang['sms_broadcast_column_btn_cancel'] = "Cancel";
$lang['sms_broadcast_column_scheduling'] = "Schedule Date";
$lang['sms_broadcast_column_title'] = "Title";
$lang['sms_broadcast_column_status'] = "Status";

$lang['sms_broadcast_filter_title'] = "Filter broadcast";
$lang['sms_broadcast_filter_search'] = "Title";
$lang['sms_broadcast_filter_search_placeholder'] = "Search...";
$lang['sms_broadcast_filter_status'] = "Status";
$lang['sms_broadcast_filter_status_select'] = "Select...";
$lang['sms_broadcast_filter_status_processing'] = "Processing";
$lang['sms_broadcast_filter_status_processed'] = "Processed";
$lang['sms_broadcast_filter_status_send'] = "Sent";
$lang['sms_broadcast_filter_status_canceling'] = "Canceling";
$lang['sms_broadcast_filter_status_cancel'] = "Canceled";
$lang['sms_broadcast_filter_status_sent'] = "Sent";
$lang['sms_broadcast_filter_status_waiting'] = "Waiting";
$lang['sms_broadcast_filter_period'] = "Period";
$lang['sms_broadcast_filter_period_placeholder_date_start'] = "Date start";
$lang['sms_broadcast_filter_period_placeholder_date_end'] = "Date end";
$lang['sms_broadcast_filter_btn_return'] = "Return";
$lang['sms_broadcast_filter_btn_search'] = "Search";

$lang['sms_broadcast_export_title'] = "Information export";
$lang['sms_broadcast_export_email'] = "Do you want send data for the email";
$lang['sms_broadcast_export_email_placeholder'] = "Inform the e-mail for export";
$lang['sms_broadcast_export_btn_confirm'] = "Confirm";
$lang['sms_broadcast_export_btn_return'] = "Return";

// Generic add e edit // 
$lang['sms_broadcast_title'] = "Title";
$lang['sms_broadcast_date_scheduling'] = "Schedule date";
$lang['sms_broadcast_date_scheduling_placeholder'] = "Schedule date";
$lang['sms_broadcast_hour_scheduling'] = "Schedule time";
$lang['sms_broadcast_segments_select_placeholder'] = "Select";
$lang['sms_broadcast_segments_select_group'] = "Select group";
$lang['sms_broadcast_segments_select_group_placeholder'] = "Select";
$lang['sms_broadcast_message'] = "Message: (Up to 140 characters or 70 with special characters) | Remaining Characters:";
$lang['sms_broadcast_status_message_placeholder'] = "Type here...";
$lang['sms_broadcast_btn_cancel'] = "Cancel";
$lang['sms_broadcast_btn_return'] = "Return";
$lang['sms_broadcast_btn_save'] = "Save";
$lang['sms_broadcast_choose_type'] = "Select campaign type";

// View add // 
$lang['sms_broadcast_add_title'] = "New";
$lang['sms_broadcast_add_information'] = "Add new campaign";

// Modal campaing estimate
$lang['campaign_estimate_title'] = "Pay attention to the sending time of the scheduled campaign";
$lang['campaign_estimate_notify_on_modal'] = "The estimated end of this campaign exceeds the established times for the channel. What do you want to do?";
$lang['campaign_estimate_suspend_campaign'] = "Suspend Campaign ";
$lang['campaign_estimate_suspend_campaign_extension'] = "Immediately cancel the campaign schedule.";
$lang['campaign_estimate_review_queue'] = "Review submission queue";
$lang['campaign_estimate_review_queue_extension'] = "Manage campaigns to free up queue space";
$lang['campaign_estimate_change_date'] = "Change date";
$lang['campaign_estimate_change_date_extension'] = "Choose another date and time to launch the campaign.";
$lang['campaign_estimate_send_partially'] = "Partially send";
$lang['campaign_estimate_send_partially_extension'] = "Be aware that only part of your base will receive the campaign.";
$lang['campaign_estimate_send_after_hours'] = "Send after hours";
$lang['campaign_estimate_send_after_hours_extension'] = "Use the time necessary to send the campaign to the entire base.";
$lang['campaign_estimate_suspend_notify_title'] = "Is he sure?";
$lang['campaign_estimate_suspend_notify_text'] = "Want to unschedule this campaign?";
$lang['campaign_estimate_suspend_notify_ok'] = "Yes";
$lang['campaign_estimate_suspend_notify_cancel'] = "No";
$lang['campaign_estimate_suspend_notify_deleted_title'] = "Deleted!";
$lang['campaign_estimate_suspend_notify_notify_deleted_text'] = "Campaign removed successfully!";
$lang['campaign_estimate_partial_notify_title'] = "Is he sure?";
$lang['campaign_estimate_partial_notify_text'] = "This campaign will be sent respecting the previously configured schedule for the selected channels.";
$lang['campaign_estimate_partial_notify_ok'] = "Yes";
$lang['campaign_estimate_partial_notify_cancel'] = "No";
$lang['campaign_estimate_partial_notify_add_title'] = "Publication registered!";
$lang['campaign_estimate_partial_notify_sucess'] = "Campaign scheduled successfully!";
$lang['campaign_estimate_partial_notify_notify_deleted_text'] = "Campaign removed successfully!";
$lang['campaign_estimate_after_notify_text'] = "This campaign will be sent to every contact base with no time limit.";

// column
$lang['sms_broadcast_datatable_column_status_processing'] = "Waiting to be sent";
$lang['sms_broadcast_datatable_column_status_send'] = "Sent";
$lang['sms_broadcast_datatable_column_status_called_off'] = "Canceled";
$lang['sms_broadcast_datatable_column_status_canceling'] = "Canceling";
$lang['sms_broadcast_datatable_column_status_paused'] = "Paused";
$lang['sms_broadcast_datatable_column_status_sending'] = "Sending";

// dropdown
$lang['sms_broadcast_datatable_column_action_view'] = "View";
$lang['sms_broadcast_datatable_column_action_cancel'] = "Cancel";
$lang['sms_broadcast_datatable_column_action_pause'] = "Pause";
$lang['sms_broadcast_datatable_column_action_pause_distribution'] = "Pause distribution";
$lang['sms_broadcast_datatable_column_action_resume'] = "Resume";
$lang['sms_broadcast_datatable_column_action_resume_distribution'] = "Resume distribution";

// alert delete
$lang['sms_broadcast_alert_delete_title'] = "Are you sure?";
$lang['sms_broadcast_alert_delete_text'] = "Do you want to remove this campaign?";
$lang['sms_broadcast_alert_delete_confirmButtonText'] = "Yes";
$lang['sms_broadcast_alert_delete_cancelButtonText'] = "No";

$lang['sms_broadcast_alert_delete_two_title'] = "Deleted!";
$lang['sms_broadcast_alert_delete_two_text'] = "Campaign successfully removed!";

// alert generic
$lang['sms_broadcast_alert_broadcast_title'] = "Are you sure?";
$lang['sms_broadcast_alert_broadcast_confirmButtonText'] = "Yes";
$lang['sms_broadcast_alert_broadcast_cancelButtonText'] = "No";
$lang['sms_broadcast_alert_broadcast_two_title'] = "Done!";

// alert pause
$lang['sms_broadcast_alert_broadcast_pause_text'] = "Do you want to pause the selected campaign?";
$lang['sms_broadcast_alert_broadcast_pause_all_text'] = "Do you want to pause all selected campaigns?";
$lang['sms_broadcast_alert_broadcast_pause_two_text'] = "Campaign paused successfully!";
$lang['sms_broadcast_alert_broadcast_pause_two_all_text'] = "Campaigns paused successfully!";

// alert resume
$lang['sms_broadcast_alert_broadcast_resume_text'] = "Do you want to resume the selected campaign?";
$lang['sms_broadcast_alert_broadcast_resume_all_text'] = "Do you want to resume all selected campaigns?";
$lang['sms_broadcast_alert_broadcast_resume_two_text'] = "Campaign successfully resumed!";
$lang['sms_broadcast_alert_broadcast_resume_two_all_text'] = "Campaigns successfully resumed!";

// alert error
$lang['sms_broadcast_error_broadcast_title'] = "Error!";
$lang['sms_broadcast_error_broadcast_message'] = "An error occurred while executing the action";

// alert export
$lang['sms_broadcast_alert_export_title'] = "List sent!";
$lang['sms_broadcast_alert_export_text'] = "The email can take up to an hour to arrive.";
$lang['sms_broadcast_alert_export_confirmButtonText'] = "Ok!";

// select canais
$lang['sms_broadcast_select_channel'] = "Select channel";

// View form validation
$lang['sms_broadcast_check_date'] = "The schedule date is less than the current date";
$lang['sms_broadcast_check_hour'] = "The scheduling time must be at least 30 minutes greater than the current time!";
$lang['sms_broadcast_check_date_validity'] = "The effective date is less than or equal to the scheduling date";
// $lang['sms_broadcast_check_hour_validity'] = "The effective date is less than or equal to the scheduling date";
$lang['sms_broadcast_check_hour_validity'] = "The effective time is less than or equal to schedule time";
$lang['sms_broadcast_alert_field_validation'] = "This field is required.";

$lang['sms_broadcast_validation_message'] = "Message";
$lang['sms_broadcast_validation_img'] = "Picture";
$lang['sms_broadcast_validation_cancel_title'] = "Error";
$lang['sms_broadcast_validation_cancel_and_send'] = "Campaign already canceled or sent!";
