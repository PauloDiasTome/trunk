<input type="hidden" id="view" value="<?php echo $data["view"] ?>">
<input type="hidden" id="countImages" value="<?= $data['media_url'] != null ? 1 : 0 ?>">

<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("whatsapp_status_view_title") ?></h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-6">
                            <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("whatsapp_status_edit_information") ?></h6>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group view-broadcast">

                                        <div class="view-title">
                                            <label class="form-control-label"><?php echo $this->lang->line("whatsapp_status_title") ?>: </label><span> <?php echo $data['title']  ?></span>
                                        </div>

                                        <div class="view-schedule">
                                            <label class="form-control-label"><?php echo $this->lang->line("whatsapp_status_date_scheduling") ?>: </label> <span> <?php echo explode(" ", $data['schedule'])[0]; ?></span>
                                        </div>

                                        <div class="view-schedule">
                                            <label class="form-control-label"><?php echo $this->lang->line("whatsapp_status_hour_scheduling") ?>: </label> <span> <?php echo explode(" ", $data['schedule'])[1]; ?></span>
                                        </div>

                                        <div class="view-schedule">
                                            <label class="form-control-label"><?php echo $this->lang->line("whatsapp_status_select_channel_view") ?>: </label>
                                            <span> <?= $data['name_channel'] ?></span>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="row template-whatsapp-status-view">
                                <div class="wa-template">

                                    <div class="col-12">

                                        <div class="row justify-content-center">

                                            <div class="wa-header">
                                                <div class="col-12">
                                                    <img src="https://app.talkall.com.br/assets/img/status_approval/header_status_whatsapp.png" draggable="false" alt="">
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row justify-content-center">

                                            <div class="wa-body">

                                                <div class="col align-self-center d-flex justify-content-center">

                                                    <?php if ($data['media_type'] == 5) { ?>

                                                        <div class="box-broadcast-approval row" draggable="false">

                                                            <video id="videoPreview" muted autoplay loop>
                                                                <source src="<?php echo $data["media_url"]; ?>" type="video/mp4">
                                                            </video>

                                                            <?php if ($data['data'] != '') { ?>

                                                                <div class="textarea-status" id="textarea-status">
                                                                    <textarea class="tex-area" disabled id="tex_area0" data-type="type-image"><?php echo isset($data['data']) ? $data['data'] : '' ?></textarea>
                                                                    <a href="#" id="read_more0"><?php echo $this->lang->line("report_send_read_more") ?></a>
                                                                </div>

                                                            <?php } ?>

                                                        </div>

                                                    <?php } else { ?>

                                                        <div class="box-broadcast-approval row" draggable="false">

                                                            <?php if ($data['media_url'] != null) { ?>

                                                                <input type="hidden" id="thumb_image">
                                                                <div class="box-inner" style="justify-content: inherit;">

                                                                    <div class="img-broadcast" style="cursor:default">
                                                                        <img class="i_st" id="thumb_image" src="<?php echo $data['media_url'] ?>">
                                                                    </div>

                                                                </div>

                                                                <div class="textarea-status" id="textarea-status">
                                                                    <textarea class="tex-area" disabled id="tex_area" data-type="type-image"><?php echo $data['data'] ?></textarea>
                                                                </div>

                                                            <?php  } else { ?>

                                                                <div class="textarea-status-onlytext" id="textarea-status">
                                                                    <textarea class="tex-area-onlytext" disabled id="tex-area-onlytext" data-type="type-image"><?php echo $data['data'] ?></textarea>
                                                                </div>

                                                            <?php } ?>

                                                        </div>

                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row justify-content-center">

                                            <div class="wa-footer">
                                                <div class="col">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-6">
                            <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("whatsapp_status_timeline_view") ?></h6>

                            <div class="timeline timeline-one-side">

                                <?php for ($i = 0; $i < count($log); $i++) { ?>

                                    <?php if ($log[$i]['log_status'] == 1) { ?>
                                        <!-- Criou campanha -->
                                        <div class="timeline-block mb-3">
                                            <span class="timeline-step">
                                                <i class="far fa-calendar-plus"></i>
                                            </span>
                                            <div class="timeline-content">
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("whatsapp_status_timeline_creation") ?></h6>
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
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("whatsapp_status_timeline_canceled") ?></h6>
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
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("whatsapp_status_timeline_edited") ?></h6>
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
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("whatsapp_status_timeline_send_now") ?></h6>
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
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("whatsapp_status_timeline_resend") ?></h6>
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
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $this->lang->line("whatsapp_status_timeline_creation_api") ?></h6>
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
                            <a href="<?php echo base_url(); ?>publication/whatsapp/status"><button class="btn btn-primary" type="button"><?php echo $this->lang->line("whatsapp_status_btn_return") ?></button></a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>