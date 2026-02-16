<div class="header pb-8 pt-lg-8 d-flex align-items-center" style="min-height: 400px; background-image: url(''); background-size: cover; background-position: center;">
    <span class="mask bg-gradient-default opacity-8" style="background: linear-gradient(87deg, #003448 0, #003447 100%) !important;"></span>

    <div class="container-fluid d-flex align-items-center">
        <div class="row">
            <div class="opacity-8" style="z-index:9;width:185px;margin-top:-3%;margin-left:2%;">
                <img src="https://files.talkall.com.br:3000/p/<?php echo $user['key_remote_id'] ?>.jpeg" alt="" style="width:100%;object-fit: cover;border-radius:100px;border:solid #003448 3px">
            </div>
            <div class="col-lg-7 col-md-10">
                <h1 class="display-2 text-white"><?php echo $user['name'] ?></h1>
                <p class="text-white mt-0 mb-5"><?php echo $this->lang->line("user_security_manage_information") ?></p>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--7">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("user_security_title_device") ?></h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-group">

                        <?php $i = 0;
                        if ($login != null) { ?>

                            <?php foreach ($login as $row) { ?>
                                <li class="list-group-item" <?php if ($i == 0 && $this->session->userdata("key_remote_id") == $user['key_remote_id']) {
                                                                echo "data-toggle='tooltip' data-placement='top' title='" . $this->lang->line("user_security_connection_talkall") . "'";
                                                            }  ?>>
                                    <img src="<?php echo base_url('assets/img/browsers/' . $row['agent'] . '.png'); ?>" class="float-left mr-3" style="width: 43px;">
                                    <span class="badge badge-success float-right"><?php echo $row['date'] ?></span>
                                    <?php echo $row['system'] ?><br><?php echo $row['agent'] . ' - ' .  $row['version']  ?>
                                    <span class="badge badge-info float-right"> <?php echo $row['ip'] ?></span>
                                </li>

                                <?php $i = $i + 1; ?>
                            <?php } ?>

                        <?php } else {
                            echo " <p class=' " + $this->lang->line("user_security_user_login") + " '</p>";
                        } ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("user_security_title_safety") ?></h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <p class="card-text"><?php echo $this->lang->line("user_security_protect_account") ?></p>
                    <ul class="list-group" style="margin-bottom: 5px;">
                        <li class="list-group-item" data-toggle="tooltip" data-placement="right" title="<?php echo $this->lang->line("user_security_protect_two_favor_authentication"); ?>">
                            <?php echo $this->lang->line("user_security_protect_add_two_favor_authentication") ?>
                        </li>
                        <?php if ((int)$user['2fa'] === 0) { ?>
                            <li onclick="RequestTwoFa('<?php echo $user['key_remote_id']; ?>', true)" class="list-group-item list-group-item-danger" style='cursor: pointer;' data-toggle="tooltip" data-placement="right" title="<?php echo $this->lang->line("user_security_protect_click_enable_two_favor_authentication") ?>">
                                <?php echo $this->lang->line("user_security_protect_add"); ?>
                                <span class="badge badge-danger badge-pill float-right"><i class="ni ni-fat-remove"></i></span>
                            </li>
                        <?php } else { ?>
                            <li onclick="RequestTwoFa('<?php echo $user['key_remote_id']; ?>', false)" class="list-group-item list-group-item-success" style='cursor: pointer;' data-toggle="tooltip" data-placement="right" title="<?php echo $this->lang->line("user_security_protect_click_disable"); ?>">
                                <?php echo $this->lang->line("user_security_protect_disable"); ?>
                                <span class="badge badge-success badge-pill float-right"><i class="ni ni-check-bold"></i></span>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $this->lang->line("user_security_datatable_title") ?></h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive rounded border-bottom border-right border-left" style="overflow: hidden;">
                        <table class="table align-items-center table-flush" id="datatable-history" cellspacing="0" style="margin-top: 0px !important; margin-bottom: 0px !important;">
                            <thead class="thead-light">
                                <tr>
                                    <th><?php echo $this->lang->line("user_security_culumn_time_hour") ?></th>
                                    <th><?php echo $this->lang->line("user_security_culumn_fulfilled") ?></th>
                                    <th><?php echo $this->lang->line("user_security_culumn_operating_system") ?></th>
                                    <th><?php echo $this->lang->line("user_security_culumn_browser") ?></th>
                                    <th><?php echo $this->lang->line("user_security_culumn_origin") ?></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>