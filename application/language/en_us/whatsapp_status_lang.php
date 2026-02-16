<?php

defined('BASEPATH') or exit('No direct script access allowed');

// View Waba
$lang['whatsapp_status_waba_header'] = "Status Publication";
$lang['whatsapp_status_waba_add'] = "New status publication";
$lang['whatsapp_status_waba_edit'] = "Edit status publication";
$lang['whatsapp_status_waba_view'] = "View status publication";
$lang['whatsapp_status_text_caracter'] = "Characters remaining:";

// View find 
$lang['whatsapp_status_topnav'] = "WhatsApp Status Publication";
$lang['whatsapp_status_btn_new'] = "New";
$lang['whatsapp_status_btn_filter'] = "Filter";
$lang['whatsapp_status_btn_export'] = "Export";

$lang['whatsapp_status_column_btn_cancel'] = "Cancel";
$lang['whatsapp_status_column_arquive'] = "Arquive";
$lang['whatsapp_status_column_scheduling'] = "Schedule Date";
$lang['whatsapp_status_column_channel'] = "Channel";
$lang['whatsapp_status_column_title'] = "Title";
$lang['whatsapp_status_column_status'] = "Status";

$lang['whatsapp_status_filter_title'] = "Filter status";
$lang['whatsapp_status_filter_search'] = "Title";
$lang['whatsapp_status_filter_search_placeholder'] = "Search...";
$lang['whatsapp_status_filter_channel'] = "Channel";
$lang['whatsapp_status_filter_status'] = "Status";
$lang['whatsapp_status_filter_status_select'] = "Select...";
$lang['whatsapp_status_filter_status_processing'] = "Waiting to be sent";
$lang['whatsapp_status_filter_status_sending'] = "Sending";
$lang['whatsapp_status_filter_status_send'] = "Send";
$lang['whatsapp_status_filter_status_called_off'] = "Canceled";
$lang['whatsapp_status_filter_period'] = "Period";
$lang['whatsapp_status_filter_period_placeholder_date_start'] = "Date start";
$lang['whatsapp_status_filter_period_placeholder_date_end'] = "Date end";
$lang['whatsapp_status_filter_btn_return'] = "Return";
$lang['whatsapp_status_filter_btn_search'] = "Search";

$lang['whatsapp_status_export_title'] = "Information export";
$lang['whatsapp_status_export_email'] = "Do you want send data for the email";
$lang['whatsapp_status_export_email_placeholder'] = "Inform the e-mail for export";
$lang['whatsapp_status_export_btn_confirm'] = "Confirm";
$lang['whatsapp_status_export_btn_return'] = "Return";

// Generic add, edit //
$lang['whatsapp_status_title'] = "Title";
$lang['whatsapp_status_date_scheduling'] = "Schedule date";
$lang['whatsapp_status_date_scheduling_placeholder'] = "Select date";
$lang['whatsapp_status_hour_scheduling'] = "Schedule time";
$lang['whatsapp_status_hour_scheduling_placeholder'] = "Select time";
$lang['whatsapp_status_select_channel'] = "Select channel";
$lang['whatsapp_status_type_photo'] = "Create media campaign";
$lang['whatsapp_status_type_text'] = "Create text campaign";
$lang['whatsapp_status_segments_select_group_placeholder'] = "Select";
$lang['whatsapp_status_message'] = "Message: (Up to 700 characters) | Characters remaining:";
$lang['whatsapp_status_message_placeholder'] = "Type here...";
$lang['whatsapp_status_loading_arquive'] = "Upload photos or videos (<b>jpeg</b>, <b>jpg</b>, <b>MP4</b> or from up to <b>10 MB</b>)";
$lang['whatsapp_status_title_drop'] = "Ready to add something?";
$lang['whatsapp_status_subtitle_drop'] = "Drag and pics and videos here to get started.";
$lang['whatsapp_status_btn_cancel'] = "Cancel";
$lang['whatsapp_status_btn_return'] = "Return";
$lang['whatsapp_status_btn_save'] = "Save";
$lang['whatsapp_status_choose_type'] = "Select campaign type";
$lang['whatsapp_status_add_edit_status_placeholder'] = "Description (optional)";
$lang['whatsapp_status_select'] = "Select...";

