<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("instagram_post_view_title") ?></h3>
                        </div>
                    </div>
                </div>

                <?php echo form_open_multipart('publication/instagram/post/edit/' . $data['id_token']); ?>
                <input type="hidden" id="verify-view" value="<?php echo isset($view) ? "view" : "" ?>">
                <div class="card-body">
                    <div class="row">

                        <div class="col-6">
                            <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("instagram_post_edit_information") ?></h6>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group view-post">

                                        <div class="view-title">
                                            <label class="form-control-label"><?php echo $this->lang->line("instagram_post_title") ?>: </label><span> <?php echo $view[0]["title"]; ?></span>
                                        </div>

                                        <div class="view-schedule">
                                            <label class="form-control-label"><?php echo $this->lang->line("instagram_post_date_scheduling") ?>: </label> <span> <?php echo str_split($view[0]["schedule"], 11)[0] ?></span>
                                        </div>

                                        <div class="view-schedule">
                                            <label class="form-control-label"><?php echo $this->lang->line("instagram_post_hour_scheduling") ?>: </label> <span> <?php echo str_split($view[0]["schedule"], 11)[1] ?></span>
                                        </div>

                                        <div class="view-schedule">

                                            <label class="form-control-label"><?php echo $this->lang->line("instagram_post_status_scheduling") ?>:

                                                <?php switch ($view[0]["status"]) {
                                                    case '1': ?>
                                                        <span><?php echo $this->lang->line("instagram_post_filter_status_processed") ?></span>
                                                        <?php break; ?>

                                                    <?php
                                                    case '2': ?>
                                                        <span><?php echo $this->lang->line("instagram_post_filter_status_send") ?> </span>
                                                        <?php break; ?>

                                                    <?php
                                                    case '3': ?>
                                                        <span><?php echo $this->lang->line("instagram_post_filter_status_processing") ?> </span>
                                                        <?php break; ?>

                                                    <?php
                                                    case '4': ?>
                                                        <span> <?php echo $this->lang->line("instagram_post_filter_status_canceling") ?> </span>
                                                        <?php break; ?>

                                                    <?php
                                                    case '5': ?>
                                                        <span> <?php echo $this->lang->line("instagram_post_filter_status_called_off") ?> </span>
                                                        <?php break; ?>

                                                    <?php
                                                    default:
                                                        # code...
                                                        break; ?>

                                                <?php } ?>

                                        </div>

                                        <div class="view-channel" style="margin-top: -10px;">
                                            <label class="form-control-label"><?php echo $this->lang->line("instagram_post_selected_channel_view") ?>: </label>

                                            <div class="list-channel">
                                                <?php foreach ($view as $val) {

                                                    if (count($view) > 1) {
                                                ?>
                                                        <span><?php echo ($val["name"]) ?>,</span>
                                                    <?php
                                                    } else { ?>
                                                        <span><?php echo ($val["name"]) ?></span>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </div>

                                        </div>

                                        <div class="view-text">
                                            <label class="form-control-label"><?php echo $this->lang->line("instagram_post_subtitle_view") ?>: </label>
                                            <span><?php echo $view[0]['data'] ?></span>
                                        </div>

                                        <div class="view-preview">

                                            <div class="container-preview">

                                                <div class="preview-post-header">
                                                    <div class="title">
                                                        <img src="<?php echo base_url("assets/img/panel/preview-header-instagram.png"); ?>" alt="profile">
                                                    </div>
                                                    <div class="profile">
                                                        <img src="<?php echo base_url("assets/img/panel/instagram.png"); ?>" alt="profile">
                                                    </div>
                                                </div>

                                                <div class="preview-post-body">
                                                    <div class="box-slider" id="box__slider">
                                                        <div class="background-no-picture">

                                                            <?php if ($view[0]['media_type'] == 5) { ?>
                                                                <video style="margin-top: -60px;" width="280" height="310" controls>
                                                                    <source src="<?php echo $view[0]['media_url'] ?>" type="video/mp4">
                                                                </video>
                                                            <?php } else { ?>
                                                                <img src="<?php echo $view[0]["thumb_image"] ?>" alt="" style="opacity:1;">
                                                            <?php } ?>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="preview-post-footer">
                                                    <div class="icons">
                                                        <img src="<?php echo base_url("assets/img/panel/instagram-like.png") ?>" alt="">
                                                        <img src="<?php echo base_url("assets/img/panel/instagram-comment.png") ?>" alt="">
                                                        <img src="<?php echo base_url("assets/img/panel/instagram-direct.png") ?>" alt="">
                                                        <img class="save" src="<?php echo base_url("assets/img/panel/instagram-save.png") ?>" alt="">
                                                    </div>
                                                    <div id="description-post" class="description-post">
                                                        <p></p>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">

                            <?php for ($i = 0; $i < count($log); $i++) { ?>
                                <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("whatsapp_broadcast_view_timeline") ?></h6>
                                <div class="timeline timeline-one-side">

                                    <?php if ($log[$i]['log_status'] == 1) { ?>
                                        <!-- Criou campanha -->
                                        <div class="timeline-block mb-3">
                                            <span class="timeline-step">
                                                <i class="far fa-calendar-plus"></i>
                                            </span>
                                            <div class="timeline-content">
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("instagram_post_timeline_creation") ?></h6>
                                                <p class="font-weight-bold text-xs mt-1 mb-0"><?= $log[$i]['log_creation'] ?></p>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <?php if ($log[$i]['log_status'] == 2) { ?>
                                        <!-- Pausou campanha -->
                                        <div class="timeline-block mb-3">
                                            <span class="timeline-step">
                                                <i class="far fa-pause-circle"></i>
                                            </span>
                                            <div class="timeline-content">
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("instagram_post_timeline_paused") ?></h6>
                                                <p class="font-weight-bold text-xs mt-1 mb-0"><?= $log[$i]['log_creation'] ?></p>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <?php if ($log[$i]['log_status'] == 3) { ?>
                                        <!-- Retomou campanha -->
                                        <div class="timeline-block mb-3">
                                            <span class="timeline-step">
                                                <i class="far fa-play-circle"></i>
                                            </span>
                                            <div class="timeline-content">
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("instagram_post_timeline_resume") ?></h6>
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
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("instagram_post_timeline_canceled") ?></h6>
                                                <p class="font-weight-bold text-xs mt-1 mb-0"><?= $log[$i]['log_creation'] ?></p>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <?php if ($log[$i]['log_status'] == 5) { ?>
                                        <!-- ultrapassou período de envio -->
                                        <div class="timeline-block mb-3">
                                            <span class="timeline-step">
                                                <i class="far fa-hourglass"></i>
                                            </span>
                                            <div class="timeline-content">
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("instagram_post_timeline_exceed") ?></h6>
                                                <p class="font-weight-bold text-xs mt-1 mb-0"><?= $log[$i]['log_creation'] ?></p>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <?php if ($log[$i]['log_status'] == 6) { ?>
                                        <!-- parcial campanha -->
                                        <div class="timeline-block mb-3">
                                            <span class="timeline-step">
                                                <i class="fas fa-hourglass-end"></i>
                                            </span>
                                            <div class="timeline-content">
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("instagram_post_timeline_partial") ?></h6>
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
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("instagram_post_timeline_edited") ?></h6>
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
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("instagram_post_timeline_send_now") ?></h6>
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
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("instagram_post_timeline_resend") ?></h6>
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
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $this->lang->line("instagram_post_timeline_creation_api") ?></h6>
                                                <p class="font-weight-bold text-xs mt-1 mb-0"><?= $log[$i]['log_creation'] ?></p>
                                            </div>
                                        </div>
                                    <?php } ?>

                                </div>
                            <?php } ?>

                        </div>

                    </div>

                    <br>
                    <div class="row mt-2">
                        <div class="col-lg-12">
                            <a href="<?php echo base_url(); ?>publication/instagram/post"><button class="btn btn-primary" type="button"><?php echo $this->lang->line("instagram_post_btn_return") ?></button></a>
                        </div>
                    </div>

                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>