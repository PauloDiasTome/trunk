<input type="hidden" id="view" value="<?php echo $data["view"] ?>">
<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("whatsapp_broadcast_community_view") ?></h3>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("whatsapp_broadcast_community_edit_information") ?></h6>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group view-broadcast">
                                        <div class="view-title">
                                            <label class="form-control-label"><?php echo $this->lang->line("whatsapp_broadcast_community_title") ?>: </label><span> <?php echo $data['title']  ?></span>
                                        </div>

                                        <div class="view-schedule">
                                            <label class="form-control-label"><?php echo $this->lang->line("whatsapp_broadcast_community_date_scheduling") ?>: </label> <span> <?php echo explode(" ", $data['schedule'])[0]; ?></span>
                                        </div>

                                        <div class="view-schedule">
                                            <label class="form-control-label"><?php echo $this->lang->line("whatsapp_broadcast_community_hour_scheduling") ?>: </label> <span> <?php echo explode(" ", $data['schedule'])[1]; ?></span>
                                        </div>

                                        <div class="view-schedule">
                                            <label class="form-control-label"><?php echo $this->lang->line("whatsapp_broadcast_community_selected_channel_view") ?>: </label>
                                            <span> <?= $data['name_channel'] ?></span>
                                        </div>

                                        <?php if ($data['groups'] != 0) { ?>
                                            <div class="view-schedule">
                                                <label class="form-control-label"><?php echo $this->lang->line("whatsapp_broadcast_community_selected_community_view") ?>: </label> <span><?php echo $data['community_name']; ?></span>
                                            </div>
                                        <?php } ?>
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

                                        <div class="row justify-content-end">
                                            <div class="wa-body" id="wa-body">
                                                <div>

                                                    <?php if ($data['media_type'] == 1) { ?>
                                                        <div class="box-broadcast-approval" draggable="false">
                                                            <?php if ($data['data'] != null) { ?>
                                                                <div class="textarea-status">
                                                                    <textarea placeholder="<?php echo $this->lang->line("whatsapp_broadcast_community_description_placeholder") ?>" class="tex-area" disabled id="tex_area" data-type="type-image"><?php echo $data['data'] ?></textarea>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    <?php } ?>

                                                    <?php if ($data['media_type'] == 3) { ?>
                                                        <div class="box-broadcast-approval" draggable="false">
                                                            <?php if ($data['media_url'] != null) { ?>
                                                                <input type="hidden" id="thumb_image">
                                                                <div class="box-inner" style="justify-content: inherit;">

                                                                    <div class="img-broadcast" style="cursor:default">
                                                                        <img class="i_st" id="thumb_image" src="<?php echo $data['media_url'] ?>">
                                                                    </div>
                                                                </div>

                                                            <?php  }

                                                            if ($data['data'] != null) { ?>
                                                                <div class="textarea-status">
                                                                    <textarea placeholder="<?php echo $this->lang->line("whatsapp_broadcast_community_description_placeholder") ?>" class="tex-area" disabled id="tex_area" data-type="type-image"><?php echo $data['data'] ?></textarea>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    <?php } ?>

                                                    <?php if ($data['media_type'] == 4) { ?>
                                                        <div class="box-broadcast-approval" draggable="false">
                                                            <div class="box-inner-pdf" style="justify-content: inherit;" data-media-url="<?php echo $data["media_url"] ?>" data-media-type="4">
                                                                <img class="icon-loading" id="thumb_image" src="<?php echo base_url("assets/img/loads/loading_2.gif") ?>">
                                                            </div>

                                                            <?php if ($data['data'] != null) { ?>
                                                                <div class="textarea-status">
                                                                    <textarea placeholder="<?php echo $this->lang->line("whatsapp_broadcast_edit_broadcast_placeholder") ?>" class="tex-area" disabled id="tex_area" data-type="type-image"><?php echo $data['data'] ?></textarea>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    <?php } ?>

                                                    <?php if ($data['media_type'] == 5) { ?>
                                                        <div class="box-broadcast-approval" draggable="false">
                                                            <video id="videoPreview" width="274" height="180" controls>
                                                                <source src="<?php echo $data["media_url"]; ?>" type="video/mp4">
                                                            </video>

                                                            <?php if ($data['data'] != null) { ?>
                                                                <div class="textarea-status">
                                                                    <textarea placeholder="<?php echo $this->lang->line("whatsapp_broadcast_community_description_placeholder") ?>" class="tex-area" disabled id="tex_area" data-type="type-image"><?php echo $data['data'] ?></textarea>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    <?php } ?>

                                                    <?php if ($data['media_type'] == 33) { ?>
                                                        <div class="box-broadcast-approval message-poll" draggable="false">
                                                            <?php $array = json_decode($data["data"], true); ?>

                                                            <div class="question">
                                                                <span><?php echo $array["question"] ?></span>
                                                            </div>

                                                            <div class="poll-multiple-answers">
                                                                <img class="poll-multiple-answers-svg" src=<?php echo $array["multiple_answers"] ? "/assets/icons/kanban/concluded_dark_gray2.svg" : "/assets/icons/kanban/concluded_dark_gray1.svg" ?>>
                                                                <span><?php echo $array["multiple_answers"] ? $this->lang->line("whatsapp_broadcast_community_message_pollone_option_or_more") : $this->lang->line("whatsapp_broadcast_community_message_poll_one_option") ?></span>
                                                            </div>

                                                            <?php foreach ($array["option"] as $val) { ?>
                                                                <div class="item">
                                                                    <div class="_top">
                                                                        <div class="box-checkbox-res"><input class="checkbox-res" type="checkbox"></div>
                                                                        <div class="text"><span><?php echo $val; ?></span></div>
                                                                        <span class="count-wishes">0</span>
                                                                    </div>
                                                                    <div class="_bottom">
                                                                        <div class="wishes"></div>
                                                                    </div>
                                                                </div>
                                                            <?php  } ?>

                                                            <div class="show-wishes">
                                                                <span><?php echo $this->lang->line("whatsapp_broadcast_community_show_votes") ?></span>
                                                            </div>
                                                        </div>
                                                    <?php } ?>

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
                            <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("whatsapp_broadcast_community_timeline_view") ?></h6>

                            <div class="timeline timeline-one-side">

                                <?php for ($i = 0; $i < count($log); $i++) { ?>

                                    <?php if ($log[$i]['log_status'] == 1) { ?>
                                        <!-- Criou campanha -->
                                        <div class="timeline-block mb-3">
                                            <span class="timeline-step">
                                                <i class="far fa-calendar-plus"></i>
                                            </span>
                                            <div class="timeline-content">
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("whatsapp_broadcast_community_timeline_creation") ?></h6>
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
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("whatsapp_broadcast_community_timeline_canceled") ?></h6>
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
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("whatsapp_broadcast_community_timeline_edited") ?></h6>
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
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("whatsapp_broadcast_community_timeline_send_now") ?></h6>
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
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("whatsapp_broadcast_community_timeline_resend") ?></h6>
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
                                                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $this->lang->line("whatsapp_broadcast_community_timeline_creation_api") ?></h6>
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
                            <a href="<?php echo base_url(); ?>publication/whatsapp/broadcast/community"><button class="btn btn-primary" type="button"><?php echo $this->lang->line("whatsapp_broadcast_community_btn_return") ?></button></a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="<?php echo base_url('assets/lib/build_pdf/build_pdf.js') ?>"></script>