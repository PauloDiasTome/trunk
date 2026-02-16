<div class="modal fade" id="modal-tutorial-step-1" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content tutorial-modal-content">

            <div class="modal-body text-center">

                <!-- Ícone -->
                <div class="tutorial-icon">
                    <img src="<?php echo base_url('assets/icons/panel/onboarding.svg'); ?>" style="width:48px;">
                </div>

                <!-- Título -->
                <h4 class="tutorial-title">
                    <?php echo $this->lang->line("tutorial_step1_title"); ?>
                </h4>

                <!-- Destaque -->
                <p class="tutorial-highlight">
                    <?php echo $this->lang->line("tutorial_step1_highlight"); ?><br>
                    <strong><?php echo $this->lang->line("tutorial_step1_highlight_strong"); ?></strong>
                </p>

                <!-- Descrição -->
                <p class="tutorial-description">
                    <?php echo $this->lang->line("tutorial_step1_description"); ?>
                </p>

                <!-- Lista -->
                <div class="tutorial-features">
                    <p class="tutorial-features-title"><strong><?php echo $this->lang->line("tutorial_step1_features_title"); ?></strong></p>
                    <ul>
                        <li>✓ <?php echo $this->lang->line("tutorial_step1_feature_1"); ?></li>
                        <li>✓ <?php echo $this->lang->line("tutorial_step1_feature_2"); ?></li>
                        <li>✓ <?php echo $this->lang->line("tutorial_step1_feature_3"); ?></li>
                        <li>✓ <?php echo $this->lang->line("tutorial_step1_feature_4"); ?></li>
                    </ul>
                </div>

                <!-- Botão -->
                <button type="button" class="btn btn-primary btn-block mt-4" id="tutorial-step-1-next">
                    <?php echo $this->lang->line("tutorial_step1_btn_start"); ?>
                </button>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-tutorial-step-2" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content tutorial-modal-content">

            <div class="modal-body text-center">

                <!-- Ícone -->
                <div class="tutorial-icon mb-3">
                    <img src="<?php echo base_url('assets/icons/panel/onboarding-title.svg'); ?>" style="width:48px;">
                </div>

                <!-- Título -->
                <h4 class="tutorial-title">
                    <?php echo $this->lang->line("tutorial_step2_title"); ?><br><?php echo $this->lang->line("tutorial_step2_title_break"); ?>
                </h4>

                <!-- Descrição principal -->
                <p class="tutorial-description">
                    <?php echo $this->lang->line("tutorial_step2_description_one"); ?>
                    <strong><?php echo $this->lang->line("tutorial_step2_description_whatsapp"); ?></strong>
                    <strong><?php echo $this->lang->line("tutorial_step2_description_two"); ?></strong>
                </p>

                <!-- Texto auxiliar -->
                <p class="tutorial-description small">
                    <?php echo $this->lang->line("tutorial_step2_description_small_one"); ?>
                    <strong><?php echo $this->lang->line("tutorial_step2_description_small_qrcode"); ?></strong>
                    <?php echo $this->lang->line("tutorial_step2_description_small_two"); ?>
                </p>

                <!-- Botão conectar -->
                <button type="button" class="btn btn-outline-success btn-block mt-3" id="connect-whatsapp">
                    <img src="<?php echo base_url('assets/icons/panel/whatsapp.svg'); ?>" style="width:18px;margin-right:6px;">
                    <?php echo $this->lang->line("tutorial_step2_btn_connect_whatsapp"); ?>
                </button>

                <hr>

                <!-- Criar conta Facebook -->
                <p class="tutorial-description small mb-2">
                    <?php echo $this->lang->line("tutorial_step2_no_facebook"); ?>
                </p>

                <button type="button" class="btn btn-outline-primary btn-block" onclick="window.open('https://www.facebook.com/r.php', '_blank')">
                    <img src="<?php echo base_url('assets/icons/panel/facebook.svg'); ?>" style="width:18px;margin-right:6px;">
                    <?php echo $this->lang->line("tutorial_step2_btn_create_facebook"); ?>
                </button>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-tutorial-step-3" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content tutorial-modal-content">

            <div class="modal-body text-center">

                <!-- Ícone topo -->
                <div class="tutorial-icon">
                    <img src="<?php echo base_url('assets/icons/panel/icon_step3.svg'); ?>" style="width:48px;">
                </div>

                <!-- Título -->
                <h4 class="tutorial-title">
                    <?php echo $this->lang->line("tutorial_step3_title"); ?><br><?php echo $this->lang->line("tutorial_step3_title_break"); ?>
                </h4>

                <!-- Status OK -->
                <div class="tutorial-status">
                    <img src="<?php echo base_url('assets/icons/panel/group.svg'); ?>" style="width:36px;">
                    <strong>
                        <p class="tutorial-status-title"><?php echo $this->lang->line("tutorial_step3_status_title"); ?></p>
                    </strong>
                    <p class="tutorial-status-subtitle"><?php echo $this->lang->line("tutorial_step3_status_subtitle"); ?></p>
                </div>

                <!-- Número -->
                <p class="tutorial-description">
                    <?php echo $this->lang->line("tutorial_step3_send_message"); ?>
                </p>

                <p class="tutorial-phone" id="integrated-phone">--</p>

                <hr>

                <!-- Instrução -->
                <p class="tutorial-description">
                    <?php echo $this->lang->line("tutorial_step3_after_text"); ?>
                </p>

                <p class="tutorial-description small">
                    <?php echo $this->lang->line("tutorial_step3_messenger_description"); ?>
                </p>

                <!-- Botão -->
                <button type="button" class="btn btn-primary btn-block mt-3" id="open-messenger">
                    <?php echo $this->lang->line("tutorial_step3_btn_open_messenger"); ?>
                </button>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-tutorial-step-4" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content tutorial-modal-content">

            <div class="modal-body text-center">

                <h4 class="tutorial-title" style="font-size: 24px;">
                    <?php echo $this->lang->line("tutorial_step4_title"); ?>
                </h4>

                <div class="tutorial-options-list">

                    <a href="https://app.talkall.com.br/user" target="_blank" class="tutorial-option-item" id="opt-convidar-equipe">
                        <div class="tutorial-option-text">
                            <h5><?php echo $this->lang->line("tutorial_step4_opt_team_title"); ?></h5>
                            <p><?php echo $this->lang->line("tutorial_step4_opt_team_desc"); ?></p>
                        </div>
                        <div class="tutorial-option-icon">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </a>

                    <a href="https://app.talkall.com.br/bot/trainer" target="_blank" class="tutorial-option-item" id="opt-criar-chatbot">
                        <div class="tutorial-option-text">
                            <h5><?php echo $this->lang->line("tutorial_step4_opt_chatbot_title"); ?></h5>
                            <p><?php echo $this->lang->line("tutorial_step4_opt_chatbot_desc"); ?></p>
                        </div>
                        <div class="tutorial-option-icon">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </a>

                    <a href="https://app.talkall.com.br/integration" target="_blank" class="tutorial-option-item" id="opt-mensagem-boas-vindas">
                        <div class="tutorial-option-text">
                            <h5><?php echo $this->lang->line("tutorial_step4_opt_welcome_title"); ?></h5>
                            <p><?php echo $this->lang->line("tutorial_step4_opt_welcome_desc"); ?></p>
                        </div>
                        <div class="tutorial-option-icon">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </a>

                    <a href="https://app.talkall.com.br/kanban/attendance" target="_blank" class="tutorial-option-item" id="opt-kanban">
                        <div class="tutorial-option-text">
                            <h5><?php echo $this->lang->line("tutorial_step4_opt_kanban_title"); ?></h5>
                            <p><?php echo $this->lang->line("tutorial_step4_opt_kanban_desc"); ?></p>
                        </div>
                        <div class="tutorial-option-icon">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </a>

                </div>
            </div>
        </div>
    </div>
</div>