// View add // 
$lang['whatsapp_status_add_title'] = "New";
$lang['whatsapp_status_add_information'] = "Add new campaign";

// View edit //
$lang['whatsapp_status_edit_title'] = "Edit";
$lang['whatsapp_status_edit_information'] = "Broadcast information";
$lang['whatsapp_status_edit_status_placeholder'] = "";
$lang['whatsapp_status_edit_selected_channel'] = "Selected channel";

// View status //
$lang['whatsapp_status_view_title'] = "View campaign";
$lang['whatsapp_status_select_channel_view'] = "Selected channel";
$lang['whatsapp_status_change_request_timeline_view'] = "made a change request";
$lang['whatsapp_status_request_fulfilled_timeline_view'] = "Request fulfilled";
$lang['whatsapp_status_approved_one_status_timeline_view'] = "Approved the status";
$lang['whatsapp_status_approved_status_timeline_view'] = "Approved status";
$lang['whatsapp_status_canceled_status_timeline_view'] = "Status canceled";

$lang['whatsapp_status_timeline_creation'] = "created the campaign";
$lang['whatsapp_status_timeline_resend'] = "resend the campanha";
$lang['whatsapp_status_timeline_edited'] = "edited the campaign";
$lang['whatsapp_status_timeline_canceled'] = "canceled the campaign";
$lang['whatsapp_status_timeline_send_now'] = "changed it to send now";
$lang['whatsapp_status_timeline_creation_api'] = "Campaign created via external system integration";

// Modal campaign overlap
$lang['campaign_overlap_title'] = "Campaign Impact Notification";
$lang['campaign_overlap_notify_on_modal'] = "The new campaign will impact your emails today. What do you want to do?";
$lang['campaign_overlap_channel_info'] = "This is the number that is in conflict: ";
$lang['campaign_overlap_channels_info'] = "These are the numbers that are in conflict: ";
$lang['campaign_overlap_channel_info_others'] = " and others.";
$lang['campaign_overlap_suspend_campaign'] = "Suspend Campaign ";
$lang['campaign_overlap_suspend_campaign_extension'] = "Immediately cancel the campaign schedule.";
$lang['campaign_overlap_change_date'] = "Change sending date";
$lang['campaign_overlap_change_date_extension'] = "Choose another date and time to send the campaign.";
$lang['campaign_overlap_send_after_hours'] = "Send anyway";
$lang['campaign_overlap_send_after_hours_extension'] = "Use the time needed to send the campaigns to the entire base.";

