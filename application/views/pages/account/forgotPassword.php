<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Talkall</title>
    <link href=" <?php echo base_url('assets/img/brand/favicon.png') ?>" rel="icon" type="image/png">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="<?php echo base_url('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/login.css') ?>" rel="stylesheet">

</head>

<body>

    <div class="main-forgot-password">
        <div class="box-content">
            <div class="box-inner">

                <div class="reset-password">
                    <div class="logo">
                        <img src="<?php echo base_url("/assets/icons/panel/talkall-blue.svg"); ?> " alt="">
                    </div>

                    <div class="row">
                        <div class="title">
                            <span>Digite seu e-mail:</span>
                        </div>
                    </div>

                    <?php echo form_open('account/ResetPassword', 'id="ResetPassword"'); ?>
                    <?php echo validation_errors(); ?>
                    <div class="row">
                        <div class="form-group">
                            <input type="email" require class="form-control-input input-email" name="email" id="email" onchange="validEmail(email.value)" placeholder="Email" maxlength="92">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <input type="submit" class="btn-login" value="Redefinir senha" onclick="this.disabled=true;this.value='Entrando, aguarde, por favor';this.form.submit();">
                        </div>
                    </div>
                    <?php echo form_close(); ?>

                    <div class="row">
                        <div class="form-group">
                            <div class="b-pass">
                                <a href="<?php echo base_url("/") ?>">
                                    <label for="" class="form-control-label" id="returnLogin">Já possui login e senha? Entrar</label>
                                </a>
                            </div>
                        </div>
                    </div>

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

    <script type="text/javascript" src="<?php echo base_url('assets/vendor/jquery/dist/jquery.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('/assets/js/login.js') ?>"></script>

    <?php if ($type == "email" || $type == "success" || $type == "error") { ?>
        <script>
            const data = {
                type: '<?php echo $type ?>',
                title: '<?php echo $title ?>',
                description: '<?php echo $message ?>',
                subtitle: '<?php echo $subtitle ?>',
                btn: '<?php echo $btn ?>'
            }
            openModal(data);
        </script>

    <?php } ?>
</body>

</html>