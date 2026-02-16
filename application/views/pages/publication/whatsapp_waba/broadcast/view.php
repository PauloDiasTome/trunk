<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("whatsapp_broadcast_waba_view_title") ?></h3>
                        </div>
                    </div>
                </div>

                <input type="hidden" id="view" value="view">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("whatsapp_broadcast_waba_edit_information") ?></h6>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group view-broadcast">
                                        <div class="view-title">
                                            <label class="form-control-label"><?php echo $this->lang->line("whatsapp_broadcast_waba_title") ?>: </label><span> <?php echo $data['title']  ?></span>
                                        </div>

                                        <div class="view-schedule">
                                            <label class="form-control-label"><?php echo $this->lang->line("whatsapp_broadcast_waba_date_scheduling") ?>: </label> <span> <?php echo explode(" ", $data['schedule'])[0]; ?></span>
                                        </div>

                                        <div class="view-schedule">
                                            <label class="form-control-label"><?php echo $this->lang->line("whatsapp_broadcast_waba_hour_scheduling") ?>: </label> <span> <?php echo explode(" ", $data['schedule'])[1]; ?></span>
                                        </div>

                                        <div class="view-schedule">
                                            <label class="form-control-label"><?php echo $this->lang->line("whatsapp_broadcast_waba_select_channel_view") ?>: </label> <span> <?= $data['name_channel'] ?></span>
                                        </div>

                                        <div class="view-schedule">
                                            <label class="form-control-label"><?php echo $this->lang->line("whatsapp_broadcast_waba_select_segmented_view") ?>: </label> <span> <?= $data['groups'] == 0 ? $this->lang->line("whatsapp_broadcast_waba_select_group_view_no") : $this->lang->line("whatsapp_broadcast_waba_select_group_view_yes") ?></span>
                                        </div>

                                        <?php if ($data['groups'] != 0) { ?>
                                            <div class="view-schedule">
                                                <label class="form-control-label"><?php echo $this->lang->line("whatsapp_broadcast_waba_selected_groups_view") ?>: </label> <span> <?= $data['group_name'] ?></span>
                                            </div>
                                        <?php } ?>

                                        <div class="view-schedule">
                                            <label class="form-control-label"><?php echo $this->lang->line("whatsapp_broadcast_waba_select_template_view") ?>: </label> <span> <?= $data['name_template'] ?></span>
                                            <input id="id_template" hidden value="<?php echo $data['id_template'] ?>">
                                            <input id="json_parameters" hidden value='<?php echo json_encode($data['json_parameters']) ?>'>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row template-whatsapp-broadcast-view">
                                <div class="wa-template">
                                    <div class="col-12">

                                        <div class="row justify-content-center">
                                            <div class="wa-header">
                                                <div class="col-12 col-img">
                                                    <img src="https://app.talkall.com.br/assets/img/broadcast_approval/header_wa.jpeg" draggable="false" alt="">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="wa-body">
                                                <div class="col align-self-end justify-content-end">

                                                    <div class="box-broadcast-approval" draggable="false"></div>
                                                    <div class="buttons-quick-reply"></div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="row justify-content-center">

                                            <div class="wa-footer">
                                                <div class="col col-img">
                                                    <img src="https://app.talkall.com.br/assets/img/broadcast_approval/footer_wa.png" draggable="false" alt="">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                       <div class="col-6">
                            <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("whatsapp_broadcast_waba_timeline_view") ?></h6>

                            <div class="timeline timeline-one-side">

                                <?php for ($i = 0; $i < count($log); $i++) { ?>

                                    <?php if ($log[$i]['log_status'] == 1) { ?>
                                        <!-- Criou campanha -->
                                        <div class="timeline-block mb-3">
                                            <span class="timeline-step">
                                                <i class="far fa-calendar-plus"></i>
                                            </span>
                                            <div class="timeline-content">
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("whatsapp_broadcast_waba_timeline_creation") ?></h6>
                                                <p class="font-weight-bold text-xs mt-1 mb-0"><?= $log[$i]['log_creation'] ?></p>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <?php if ($log[$i]['log_status'] == 4) { ?>
                                        <!-- Cancelou campanha -->
                                        <div class="timeline-block mb-3">
                                            <span class="timeline-step">
                                                <i class="fas fa-times text-danger"></i>
                                            </span>
                                            <div class="timeline-content">
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("whatsapp_broadcast_waba_timeline_canceled") ?></h6>
                                                <p class="font-weight-bold text-xs mt-1 mb-0"><?= $log[$i]['log_creation'] ?></p>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <?php if ($log[$i]['log_status'] == 7) { ?>
                                        <!-- Editou campanha -->
                                        <div class="timeline-block mb-3">
                                            <span class="timeline-step">
                                                <i class="far fa-edit"></i>
                                            </span>
                                            <div class="timeline-content">
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("whatsapp_broadcast_waba_timeline_edited") ?></h6>
                                                <p class="font-weight-bold text-xs mt-1 mb-0"><?= $log[$i]['log_creation'] ?></p>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <?php if ($log[$i]['log_status'] == 8) { ?>
                                        <!-- Alterou para enviar agora -->
                                        <div class="timeline-block mb-3">
                                            <span class="timeline-step">
                                                <i class="far fa-paper-plane"></i>
                                            </span>
                                            <div class="timeline-content">
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("whatsapp_broadcast_waba_timeline_send_now") ?></h6>
                                                <p class="font-weight-bold text-xs mt-1 mb-0"><?= $log[$i]['log_creation'] ?></p>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <?php if ($log[$i]['log_status'] == 9) { ?>
                                        <!-- Reenviou campanha -->
                                        <div class="timeline-block mb-3">
                                            <span class="timeline-step">
                                                <i class="fas fa-redo"></i>
                                            </span>
                                            <div class="timeline-content">
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("whatsapp_broadcast_waba_timeline_resend") ?></h6>
                                                <p class="font-weight-bold text-xs mt-1 mb-0"><?= $log[$i]['log_creation'] ?></p>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <?php if ($log[$i]['log_status'] == 10) { ?>
                                        <!-- Criou campanha pela API -->
                                        <div class="timeline-block mb-3">
                                            <span class="timeline-step">
                                                <i class="fas fa-external-link-alt"></i>
                                            </span>
                                            <div class="timeline-content">
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $this->lang->line("whatsapp_broadcast_waba_timeline_creation_api") ?></h6>
                                                <p class="font-weight-bold text-xs mt-1 mb-0"><?= $log[$i]['log_creation'] ?></p>
                                            </div>
                                        </div>
                                    <?php } ?>

                                <?php } ?>

                            </div>
                        </div>

                    </div>
                    
                    <div class="row">
                        <div class="col-lg-8">
                            <a href="<?php echo base_url(); ?>publication/whatsapp/broadcast/waba"><button class="btn btn-primary" type="button"><?php echo $this->lang->line("whatsapp_broadcast_waba_btn_return") ?></button></a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url('/assets/lib/build_pdf/build_pdf.js') ?>"></script>