// Modal campaign estimate
$lang['campaign_estimate_title'] = "Attention to the scheduled campaign sending time";
$lang['campaign_estimate_notify_on_modal'] = "The estimated end time of this campaign exceeds the established hours for the channel. What would you like to do?";
$lang['campaign_estimate_suspend_campaign'] = "Suspend Campaign";
$lang['campaign_estimate_suspend_campaign_extension'] = "Immediately cancel the campaign schedule.";
$lang['campaign_estimate_review_queue'] = "Review send queue";
$lang['campaign_estimate_review_queue_extension'] = "Manage the campaigns to free up space in the queue";
$lang['campaign_estimate_change_date'] = "Change send date";
$lang['campaign_estimate_change_date_extension'] = "Choose another date and time to send the campaign.";
$lang['campaign_estimate_send_partially'] = "Send partially";
$lang['campaign_estimate_send_partially_extension'] = "Be aware that only part of your base will receive the campaign.";
$lang['campaign_estimate_send_after_hours'] = "Send after hours";
$lang['campaign_estimate_send_after_hours_extension'] = "Use the necessary time to send the campaign to the entire base.";
$lang['campaign_estimate_channel_info'] = "These are the numbers that are in conflict: ";
$lang['campaign_estimate_channel_info1'] = " and others.";
$lang['campaign_estimate_suspend_notify_title'] = "Are you sure?";
$lang['campaign_estimate_suspend_notify_text'] = "Do you want to cancel this campaign schedule?";
$lang['campaign_estimate_suspend_notify_ok'] = "Yes";
$lang['campaign_estimate_suspend_notify_cancel'] = "No";
$lang['campaign_estimate_suspend_notify_deleted_title'] = "Deleted!";
$lang['campaign_estimate_suspend_notify_notify_deleted_text'] = "Campaign successfully removed!";
$lang['campaign_estimate_partial_notify_title'] = "Are you sure?";
$lang['campaign_estimate_partial_notify_text'] = "This campaign will be sent respecting the previously configured time limit for the selected channels.";
$lang['campaign_estimate_partial_notify_ok'] = "Yes";
$lang['campaign_estimate_partial_notify_cancel'] = "No";
$lang['campaign_estimate_partial_notify_add_title'] = "Publication registered!";
$lang['campaign_estimate_partial_notify_sucess'] = "Campaign successfully scheduled!";
$lang['campaign_estimate_partial_notify_notify_deleted_text'] = "Campaign successfully removed!";
$lang['campaign_estimate_after_notify_text'] = "This campaign will be sent to the entire contact base with no time limit.";


// $lang['alert_review_message'] = "The review message is mandatory";

// Js 

// datatable column 
$lang['whatsapp_status_datatable_column_status_processing'] = "Waiting to be sent";
$lang['whatsapp_status_datatable_column_status_send'] = "Send";
$lang['whatsapp_status_datatable_column_status_sending'] = "Sending";
$lang['whatsapp_status_datatable_column_status_called_off'] = "Canceled";
$lang['whatsapp_status_datatable_column_status_review'] = "PReview request";
$lang['whatsapp_status_datatable_column_status_rejected'] = "Rejected";
$lang['whatsapp_status_datatable_column_action_view'] = "View";
$lang['whatsapp_status_datatable_column_action_cancel'] = "Cancel";
$lang['whatsapp_status_datatable_column_action_edit'] = "Edit";

// alert change type
$lang['whatsapp_status_alert_change_type_title'] = "Are you sure?";
$lang['whatsapp_status_alert_change_type_text'] = "If you change the campaign type you will lose the current edit.";
$lang['whatsapp_status_alert_change_type_yes'] = "Yes";
$lang['whatsapp_status_alert_change_type_no'] = "No";

// alert delete
$lang['whatsapp_status_alert_group_delete_title'] = "Are you sure?";
$lang['whatsapp_status_alert_group_delete_text'] = "Do you want to remove the selected status?";
$lang['whatsapp_status_alert_group_delete_confirmButtonText'] = "Yes";
$lang['whatsapp_status_alert_group_delete_cancelButtonText'] = "No";

$lang['whatsapp_status_alert_group_delete_two_title'] = "Deleted!";
$lang['whatsapp_status_alert_group_delete_two_text'] = "Status successfully removed!";

// alert delete
$lang['whatsapp_status_alert_delete_title'] = "Are you sure?";
$lang['whatsapp_status_alert_delete_text'] = "Do you want to remove this status?";
$lang['whatsapp_status_alert_delete_confirmButtonText'] = "Yes";
$lang['whatsapp_status_alert_delete_cancelButtonText'] = "No";

$lang['whatsapp_status_alert_delete_two_title'] = "Deleted!";
$lang['whatsapp_status_alert_delete_two_text'] = "Status successfully removed!";

// alert resend
$lang['whatsapp_status_alert_resend'] = "Resend";
$lang['whatsapp_status_alert_resend_title'] = "Are you sure?";
$lang['whatsapp_status_alert_resend_text'] = "Do you want to resend all queues from this campaign?";
$lang['whatsapp_status_alert_resend_confirmButtonText'] = "Yes";
$lang['whatsapp_status_alert_resend_cancelButtonText'] = "No";
$lang['whatsapp_status_alert_resend_two_title'] = "Resend";
$lang['whatsapp_status_alert_resend_two_text'] = "Campaign resent successfully!";

