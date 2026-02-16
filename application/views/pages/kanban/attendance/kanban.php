<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset='UTF-8'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" />
    <link rel="stylesheet" href="<?php echo base_url("/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css"); ?>" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url("/assets/css/argon.min.css?v=1.0.0"); ?>" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url("/assets/css/kanban_attendance.css"); ?>" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url("/assets/css/kanban_attendance_dark.css"); ?>" type="text/css" />
    <title>TalkAll | Kanban</title>
    <link rel="shortcut icon" type="image/x-icon" href="/assets/img/favicon-32x32.png">
</head>

<body>

    <script>
        const attendanceKanbanTheme = localStorage.getItem("attendanceKanbanTheme");
        const communicationKanbanColor = localStorage.getItem("communicationKanbanColor");
        const bodyKanban = document.querySelector("body");

        switch (attendanceKanbanTheme) {
            case "standard":
            case null:
                if (window.matchMedia("(prefers-color-scheme: dark)").matches) {
                    bodyKanban.className = "dark";
                    if (communicationKanbanColor == null) {
                        bodyKanban.style.background = "#1B277C";
                        bodyKanban.setAttribute("color", "hex7");
                    } else {
                        bodyKanban.style.background = communicationKanbanColor.split("__")[1];
                        bodyKanban.setAttribute("color", communicationKanbanColor.split("__")[0]);
                    }
                } else {
                    if (communicationKanbanColor == null) {
                        bodyKanban.style.background = "#2263D3";
                        bodyKanban.setAttribute("color", "hex7");
                    } else {
                        bodyKanban.style.background = communicationKanbanColor.split("__")[1];
                        bodyKanban.setAttribute("color", communicationKanbanColor.split("__")[0]);
                    }
                }
                break;
            case "dark":
                if (attendanceKanbanTheme == "dark") {
                    bodyKanban.className = "dark";
                    if (communicationKanbanColor == null) {
                        bodyKanban.style.background = "#1B277C";
                        bodyKanban.setAttribute("color", "hex7");
                    } else {
                        bodyKanban.style.background = communicationKanbanColor.split("__")[1];
                        bodyKanban.setAttribute("color", communicationKanbanColor.split("__")[0]);
                    }
                }
                break;
            case "ligth":
                if (communicationKanbanColor == null) {
                    bodyKanban.style.background = "#2263D3";
                    bodyKanban.setAttribute("color", "hex7");
                } else {
                    bodyKanban.style.background = communicationKanbanColor.split("__")[1];
                    bodyKanban.setAttribute("color", communicationKanbanColor.split("__")[0]);
                }
                break;
            default:
                bodyKanban.style.background = "#2263D3";
                break;
        }
    </script>

    <div class="kanban">
        <div class="header">
            <div class="content-left">
                <div class="logo">
                    <a href="<?= base_url() ?>">
                        <img src="<?php echo base_url("/assets/img/brand/blue.png"); ?>" alt="image">
                    </a>
                </div>
            </div>
            <div class="content-center">
                <div class="search">
                    <input type="text" id="general-search" placeholder="<?php echo $this->lang->line("kanban_attendance_filter_search_placeholder"); ?>" selected-option="">
                    <img class="icon-search" src="<?php echo base_url("/assets/icons/kanban/search2.svg"); ?>" alt="icon-search">
                    <img class="icon-clear" id="clear-search-general" src="<?php echo base_url("/assets/icons/kanban/close2.svg"); ?>" alt="icon-close">
                    <div class="battery-filter-selector" id="battery-filter-selector">
                        <svg class="icon-battery" id="icon-battery" width="19" height="13" viewBox="0 0 19 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8.48343 12.6001H10.5168C11.0759 12.6001 11.5334 12.1426 11.5334 11.5835C11.5334 11.0243 11.0759 10.5668 10.5168 10.5668H8.48343C7.92426 10.5668 7.46676 11.0243 7.46676 11.5835C7.46676 12.1426 7.92426 12.6001 8.48343 12.6001ZM0.350098 1.41681C0.350098 1.97598 0.807598 2.43348 1.36676 2.43348H17.6334C18.1926 2.43348 18.6501 1.97598 18.6501 1.41681C18.6501 0.857646 18.1926 0.400146 17.6334 0.400146H1.36676C0.807598 0.400146 0.350098 0.857646 0.350098 1.41681ZM4.41676 7.51681H14.5834C15.1426 7.51681 15.6001 7.05931 15.6001 6.50015C15.6001 5.94098 15.1426 5.48348 14.5834 5.48348H4.41676C3.8576 5.48348 3.4001 5.94098 3.4001 6.50015C3.4001 7.05931 3.8576 7.51681 4.41676 7.51681Z" fill="black" />
                        </svg>
                    </div>
                    <div class="tooltips"></div>
                </div>
            </div>
            <div class="content-right">
                <div class="filter"></div>
                <div class="settings-icon">
                    <img src="<?php echo base_url("/assets/icons/kanban/three_points.svg") ?>" alt="">
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="main-flex">

                <div class="col-lg-3 mx-rev-card">
                    <div class="card card-stats">
                        <div class="card-body">
                            <div class="row d-flex justify-content-between pl-3 pr-3">
                                <div class="responsive">
                                    <h6 class="card-title mb-0 font-weight-title"><?php echo $this->lang->line("kanban_attendance_column_waiting") ?></h6>
                                    <span id="count__Waiting" class="count-card">0</span>
                                </div>
                                <div>
                                    <div class="icon icon-shape text-white rounded-circle shadow">
                                        <img src="<?php echo base_url("/assets/icons/kanban/arrow_waiting.svg"); ?>" alt="image">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column-list" id="column__Waiting"></div>
                </div>
                <div class="col-lg-3 mx-rev-card">
                    <div class="card card-stats">
                        <div class="card-body">
                            <div class="row d-flex justify-content-between pl-3 pr-3">
                                <div class="responsive">
                                    <h6 class="card-title mb-0 font-weight-title"><?php echo $this->lang->line("kanban_attendance_column_in_progress") ?></h6>
                                    <span id="count__InService" class="count-card">0</span>
                                </div>
                                <div>
                                    <div class="icon icon-shape text-white rounded-circle shadow">
                                        <img src="<?php echo base_url("/assets/icons/kanban/chat.svg"); ?>" alt="image">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column-list" id="column__InService"></div>
                </div>
                <div class="col-lg-3 mx-rev-card">
                    <div class="card card-stats">
                        <div class="card-body">
                            <div class="row d-flex justify-content-between pl-3 pr-3">
                                <div class="responsive">
                                    <h6 class="card-title mb-0 font-weight-title"><?php echo $this->lang->line("kanban_attendance_column_paused") ?></h6>
                                    <span id="count__OnHold" class="count-card">0</span>
                                </div>
                                <div>
                                    <div class="icon icon-shape text-white rounded-circle shadow">
                                        <img src="<?php echo base_url("/assets/icons/kanban/pause.svg"); ?>" alt="image">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column-list" id="column__OnHold"></div>
                </div>
                <div class="col-lg-3 mx-rev-card">
                    <div class="card card-stats">
                        <div class="card-body">
                            <div class="row d-flex justify-content-between pl-3 pr-3">
                                <div class="responsive">
                                    <h6 class="card-title mb-0 font-weight-title"><?php echo $this->lang->line("kanban_attendance_column_complete") ?></h6>
                                    <span id="count__Closed" class="count-card">0</span>
                                </div>
                                <div>
                                    <div class="icon icon-shape text-white rounded-circle shadow">
                                        <img src="<?php echo base_url("/assets/icons/kanban/concluded.svg"); ?>" alt="image">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column-list" id="column__Closed"></div>
                </div>
            </div>

            <div class="loading-load">
                <img src="<?php echo base_url("/assets/img/loads/loading_2.gif"); ?>" alt="image" width="50px">
            </div>

            <div class="settings-option-modal">
                <div class="settings-header">
                    <img class="to-go-back close" id="selectLeft" src="<?php echo base_url("/assets/icons/kanban/select_left.svg") ?>" alt="image" onclick="backSettingsWindow()">
                    <span class="title"><?php echo $this->lang->line("kanban_attendance_settings_title") ?></span>
                    <img class="to-close close" src="<?php echo base_url("/assets/icons/kanban/close2.svg") ?>" alt="image" onclick="closeSettingsWindow()">
                </div>
                <div class="settings-body"></div>
            </div>

            <div class="drawer-side">
                <div class="user-window">
                    <div class="settings-header">
                        <div class="title">
                            <span><?php echo $this->lang->line("kanban_communication_filter_select_channel") ?></span>
                        </div>
                        <div class="input-search">
                            <img class="icon-search" src="<?php echo base_url("/assets/icons/kanban/search2.svg"); ?>" alt="image">
                            <input type="text" placeholder="<?php echo $this->lang->line("kanban_attendance_filter_search_user_placeholder"); ?>" id="search-user" selected-option="">
                            <img class="icon-clear" id="clear-search-user" src="<?php echo base_url("/assets/icons/kanban/close2.svg"); ?>" alt="icon-X">
                            <div class="filter-selector-user" id="filter-selector-user">
                                <svg class="icon-filter-user" id="icon-filter-user" width="19" height="13" viewBox="0 0 19 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8.48343 12.6001H10.5168C11.0759 12.6001 11.5334 12.1426 11.5334 11.5835C11.5334 11.0243 11.0759 10.5668 10.5168 10.5668H8.48343C7.92426 10.5668 7.46676 11.0243 7.46676 11.5835C7.46676 12.1426 7.92426 12.6001 8.48343 12.6001ZM0.350098 1.41681C0.350098 1.97598 0.807598 2.43348 1.36676 2.43348H17.6334C18.1926 2.43348 18.6501 1.97598 18.6501 1.41681C18.6501 0.857646 18.1926 0.400146 17.6334 0.400146H1.36676C0.807598 0.400146 0.350098 0.857646 0.350098 1.41681ZM4.41676 7.51681H14.5834C15.1426 7.51681 15.6001 7.05931 15.6001 6.50015C15.6001 5.94098 15.1426 5.48348 14.5834 5.48348H4.41676C3.8576 5.48348 3.4001 5.94098 3.4001 6.50015C3.4001 7.05931 3.8576 7.51681 4.41676 7.51681Z" fill="black" />
                                </svg>
                            </div>
                            <div class="tooltips-user"></div>
                        </div>

                    </div>
                    <div class="all-users">
                        <span class="user-item" id="all__users">
                            <div class="box"><img src="<?php echo base_url("/assets/icons/kanban/chat_green.svg") ?>"></div>
                            <div class="information">
                                <span><?= $this->lang->line("kanban_attendance_user_window_all"); ?></span>
                            </div>
                        </span>
                    </div>
                    <div class="list-users" id="list__users"></div>
                </div>
            </div>

            <div class="modal" id="modal-chat" tabindex="-1" role="dialog" aria-labelledby="modal-chat" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-custom" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="status">
                                <img id="modal__icon-status">
                                <div id="modal__status"></div>
                                <div id="modal__close">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true" style="font-size:30px">&times;</span>
                                    </button>
                                </div>
                            </div>
                            <div class="profile">
                                <div class="group-contact d-flex">
                                    <img id="modal__profile-contact" class="rounded-circle profile-left">
                                    <div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <span id="modal__name-contact" class="font-weight-bold"></span>
                                            <small id="modal__number-contact" class="d-block font-weight-400"></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="group-user d-flex">
                                    <div class="user-info d-flex flex-column justify-content-center">
                                        <span id="modal__name-user" class="font-weight-bold text-end"></span>
                                        <small id="modal__departament-user" class="d-block font-weight-400"></small>
                                    </div>
                                    <img id="modal__profile-user" class="rounded-circle profile-left">
                                </div>
                            </div>
                            <div class="info">
                                <div class="label">
                                    <div id="modal__label"></div>
                                </div>
                                <div class="channel">
                                    <span id="modal__name-channel"></span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="chat">
                                <div class="messages" id="messages"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="group-transfer">
                                <div class="sector" style="display: none;">
                                    <label for="modal__select-sector"><?= $this->lang->line("kanban_attendance_chat_select_department") ?></label>
                                    <select id="modal__select-sector">
                                    </select>
                                </div>
                                <div class="attendance" style="display: none;">
                                    <label for="modal__select-attendance"><?= $this->lang->line("kanban_attendance_chat_select_attendant") ?></label>
                                    <select id="modal__select-attendance">
                                    </select>
                                </div>
                                <button id="modal__btn-transfer"><?= $this->lang->line("kanban_attendance_btn_transfer") ?></button>
                                <button id="modal__btn-cancel" style="display: none;"><?= $this->lang->line("kanban_attendance_btn_cancel") ?></button>
                            </div>
                            <div class="group-close-chat">
                                <button id="modal__btn-concluded">
                                    <span><?= $this->lang->line("kanban_attendance_chat_btn_finish"); ?></span>
                                    <i class="fas fa-check-circle"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modal-list-interactive" tabindex="-1" role="dialog" aria-labelledby="modal-list-interactive" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true" style="font-size:30px">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body"></div>
                    </div>
                </div>
            </div>

            <div class="bgbox"></div>
        </div>
    </div>

    <script>
        var WebSessionBowser = '<?php echo $_SESSION["WebSessionToken"] ?>';
        var UserKeyRemoteId = '<?php echo $_SESSION["key_remote_id"] ?>';
        const GLOBAL_LANG = <?php echo json_encode($this->lang->language); ?>
    </script>

    <script type="text/javascript" src="<?php echo base_url("/assets/vendor/jquery/dist/jquery.min.js"); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url("/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url("assets/js/randomColor.min.js"); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url("/assets/dist/components_dom.js");  ?>"></script>
    <script type="text/javascript" src="<?php echo base_url("/assets/js/kanban_attendance.js"); ?>"></script>

</body>

</html>