<?php

defined('BASEPATH') or exit('No direct script access allowed');

// View Waba
$lang['persona_waba_header'] = "Personas";
$lang['persona_waba_edit'] = "Edit Persona";
$lang['persona_waba_add'] = "New Persona";

// View find
$lang['persona_topnav'] = "Personas";

$lang['persona_btn_new'] = "New";
$lang['persona_btn_export'] = "Export";

$lang['persona_column_name'] = "Name";
$lang['persona_column_channel'] = "Channel";
$lang['persona_column_creation'] = "Creation";
$lang['persona_column_subscribers'] = "Subscribers";
$lang['persona_column_action_export'] = "Export";
$lang['persona_column_action_edit'] = "Edit";
$lang['persona_column_action_delete'] = "Delete";

$lang['persona_tooltip_update'] = "Updates every 7 days";
$lang['persona_row_subscriber'] = " Subscriber";
$lang['persona_row_subscribers'] = " Subscribers";

$lang['persona_export_title'] = "Data Export";
$lang['persona_export_email'] = "Do you want to send the data to email:";
$lang['persona_export_btn_confirm'] = "Confirm";
$lang['persona_export_btn_return'] = "Return";

$lang['persona_export_contacts_title'] = "Information export";
$lang['persona_export_contacts_email'] = "Do you want to send data for the email";
$lang['persona_export_contacts_btn_confirm'] = "Confirm";
$lang['persona_export_contacts_btn_return'] = "Return";
$lang['persona_export_contacts_no_permit_title'] = "Export limit reached!";
$lang['persona_export_contacts_no_permit_content'] = "Too many exports performed by this user, please try again later";

// View add
$lang['persona_add_title'] = "New";
$lang['persona_add_select_channel'] = "Select channel";
$lang['persona_add_select_channel_placeholder'] = "Select";
$lang['persona_add_btn_cancel'] = "Cancel";

// View edit
$lang["persona_edit_title"] = "Edit";
$lang['persona_edit_btn_return'] = "Return";
$lang['persona_edit_selected_channel'] = "Selected Channel";

// View add | edit
$lang['persona_name'] = "Name";
$lang['persona_name_placeholder'] = "Enter name";

$lang['persona_link_add_contacts'] = "Add contacts";
$lang['persona_link_import'] = "Import";

$lang['persona_btn_save'] = "Save";
$lang['persona_number_of_contacts'] = "Number of contacts: ";
$lang['persona_add_picture'] = "ADD AN IMAGE";

// Modal add participants
$lang['persona_participants_title'] = "Add Participants";
$lang['persona_participants_placeholder'] = "Enter number or name";
$lang['persona_participants_btn_confirm'] = "Confirm";
$lang['persona_participants_btn_cancel'] = "Clean";

// Modal add participants - JS
$lang['persona_participants_select_all'] = "Select all";
$lang['persona_participants_no_contacts'] = "No contacts found";

// Modal import
$lang['persona_import_title'] = "Import contacts";
$lang['persona_import_alert_import'] = "Attention to data entry standards.";
$lang['persona_import_obs'] = "Copy your list of contacts (Excel or Google Sheets) and paste below, then click Next.";
$lang['persona_import_btn_import_advance'] = "Next";
$lang['persona_import_column_relate_data'] = "Relate columns with data types";
$lang['persona_import_btn_import_contacts'] = "Import Contacts";

// Modal import - JS
$lang['persona_import_select_data_type'] = "Select data type";
$lang['persona_import_option_name'] = "Name";
$lang['persona_import_option_phone'] = "Phone";
$lang['persona_import_option_email'] = "Email";

// Modal progress
$lang['persona_progress_title_save'] = "Saving data";
$lang['persona_progress_title_import'] = "Importing data";
$lang['persona_progress_body'] = "Please wait, do not close this browser tab.";

// Modal progress - JS
$lang['persona_progress_default'] = "Processing...";
$lang['persona_progress_import'] = "Imported {1} of {2} contacts";
$lang['persona_progress_save'] = "Saving...";

//Alert save - JS
$lang['persona_alert_empty_title'] = "Attention!";
$lang['persona_alert_empty_text'] = "Please provide at least one participant.";
$lang['persona_alert_empty_text_confirmButtonText'] = "Ok";

// Alert cancel persona | change channel
$lang['persona_alert_title'] = "Attention!";
$lang['persona_alert_text'] = "All participant selections will be lost";
$lang['persona_alert_btn_confirm'] = "Confirm";
$lang['persona_alert_btn_cancel'] = "Cancel";

// Alert delete
$lang['persona_alert_delete_title'] = "Are you sure?";
$lang['persona_alert_delete_text'] = "Do you really want to remove this persona?";
$lang['persona_alert_delete_confirmButtonText'] = "Yes";
$lang['persona_alert_delete_cancelButtonText'] = "No";
$lang['persona_alert_delete_sucess'] = "Successfully deleted!";
$lang['persona_alert_delete_two_confirmButtonTextt'] = "Ok";

//Alert dropzone - JS
$lang['persona_alert_dropzone_maxSize_title'] = "Attention!";
$lang['persona_alert_dropzone_maxSize_text'] = "Maximum limit is 10 MB";
$lang['persona_alert_dropzone_maxSize_confirmButtonText'] = "Ok";

$lang['persona_alert_dropzone_archives_title'] = "Attention!";
$lang['persona_alert_dropzone_archives_text'] = "Only files (jpeg, jpg, or png) are accepted.";
$lang['persona_alert_dropzone_archives_confirmButtonText'] = "Ok";

// Alert export
$lang['persona_alert_export_title'] = "List Sent!";
$lang['persona_alert_export_text'] = "The email may take up to an hour to arrive.";
$lang['persona_alert_export_confirmButtonText'] = "Ok";

// Alert import
$lang['persona_alert_import_info'] = "Each import is subject to a maximum limit of 10 thousand contacts.\nDuplicate numbers will not be imported.\nThe number must have DDI and DDD.\nThe name must be up to 100 characters.\nThe email must be up to 55 characters.";
$lang['persona_alert_import_info_preview'] = "Data highlighted in red indicates duplicate information.\nDuplicate numbers will not be imported.\nThe number must have DDI and DDD.\nThe name must have up to 100 characters.\nThe email must have up to 55 characters.";

// Alert import - JS
$lang['persona_alert_import_title'] = "Attention!";
$lang['persona_alert_import_phone_select'] = "At least one column must contain the phone number.";
$lang['persona_alert_import_contact_number'] = "Numbers do not follow data entry standards.";
$lang['persona_alert_import_contact_limit_exceeded'] = "Each import is subject to a maximum limit of 10,000 contacts.";

// Alert info channel  - JS
$lang['persona_alert_channel_btn_import_contacts'] = "Select a channel to import";
$lang['persona_alert_channel_btn_add_contacts'] = "Select a channel to get contacts";

// form validation - JS
$lang['persona_alert_name'] = "The Name field is required.";
$lang['persona_alert_channel'] = "The Channel field is required.";
$lang['persona_alert_name_min_length'] = "The Name field must be at least 3 characters.";