// alert export
$lang['whatsapp_status_alert_export_title'] = "List sent!";
$lang['whatsapp_status_alert_export_text'] = "The email can take up to an hour to arrive.";
$lang['whatsapp_status_alert_export_confirmButtonText'] = "Ok!";

// alert dropzone
$lang['whatsapp_status_alert_dropzone_title'] = "Warning";
$lang['whatsapp_status_alert_dropzone_text'] = "You have exceeded the 10 image limit!";
$lang['whatsapp_status_alert_dropzone_confirmButtonText'] = "Ok!";

$lang['whatsapp_status_alert_dropzone_two_title'] = "Warning";
$lang['whatsapp_status_alert_dropzone_two_text'] = "Maximum limit of 10 MB per file";
$lang['whatsapp_status_alert_dropzone_two_confirmButtonText'] = "Ok!";

$lang['whatsapp_status_alert_dropzone_three_title'] = "Warning";
$lang['whatsapp_status_alert_dropzone_three_text'] = "PNG images are not allowed!";
$lang['whatsapp_status_alert_dropzone_three_confirmButtonText'] = "Ok!";

$lang['whatsapp_status_alert_dropzone_four_title'] = "Warning";
$lang['whatsapp_status_alert_dropzone_four_text'] = "PDF file not allowed in status";
$lang['whatsapp_status_alert_dropzone_four_confirmButtonText'] = "Ok!";

$lang['whatsapp_status_alert_files_title'] = "Attention";
$lang['whatsapp_status_alert_files_text'] = "Only files (jpeg, jpg, or mp4 up to 10 MB) are accepted";
$lang['whatsapp_status_alert_files_confirmButtonText'] = "Ok!";


// alert verify status
$lang['whatsapp_status_alert_verify_status_title'] = "Warning";
$lang['whatsapp_status_alert_verify_status_text'] = "Some of the Statuses among those selected has already been cancelled!";
$lang['whatsapp_status_alert_verify_status_confirmButtonText'] = "Ok!";

// Alert check time to edit
$lang['whatsapp_status_error_title'] = "Error";
$lang['whatsapp_status_error_ta023'] = "It is not possible to edit this campaign. The editing time limit is up to 1 hour before sending.";
$lang['whatsapp_status_error_ta024'] = "It is not possible to edit a campaign that has been sent, canceled, or is in progress.";

// Alert datatables
$lang['whatsapp_status_datatables_edit_status_2'] = "It is not possible to edit a campaign that has already been sent.";
$lang['whatsapp_status_datatables_edit_status_5'] = "It is not possible to edit a campaign that has been cancelled.";
$lang['whatsapp_status_datatables_edit_status_6'] = "It is not possible to edit a campaign that is in progress.";
$lang['whatsapp_status_datatables_edit_default'] = "It's not possible to edit this campaign.";
$lang['whatsapp_status_datatables_edit_less_than_one_hour'] = "It is not possible to edit this campaign. The editing time limit is up to 1 hour before sending.";

// select canais
$lang['whatsapp_status_select_channel'] = "Select channel";
$lang['whatsapp_status_select_all'] = "Select all";

// form validation 
$lang['whatsapp_status_validation_date'] = "The date is smaller how the current date!";
$lang['whatsapp_status_validation_hour'] = "The scheduling time must be at least 30 minutes greater than the current time!";
$lang['whatsapp_status_validation_cancel_title'] = "Error";
$lang['whatsapp_status_validation_cancel_description'] = "Try again later!";
$lang['whatsapp_status_validation_status_cancel'] = "Status already canceled!";
$lang['whatsapp_status_check_date'] = "The schedule date is less than the current date";
$lang['whatsapp_status_check_hour'] = "The scheduling time must be at least 30 minutes longer than the current time!";
$lang['whatsapp_status_alert_field_validation'] = "This field is required.";
