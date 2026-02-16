<?php echo form_open_multipart('publication/broadcast/sms/edit/' . $data['token']); ?>
<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("sms_broadcast_view") ?></h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("sms_broadcast_edit_information") ?></h6>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group view-sms">
                                        <div class="view-title">
                                            <label class="form-control-label"><?php echo $this->lang->line("sms_broadcast_title") ?>: </label><span> <?php echo $data['title']  ?></span>
                                        </div>

                                        <div class="view-schedule">
                                            <label class="form-control-label"><?php echo $this->lang->line("sms_broadcast_date_scheduling") ?>: </label> <span> <?php echo explode(" ", $data['schedule'])[0]; ?></span>
                                        </div>

                                        <div class="view-schedule">
                                            <label class="form-control-label"><?php echo $this->lang->line("sms_broadcast_schedule_hour_view") ?>: </label> <span> <?php echo explode(" ", $data['schedule'])[1]; ?></span>
                                        </div>

                                        <div class="view-schedule">
                                            <label class="form-control-label"><?php echo $this->lang->line("sms_broadcast_segmented_view") ?>: </label> <span> <?= $data['groups'] == 0 ? $this->lang->line("sms_broadcast_segmented_view_no") : $this->lang->line("sms_broadcast_segmented_view_yes") ?></span>
                                        </div>

                                        <?php if ($data['groups'] != 0) { ?>
                                            <div class="view-schedule">
                                                <label class="form-control-label"><?php echo $this->lang->line("sms_broadcast_selected_group_view") ?>: </label> <span> <?= $data['group_name'] ?></span>
                                            </div>
                                        <?php } ?>

                                        <div class="view-schedule">
                                            <label class="form-control-label"><?php echo $this->lang->line("sms_broadcast_select_channel_view") ?>: </label>
                                            <span> <?= $data['name'] . " (" . $data['channel'] . ")" ?></span>
                                        </div>
                                        <div class="view-schedule" id="textarea-sms">
                                            <label><b><?php echo $this->lang->line("sms_broadcast_description_sent_view") ?>:</b> </label>
                                            <textarea class="form-control fixed-textarea" disabled id="tex_area"><?= $data['data'] ?></textarea>
                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>

                        <div class="col-6">

                            <input type="hidden" id="log_creation_timestamp" value="<?= isset($log[0]['log_status']) && $log[0]['log_status'] == 7 ? $log[0]['log_timestamp_creation'] : 0 ?>">
                            <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("sms_broadcast_timeline_view") ?></h6>
                            <div class="timeline timeline-one-side">

                                <?php for ($i = 0; $i < count($log); $i++) { ?>

                                    <?php if ($log[$i]['log_status'] == 1) { ?>
                                        <!-- Criou campanha -->
                                        <div class="timeline-block mb-3">
                                            <span class="timeline-step">
                                                <i class="far fa-calendar-plus"></i>
                                            </span>
                                            <div class="timeline-content">
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("sms_broadcast_timeline_creation") ?></h6>
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
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("sms_broadcast_timeline_canceled") ?></h6>
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
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("sms_broadcast_timeline_edited") ?></h6>
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
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("sms_broadcast_timeline_send_now") ?></h6>
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
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("sms_broadcast_timeline_resend") ?></h6>
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
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $this->lang->line("sms_broadcast_timeline_creation_api") ?></h6>
                                                <p class="font-weight-bold text-xs mt-1 mb-0"><?= $log[$i]['log_creation'] ?></p>
                                            </div>
                                        </div>
                                    <?php } ?>

                                <?php } ?>

                            </div>
                        </div>

                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-8">
                            <a href="<?php echo base_url(); ?>broadcast/sms"><button class="btn btn-primary" type="button"><?php echo $this->lang->line("sms_broadcast_btn_return") ?></button></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>
<script src="<?php echo base_url('/assets/lib/build_pdf/build_pdf.js') ?>"></script>