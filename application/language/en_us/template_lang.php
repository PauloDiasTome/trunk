<?php

defined('BASEPATH') or exit('No direct script access allowed');

// View Waba
$lang['template_waba_header'] = "Templates";
$lang['template_waba_add'] = "New template";
$lang['template_waba_edit'] = "Edit template";
$lang['template_waba_view'] = "View template";

// View find 
$lang['template_topnav'] = "Templates";
$lang['template_btn_new'] = "New";
$lang['template_btn_export'] = "Export";

$lang['template_column_creation'] = "Creation date";
$lang['template_column_name'] = "Name";
$lang['template_column_channel'] = "Channel";
$lang['template_column_category'] = "Category";
$lang['template_column_language'] = "Language";
$lang['template_column_status'] = "Status";
$lang['template_column_actions'] = "Actions";

$lang['template_export_title'] = "Information export";
$lang['template_export_email'] = "Do you want to send data to the email:";
$lang['template_export_email_placeholder'] = "Inform the e-mail to export";
$lang['template_export_btn_confirm'] = "Confirm";
$lang['template_export_btn_return'] = "Return";

// Generic edit and add
$lang['template_select_option_none'] = "None";

$lang['template_creation'] = "Creation";
$lang['template_channel'] = "Channel";

$lang['template_name'] = "Template name";
$lang['template_name_placeholder'] = "Enter template name";

$lang['template_lang'] = "Language";
$lang['template_port'] = "Portuguese";
$lang['template_en'] = "English";

$lang['template_header'] = "Header";
$lang['template_header_placeholder'] = "Enter header text";
$lang['template_header_option_text'] = "Text";
$lang['template_header_option_media'] = "Media";
$lang['template_header_media_image'] = "Image";
$lang['template_header_media_video'] = "Video";
$lang['template_header_media_document'] = "Document";

$lang['template_body_label'] = "Body";
$lang['template_body_description'] = "Description: (Up to 1024 characters) | Characters remaining:";
$lang['template_body_description_validation'] = "Description";
$lang['template_body_button_add_variable'] = "+ Add variable";
$lang['template_body_button_variable_first_last_error'] = "Body text contains variable parameters at the beginning or at the end.";
$lang['template_body_button_variable_qtd_error'] = "The body text contains many variable parameters regarding the length of the message.";

$lang['template_footer_label'] = "Footer";
$lang['template_footer_placeholder'] = "Enter footer text";

$lang['template_button_label'] = "Buttons";
$lang['template_button_option_cta'] = "Call To Action";
$lang['template_button_option_quick_answer'] = "Quick answer";
$lang['template_button_cta_type'] = "Button type";
$lang['template_button_cta_type_call'] = "Call";
$lang['template_button_cta_type_url'] = "Access the website";
$lang['template_button_text_placeholder'] = "Enter text button";
$lang['template_button_country_label'] = "Country";
$lang['template_button_phone_label'] = "Phone";
$lang['template_button_url_type_label'] = "URL Type";
$lang['template_button_url_type_option_static'] = "Static";
$lang['template_button_url_type_option_dynamic'] = "Dynamic";
$lang['template_button_url_label'] = "Website URL";
$lang['template_button_text_label'] = "Button text";
$lang['template_button_add_button'] = "+ Add one more button";

$lang['template_api_validation'] = "There was an error creating the template. Try again.";
$lang['template_api_validation_repeated_name'] = "The name can´t be the same as one that already exists";
$lang['template_number'] = "1024";
$lang['template_baseboard'] = "Baseboard";

$lang['template_btn_cancel'] = "Cancel";
$lang['template_btn_save'] = "Save";
$lang['template_btn_return'] = "Return";

// Add validation 
$lang['template_validation_name'] = "The name field is required";
$lang['template_validation_same_name'] = "Template with that name already exists";
$lang['template_validation_same_name_deleted'] = "A template with this name is being deleted";
$lang['template_validation_invalid_parameter_header'] = "Message header cannot have newlines, formatting characters, emojis or asterisks.";
$lang['template_validation_invalid_parameter_button'] = "Buttons cannot contain direct links to WhatsApp.";
$lang['template_validation_header_text'] = "Header text is required";
$lang['template_validation_header_media'] = "Select a media type for the header";
$lang['template_validation_body'] = "The body field is required";
$lang['template_validation_button'] = "Fill in all the button fields";
$lang['template_validation_url_button'] = "Enter a valid URL";
$lang['template_validation_url_button_phone'] = "Enter a valid number";
$lang['template_validation_general_error'] = "An error occurred in the request. Try again later";

// View add 
$lang["template_add_title"] = "New";
$lang["template_add_information"] = "Add a new message template";

// View view
$lang["template_view_title"] = "Message template preview";
$lang["template_view_information"] = "Template information";
$lang['template_view_name'] = "Title";
$lang['template_view_channel'] = "Channel";
$lang['template_view_category'] = "Category";
$lang['template_view_language'] = "Language";
$lang['template_view_status'] = "Status";
$lang['template_view_rejected_reason'] = "Rejected reason";

// View edit
$lang["template_edit_title"] = "Edit";
$lang["template_edit_information"] = "Edit message template";

// alert delete
$lang['template_alert_delete_title'] = "Are you sure?";
$lang['template_alert_delete_text'] = "Do you really want to delete this template?";
$lang['template_alert_delete_confirmButtonText'] = "Yes";
$lang['template_alert_delete_cancelButtonText'] = "No";

$lang['template_alert_delete_two_title'] = "Deleted!";
$lang['template_alert_delete_two_text'] = "Template removed with success!";

$lang['template_alert_delete_two_title_error'] = "Error!";
$lang['template_alert_delete_two_text_error'] = "An error occurred while trying to remove the template";

// alert export
$lang['template_alert_export_title'] = "List sent!";
$lang['template_alert_export_text'] = "The e-mail may take up to an hour to arrive.";
$lang['template_alert_export_confirmButtonText'] = "Ok!";

// JS
$lang['template_datatable_column_status_title_review'] = "Under analysis";
$lang['template_datatable_column_status_title_approved'] = "Approved";
$lang['template_datatable_column_status_title_rejected'] = "Rejected";
$lang['template_datatable_column_status_title_called'] = "Submitted";
$lang['template_datatable_column_status_title_delete'] = "Delete";
$lang['template_datatable_column_status_title_view'] = "View";
$lang['template_datatable_column_status_title_edit'] = "Edit";
