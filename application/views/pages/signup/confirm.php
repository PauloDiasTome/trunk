<html>

<head>
    <title>TalkAll</title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() . "assets/css/application.css"; ?>">
    <link rel="icon" href="https://www.talkall.com.br/wp-content/uploads/2019/02/cropped-icon-32x32.png" sizes="32x32">
</head>

<body>
    <div id="content">
        <div class="centered margin--lv8 padding--lv6 !margin-bottom--lv0 !padding-bottom--lv0 nomargin--tablet nopadding--tablet c-signupWrapper">
            <div class="line c-signupContent centered">
                <div class="unit size1of3 full-width-mobile">
                    <div class=" freddie margin--lv6 !margin-bottom--lv2 !margin-left--lv0 !margin-right--lv0" data-mc-el="freddie"></div>
                </div>
                <div class="lastUnit size2of3 nofloat-tablet full-width-mobile mar-lr-auto--tablet float-left">
                    <div id="signup-content" class="line selfclear !margin-bottom--lv8">
                        <div class="signup-wrap lastUnit">
                            <h1 class="no-transform !margin-bottom--lv3 center-on-medium"> Falta pouco! </h1>
                            <p class="!margin-bottom--lv5">
                                <span class="hide-phone">Só mais alguns informações :-)
                                </span>
                            </p>
                            <?php echo form_open("signup/company/post", "class='c-largeForm' id='signup-form' novalidate='novalidate'"); ?>
                            <input type="hidden" name="token" id="token" value="<?php echo $token; ?>">
                            <fieldset class="!margin-bottom--lv2">
                                <div class="line login-field">
                                    <div class="field-wrapper">
                                        <label for="corporate_name" class="">Nome da sua empresa</label>
                                        <input type="text" id="corporate_name" name="corporate_name" tabindex="1" autofocus="autofocus" class="av-email" value="">
                                    </div>
                                </div>
                                <div class="line login-field section">
                                    <div class="field-wrapper">
                                        <div class="line">
                                            <label for="full_name" class="float-left ">Seu nome completo</label>
                                        </div>
                                        <div class="line">
                                            <input type="text" name="full_name" tabindex="3" id="full_name" maxlength="51" value="">
                                            <span id="invalid-full_name" class="invalid-error hide">Insira seu nome completo</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="line">
                                    <div class="unit size3of8 !padding-left-right--lv0">
                                        <button id="create-account" type="button" class="!margin-top--lv0 !margin-bottom--lv3 button-large button-wide p1 full-width-mobile" disabled="disabled" tabindex="4">Iniciar!</button>
                                    </div>
                                    <div class="lastUnit size5of8 nopadding--phone">
                                        <p class="inline-block small-meta">Ao clicar neste botão, você concorda com o TalkAll's
                                            <a href="http://www.talkall.com.br.com/legal/terms/" target="_blank" rel="noopener noreferrer">Política anti-spam &amp; Termos de uso</a>.
                                        </p>
                                    </div>
                                </div>
                            </fieldset>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?php echo base_url(); ?>assets/vendor/jquery/dist/jquery.min.js"></script>
    <script type="text/javascript">
        function is_mail_valid() {
            var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if (!filter.test($("#email").val())) {
                return false;
            } else {
                return true;
            }
        }

        $("#corporate_name").keyup(function() {
            var corporate_name = $("#corporate_name").val().length;
            var full_name = $("#full_name").val().length;
            if (corporate_name > 0 && full_name > 0 ) {
                $('#create-account').removeAttr("disabled");
            } else {
                $('#create-account').prop("disabled", true);
            }
        });

        $("#full_name").keyup(function() {
            var corporate_name = $("#corporate_name").val().length;
            var full_name = $("#full_name").val().length;
            if (corporate_name > 0 && full_name > 0 ) {
                $('#create-account').removeAttr("disabled");
            } else {
                $('#create-account').prop("disabled", true);
            }
        });

        $("#create-account").click(function() {

            if ($("#full_name").val().length > 8 && $("#corporate_name").val().length > 0) {

                $('#create-account').prop("disabled", true);

                $("#invalid-password").removeClass("invalid-error hide").addClass("invalid-error hide");

                $("#signup-form").submit();

            } else {
                $("#invalid-password").removeClass("invalid-error hide").addClass("invalid-error show");
            }
        });
    </script>
</body>

</html>