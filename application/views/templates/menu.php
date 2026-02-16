<?php

function StrLike($needle, $haystack)
{
    $regex = '/' . str_replace('%', '.*?', $needle) . '/';
    return preg_match($regex, $haystack) > 0;
}

?>

<?php if ($_SERVER['HTTP_HOST'] == $this->config->item('platform_domain')) { ?>
    <li class="nav-item menu-attendance">
        <a class="nav-link" href="#navbar-dashboard" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-dashboard">
            <i class="far fa-chart-bar"></i>
            <span class="nav-link-text"><?php echo $this->lang->line("menu_dashboard_dashboard_name") ?></span>
        </a>
        <div class="collapse" id="navbar-dashboard">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>dashboard/attendance" class="nav-link"><?php echo $this->lang->line("menu_dashboard_attendance") ?></a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>dashboard/communication" class="nav-link"><?php echo $this->lang->line("menu_dashboard_communication") ?></a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>dashboard/tickets" class="nav-link"><?php echo $this->lang->line("menu_dashboard_ticket") ?></a>
                </li>
            </ul>
        </div>
    </li>
    <li class="nav-item menu-attendance">
        <a class="nav-link" href="#navbar-components" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-components">
            <i class="fas fa-id-badge text-primary"></i>
            <span class="nav-link-text"><?php echo $this->lang->line("menu_contact") ?></span>
        </a>
        <div class="collapse" id="navbar-components">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>contact" class="nav-link"><?php echo $this->lang->line("menu_contact") ?></a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>persona" class="nav-link"><?php echo $this->lang->line("menu_contact_personas") ?></a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>label" class="nav-link"><?php echo $this->lang->line("menu_contact_label") ?></a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>blocklist" class="nav-link"><?php echo $this->lang->line("menu_contact_blocklist") ?></a>
                </li>
            </ul>
        </div>
    </li>
    <li class="nav-item menu-attendance">
        <a class="nav-link" href="#navbar-community" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-community">
            <i class="fas fa-users" style="color:#FFAB00;"></i>
            <span class="nav-link-text"><?php echo $this->lang->line("menu_community") ?></span>
        </a>
        <div class="collapse" id="navbar-community">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>community" class="nav-link"><?php echo $this->lang->line("menu_generate_community") ?></a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>community/participants" class="nav-link"><?php echo $this->lang->line("menu_participant") ?></a>
                </li>
            </ul>
        </div>
    </li>
    <li class="nav-item menu-attendance">
        <a class="nav-link" href="#navbar-user" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-user">
            <i class="fas fa-user text-orange"></i></i>
            <span class="nav-link-text"><?php echo $this->lang->line("menu_user"); ?></span>
        </a>
        <div class="collapse" id="navbar-user">
            <ul class="nav nav-sm flex-column">

                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>usergroup" class="nav-link"><?php echo $this->lang->line("menu_user_usergroup"); ?></a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>permission" class="nav-link"><?php echo $this->lang->line("menu_user_permission"); ?></a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>usercall" class="nav-link"><?php echo $this->lang->line("menu_user_call"); ?></a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>user" class="nav-link"><?php echo $this->lang->line("menu_user"); ?></a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>replies" class="nav-link"><?php echo $this->lang->line("menu_user_replies"); ?></a>
                </li>
            </ul>
        </div>
    </li>
    <li class="nav-item menu-attendance">
        <a class="nav-link" href="<?php echo base_url(); ?>messenger" target="_blank">
            <i class="fas fa-comment-dots text-info"></i>
            <span class="nav-link-text"><?php echo $this->lang->line("menu_messenger"); ?></span>
        </a>
    </li>
    <li class="nav-item menu-media">
        <a class="nav-link" href="#navbar-marketing" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-report">
            <i class="fas fa-bullhorn" style="color: #71b093!important;"></i>
            <span class="nav-link-text"><?php echo $this->lang->line("menu_publication"); ?></span>
        </a>
        <div class="collapse" id="navbar-marketing">
            <ul class="nav nav-sm flex-column">
                <a class="nav-link" href="#navbar-waba" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-report">
                    <i class="fab fa-whatsapp text-green"></i>
                    <span class="nav-link-text"><?php echo $this->lang->line("menu_publication_whatsapp_waba"); ?></span>
                </a>
                <div class="collapse" id="navbar-waba">
                    <div style="margin-left: 29px">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="<?php echo base_url(); ?>publication/whatsapp/broadcast/waba" class="nav-link"><?php echo $this->lang->line("menu_publication_whatsapp_broadcast"); ?></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <a class="nav-link" href="#navbar-whatsapp" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-report">
                    <img src="<?php echo base_url("assets/img/panel/whatsapp_business.svg") ?>" alt="" style="width:16px;margin-right:16px;margin-left:-2px">
                    <span class="nav-link-text"><?php echo $this->lang->line("menu_publication_whatsapp_business"); ?></span>
                </a>
                <div class="collapse" id="navbar-whatsapp">
                    <div style="margin-left: 29px">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="<?php echo base_url(); ?>publication/whatsapp/broadcast/newsletter" class="nav-link"><?php echo $this->lang->line("menu_publication_whatsapp_newsletter"); ?></a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url(); ?>publication/whatsapp/broadcast/community" class="nav-link"><?php echo $this->lang->line("menu_publication_whatsapp_community"); ?></a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url(); ?>publication/whatsapp/broadcast" class="nav-link"><?php echo $this->lang->line("menu_publication_whatsapp_broadcast"); ?></a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url(); ?>publication/whatsapp/status" class="nav-link"><?php echo $this->lang->line("menu_publication_whatsapp_status"); ?></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <a class="nav-link" href="#navbar-facebook" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-report">
                    <i class="fab fa-facebook-f text-blue"></i>
                    <span class="nav-link-text"><?php echo $this->lang->line("menu_publication_facebook"); ?></span>
                </a>
                <div class="collapse" id="navbar-facebook">
                    <div style="margin-left: 29px">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="<?php echo base_url(); ?>publication/facebook/post" class="nav-link"><?php echo $this->lang->line("menu_publication_facebook_post"); ?></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <a class="nav-link" href="#navbar-instagram" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-report">
                    <i class="fab fa-instagram text-orange"></i>
                    <span class="nav-link-text"><?php echo $this->lang->line("menu_publication_instagram"); ?></span>
                </a>
                <div class="collapse" id="navbar-instagram">
                    <div style="margin-left: 29px">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="<?php echo base_url(); ?>publication/instagram/post" class="nav-link"><?php echo $this->lang->line("menu_publication_instagram_post"); ?></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <a class="nav-link" href="#navbar-tv" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-report">
                    <i class="fas fa-tv"></i>
                    <span class="nav-link-text">TV</span>
                </a>
                <div class="collapse" id="navbar-tv">
                    <div style="margin-left: 29px">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="<?php echo base_url(); ?>publication/tv/broadcast" class="nav-link"><?php echo $this->lang->line("menu_publication_sms_broadcast"); ?></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <a class="nav-link" href="#navbar-telegram" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-report">
                    <i class="fab fa-telegram text-blue"></i>
                    <span class="nav-link-text"><?php echo $this->lang->line("menu_publication_telegram"); ?></span>
                </a>
                <div class="collapse" id="navbar-telegram">
                    <div style="margin-left: 29px">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="<?php echo base_url(); ?>publication/telegram/channel" class="nav-link"><?php echo $this->lang->line("menu_publication_telegram_channel"); ?></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- <a class="nav-link" href="#navbar-sms" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-report">
                    <i class="fas fa-mobile-alt"></i>
                    <span class="nav-link-text"><?php echo $this->lang->line("menu_publication_sms"); ?></span>
                </a> -->
                <!-- <div class="collapse" id="navbar-sms">
                    <div style="margin-left: 29px">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="<?php echo base_url(); ?>broadcast/sms" class="nav-link"><?php echo $this->lang->line("menu_publication_sms_broadcast"); ?></a>
                            </li>
                        </ul>
                    </div>
                </div> -->
            </ul>
        </div>
    </li>
    <li class="nav-item menu-attendance">
        <a class="nav-link" href="#navbar-ticket" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-ticket">
            <i class="fas fa-clipboard-list"></i>
            <span class="nav-link-text"><?php echo $this->lang->line("menu_ticket") ?></span>
        </a>
        <div class="collapse" id="navbar-ticket">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>ticket" class="nav-link"><?php echo $this->lang->line("menu_tickets"); ?></a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>ticket/type" class="nav-link"><?php echo $this->lang->line("menu_ticket_type"); ?></a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>ticket/status" class="nav-link"><?php echo $this->lang->line("menu_ticket_status"); ?></a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>ticket/sla" class="nav-link"><?php echo $this->lang->line("menu_ticket_sla"); ?></a>
                </li>
            </ul>
        </div>
    </li>
    <li class="nav-item menu-attendance">
        <a class="nav-link" href="#navbar-kanban" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-kanban">
            <i class="ni ni-archive-2 text-red"></i>
            <span class="nav-link-text"><?php echo $this->lang->line("menu_kanban") ?></span>
        </a>
        <div class="collapse" id="navbar-kanban">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>kanban/attendance" class="nav-link" target="_blank"><?php echo $this->lang->line("menu_attendance") ?></a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>kanban/communication" class="nav-link" target="_blank"><?php echo $this->lang->line("menu_communication") ?></a>
                </li>
            </ul>
        </div>
    </li>
    <li class="nav-item menu-attendance">
        <a class="nav-link" href="#navbar-report" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-report">
            <i class="ni ni-single-copy-04 text-success"></i>
            <span class="nav-link-text"><?php echo $this->lang->line("menu_report") ?></span>
        </a>
        <div class="collapse" id="navbar-report">
            <ul class="nav nav-sm flex-column">

                <a class="nav-link" href="#navbar-facebook4" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-report">
                    <span class="nav-link-text">Chatbot</span>
                </a>
                <div class="collapse" id="navbar-facebook4">
                    <div style="margin-left: 10px">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="<?php echo base_url(); ?>report/chatbot/quantitative" class="nav-link"><?php echo $this->lang->line("menu_report_quantitative_chatbot") ?></a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url(); ?>report/chatbot/analytical" class="nav-link"><?php echo $this->lang->line("menu_report_analytical_chatbot") ?></a>
                            </li>
                        </ul>
                    </div>
                </div>

                <a class="nav-link" href="#navbar-facebook2" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-report">
                    <span class="nav-link-text"><?php echo $this->lang->line("menu_report_attendance") ?></span>
                </a>
                <div class="collapse" id="navbar-facebook2">
                    <div style="margin-left: 10px">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="<?php echo base_url(); ?>report/call" class="nav-link"><?php echo $this->lang->line("menu_report_history") ?></a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url(); ?>report/waiting_service" class="nav-link"><?php echo $this->lang->line("menu_report_waiting_service") ?></a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url(); ?>report/evaluate" class="nav-link"><?php echo $this->lang->line("menu_report_service_evaluation") ?></a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url(); ?>report/interaction/synthetic" class="nav-link"><?php echo $this->lang->line("menu_report_interaction_synthetic") ?></a>
                            </li>
                        </ul>
                    </div>
                </div>

                <a class="nav-link" href="#navbar-facebook3" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-report">
                    <span class="nav-link-text">Publicações</span>
                </a>
                <div class="collapse" id="navbar-facebook3">
                    <div style="margin-left: 10px">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="<?php echo base_url(); ?>report/send" class="nav-link"><?php echo $this->lang->line("menu_report_synthetic_broadcast") ?></a>
                            </li>
                            <?php if (StrLike('suporte.%', $this->session->userdata('email')) && StrLike('%@talkall.com.br', $this->session->userdata('email'))) { ?>
                                <li class="nav-item">
                                    <a href="<?php echo base_url(); ?>report/broadcast" class="nav-link"><?php echo $this->lang->line("menu_report_analytical_broadcast") ?></a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>

                <!-- <li class="nav-item">
                    <a href="<?php echo base_url(); ?>search" target="_blank" class="nav-link"><?php echo $this->lang->line("menu_report_marchine_learning") ?></a>
                </li> -->
                <!-- Específica para a company 341  -->
                <?php if ($_SESSION['id_company'] == "341") { ?>
                    <li class="nav-item">
                        <a href="<?php echo base_url(); ?>report/copacol/analytical" class="nav-link"><?php echo $this->lang->line("menu_report_copacol_analytic") ?></a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo base_url(); ?>report/copacol/synthetic" class="nav-link"><?php echo $this->lang->line("menu_report_copacol_synthetic") ?></a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </li>
    <li class="nav-item menu-attendance">
        <a class="nav-link" href="#navbar-config" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-config">
            <i class="ni ni-settings-gear-65"></i>
            <span class="nav-link-text"><?php echo lang('menu_settings') ?></span>
        </a>
        <div class="collapse" id="navbar-config">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>category" class="nav-link"><?php echo $this->lang->line("menu_category") ?></a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>worktime" class="nav-link"><?php echo $this->lang->line('menu_settings_timetable') ?></a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>integration" class="nav-link"><?php echo $this->lang->line("menu_settings_integration") ?></a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>templates" class="nav-link"><?php echo $this->lang->line('menu_settings_template') ?></a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>shortlink" class="nav-link"><?php echo $this->lang->line('menu_settings_shortlink') ?></a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>bot/trainer" class="nav-link"><?php echo $this->lang->line('menu_settings_chatbot') ?></a>
                </li>
            </ul>
        </div>
    </li>
    <!-- <li class="nav-item menu-attendance">
        <a class="nav-link" href="#navbar-financial" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-config">
            <i class="fas fa-donate"></i>
            <span class="nav-link-text"><?php echo lang('menu_settings_financial') ?></span>
        </a>
        <div class="collapse" id="navbar-financial">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>myinvoice" class="nav-link"><?php echo lang('menu_settings_invoice') ?></a>
                </li>
            </ul>
        </div>
    </li> -->
<?php } ?>