<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
<link href="<?php echo base_url('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/css/login.css') ?>" rel="stylesheet">

<div class="main-new-password">
    <div class="box-content">
        <div class="box-inner">

            <div class="new-password">
                <div class="logo">
                    <img src="<?php echo base_url("/assets/icons/panel/talkall-blue.svg"); ?> " alt="">
                </div>

                <div class="row">
                    <div class="title">
                        <span><?php echo $this->lang->line("account_alert_new_password_enter_password") ?>:</span>
                    </div>
                </div>

                <?php echo form_open("NewPassword/$token"); ?>
                <div class="row">
                    <div class="form-group">
                        <span style="display: none;" id="" class="capsLock"></span>
                        <div class="transt">
                            <input type="password" class="form-control-input" id="password" name="password" oncopy="return false" oncut="return false" placeholder="<?php echo $this->lang->line("account_alert_new_password_placeholder") ?>" maxlength="30">
                            <i class="far fa-eye view-password" id="visualized" style="display: none;"></i>
                            <i class="far fa-eye-slash view-password" id="viewPassword" style="display: none;"></i>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <span style="display: none;" id="" class="capsLock"></span>
                        <div class="transt">
                            <input type="password" class="form-control-input" id="passconf" name="passconf" oncopy="return false" oncut="return false" placeholder="<?php echo $this->lang->line("account_alert_new_password_placeholder_repeat") ?>" maxlength="30">
                            <i class="far fa-eye view-password-conf" id="visualized2" style="display: none;"></i>
                            <i class="far fa-eye-slash view-password-conf" id="viewPasswordConf" style="display: none;"></i>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <input type="submit" class="btn-login" id="sendForm" value="<?php echo $this->lang->line("account_alert_new_password_button") ?>">
                    </div>
                </div>
                <?php echo form_close(); ?>

            </div>
        </div>

    </div>
</div>

<div class="bgbox" id="bgbox" style="display:none"></div>
<div class="modal" id="modalAlert" style="display:none">
    <div class="md-alert">
        <div class="md-content">

            <div class="md-left" id="box-md-left">
                <div class="icon">
                    <img src="<?php echo base_url("/assets/icons/panel/beauty.svg") ?>" id="icon_ok" alt="beauty" style="display:none">
                    <img src="<?php echo base_url("/assets/icons/panel/attention.svg") ?>" id="icon_error_email" alt="email" style="display:none">
                    <img src="<?php echo base_url("/assets/icons/panel/attention.svg") ?>" id="icon_error_login" alt="attention" style="display:none">
                    <img src="<?php echo base_url("/assets/icons/panel/attention2.svg") ?>" id="icon_error_login_password" alt="attention" style="display:none">
                </div>
            </div>

            <div class="md-right" id="box-md-right">
                <div class="title"><span></span></div>
                <div class="subtitle"><span></span></div>
                <div class="description"><span></span></div>
                <div class="button">
                    <input type="button" id="goToLogin">
                </div>
            </div>

        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo base_url('/assets/js/login.js') ?>"></script>

<?php if ($type == "error" || $type == "success") { ?>

    <span id="message" style="display: none;"><?php echo $message ?></span>

    <script>
        const data = {
            type: '<?php echo $type ?>',
            title: '<?php echo $title ?>',
            description: document.getElementById("message").innerHTML,
            subtitle: '<?php echo $subtitle ?>',
            btn: '<?php echo $btn ?>'
        }
        openModal(data);
    </script>

<?php  }
?>
</body>

</html>