<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistema de atendimento pelo whatsapp">
    <meta name="author" content="Talkall Envio">
    <title><?php echo $this->lang->line("account_title") ?></title>
    <link href=" <?php echo base_url('assets/img/brand/favicon.png') ?>" rel="icon" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/animate.css@3.7.2/animate.min.css" rel="stylesheet">
    <link href="<?php echo base_url('assets/vendor/nucleo/css/nucleo.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/login.css">
</head>

<body>

    <div class="main-login">

        <div class="box-content">

            <?php if (isset($data['msgAlert'])) : ?>
                <h3 style="color:#fff; text-align: center;padding: 15px 5px;"><?= $data['msgAlert'] ?></h3>
            <?php endif; ?>

            <div class="container">

                <div class="left">
                    <div class="b-info">

                        <div id="slider-container-outer">
                            <div id="slider-container" class="slider-container-transition">

                                <div class="slider-item" data-position="1">
                                    <div class="title">
                                        <h2>Otimize seu trabalho com o app WhatsApp</h2>
                                    </div>
                                    <div class="description">
                                        <span>Tenha as melhores ferramentas de atendimento, interação e comunicação juntas, em um ambiente único, prático e seguro.</span>
                                    </div>
                                </div>

                                <div class="slider-item" data-position="2">
                                    <div class="title">
                                        <h2>Canais integrados, atendimento ágil e completo</h2>
                                    </div>
                                    <div class="description">
                                        <span>Automatize sua comunicação utilizando chatbots para apoiar o atendimento. Escolha entre humanizado, robotizado ou híbrido.</span>
                                    </div>
                                </div>

                                <div class="slider-item" data-position="3">
                                    <div class="title">
                                        <h2>Esteja sempre próximo do seu público-alvo</h2>
                                    </div>
                                    <div class="description">
                                        <span>Mantenha um diálogo direto e efetivo com seus clientes através do WhatsApp, enviando comunicações de acordo com a LGPD.</span>
                                    </div>
                                </div>

                                <div class="slider-item" data-position="4">
                                    <div class="title">
                                        <h2>Conheça o gerenciador de mídias sociais TalkAll</h2>
                                    </div>
                                    <div class="description">
                                        <span>Conte com uma plataforma unificada para gerenciar diversos perfis, encontrar seu público e aumentar o alcance da sua marca.</span>
                                    </div>
                                </div>

                                <div class="slider-item" data-position="5">
                                    <div class="title">
                                        <h2>Crescimento expressivo e alcance escalável</h2>
                                    </div>
                                    <div class="description">
                                        <span>Alcance os objetivos do seu negócio com campanhas de marketing efetivas e fortaleça sua marca no mercado.</span>
                                    </div>
                                </div>

                                <div class="slider-item" data-position="6">
                                    <div class="title">
                                        <h2>Campanhas efetivas e mais força de mercado</h2>
                                    </div>
                                    <div class="description">
                                        <span>Amplie a participação do seu público e conversões das ações promocionais do seu negócio com a TalkAll Promoção Autorizada.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button style="display: none;" id="move-button">Move Item</button>


                        <div class="box-ball">
                            <span class="ball selected">&#9679;</span>
                            <span class="ball">&#9675;</span>
                            <span class="ball">&#9675;</span>
                            <span class="ball">&#9675;</span>
                            <span class="ball">&#9675;</span>
                            <span class="ball">&#9675;</span>
                            <i class="fas fa-angle-double-right" id="next-ball"></i>
                        </div>
                    </div>

                </div>

                <div class="right">

                    <div class="center-hz" id="logon">
                        <div class="logo">
                            <img src="<?php echo base_url("/assets/icons/panel/talkall-blue.svg") ?> " alt="">
                        </div>

                        <?php echo form_open('account/logon', 'id="Logon"'); ?>
                        <?php echo validation_errors(); ?>
                        <div class="login">
                            <div class="row">
                                <div class="title">
                                    <span>Entre com suas credenciais</span>
                                </div>

                                <div class="form-group">
                                    <input type="email" class="form-control-input input-email" id="email" name="email" placeholder="Email" onchange="validEmail(email.value)" maxlength="92">
                                </div>

                                <div class="form-group">
                                    <span style="display: none;" id="" class="capsLock"></span>
                                    <div class="transt">
                                        <input type="password" class="form-control-input" id="password" name="password" placeholder="Senha" maxlength="30">
                                        <i class="far fa-eye view-password" id="visualized" style="display: none;"></i>
                                        <i class="far fa-eye-slash view-password" id="viewPassword" style="display: none;"></i>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <input type="submit" class="btn-login" value="Entrar" oncopy="return false" oncut="return false" onclick="this.disabled=true;this.value='Entrando, aguarde, por favor';this.form.submit();">
                                </div>

                                <div class="form-group">
                                    <div class="b-pass">
                                        <?php if ($_SERVER['HTTP_HOST'] != $this->config->item('intranet_domain')) { ?>
                                            <a href="../../account/forgotPassword">
                                                <label for="" class="form-control-label" id="forgotPassword">Esqueceu sua senha?</label>
                                            </a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>

                </div>
            </div>


            <div class="bgbox" id="bgbox" style="display:none"></div>
            <div class="modal" id="modalAlert" style="display:none">
                <div class="md-alert">
                    <div class="md-content">

                        <div class="md-left" id="box-md-left">
                            <div class="icon">
                                <img src="<?php echo base_url("/assets/icons/panel/attention.svg") ?>" id="icon_error_login" alt="attention" style="display:none">
                                <img src="<?php echo base_url("/assets/icons/panel/attention2.svg") ?>" id="icon_error_login_password" alt="attention" style="display:none">
                                <img src="<?php echo base_url("/assets/icons/panel/error.svg") ?>" id="icon_error_login_block" alt="block" style="display:none">
                            </div>
                        </div>

                        <div class="md-right" id="box-md-right">
                            <div class="title"><span></span></div>
                            <div class="subtitle"><span></span></div>
                            <div class="description"><span></span></div>
                            <div class="button"> <input type="button" id="goToLogin"></div>
                        </div>

                    </div>
                </div>
            </div>


            <div class="modal-2fa" id="modalAuthen" style="display:none">
                <div class="md-authen">
                    <div class="md-content">

                        <div class="icon-mobile">
                            <img src="<?php echo base_url("/assets/icons/panel/padlockWhite.svg") ?>" id="iconMobile" alt="padlock" style="display:none">
                        </div>

                        <div class="md-top">
                            <div class="icon">
                                <img src="<?php echo base_url("/assets/icons/panel/padlock.svg") ?>" id="icon_authen" alt="padlock" style="display:none">
                            </div>
                            <div class="header">
                                <div class="title"><span></span></div>
                                <div class="description"></div>
                            </div>
                            <div class="close" id="classModal2FA">
                                <img src="<?php echo base_url("/assets/icons/panel/closeWhite.svg") ?>" alt="clock">
                            </div>
                        </div>

                        <div class="md-bottom">
                            <div class="load" style="display:none;">
                                <img src="<?php echo base_url("/assets/icons/panel/load_spinner.svg") ?>" alt="">
                            </div>
                            <div class="info">
                                <span class="resend-code" style="display: none;">Não recebeu o código? <span id="resendCode">clique aqui para reenviar o código</span></span>
                                <span class="expired-code" style="display: none;">Você excedeu as tentativas de solidação do código de verificação</span>
                                <div class="login-difficulty" style="display: none;">Com dificuldade de fazer login? procure nosso <a target="_blank" href="https://api.whatsapp.com/send?1=pt_BR&amp;phone=554333753130">suporte técnico</a></div>
                            </div>
                            <div class="container-el">
                                <input type="text" name="FA1" class="item" maxlength="1" onKeyDown="preventBackspace();" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                                <input type="text" name="FA2" class="item" maxlength="1" onKeyDown="preventBackspace();" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                                <input type="text" name="FA3" class="item" maxlength="1" onKeyDown="preventBackspace();" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                                <input type="text" name="FA4" class="item" maxlength="1" onKeyDown="preventBackspace();" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                                <input type="text" name="FA5" class="item" maxlength="1" onKeyDown="preventBackspace();" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                                <input type="text" name="FA6" class="item" maxlength="1" onKeyDown="preventBackspace();" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                            </div>
                            <div class="alert_">
                                <span></span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>


        </div>

    </div>

    <script type="text/javascript" src="<?php echo base_url('assets/vendor/jquery/dist/jquery.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url("assets/vendor/js-cookie/js.cookie.js"); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/js/argon.js?v=1.0.0') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url("assets/vendor/sweetalert2/dist/sweetalert2.min.js?v={$this->config->item('application_version')}"); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('/assets/js/login.js') ?>"></script>

    <?php if ($data != null) { ?>

        <script>
            $(document).ready(function() {

                <?php if ($data['type'] == 'email' ||  $data['type'] == 'error' || $data['type'] == "block") { ?>

                    const data = {
                        type: '<?php echo $data['type'] ?>',
                        title: '<?php echo $data['title'] ?>',
                        description: '<?php echo $data['message'] ?>',
                        subtitle: '<?php echo $data['subtitle'] ?>',
                        btn: '<?php echo $data['btn'] ?>'
                    }
                    openModal(data);

                <?php } ?>

                <?php if ($data['type'] == '2fa') { ?>

                    const data = {
                        type: '<?php echo $data['type'] ?>',
                        title: '<?php echo $data['title'] ?>',
                        description: '<?php echo $data['message'] ?>',
                        subtitle: '<?php echo $data['subtitle'] ?>',
                        email: '<?php echo $data['email'] ?>',
                        password: '<?php echo $data['password'] ?>',
                    }
                    openModal2fa(data);
                <?php } ?>
            });
        </script>

    <?php } ?>

</body>


<script>
    // START CARROSEL //
    const carrosel = setInterval(() => {

        const ball = document.querySelectorAll(".ball");
        const boxInfo = document.querySelectorAll(".coletion li");

        const qtdeBall = ball.length - 1;

        for (i = 0; i < ball.length; i++) {

            if (ball[i].attributes.class.nodeValue == "ball selected") {

                document.getElementById("move-button").click();

                const idx = i + 1;

                ball[i].innerHTML = "&#9675;";
                ball[i].attributes.class.nodeValue = "ball";

                if (qtdeBall == i) {

                    ball[0].innerHTML = "&#9679;";
                    ball[0].attributes.class.nodeValue = "ball selected";
                } else {

                    ball[idx].innerHTML = "&#9679;";
                    ball[idx].attributes.class.nodeValue = "ball selected";

                }
                break;
            }
        }

    }, 10000);

    const FlexSlider = {

        num_items: document.querySelectorAll(".slider-item").length,

        current: 1,

        init: function() {
            document.querySelectorAll(".slider-item").forEach(function(element, index) {
                element.style.order = index + 1;
            });

            this.addEvents();
        },

        addEvents: function() {
            var that = this;
            document.querySelector("#move-button").addEventListener('click', () => {
                this.gotoNext();
            });

            document.querySelector("#slider-container").addEventListener('transitionend', () => {
                this.changeOrder();
            });
        },

        changeOrder: function() {
            if (this.current == this.num_items)
                this.current = 1;
            else
                this.current++;

            let order = 1;

            for (let i = this.current; i <= this.num_items; i++) {
                document.querySelector(".slider-item[data-position='" + i + "']").style.order = order;
                order++;
            }
            for (let i = 1; i < this.current; i++) {
                document.querySelector(".slider-item[data-position='" + i + "']").style.order = order;
                order++;
            }
            document.querySelector("#slider-container").classList.remove('slider-container-transition');
            document.querySelector("#slider-container").style.transform = 'translateX(0)';
        },

        gotoNext: function() {
            document.querySelector("#slider-container").classList.add('slider-container-transition');
            document.querySelector("#slider-container").style.transform = 'translateX(-100%)';
        }
    };

    FlexSlider.init();
    // END CARROSEL //
</script>

</html>