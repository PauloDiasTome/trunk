<div class="header bg-primary pb-6 color-header">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0"><?php echo $this->lang->line("setting_integration_topnav") ?></h6>
                </div>
                <div class="col-lg-6 text-right">
                    <a href="integration/add"><button type="button" class="btn btn-sm btn-neutral mr-2"><?php echo $this->lang->line("setting_integration_new_topnav") ?></button></a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--6">
    <div class="row integration">

        <input id="integrationValidation" value="<?php echo $integration ?>" hidden>

        <?php foreach ($data as $row) { ?>

            <?php if ($row['type'] != 8 && $row['type'] != 9) { ?>

                <?php if ($row['id_channel'] != null) { ?>

                    <div class="col-xl-6 col-md-6">
                        <div class="card">

                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <a href="#" class="avatar avatar-xl rounded-circle" style="background-color: white;">
                                            <?php switch ($row['type']) {
                                                case 2:
                                                    echo "<img src='" . base_url('assets/img/whasapp_inegration.png') . "'>";
                                                    break;
                                                case 3:
                                                    echo "<img src='" . base_url('assets/img/widget.svg') . "'>";
                                                    break;
                                                case 6:
                                                    echo "<img src='" . base_url('assets/img/sms.svg') . "'>";
                                                    break;
                                                case 7:
                                                    echo "<img src='" . base_url('assets/img/mercadolivre.svg') . "'>";
                                                    break;
                                                case 8:
                                                    echo "<img src='" . base_url('assets/img/facebook.svg') . "'>";
                                                    break;
                                                case 9:
                                                    echo "<img src='" . base_url('assets/img/instagram_integration.png') . "'>";
                                                    break;
                                                case 10:
                                                case 31:
                                                    echo "<img src='" . base_url('assets/img/telegram.svg') . "'>";
                                                    break;
                                                case 11:
                                                    echo "<img src='" . base_url('assets/img/facebook.svg') . "'>";
                                                    break;
                                                case 12:
                                                    echo "<img src='" . base_url('assets/img/whatsapp_integration_business.png') . "'>";
                                                    break;
                                                case 13:
                                                    echo "<img src='" . base_url('assets/img/rdstation.jpg') . "'>";
                                                    break;
                                                case 14:
                                                    echo "<img src='" . base_url('assets/img/hubspot.jpg') . "'>";
                                                    break;
                                                case 15:
                                                    echo "<img src='" . base_url('assets/img/zendesk_integration.png') . "'>";
                                                    break;
                                                case 16:
                                                    echo "<img src='" . base_url('assets/img/whasapp_inegration.png') . "'>";
                                                    break;
                                                case 28:
                                                    echo "<img src='" . base_url('assets/img/tv.png') . "'>";
                                                    break;
                                                case 30:
                                                    echo "<img src='" . base_url('assets/icons/panel/openai.svg') . "'>";
                                                    break;
                                            } ?>
                                        </a>
                                    </div>

                                    <div class="col ml--2">
                                        <h4 class="mb-0">
                                            <a class="channel-name"><?php echo $row['name']; ?></a>
                                        </h4>
                                        <?php if ($row['type'] != 6) { ?>
                                            <p class="text-sm text-muted mb-0 phone-id truncate">
                                                <a href="<?= $row['clean_phone'] != '' ? 'https://wa.me/' . $row['clean_phone'] : '#' ?>" target="_blank">
                                                    <?= $row['formatted_phone'] != '' ? $row['formatted_phone'] : $row['id'] ?>
                                                </a>
                                            </p>
                                        <?php } ?>

                                        <?php
                                        $status = $row['status'];

                                        switch ($status) {
                                            case 1:
                                                $statusClass = 'badge bg-light-lime text-dark';
                                                $icon = '<img class="status-icon" src="/assets/icons/status/play.png" width="12" height="12" alt="Ativo">';
                                                $statusText = $this->lang->line('setting_integration_active');

                                                break;
                                            case 3:
                                                $statusClass = 'badge bg-light-warning';
                                                $icon = '<img class="status-icon" src="/assets/icons/status/Icon-arrow-path.png" width="12" height="12" alt="Ativo">';
                                                $statusText = $this->lang->line('setting_integration_analysis');
                                                break;
                                            case 4:
                                                $statusClass = 'badge bg-light-red text-dark';
                                                $icon = '<img class="status-icon" src="/assets/icons/status/Vector@2x.png" width="12" height="12" alt="Bloqueado">';
                                                $statusText = $this->lang->line('setting_integration_blocked');
                                                break;
                                            case 5:
                                                $statusClass = 'badge bg-light-white text-dark';
                                                $icon = '<i class="far fa-building"></i>';
                                                $statusText = $this->lang->line('setting_integration_activation');
                                                break;
                                            default:
                                                $statusClass = 'badge bg-light-secondary text-muted';
                                                $icon = '<svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="6"/></svg>';
                                                $statusText = "-";
                                                break;
                                        }

                                        ?>

                                        <div class="<?= $statusClass ?>">
                                            <?= $icon ?>
                                            <span><?= $statusText ?></span>
                                        </div>

                                        <?php if (in_array($row['type'], [2, 12, 16])) : ?>
                                            <small class="contacts-name">
                                                <?= $row['contacts'] ?> <?= $this->lang->line('setting_integration_contact'); ?>
                                            </small>
                                        <?php endif; ?>
                                    </div>

                                    <div class="col-auto">

                                        <?php if ($row['type'] != 6) { ?>

                                            <?php if ($row['type'] == 12) { ?>
                                                <a class="setting_integration_availabele">
                                                    <!-- <span style="font-size: 15px;">R$</span> -->
                                                    <!-- <span><?= number_format($row['credit_conversation'], 2, ',', '.'); ?></span> -->
                                                </a>
                                            <?php } ?>


                                            <!-- menu settings -->
                                            <?php $uuid = uniqid(); ?>
                                            <div class="img-settings" data-id="<?php echo $row['id'] . '__' . $uuid; ?>">
                                                <i class="fa fa-ellipsis-v"></i>
                                            </div>
                                            <div class="pop-settings" id="<?php echo $row['id'] . '__' . $uuid; ?>" style="display: none;">

                                            <?php if ($row['type'] == 31) { ?>
                                                <a href='<?php echo base_url("config/edit/" . (string)$row['id_channel']); ?>'>
                                                    <div class="edit item">
                                                        <i class="far fa-edit"></i>
                                                        <span><?php echo $this->lang->line("setting_integration_btn_edit") ?></span>
                                                    </div>
                                                </a>
                                                <a href="#" onclick='return DeleteIntegration("<?= base_url("integration/delete/{$row["id_channel"]}") ?> ")'>
                                                        <div class="disconnect item">
                                                            <i class="far fa-trash-alt"></i>
                                                            <span><?php echo $this->lang->line("setting_integration_btn_disconnect") ?></span>
                                                        </div>
                                                    </a>
                                            <?php } ?>

                                                <!-- To verify waba is connected   -->
                                                <?php if ($row['type'] == 12 || $row['type'] == 16 || $row['type'] == 10 || $row['type'] == 2) { ?>

                                                    <!-- //test_integration_ok  -->
                                                    <?php if (isset($_GET['tik']) and $_GET['tik'] == 'ok') { ?>

                                                        <a href="integration/add/<?= $row['type'] ?>">
                                                            <button type="button" class="btn btn-sm btn-success"><?php echo $this->lang->line("setting_integration_connect") ?></button>
                                                        </a>

                                                    <?php } else ?>

                                                    <a href='<?php echo base_url("config/edit/" . (string)$row['id_channel']); ?>'>
                                                        <div class="edit item">
                                                            <i class="far fa-edit"></i>
                                                            <span><?php echo $this->lang->line("setting_integration_btn_edit") ?></span>
                                                        </div>

                                                    </a>

                                                    <?php if ($row['type'] == 2 && $id_suport_user) { ?>

                                                        <div class="clear" id="<?php echo $row['id_channel'] ?>">
                                                            <div class="item">
                                                                <i class="fas fa-times" style="font-size: 1rem;"></i>
                                                                <span><?php echo $this->lang->line("setting_integration_btn_clear") ?></span>
                                                            </div>
                                                        </div>

                                                    <?php } ?>

                                                    <a href="#" onclick='return DeleteIntegration("<?= base_url("integration/delete/{$row["id_channel"]}") ?> ")'>
                                                        <div class="disconnect item">
                                                            <i class="far fa-trash-alt"></i>
                                                            <span><?php echo $this->lang->line("setting_integration_btn_disconnect") ?></span>
                                                        </div>
                                                    </a>

                                                <?php } else {  ?>

                                                    <?php if ($row['type'] == 3) { ?>

                                                        <!-- Widget -->
                                                        <a href='<?php echo base_url("copy/integration/script/" . (string)$row['id_channel']); ?>'>
                                                            <div class="script item">
                                                                <i class="fas fa-code"></i>
                                                                <span><?php echo $this->lang->line("setting_integration_btn_script") ?></span>
                                                            </div>
                                                        </a>
                                                        <a href='<?php echo base_url("integration/edit/" . (string)$row['id_channel']); ?>'>
                                                            <div class="edit item">
                                                                <i class="far fa-edit"></i>
                                                                <span><?php echo $this->lang->line("setting_integration_btn_edit") ?></span>
                                                            </div>
                                                        </a>
                                                        <a href="#" onclick='return DeleteIntegration("<?= base_url("integration/delete/{$row["id_channel"]}") ?> ")'>
                                                            <div class="disconnect item">
                                                                <i class="far fa-trash-alt"></i>
                                                                <span><?php echo $this->lang->line("setting_integration_btn_disconnect") ?></span>
                                                            </div>
                                                        </a>

                                                    <?php } ?>

                                                    <?php if ($row['type'] == 28) { ?>

                                                        <!-- tv -->

                                                        <a href='<?php echo base_url("integration/edit/" . (string)$row['id_channel']); ?>'>
                                                            <div class="edit item">
                                                                <i class="far fa-edit"></i>
                                                                <span><?php echo $this->lang->line("setting_integration_btn_edit") ?></span>
                                                            </div>
                                                        </a>
                                                        <a href="#" onclick='return DeleteIntegration("<?= base_url("integration/delete/tv/{$row["id_channel"]}") ?> ")'>
                                                            <div class="disconnect item">
                                                                <i class="far fa-trash-alt"></i>
                                                                <span><?php echo $this->lang->line("setting_integration_btn_disconnect") ?></span>
                                                            </div>
                                                        </a>

                                                    <?php } ?>


                                                    <?php if ($row['type'] == 30) { ?>

                                                        <!-- OpenAI -->

                                                        <a href="#" onclick='return DeleteIntegration("<?= base_url("integration/delete/openai/{$row["id_channel"]}") ?> ")'>
                                                            <div class="disconnect item">
                                                                <i class="far fa-trash-alt"></i>
                                                                <span><?php echo $this->lang->line("setting_integration_btn_disconnect") ?></span>
                                                            </div>
                                                        </a>

                                                    <?php } ?>

                                                <?php } ?>

                                            </div>


                                        <?php } else { ?>
                                            <h4><?php echo $this->lang->line("setting_integration_balance") ?>: <b><?= $row['balance_sms'] ?></b></h4>
                                        <?php }; ?>

                                    </div>


                                    <?php if ($row['type'] == 200) { ?>
                                        <div class="col-auto">
                                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#qrcode"><?php echo $this->lang->line("setting_integration_qrcode") ?></button>
                                        </div>
                                    <?php  } ?>

                                </div>
                            </div>
                        </div>
                    </div>
        <?php }
            }
        } ?>


        <?php foreach ($pages as $page) { ?>

            <?php if (isset($page["type"]) == 8) { ?>

                <div class="col-xl-6 col-md-6 facebook_api">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">

                                    <a href="#" class="avatar avatar-xl rounded-circle" style="background-color: white;">
                                        <img src="<?php echo $page["picture"] ?>">
                                    </a>

                                    <div class="picture-face">
                                        <img src="<?php echo base_url("assets/img/panel/" . "facebook2.png") ?>" alt="" style="width: 100%; border-radius: 20px;">
                                    </div>

                                    <?php if (isset($page["is_instagram"]) == 1) { ?>

                                        <div class="picture-insta">
                                            <img src="https://app.talkall.com.br/assets/img/instagram_integration.png" alt="" style="width: 100%; border-radius: 20px;">
                                        </div>

                                    <?php } ?>

                                </div>
                                <div class="col ml--2">

                                    <h4 class="mb-0">
                                        <a class="channel-name"><?php echo $page["name"] ?></a>
                                    </h4>

                                    <?php
                                    if (!function_exists('getStatusBadge')) {
                                        function getStatusBadge($status)
                                        {
                                            switch ((int) $status) {
                                                case 1:
                                                    $statusClass = 'badge bg-light-lime text-dark';
                                                    $icon = '<img class="status-icon" src="/assets/icons/status/play.png" width="12" height="12" alt="Ativo">';
                                                    $statusText = lang('setting_integration_active');
                                                    break;

                                                case 3:
                                                    $statusClass = 'badge bg-light-warning';
                                                    $icon = '<img class="status-icon" src="/assets/icons/status/Icon-arrow-path.png" width="12" height="12" alt="Em Análise">';
                                                    $statusText = lang('setting_integration_analysis');
                                                    break;

                                                case 4:
                                                    $statusClass = 'badge bg-light-red text-dark';
                                                    $icon = '<img class="status-icon" src="/assets/icons/status/Vector@2x.png" width="12" height="12" alt="Bloqueado">';
                                                    $statusText = lang('setting_integration_blocked');
                                                    break;

                                                default:
                                                    $statusClass = 'badge bg-light-secondary text-muted';
                                                    $icon = '<svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="6"/></svg>';
                                                    $statusText = '-';
                                                    break;
                                            }

                                            return '<span class="' . $statusClass . '" style="margin-top: 1rem; display: inline-flex; align-items: center; gap: 4px;">' . $icon . ' ' . $statusText . '</span>';
                                        }
                                    }
                                    ?>

                                    <div style="display: flex; gap: 0.3rem; align-items: center; margin-top: 8px;">

                                        <!-- Facebook status -->
                                        <div style="display: flex; align-items: center; gap: 0.4rem;">
                                            <img src="<?php echo base_url('assets/img/panel/facebook2.png'); ?>" alt="Facebook" style="width: 20px; height: 20px; border-radius: 4px; margin-top: 1rem;">
                                            <?php echo getStatusBadge($page['status'] ?? ''); ?>
                                        </div>

                                        <!-- Instagram status, só mostra se existir -->
                                        <?php if (isset($page['is_instagram']) && $page['is_instagram'] == 1) : ?>
                                            <div style="display: flex; align-items: center; gap: 0.4rem;">
                                                <img src="https://app.talkall.com.br/assets/img/instagram_integration.png" alt="Instagram" style="width: 20px; height: 20px; border-radius: 4px; margin-top: 1rem;">
                                                <?php echo getStatusBadge($page['status_instagram'] ?? ''); ?>
                                            </div>
                                        <?php endif; ?>

                                    </div>
                                </div>
                                <div class="col-auto">

                                    <!-- menu settings -->
                                    <div class="img-settings" data-id="<?php echo $page["id"] ?>">
                                        <i class="fa fa-ellipsis-v"></i>
                                    </div>
                                    <div class="pop-settings" id="<?php echo $page["id"] ?>" style="display: none;">

                                        <a href="<?php echo base_url("config/edit/" . $page['id_channel']); ?>">
                                            <div class="edit item">
                                                <i class="fab fa-facebook-square"></i>
                                                <span><?php echo $this->lang->line("setting_integration_btn_edit_facebook") ?></span>
                                            </div>
                                        </a>

                                        <?php if (isset($page["is_instagram"]) == 1) { ?>
                                            <a href="<?php echo base_url("config/edit/" . $page['id_channel_instagram']); ?>">
                                                <div class="edit item">
                                                    <i class="fab fa-instagram"></i>
                                                    <span><?php echo $this->lang->line("setting_integration_btn_edit_instagram") ?></span>
                                                </div>
                                            </a>
                                        <?php } ?>

                                        <div class="disconnect anchor" data-itg="<?php echo $page['id'] ?>">
                                            <div class="item">
                                                <i class="far fa-trash-alt"></i>
                                                <span><?php echo $this->lang->line("setting_integration_btn_disconnect") ?></span>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php } ?>

        <?php } ?>

    </div>
    <input type="hidden" id="oauth_response" name="oauth_response" value="<?php echo isset($resp['response']); ?>">
</div>

<!-- Modal -->
<div class="modal fade" id="qrcode" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo $this->lang->line("setting_integration_whatsapp_title") ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!--<iframe src="https://messenger.talkall.com.br/QRCode/" style="height: 400px;border: 0px; width: 422px">
                </iframe>
                -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>