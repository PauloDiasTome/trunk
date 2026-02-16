<input type="hidden" id="verifyView" value="true">
<div class="container mt--5">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">
                                <?php echo $this->lang->line("template_view_title") ?>
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="card-body">

                    <div class="row">
                        <div class="col-12">

                            <h6 class="heading-small text-muted mb-3"><?php echo $this->lang->line("template_view_information") ?></h6>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group view-template">
                                        <div class="view-title">
                                            <label class="form-control-label"><?php echo $this->lang->line("template_view_name") ?>: </label><span> <?php echo $name ?></span>
                                        </div>
                                        <div class="view-title">
                                            <label class="form-control-label"><?php echo $this->lang->line("template_view_channel") ?>: </label><span> <?php echo $channel ?></span>
                                        </div>
                                        <div class="view-title">
                                            <label class="form-control-label"><?php echo $this->lang->line("template_view_category") ?>: </label><span> <?php echo $category ?></span>
                                        </div>
                                        <div class="view-title">
                                            <label class="form-control-label"><?php echo $this->lang->line("template_view_language") ?>: </label><span> <?php echo $language ?></span>
                                        </div>
                                        <div class="view-title">
                                            <label class="form-control-label"><?php echo $this->lang->line("template_view_status") ?>: </label><span> <?php echo $status ?></span>
                                        </div>

                                        <?php if (isset($rejected_reason)) { ?>
                                            <div class="view-title">
                                                <label class="form-control-label"><?php echo $this->lang->line("template_view_rejected_reason") ?>: </label><span> <?php echo $rejected_reason ?></span>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="wa-template">

                                        <div class="col-12">

                                            <div class="row justify-content-center">

                                                <div class="wa-header">
                                                    <div class="col-12">
                                                        <img src="https://app.talkall.com.br/assets/img/broadcast_approval/header_wa.jpeg" draggable="false" alt="">
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row">

                                                <div class="wa-body">
                                                    <div class="col align-self-end justify-content-end">
                                                        <div class="box-broadcast-approval" draggable="false">

                                                            <div class="header-message">

                                                                <?php
                                                                switch ($header_type) {

                                                                    case 0:
                                                                        break;
                                                                    case 1:
                                                                ?>
                                                                        <div class="header-message-text">
                                                                            <span><?= $header ?></span>
                                                                        </div>
                                                                    <?php
                                                                        break;
                                                                    case 3:
                                                                    ?>
                                                                        <div class="header-message-media">
                                                                            <i class="far fa-image fa-4x"></i>
                                                                        </div>
                                                                    <?php
                                                                        break;
                                                                    case 5:
                                                                    ?>
                                                                        <div class="header-message-media">
                                                                            <i class="far fa-play-circle fa-4x"></i>
                                                                        </div>
                                                                    <?php
                                                                        break;
                                                                    case 10:
                                                                    ?>
                                                                        <div class="header-message-media">
                                                                            <i class="far fa-file-alt fa-4x"></i>
                                                                        </div>
                                                                <?php
                                                                        break;
                                                                }
                                                                ?>

                                                            </div>

                                                            <div class="textarea-status">
                                                                <p class="tex-area" disabled id="tex_area" data-type="type-image"><?php echo $text_body ?></p>
                                                                <?php if ($text_footer != '') { ?>
                                                                    <span class="tex_footer" disabled id="tex_footer" data-type="type-image"><?php echo $text_footer ?></span>
                                                                <?php } ?>
                                                            </div>

                                                            <?php if ($buttons != null && count($buttons) > 0 && $buttons[0]->type != 'QUICK_REPLY') { ?>
                                                                <hr>
                                                                <div class="buttons-message">

                                                                    <?php

                                                                    for ($i = 0; $i < count($buttons); $i++) {

                                                                        switch ($buttons[$i]->type) {

                                                                            case 'PHONE_NUMBER':
                                                                    ?>
                                                                                <span class="button_link"><i class="fas fa-phone" style="transform: scaleX(-1);"></i> <?= $buttons[$i]->text ?></span>

                                                                            <?php
                                                                                break;

                                                                            case 'URL':
                                                                            ?>
                                                                                <span class="button_link"><i class="fas fa-external-link-alt"></i> <?= $buttons[$i]->text ?></span>
                                                                    <?php
                                                                                break;
                                                                        }
                                                                    } ?>

                                                                </div>

                                                            <?php } ?>
                                                        </div>

                                                        <?php if ($buttons != null && count($buttons) > 0 && $buttons[0]->type == 'QUICK_REPLY') {  ?>
                                                            <div class="buttons-quick-reply">

                                                                <?php for ($i = 0; $i < count($buttons); $i++) { ?>

                                                                    <button class="btn-quick-reply"><?= $buttons[$i]->text ?></button>

                                                                <?php } ?>
                                                            </div>
                                                        <?php } ?>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row justify-content-center">

                                                <div class="wa-footer">
                                                    <div class="col">
                                                        <img src="https://app.talkall.com.br/assets/img/broadcast_approval/footer_wa.png" draggable="false" alt="">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row mt-4">
                        <div class="col-lg-8">
                            <a href="<?= base_url() ?>templates" class="btn btn-primary"><?php echo $this->lang->line("template_btn_return") ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>