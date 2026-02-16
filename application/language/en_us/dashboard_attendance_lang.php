<?php

defined('BASEPATH') or exit('No direct script access allowed');

// Waba navegador
$lang['dashboard_attendance_waba_header'] = "Dashboard";
$lang["dashboard_attendance_title"] = "Attendance Dashboard";

$lang["dashboard_attendance_header_avg_wait_time"] = "Average wait time";
$lang["dashboard_attendance_header_avg_response_time"] = "Average response time";
$lang["dashboard_attendance_header_avg_service_time"] = "Average service time";
$lang["dashboard_attendance_header_total_attendances"] = "Attendances";

// tooltip
$lang["dashboard_attendance_header_tooltip_avg_wait_time"] = "Average waiting time until the first service";
$lang["dashboard_attendance_header_tooltip_avg_response_time"] = "Average time to respond to the customer in attendances";
$lang["dashboard_attendance_header_tooltip_avg_service_time"] = "Average time from opening to closing in attendances";
$lang["dashboard_attendance_header_tooltip_total_attendances"] = "Number of attendances started in the selected period";

$lang["dashboard_ticket_month_short"] = "Jan_Feb_Mar_Apr_May_Jun_Jul_Aug_Sep_Oct_Nov_Dec";

// Filter
$lang['dashboard_attendance_filter_period'] = "Period";
$lang['dashboard_attendance_filter_select_today'] = "Today";
$lang['dashboard_attendance_filter_select_yesterday'] = "Yesterday";
$lang['dashboard_attendance_filter_select_week'] = "7 Days";
$lang['dashboard_attendance_filter_select_fifteen_days'] = "15 Days";
$lang['dashboard_attendance_filter_select_this_month'] = "30 Days";
$lang['dashboard_attendance_filter_select_last_month'] = "60 Days";
$lang['dashboard_attendance_filter_select_two_months_ago'] = "90 Days";
$lang['dashboard_attendance_filter_select_total'] = "Total";
$lang['dashboard_attendance_filter_select'] = "Select";
$lang['dashboard_attendance_filter_search'] = "Search";
$lang['dashboard_attendance_filter_not_found'] = "Not found";

// Graphic peak service
$lang['dashboard_attendance_graph_peak_service_title'] = "Peak time of service initialization";
$lang['dashboard_attendance_graph_peak_service_tooltip'] = "Average of services started per hour in the last 7 days.";
$lang['dashboard_attendance_graph_peak_service_caption'] = "Average number of calls initiated";

// Graphic attendance open and closed
$lang['dashboard_attendance_graph_started_end_closed_title'] = "Attendances started and closed";
$lang['dashboard_attendance_graph_started_end_closed_leng_star'] = "Started";
$lang['dashboard_attendance_graph_started_end_closed_leng_closed'] = "Closed";
$lang['dashboard_attendance_graph_started_end_closed_no_data'] = "No data for the selected period";
$lang['dashboard_attendance_graph_started_end_closed_summary'] = "Number of attendances started and closed in the selected period and channel";

// Graphic category
$lang['dashboard_attendance_graph_category_title'] = "Distribution of service categories";
$lang['dashboard_attendance_graph_category_summary'] = "Shows the number of attendances by category in the selected period and channel";
$lang['dashboard_attendance_graph_category_no_data'] = "No data found";
$lang['dashboard_attendance_graph_category_uncategorized_services'] = "Uncategorized services";
$lang['dashboard_attendance_graph_category_deleted'] = "Deleted";

// Chatbot Quantitative Chart
$lang['dashboard_attendance_graph_chatbot_quantitative_title'] = "Number of visits per chatbot option";
$lang['dashboard_attendance_graph_chatbot_quantitative_summary'] = "Displays how many times each chatbot option or step was visited by contacts during interactions";
$lang['dashboard_attendance_graph_chatbot_quantitative_main_menu'] = "Main menu";

// Graphic origin attendance
$lang['dashboard_attendance_graph_chatbot_origin_no_title'] = "Origin of initiated calls";
$lang['dashboard_attendance_graph_chatbot_origin_summary'] = "Indicates how the calls were initiated: actively by an attendant or passively by the contact";
$lang['dashboard_attendance_graph_chatbot_origin_no_data'] = "Sem dice";
$lang['dashboard_attendance_graph_chatbot_origin_manual'] = "Active";
$lang['dashboard_attendance_graph_chatbot_origin_organic'] = "Passive";

// Graphic Abandonment attendance
$lang['dashboard_attendance_graph_abandonment_title'] = "Chatbot flow adherence rate";
$lang['dashboard_attendance_graph_abandonment_summary'] = "Shows the percentage of users who followed the entire chatbot flow compared to those who abandoned the interaction before completing it";
$lang['dashboard_attendance_graph_abandonment'] = "Abandonment";
$lang['dashboard_attendance_graph_no_abandonment'] = "Do not abandon";

// User table
$lang['dashboard_attendance_table_user_title'] = "Users";
$lang['dashboard_attendance_table_placeholder_search'] = "Search by name...";
$lang['dashboard_attendance_table_user_name'] = "Name";
$lang['dashboard_attendance_table_user_start'] = "Start";
$lang['dashboard_attendance_table_user_in_service'] = "In service";
$lang['dashboard_attendance_table_user_on_hold'] = "On hold";
$lang['dashboard_attendance_table_user_finished'] = "Finished";
$lang['dashboard_attendance_table_user_ast'] = "AST";
$lang['dashboard_attendance_table_user_art'] = "ART";
$lang['dashboard_attendance_table_user_rating_average'] = "AI rating average";
$lang['dashboard_attendance_table_show_more'] = "▼ Show more";
