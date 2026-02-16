<?php

defined('BASEPATH') or exit('No direct script access allowed');

// View Waba
$lang['ticket_waba_header'] = "Tickets";
$lang['ticket_waba_add'] = "New Ticket";
$lang['ticket_waba_edit'] = "Edit ticket";
$lang['ticket_waba_history'] = "Historic of ticket";

// View find 
$lang['ticket_topnav'] = "Tickets";
$lang['ticket_btn_new'] = "New";
$lang['ticket_btn_filter'] = "Filter";
$lang['ticket_btn_export'] = "Export";

$lang['ticket_column_creation'] = "Creation Date";
$lang['ticket_column_contact'] = "Contact";
$lang['ticket_column_company'] = "Company";
$lang['ticket_column_user'] = "User";
$lang['ticket_column_ticket_type'] = "ticket type";
$lang['ticket_column_ticket_status'] = "Ticket status";

$lang['ticket_filter_title'] = "Filter ticket";
$lang['ticket_filter_search'] = "Contact or user";
$lang['ticket_filter_search_placeholder'] = "Search...";
$lang['ticket_filter_type_ticket'] = "Ticket type";
$lang['ticket_filter_type_ticket_placeholder'] = "Select...";
$lang['ticket_filter_status_ticket'] = "Ticket status";
$lang['ticket_filter_period'] = "Period";
$lang['ticket_filter_period_notify'] = "Selected period unavailable. Data stored for 90 days";
$lang['ticket_filter_period_placeholder_date_start'] = "Start date";
$lang['ticket_filter_period_placeholder_date_end'] = "End date";
$lang['ticket_filter_btn_return'] = "Return";
$lang['ticket_filter_btn_search'] = "Search";

$lang['ticket_export_title'] = "Information export";
$lang['ticket_export_email'] = "Do you want send data for the email";
$lang['ticket_export_email_placeholder'] = "Inform the e-mail for export";
$lang['ticket_export_btn_confirm'] = "Confirm";
$lang['ticket_export_btn_return'] = "Return";

// Generic edit e history
$lang['ticket_history'] = "History";
$lang['ticket_creation'] = "Creation";
$lang['ticket_contact'] = "Contact";
$lang['ticket_search'] = "Search...";
$lang['ticket_company'] = "Company";
$lang['ticket_users'] = "Users";
$lang['ticket_type'] = "Ticket type";
$lang['ticket_subtype'] = "Ticket subtype";
$lang['ticket_select'] = "Select";
$lang['ticket_status'] = "Ticket status";
$lang['ticket_comment'] = "Comment: (Up to 1500 characters) | Characters remaining:";
$lang['ticket_btn_return'] = "Return";
$lang['ticket_btn_save'] = "Save";
$lang['ticket_edit_title'] = "Edit";
$lang["ticket_edit_information"] = "Ticket information";
$lang["ticket_add_information"] = "Add ticket";
$lang['ticket_add_title'] = "New";
$lang['ticket_dropdown_add_new_ticket_type'] = "Add new ticket type";
$lang['ticket_dropdown_add_new_ticket_company'] = "Add new company";
$lang['ticket_dropdown_add_new_ticket_status'] = "Add new status";
$lang['ticket_dropdown_add_new_ticket_type_title'] = "Click here to add new";
$lang['ticket_dropdown_add_new_ticket_company_title'] = "Click here to add new";
$lang['ticket_dropdown_add_new_ticket_status_title'] = "Click here to add new";
$lang['ticket_status_ticket_info'] = "Does this status mean the ticket is in?";
$lang['ticket_status_open'] = "Open";
$lang['ticket_status_closed'] = "Closed";
$lang['ticket_status_name'] = "Name";


// modal ticket company /// 
$lang['ticket_modal_company_title'] = "Add company";
$lang['ticket_modal_company_creation'] = "Creation";
$lang['ticket_modal_company_corporate_name'] = "Corporate Name";
$lang['ticket_modal_company_corporate_name_placeholder'] = "Inform Corporate Name";
$lang['ticket_modal_company_cnpj'] = "CNPJ";
$lang['ticket_modal_company_cnpj_placeholder'] = "Inform cnpj";
$lang['ticket_modal_company_fantasy_name'] = "Fantasy Name";
$lang['ticket_modal_company_fantasy_name_placeholder'] = "Inform Fantasy Name";

