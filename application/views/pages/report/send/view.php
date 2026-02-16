<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("report_send_view_title") ?></h3>
                        </div>
                    </div>
                </div>

                <?php echo form_open_multipart('publication/whatsapp/broadcast/waba/save', 'id="myform"'); ?>
                <input type="hidden" id="view" value="view">

                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("report_send_information") ?></h6>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group view-broadcast">
                                        <div class="view-title">
                                            <label class="form-control-label"><?php echo $this->lang->line("report_send_title") ?>: </label><span> <?php echo $data['title']  ?></span>
                                        </div>

                                        <div class="view-schedule">
                                            <label class="form-control-label"><?php echo $this->lang->line("report_send_date_scheduling") ?>: </label> <span> <?php echo explode(" ", $data['schedule'])[0]; ?></span>
                                        </div>

                                        <div class="view-schedule">
                                            <label class="form-control-label"><?php echo $this->lang->line("report_send_hour_scheduling") ?>: </label> <span> <?php echo explode(" ", $data['schedule'])[1]; ?></span>
                                        </div>

                                        <?php if ($data['is_wa_broadcast'] == 1 || $data['is_waba_broadcast'] == 1) { ?>
                                            <div class="view-schedule">
                                                <label class="form-control-label"><?php echo $this->lang->line("report_send_select_segmented_view") ?>: </label> <span> <?= $data['groups'] == 0 ? $this->lang->line("report_send_select_group_view_no") : $this->lang->line("report_send_select_group_view_yes") ?></span>
                                            </div>
                                            <?php if ($data['groups'] != 0) { ?>
                                                <div class="view-schedule">
                                                    <label class="form-control-label"><?php echo $this->lang->line("report_send_selected_groups_view") ?>: </label> <span> <?= $data['group_name'] ?></span>
                                                </div>
                                            <?php } ?>
                                        <?php } ?>

                                        <?php if ($data['is_wa_broadcast'] == 1) { ?>
                                            <div class="view-schedule">
                                                <label class="form-control-label"><?php echo $this->lang->line("report_send_automatic_response_view") ?>: </label> <span> <?php echo $data['expire'] != 0 ? $this->lang->line("report_send_automatic_response_yes") : $this->lang->line("report_send_automatic_response_no") ?></span>
                                            </div>

                                            <div id="date-validity" style="display: <?php echo $data['expire'] != "" ? "" : "none" ?>">
                                                <div class="view-schedule">
                                                    <label class="form-control-label"><?php echo $this->lang->line("report_send_time_start_validity") ?>: </label> <span> <?php echo explode(" ", $data['expire'])[0]; ?></span>
                                                </div>

                                                <div class="view-schedule">
                                                    <label class="form-control-label"><?php echo $this->lang->line("report_send_hour_start_validity") ?>: </label> <span> <?php echo explode(" ", $data['expire'])[1]; ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <?php if ($data['is_waba_broadcast'] == 1) { ?>
                                            <div class="view-schedule">
                                                <label class="form-control-label"><?php echo $this->lang->line("report_send_waba_selected_template") ?>: </label> <span> <?= $data['template_name'] ?></span>
                                            </div>
                                        <?php } ?>

                                        <div class="view-schedule">
                                            <label class="form-control-label"><?php echo $this->lang->line("report_send_select_channel_view") ?>: </label> <span> <?= $data['name_channel'] . " (" . $data['channel'] . ")" ?></span>
                                        </div>

                                        <input type="hidden" id="id_template" value="<?php echo $data['id_template'] ?>">
                                        <input type="hidden" id="json_parameters" value='<?php echo json_encode($data['json_parameters']) ?>'>
                                    </div>
                                </div>
                            </div>

                            <?php if ($data['is_wa_status'] == 1) { ?>

                                <div class="row template-whatsapp-status-view">
                                    <div class="wa-template-status">

                                        <div class="col-12">

                                            <div class="row justify-content-center">

                                                <div class="wa-header-status">
                                                    <div class="col-12">
                                                        <img src="https://app.talkall.com.br/assets/img/status_approval/header_status_whatsapp.png" draggable="false" alt="">
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row justify-content-center">

                                                <div class="wa-body-status">
                                                    <div class="col align-self-center d-flex justify-content-center">

                                                        <?php if ($data["media_url"] != null && $data['thumb_image'] == null) { ?>

                                                            <div class="box-broadcast-approval-status row" draggable="false">

                                                                <video id="videoPreview" width="100%" muted autoplay loop>
                                                                    <source src="<?php echo $data["media_url"]; ?>" type="video/mp4">
                                                                </video>

                                                                <?php if ($data['data'] != '') { ?>

                                                                    <div class="textarea-status-imgtext" id="textarea-status">
                                                                        <textarea placeholder="<?php echo $this->lang->line("whatsapp_status_edit_status_placeholder") ?>" class="tex-area-status" disabled id="tex_area0" data-type="type-image"><?php echo isset($data['data']) ? $data['data'] : '' ?></textarea>
                                                                        <a href="#" id="read_more0"><?php echo $this->lang->line("report_send_read_more") ?></a>
                                                                    </div>

                                                                <?php } ?>

                                                            </div>

                                                        <?php } else { ?>

                                                            <div class="box-broadcast-approval-status row" draggable="false">

                                                                <?php if ($data['media_url'] != null) { ?>
                                                                    <input type="hidden" id="thumb_image">

                                                                    <div class="box-inner-status" style="justify-content: inherit;">
                                                                        <div class="img-broadcast-status" style="cursor:default">
                                                                            <img class="i-wa-status" id="thumb_image" src="<?php echo 'data:image/jpeg;base64,' . $data['thumb_image'] ?>">
                                                                        </div>
                                                                    </div>

                                                                    <div class="textarea-status-imgtext" id="textarea-status">
                                                                        <textarea placeholder="<?php echo $this->lang->line("whatsapp_status_edit_status_placeholder") ?>" class="tex-area-status" disabled id="" data-type="type-image"><?php echo $data['data'] ?></textarea>
                                                                    </div>

                                                                <?php  } else { ?>

                                                                    <div class="textarea-status-onlytext" id="textarea-status">
                                                                        <textarea placeholder="<?php echo $this->lang->line("whatsapp_status_edit_status_placeholder") ?>" class="tex-area-onlytext" disabled id="tex-area-onlytext" data-type="type-image"><?php echo $data['data'] ?></textarea>
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

                            <?php } else { ?>
                                <div class="row template-whatsapp-broadcast-view">
                                    <div class="wa-template">
                                        <div class="col-12">

                                            <div class="row justify-content-center">
                                                <div class="wa-header">
                                                    <div class="col-12 col-img">
                                                        <img src="https://app.talkall.com.br/assets/img/broadcast_approval/header_wa.jpeg" draggable="false" alt="image">
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row justify-content-end">
                                                <div class="wa-body">
                                                    <div class="col align-self-end d-flex justify-content-end">

                                                        <?php if ($data["media_url"] != null && $data['thumb_image'] == null) { ?>

                                                            <?php if ($data['media_type'] == 5) { ?>

                                                                <div class="box-broadcast-approval" draggable="false">
                                                                    <video id="videoPreview" width="274" height="180" controls>
                                                                        <source src="<?php echo $data["media_url"]; ?>" type="video/mp4">
                                                                    </video>

                                                                    <?php if ($data['data'] != null) { ?>
                                                                        <div class="textarea-status">
                                                                            <textarea placeholder="<?php echo $this->lang->line("whatsapp_broadcast_edit_broadcast_placeholder") ?>" class="tex-area" disabled id="tex_area" data-type="type-image"><?php echo $data['data'] ?></textarea>
                                                                        </div>
                                                                    <?php } ?>
                                                                </div>

                                                            <?php } else if ($data['media_type'] == 2) { ?>

                                                                <div class="row view-preview">
                                                                    <div class="main-content b-audio">
                                                                        <div id="tail_62700" class="tail" style="bottom: -0.1%; right: 0%; top: 319px; display: none;">
                                                                            <span class="tail-byte"></span>
                                                                        </div>
                                                                        <span class="title">
                                                                            <?php $this->lang->line("whatsapp_broadcast_edit_broadcast_audio") ?>
                                                                        </span>
                                                                        <audio controls="" class="audio" id="" src="<?php echo $data["media_url"]; ?>">
                                                                            <source id="" src="<?php echo $data["media_url"]; ?>">
                                                                        </audio>
                                                                    </div>
                                                                </div>

                                                            <?php } else if ($data['media_type'] == 4) { ?>

                                                                <div class="box-broadcast-approval" draggable="false">
                                                                    <div class="container-pdf-icon">
                                                                        <div class="box-inner-pdf" style="justify-content: inherit;" data-media-url="<?php echo $data["media_url"] ?>" data-media-type="4">
                                                                            <img class="icon-loading" id="thumb_image" src="<?php echo base_url("assets/img/loads/loading_2.gif") ?>">
                                                                        </div>
                                                                    </div>

                                                                    <?php if ($data['data'] != null) { ?>
                                                                        <div class="textarea-status">
                                                                            <textarea placeholder="<?php echo $this->lang->line("whatsapp_broadcast_edit_broadcast_placeholder") ?>" class="tex-area" disabled id="tex_area" data-type="type-image"><?php echo $data['data'] ?></textarea>
                                                                        </div>
                                                                    <?php } ?>
                                                                </div>

                                                            <?php }
                                                        } else if ($data['media_type'] == 27) { ?>

                                                            <div class="box-broadcast-approval" draggable="false">
                                                                <div class="textarea-status">

                                                                    <img id="imgHeaderText" src="" style="max-width: 250px; max-height: 160px; margin-left: 15px; margin-bottom: 15px;" />

                                                                    <textarea placeholder="<?php echo $this->lang->line("whatsapp_broadcast_edit_broadcast_placeholder") ?>" class="tex-area-template" disabled id="tex_area_view_templateHeader" data-type="type-image">
                                                                        <?php echo $data['header'] ?>
                                                                    </textarea>

                                                                    <textarea placeholder="<?php echo $this->lang->line("whatsapp_broadcast_edit_broadcast_placeholder") ?>" class="tex-area-template" disabled id="tex_area_view_template" data-type="type-image">
                                                                        <?php echo $data['text_body'] ?>
                                                                    </textarea>

                                                                    <textarea placeholder="<?php echo $this->lang->line("whatsapp_broadcast_edit_broadcast_placeholder") ?>" class="tex-area-template" disabled id="tex_area_view_templateFooter" data-type="type-image">
                                                                        <?php echo $data['text_footer'] ?>
                                                                    </textarea>

                                                                </div>
                                                            </div>

                                                        <?php } else { ?>

                                                            <div class="box-broadcast-approval" draggable="false">
                                                                <?php if ($data['media_url'] != null) { ?>
                                                                    <input type="hidden" id="thumb_image">
                                                                    <div class="box-inner" style="justify-content: inherit;">
                                                                        <div class="img-broadcast" style="cursor:default">
                                                                            <img class="i_st" id="thumb_image" src="<?php echo 'data:image/jpeg;base64,' . $data['thumb_image'] ?>">
                                                                        </div>
                                                                    </div>
                                                                <?php  }

                                                                if ($data['data'] != null) { ?>
                                                                    <div class="textarea-status">
                                                                        <textarea placeholder="<?php echo $this->lang->line("whatsapp_broadcast_edit_broadcast_placeholder") ?>" class="tex-area" disabled id="tex_area" data-type="type-image"><?php echo $data['data'] ?></textarea>
                                                                    </div>
                                                                <?php } ?>
                                                            </div>

                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>



                                            <div class="row justify-content-center">
                                                <div class="wa-footer">
                                                    <div class="col col-img">
                                                        <img src="https://app.talkall.com.br/assets/img/broadcast_approval/footer_wa.png" draggable="false" alt="image">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            <?php } ?>
                        </div>

                        <?php if ($data['is_wa_broadcast'] == 1) { ?>
                            <div class="col-6">
                                <h6 class="heading-small text-muted mb-4"><?php echo $this->lang->line("report_send_timeline_view") ?></h6>
                                <div class="timeline timeline-one-side">

                                    <?php
                                    for ($i = 0; $i < count($log); $i++) {

                                        if ($log[$i]['type'] == 1) { ?>
                                            <!-- CRIAÇÃO   -->

                                            <div class="timeline-block mb-3">
                                                <span class="timeline-step">
                                                    <i class="far fa-calendar-plus"></i>
                                                </span>
                                                <div class="timeline-content">
                                                    <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("report_send_creation_timeline_view") ?></h6>
                                                    <p class="font-weight-bold text-xs mt-1 mb-0"><?= $log[$i]['log_creation'] ?></p>
                                                </div>
                                            </div>
                                        <?php

                                        } else if ($log[$i]['type'] == 2) { ?>
                                            <!-- CAMPANHA PAUSADA -->

                                            <div class="timeline-block mb-3">
                                                <span class="timeline-step">
                                                    <i class="far fa-pause-circle"></i>
                                                </span>
                                                <div class="timeline-content">
                                                    <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("report_send_paused_timeline_view") ?></h6>
                                                    <p class="font-weight-bold text-xs mt-1 mb-0"><?= $log[$i]['log_creation'] ?></p>
                                                </div>
                                            </div>
                                        <?php

                                        } else if ($log[$i]['type'] == 3) {  ?>
                                            <!-- CAMPANHA RETOMADA -->

                                            <div class="timeline-block mb-3">
                                                <span class="timeline-step">
                                                    <i class="far fa-play-circle"></i>
                                                </span>
                                                <div class="timeline-content">
                                                    <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("report_send_resumption_timeline_view") ?></h6>
                                                    <p class="font-weight-bold text-xs mt-1 mb-0"><?= $log[$i]['log_creation'] ?></p>
                                                </div>
                                            </div>
                                        <?php

                                        } else if ($log[$i]['type'] == 4) {   ?>
                                            <!-- CAMAPANHA CANCELADA -->

                                            <div class="timeline-block mb-3">
                                                <span class="timeline-step">
                                                    <i class="fas fa-times text-danger"></i>
                                                </span>
                                                <div class="timeline-content">
                                                    <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("report_send_canceled_timeline_view") ?></h6>
                                                    <p class="font-weight-bold text-xs mt-1 mb-0"><?= $log[$i]['log_creation'] ?></p>
                                                </div>
                                            </div>
                                        <?php

                                        } else if ($log[$i]['type'] == 5) {  ?>
                                            <!-- /ULTRAPASSAR O PERÍODO DE ENVIO -->

                                            <div class="timeline-block mb-3">
                                                <span class="timeline-step">
                                                    <i class="fas fa-hourglass" id="tooltip-content-exceed-period" data-toggle="tooltip" data-placement="left" title="A campanha será disparada até que o WhatsApp envie para todos os contato ativos, mesmo que ultrapasse o período de envio configurado, ou se estenda aos dias seguintes"></i>
                                                </span>
                                                <div class="timeline-content" id="timeline-content-exceed-period">
                                                    <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("report_send_exceed_period_timeline_view") ?></h6>
                                                    <p class="font-weight-bold text-xs mt-1 mb-0"><?= $log[$i]['log_creation'] ?></p>
                                                </div>
                                            </div>
                                        <?php

                                        } else if ($log[$i]['type'] == 6) { ?>
                                            <!-- CAMPANHA PARCIAL -->

                                            <div class="timeline-block mb-3">
                                                <span class="timeline-step">
                                                    <i class="fas fa-hourglass-end" id="tooltip-content-partial" data-toggle="tooltip" data-placement="left" title="A campanha respeitará o período de envio e será interrompida no horário final, mesmo que não tenha atingido todos os contatos"></i>
                                                </span>
                                                <div class="timeline-content" id="timeline-content-partial">
                                                    <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $log[$i]['name'] ?> <?php echo $this->lang->line("report_send_partial_timeline_view") ?></h6>
                                                    <p class="font-weight-bold text-xs mt-1 mb-0"><?= $log[$i]['log_creation'] ?></p>
                                                </div>
                                            </div>
                                    <?php  }
                                    } 
                                    
                                    if ($data['key_remote_id'] == NULL) {
                                        ?>
                                        <div class="timeline-block mb-3">
                                            <span class="timeline-step">
                                                <i class="far fa-calendar-plus"></i>
                                            </span>
                                            <div class="timeline-content">
                                                <h6 class="text-dark text-sm font-weight-bold mb-0">
                                                    <?php echo $this->lang->line("whatsapp_broadcast_creation_timeline_view_via_api") ?>
                                                </h6>
                                                <p class="font-weight-bold text-xs mt-1 mb-0"><?= $data['creation'] ?></p>
                                            </div>
                                        </div>

                                        <?php
                                    }

                                    ?>

                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <br>
                    <div class="row mt-2">
                        <div class="col-lg-8">
                            <a href="<?php echo base_url(); ?>report/send"><button class="btn btn-primary" type="button"><?php echo $this->lang->line("report_send_btn_return") ?></button></a>
                        </div>
                    </div>
                </div>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url('/assets/lib/build_pdf/build_pdf.js') ?>"></script>