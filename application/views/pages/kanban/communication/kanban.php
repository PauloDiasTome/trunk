<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset='UTF-8'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" />
    <link rel="stylesheet" href="<?php echo base_url("/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css"); ?>" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url("/assets/css/argon.min.css?v=1.0.0"); ?>" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url("/assets/css/kanban_communication.css"); ?>" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url("/assets/css/kanban_communication_dark.css"); ?>" type="text/css" />
    <title>TalkAll | Kanban</title>
    <link rel="shortcut icon" type="image/x-icon" href="/assets/img/favicon-32x32.png">
</head>

<body>

    <script>
        const communicationKanbanTheme = localStorage.getItem("communicationKanbanTheme");
        const communicationKanbanColor = localStorage.getItem("communicationKanbanColor");
        const bodyKanban = document.querySelector("body");

        switch (communicationKanbanTheme) {
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
                if (communicationKanbanTheme == "dark") {
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
                <div class="time">
                    <span id="current_date"></span>
                </div>
            </div>
            <div class="content-center">
                <div class="search">
                    <input type="text" placeholder="<?php echo $this->lang->line("kanban_communication_filter_search_placeholder"); ?>" id="general-search">
                    <img class="icon-search" src="<?php echo base_url("/assets/icons/kanban/search2.svg"); ?>" alt="image">
                    <img class="icon-clear" id="clear-search-general" src="<?php echo base_url("/assets/icons/kanban/close2.svg"); ?>" alt="icon-X">
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
                                    <h6 class="card-title mb-0 font-weight-title"><?php echo $this->lang->line("kanban_communication_column_waiting") ?></h6>
                                    <span id="waiting_count" class="count-card">0</span>
                                </div>
                                <div>
                                    <div class="icon icon-shape text-white rounded-circle shadow">
                                        <img src="<?php echo base_url("/assets/icons/kanban/arrow_waiting.svg"); ?>" alt="image">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column-list" id="column__waiting"></div>
                </div>
                <div class="col-lg-3 mx-rev-card">
                    <div class="card card-stats">
                        <div class="card-body">
                            <div class="row d-flex justify-content-between pl-3 pr-3">
                                <div class="responsive">
                                    <h6 class="card-title mb-0 font-weight-title"><?php echo $this->lang->line("kanban_communication_column_ongoing") ?></h6>
                                    <span id="ongoing_count" class="count-card">0</span>
                                </div>
                                <div>
                                    <div class="icon icon-shape text-white rounded-circle shadow">
                                        <img src="<?php echo base_url("/assets/icons/kanban/send.svg"); ?>" alt="image">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column-list" id="column__ongoing"></div>
                </div>
                <div class="col-lg-3 mx-rev-card">
                    <div class="card card-stats">
                        <div class="card-body">
                            <div class="row d-flex justify-content-between pl-3 pr-3">
                                <div class="responsive">
                                    <h6 class="card-title mb-0 font-weight-title"><?php echo $this->lang->line("kanban_communication_column_paused") ?></h6>
                                    <span id="paused_count" class="count-card">0</span>
                                </div>
                                <div>
                                    <div class="icon icon-shape text-white rounded-circle shadow">
                                        <img src="<?php echo base_url("/assets/icons/kanban/pause.svg"); ?>" alt="image">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column-list" id="column__paused"></div>
                </div>
                <div class="col-lg-3 mx-rev-card">
                    <div class="card card-stats">
                        <div class="card-body">
                            <div class="row d-flex justify-content-between pl-3 pr-3">
                                <div class="responsive">
                                    <h6 class="card-title mb-0 font-weight-title"><?php echo $this->lang->line("kanban_communication_column_complete") ?></h6>
                                    <span id="complete_count" class="count-card">0</span>
                                </div>
                                <div>
                                    <div class="icon icon-shape text-white rounded-circle shadow">
                                        <img src="<?php echo base_url("/assets/icons/kanban/concluded.svg"); ?>" alt="image">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column-list" id="column__complete"></div>
                </div>
            </div>

            <div class="loading-load">
                <img src="<?php echo base_url("/assets/img/loads/loading_2.gif"); ?>" alt="image" width="50px">
            </div>

            <div class="no-campaign">
                <div class="no-campaign-card">
                    <div class="box-left">
                        <img src="<?php echo base_url("/assets/icons/kanban/speaker.svg") ?>" alt="image">
                    </div>
                    <div class="box-right">
                        <span><?php echo $this->lang->line("kanban_communication_not_the_campaign") ?></span>
                        <a href="<?php echo base_url("/publication/whatsapp/broadcast") ?>" target=”_blank”><?php echo $this->lang->line("kanban_communication_add_campaign"); ?> <img src="<?php echo base_url("/assets/icons/kanban/arrow-right.svg") ?>" alt=""></a>
                    </div>
                </div>
            </div>

            <div class="settings-option-modal">
                <div class="settings-header">
                    <img class="to-go-back close" id="selectLeft" src="<?php echo base_url("/assets/icons/kanban/select_left.svg") ?>" alt="image" onclick="backSettingsWindow()">
                    <span class="title"><?php echo $this->lang->line("kanban_communication_settings_title") ?></span>
                    <img class="to-close close" src="<?php echo base_url("/assets/icons/kanban/close2.svg") ?>" alt="image" onclick="closeSettingsWindow()">
                </div>
                <div class="settings-body"></div>
            </div>

            <div class="drawer-side">
                <div class="channel-window">
                    <div class="settings-header">
                        <div class="title">
                            <span><?php echo $this->lang->line("kanban_communication_filter_select_channel") ?></span>
                        </div>
                        <div class="input-search">
                            <img class="icon-search" src="<?php echo base_url("/assets/icons/kanban/search2.svg"); ?>" alt="image">
                            <input type="text" placeholder="<?php echo $this->lang->line("kanban_communication_filter_search_placeholder"); ?>" id="search-channel">
                            <img class="icon-clear" id="clear-search-channel" src="<?php echo base_url("/assets/icons/kanban/close2.svg"); ?>" alt="icon-X">
                        </div>
                    </div>
                    <div class="all-channels">
                        <span class="channel-item" id="all__channels">
                            <div class="box"><img src="<?php echo base_url("/assets/icons/kanban/announcement.svg") ?>"></div>
                            <div class="information">
                                <span><?php echo $this->lang->line("kanban_communication_filter_total_channel") ?></span>
                            </div>
                        </span>
                    </div>
                    <div class="list-channel" id="list__channel"></div>
                </div>
            </div>

            <div class="modal fade" id="modal-campaign" tabindex="-1" role="dialog" aria-labelledby="modal-campaign" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document" style="min-width:700px;">
                    <div class="modal-content" id="content__campaign">
                        <div class="modal-header"></div>
                        <div class="modal-body"></div>
                        <div class="modal-footer"></div>
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
    <script type="text/javascript" src="<?php echo base_url("/assets/dist/components_dom.js");  ?>"></script>
    <script type="text/javascript" src="<?php echo base_url("/assets/js/form_validation.js"); ?>"></script>
    <script src="<?php echo base_url('/assets/lib/build_pdf/build_pdf.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url("/assets/js/kanban_communication.js"); ?>"></script>

</body>

</html>