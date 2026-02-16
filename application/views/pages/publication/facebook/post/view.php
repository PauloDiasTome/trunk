<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("facebook_post_view_campaign") ?></h3>
                        </div>
                    </div>
                </div>

                <?php echo form_open_multipart('publication/facebook/post/edit/' . $data['id_token']); ?>
                <input type="hidden" id="verify-view" value="<?php echo isset($view) ? "view" : "" ?>">
                <div class="card-body">
                    <div class="row">

                        <div class="col-6">
                            <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("facebook_post_edit_information") ?></h6>
                            <div class="row">
                                <div class="col-12">

                                    <div class="form-group view-post">

                                        <div class="view-title">
                                            <label class="form-control-label"><?php echo $this->lang->line("facebook_post_title") ?>: </label><span> <?php echo $view[0]["title"]; ?></span>
                                        </div>

                                        <div class="view-schedule">
                                            <label class="form-control-label"><?php echo $this->lang->line("facebook_post_date_scheduling") ?>: </label> <span> <?php echo $view[0]["schedule"] ?></span>
                                        </div>

                                        <div class="view-channel">
                                            <label class="form-control-label"><?php echo $this->lang->line("facebook_post_selected_channel_view") ?>: </label>

                                            <div class="list-channel">
                                                <span><?php echo $view[0]["name"] ?>,</span>
                                            </div>

                                        </div>

                                        <div class="view-text">
                                            <label class="form-control-label"><?php echo $this->lang->line("facebook_post_subtitle_view") ?>: </label>
                                            <span><?php echo isset($view[0]['data']) ? $view[0]['data'] : "Sem legenda..." ?></span>
                                        </div>

                                        <?php

                                        if (!empty($view[0]["media_url"])) { ?>

                                            <div class="view-preview">
                                                <label class="form-control-label"><?php echo $this->lang->line("facebook_post_preview_view") ?>: </label>
                                                <div class="container-preview">

                                                    <div class="preview-header">
                                                        <div class="profile">
                                                            <img src="<?php echo base_url("assets/img/panel/facebook.png") ?>" alt="">
                                                        </div>
                                                        <div class="name-page">
                                                            <span>Facebook</span>
                                                        </div>
                                                    </div>

                                                    <div class="preview-body">
                                                        <div class="box-slider">
                                                            <i class="fas fa-chevron-circle-right arrow-right" id="v_arrow-right"></i>
                                                            <i class="fas fa-chevron-circle-left arrow-left" id="v_arrow-left"></i>
                                                            <div class="slider-item">

                                                                <?php if ($view[0]['media_type'] == 5) { ?>
                                                                    <video width="250" height="310" controls>
                                                                        <source src="<?php echo $view[0]['media_url'] ?>" type="video/mp4">
                                                                    </video>
                                                                <?php } else { ?>
                                                                    <img src="<?php echo $view[0]["thumb_image"] ?>" alt="img">
                                                                <?php } ?>

                                                            </div>
                                                        </div>
                                                        <div class="box-list" style="display:none;">
                                                            <ul class="img-list">
                                                                <?php foreach ($view as $key => $val) { ?>
                                                                    <li class="<?php echo $key === 0 ? "selected" : "" ?>"><?php echo $val["thumb_image"] ?></li>
                                                                <?php } ?>
                                                            </ul>
                                                        </div>
                                                    </div>

                                                    <div class="preview-footer">
                                                        <img src="<?php echo base_url("assets/img/panel/footer_preview_facebook.png") ?>" alt="">
                                                    </div>
                                                </div>
                                            </div>

                                        <?php } ?>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <?php for ($i = 0; $i < count($log); $i++) { ?>

                                <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("facebook_post_timeline_view") ?></h6>
                                <div class="timeline timeline-one-side">

                                    <?php if ($log[$i]['log_status'] == 1) { ?>
                                        <!-- Criou campanha -->
                                        <div class="timeline-block mb-3">
                                            <span class="timeline-step">
                                                <i class="far fa-calendar-plus"></i>
                                            </span>
                                            <div class="timeline-content">
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("facebook_post_timeline_creation") ?></h6>
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
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("facebook_post_timeline_paused") ?></h6>
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
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("facebook_post_timeline_resume") ?></h6>
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
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("facebook_post_timeline_canceled") ?></h6>
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
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("facebook_post_timeline_exceed") ?></h6>
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
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("facebook_post_timeline_partial") ?></h6>
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
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("facebook_post_timeline_edited") ?></h6>
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
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("facebook_post_timeline_send_now") ?></h6>
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
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("facebook_post_timeline_resend") ?></h6>
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
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $this->lang->line("facebook_post_timeline_creation_api") ?></h6>
                                                <p class="font-weight-bold text-xs mt-1 mb-0"><?= $log[$i]['log_creation'] ?></p>
                                            </div>
                                        </div>
                                    <?php } ?>


                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <br>
                    <div class="row">
                        <div class="col-lg-8">
                            <a href="<?php echo base_url(); ?>publication/facebook/post"><button class="btn btn-primary" type="button"><?php echo $this->lang->line("facebook_post_btn_return") ?></button></a>
                        </div>
                    </div>

                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>