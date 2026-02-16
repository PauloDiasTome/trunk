<input type="hidden" id="view" value="<?php echo isset($data["view"]) ?>">
<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("tv_broadcast_view") ?></h3>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("tv_broadcast_edit_information") ?></h6>

                            <div class="form-group view-broadcast">
                                <div class="view-title">
                                    <label class="form-control-label"><?php echo $this->lang->line("tv_broadcast_title") ?>: </label><span> <?php echo $data['title']  ?></span>
                                </div>

                                <div class="view-schedule">
                                    <label class="form-control-label"><?php echo $this->lang->line("tv_broadcast_date_scheduling") ?>: </label> <span> <?php echo explode(" ", $data['schedule'])[0]; ?></span>
                                </div>

                                <div class="view-schedule">
                                    <label class="form-control-label"><?php echo $this->lang->line("tv_broadcast_hour_scheduling") ?>: </label> <span> <?php echo explode(" ", $data['schedule'])[1]; ?></span>
                                </div>

                                <div id="date-validity" style="display: <?php echo $data['expire'] != "" ? "" : "none" ?>">
                                    <div class="view-schedule">
                                        <label class="form-control-label"><?php echo $this->lang->line("tv_broadcast_date_end") ?>: </label> <span> <?php echo explode(" ", $data['expire'])[0]; ?></span>
                                    </div>
                                    <div class="view-schedule">
                                        <label class="form-control-label"><?php echo $this->lang->line("tv_broadcast_hour_end") ?>: </label> <span> <?php echo explode(" ", $data['expire'])[1]; ?></span>
                                    </div>
                                </div>

                                <div class="view-schedule">
                                    <label class="form-control-label"><?php echo $this->lang->line("tv_broadcast_selected_channel") ?>: </label>
                                    <span> <?= $data['name'] ?></span>
                                </div>
                            </div>

                            <div class="row template-whatsapp-broadcast-view">
                                <div class="wa-template">
                                    <div class="col-12">
                                        <div class="row justify-content-end">
                                            <div class="col align-self-end d-flex justify-content-end">

                                                <?php if ($data["media_url"] != null) { ?>

                                                    <video id="videoPreview" controls>
                                                        <source src="<?php echo $data["media_url"]; ?>" type="video/mp4">
                                                    </video>

                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <input type="hidden" id="log_creation_timestamp" value="<?= isset($log[0]['log_status']) && $log[0]['log_status'] == 7 ? $log[0]['log_timestamp_creation'] : 0 ?>">
                            <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("tv_broadcast_timeline_view") ?></h6>

                            <div class="timeline timeline-one-side">

                                <?php for ($i = 0; $i < count($log); $i++) { ?>

                                    <!-- Criação -->
                                    <?php if ($log[$i]['log_status'] == 1) { ?>

                                        <div class="timeline-block mb-3">
                                            <span class="timeline-step">
                                                <i class="far fa-calendar-plus"></i>
                                            </span>
                                            <div class="timeline-content">
                                                <h6 class="text-dark text-sm font-weight-bold mb-0">
                                                    <?= $log[$i]['full_name'] ?> <?php echo $this->lang->line("tv_broadcast_creation_schedule_timeline_view") ?>
                                                </h6>
                                                <p class="font-weight-bold text-xs mt-1 mb-0"><?= $log[$i]['log_creation'] ?></p>
                                            </div>
                                        </div>

                                        <!-- CAMPANHA PAUSADA -->
                                    <?php } else if ($log[$i]['log_status'] == 2) {    ?>

                                        <div class="timeline-block mb-3">
                                            <span class="timeline-step">
                                                <i class="far fa-pause-circle"></i>
                                            </span>
                                            <div class="timeline-content">
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['full_name'] ?> <?php echo $this->lang->line("tv_broadcast_paused_timeline_view") ?></h6>
                                                <p class="font-weight-bold text-xs mt-1 mb-0"><?= $log[$i]['log_creation'] ?></p>
                                            </div>
                                        </div>

                                        <!-- // CAMPANHA RETOMADA -->
                                    <?php } else if ($log[$i]['log_status'] == 3) {    ?>

                                        <div class="timeline-block mb-3">
                                            <span class="timeline-step">
                                                <i class="far fa-play-circle"></i>
                                            </span>
                                            <div class="timeline-content">
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['full_name'] ?> <?php echo $this->lang->line("tv_broadcast_resumption_timeline_view") ?></h6>
                                                <p class="font-weight-bold text-xs mt-1 mb-0"><?= $log[$i]['log_creation'] ?></p>
                                            </div>
                                        </div>

                                        <!-- // CAMAPANHA CANCELADA -->
                                    <?php } else if ($log[$i]['log_status'] == 4) { ?>

                                        <div class="timeline-block mb-3">
                                            <span class="timeline-step">
                                                <i class="fas fa-times text-danger"></i>
                                            </span>
                                            <div class="timeline-content">
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['full_name'] ?> <?php echo $this->lang->line("tv_broadcast_canceled_timeline_view") ?></h6>
                                                <p class="font-weight-bold text-xs mt-1 mb-0"><?= $log[$i]['log_creation'] ?></p>
                                            </div>
                                        </div>

                                    <?php } ?>
                                <?php } ?>
                                
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-lg-12">
                            <a href="<?php echo base_url(); ?>publication/tv/broadcast"><button class="btn btn-primary" type="button"><?php echo $this->lang->line("tv_broadcast_btn_return") ?></button></a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>