$lang['ticket_modal_company_btn_save'] = "Save";
$lang['ticket_modal_company_btn_return'] = "Return";

// modal ticket type /// 
$lang['ticket_modal_type_title'] = "Add new ticket type";
$lang['ticket_modal_type_name'] = "Name";
$lang['ticket_modal_type_name_placeholder'] = "Inform name";
$lang['ticket_modal_type_sector'] = "Sector";
$lang['ticket_modal_type_sla'] = "Ticket SLA";
$lang['ticket_modal_type_sla_null'] = "There is no ticket SLA registered";

$lang['ticket_modal_type_btn_save'] = "Save";
$lang['ticket_modal_type_btn_return'] = "Return";

// modal ticket status /// 
$lang['ticket_modal_status_title'] = "Add ticket status";
$lang['ticket_modal_status_name'] = "Name";
$lang['ticket_modal_status_name_placeholder'] = "Inform name";
$lang['ticket_modal_status_cor'] = "Color";

$lang['ticket_modal_status_btn_save'] = "Save";
$lang['ticket_modal_status_btn_return'] = "Return";

// JS

// js global variables
$lang['ticket_dt_columndefs_target5_title_history'] = "Historic";
$lang['ticket_dt_columndefs_target5_title_edit'] = "Edit";
$lang['ticket_dt_columndefs_target5_title_delete'] = "Delete";

$lang['ticket_modal_filter_dt_start_placeholder_initial_date'] = "Start Date";

// alert delete 
$lang['ticket_alert_delete_title'] = "Are you sure?";
$lang['ticket_alert_delete_text'] = "Do you really want to delete this ticket?";
$lang['ticket_alert_delete_confirmButtonText'] = "Yes";
$lang['ticket_alert_delete_cancelButtonText'] = "No";

$lang['ticket_alert_delete_two_title'] = "Warning!";
$lang['ticket_alert_delete_two_text'] = "Ticket cannot be withdrawn!";

$lang['ticket_alert_delete_three_title'] = "Deleted!";
$lang['ticket_alert_delete_three_text'] = "Ticket successfully removed!";

// alert export
$lang['ticket_alert_export_title'] = "List sent!";
$lang['ticket_alert_export_text'] = "The email can take up to an hour to arrive.";
$lang['ticket_alert_export_confirmButtonText'] = "Ok!";

// alert validation modal company
$lang['ticket_validation_modal_corporate_name_required'] = "Field Corporate Name is required.";
$lang['ticket_validation_modal_corporate_name_min_length'] = "The Corporate Name field must be at least {param} character(s).";
$lang['ticket_validation_modal_corporate_name_max_length'] = "The Corporate Name field has exceeded the {param} character(s) limit.";
$lang['ticket_validation_modal_cnpj_required'] = "The CNPJ field is required.";
$lang['ticket_validation_modal_cnpj_length'] = "The CNPJ field must have {param} character(s).";
$lang['ticket_validation_modal_fantasy_name_required'] = "The Fantasy Name field is required.";
$lang['ticket_validation_modal_fantasy_name_min_length'] = "The Fantasy Name field must have at least {param} character(s).";
$lang['ticket_validation_modal_fantasy_name_max_length'] = "The Fantasy Name field exceeded the limit of {param} character(s).";

// alert validation modal ticket type
$lang['ticket_validation_modal_ticket_type_name_required'] = "Field Name is required.";
$lang['ticket_validation_modal_ticket_type_name_min_length'] = "The Name field must be at least {param} character(s).";
$lang['ticket_validation_modal_ticket_type_name_max_length'] = "The Name field has exceeded the {param} character(s) limit.";
$lang['ticket_validation_modal_ticket_type_sla_required'] = "The SLA field is required.";
$lang['ticket_validation_modal_ticket_type_group_required'] = "The sector field is required.";

// alert validation modal ticket status
$lang['ticket_validation_modal_status_name_required'] = "Field Name is required.";
$lang['ticket_validation_modal_status_name_min_length'] = "The Name field must be at least {param} character(s).";
$lang['ticket_validation_modal_status_name_max_length'] = "The Name field has exceeded the {param} character(s) limit.";

