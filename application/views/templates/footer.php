<?php $APP_VERSION = $this->config->item('application_version'); ?>
<script type="text/javascript" src="<?php echo base_url("assets/vendor/MSFmultiSelect-master/msfmultiselect.js?v=$APP_VERSION"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/vendor/jquery/dist/jquery.min.js?v=$APP_VERSION") ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js?v=$APP_VERSION"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/vendor/js-cookie/js.cookie.js?v=$APP_VERSION"); ?>"></script>

<script>
    $(function($) {
        $.ajaxSetup({
            data: {
                "csrf_token_talkall": function() {
                    return Cookies.get("csrf_cookie_talkall");
                }
            }
        });
    });

    $(document).ready(function() {
        moment.locale('pt-BR');

        localStorage.setItem("userToken", "<?php echo $this->session->userdata('key_remote_id') ?>");
        localStorage.setItem("language", "<?php echo $this->session->userdata('language') ?>");

        $('#selectedLanguage').text($(`.optionLanguage[value="${localStorage.getItem("language")}"]`).attr('alt'));

        if ($(`.optionLanguage[value="${localStorage.getItem("language")}"]`).attr('alt') == "🇺🇸") {
            $("#selectedLanguage").html(`<img id='imageLanguage' src="${document.location.origin}/assets/img/panel/us.png" style='display:none'>`);
            $(".selected_en_us").css("background-color", "rgb(244 247 251)");
        } else if ($(`.optionLanguage[value="${localStorage.getItem("language")}"]`).attr('alt') == "🇧🇷") {
            $("#selectedLanguage").html(`<img id='imageLanguage' src="${document.location.origin}/assets/img/panel/br.png" style='display:none'>`);
            $(".selected_pt_br").css("background-color", "rgb(244 247 251)");
        } else {
            $("#selectedLanguage").html(`<img id='imageLanguage' src="${document.location.origin}/assets/img/panel/es.png" style='display:none'>`);
            $(".selected_es").css("background-color", "rgb(244 247 251)");
        }
    });

    const GLOBAL_LANG = <?php echo json_encode($this->lang->language); ?>;
    const USER_KEY_REMOTE_ID = <?php echo json_encode($this->session->userdata('key_remote_id')); ?>;
    const USER_EMAIL = <?php echo json_encode($this->session->userdata('email_user')); ?>;
    const USER_ID = <?php echo json_encode($this->session->userdata('id_user')); ?>;
</script>

<script>
    if (!localStorage.getItem("warning")) {
        localStorage.setItem("warning", true);

        const bgWarning = document.createElement("div");
        bgWarning.className = "bg-box-warning";

        const warning = document.createElement("div");
        warning.className = "warning-talkall";

        const header = document.createElement("div");
        header.className = "warning-header";

        const boxTitle = document.createElement("div");
        boxTitle.className = "box-title";

        const title = document.createElement("span");
        title.innerHTML = "TalkAll";

        const boxClose = document.createElement("div");
        boxClose.className = "box-close";
        boxClose.id = "closeWarning";

        const icon = document.createElement("i");
        icon.className = "fas fa-times";

        const body = document.createElement("div");
        body.className = "warning-body";

        const img = document.createElement("img");
        img.src = document.location.origin + "/assets/img/panel/aviso.png";

        const footer = document.createElement("div");
        footer.className = "warning-footer";

        const a = document.createElement("a");
        a.href = "https://my.demio.com/ref/oE8TMxlA8nxSW0gy";
        a.target = "_blank";
        a.style.cursor = "poiter";

        boxTitle.appendChild(title);
        boxClose.appendChild(icon);

        header.appendChild(boxTitle);
        header.appendChild(boxClose);

        a.appendChild(img);
        body.appendChild(a);

        warning.appendChild(header);
        warning.appendChild(body);
        warning.appendChild(footer);

        document.querySelector("body").appendChild(warning);
        document.querySelector("body").appendChild(bgWarning);

        $(".bg-box-warning").remove();
        $(".warning-talkall").remove();
    }
</script>

<script src="https://res.cloudinary.com/dxfq3iotg/raw/upload/v1569006273/BBBootstrap/choices.min.js?version=7.0.0"></script>
<script type="text/javascript" src="<?php echo base_url("assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js?v=$APP_VERSION"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js?v=$APP_VERSION"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/vendor/lavalamp/js/jquery.lavalamp.min.js?v=$APP_VERSION"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/vendor/sweetalert2/dist/sweetalert2.min.js?v=$APP_VERSION"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/jquery.mask.min.js?v=$APP_VERSION"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/vendor/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js?v=$APP_VERSION"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/vendor/datatables.net/js/jquery.dataTables.min.js?v=$APP_VERSION"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js?v=$APP_VERSION"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/vendor/datatables.net-scroller/js/dataTables.scroller.min.js?v=$APP_VERSION"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?v=$APP_VERSION"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/vendor/bootstrap-clockpicker/dist/bootstrap-clockpicker.min.js?v=$APP_VERSION"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/vendor/chart.js/dist/Chart.min.js?v=$APP_VERSION"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/bootstrap-select.js?v=$APP_VERSION"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/dist/quill.min.js?v=$APP_VERSION"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/select2.min.js?v=$APP_VERSION"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/argon.min.js?v=$APP_VERSION"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/croppie.min.js?v=$APP_VERSION"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/global.js?v=$APP_VERSION"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/jquery.validate.min.js?v=$APP_VERSION"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/additional-methods.min.js?v=$APP_VERSION"); ?>"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript" src="<?php echo base_url("assets/dist/moment/pt-br.js?v=$APP_VERSION"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/dist/maskMoney.js?v=$APP_VERSION"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/vendor/dropzone/dist/min/dropzone.min.js?v=$APP_VERSION"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/form_validation.js?v=$APP_VERSION"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/emojionearea.js?v=$APP_VERSION"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/openAI.js?v=$APP_VERSION"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/dist/validation.js?v=$APP_VERSION"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/dist/components_dom.js?v=$APP_VERSION"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/slimselect.min.js?v=$APP_VERSION"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/tutorial.js?v=$APP_VERSION"); ?>"></script>

<script>
    $('.clockpicker').clockpicker();
</script>

<?php if (!empty($js)) {

    foreach ($js as $javascript) {
        if ($javascript != '' && isset($js_module)) {
            echo "<script type='module' src='" . base_url("assets/js/{$javascript}?v=$APP_VERSION") . "'></script>\n";
        } else {
            echo "<script type='text/javascript' src='" . base_url("assets/js/{$javascript}?v=$APP_VERSION") . "'></script>\n";
        }
    }
} ?>

<?php if ($this->session->userdata('notify')) { ?>
    <div class="modal " id="modal-notification" role="dialog" aria-labelledby="modal-chatLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="modal-notification-title"></h6>
                    <button type="button" class="close pb-1" data-dismiss="modal" aria-label="Close" id="notification-accept">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body" id="modal-body-notification">
                    <div id="modal-notification-content"></div>
                    <!-- <a href="https://promocaotalkalll.com.br/participar" target="_blank" style="position: absolute;left: 410px; top: 280px;background: #0f938f;padding: 0 15px;color: #fff;border-radius: 14px;">Click aqui</a> -->
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/notification.css?v=$APP_VERSION"); ?>">
    <script src="<?php echo base_url("assets/js/showdown.js?v=$APP_VERSION"); ?>"></script>
    <script src="<?php echo base_url("assets/js/notification.js?v=$APP_VERSION"); ?>"></script>
<?php } ?>

<?php if (!$this->session->userdata('is_in_trial_period') && $this->session->userdata('is_trial') == 1) : ?>
    <div class="modal fade show trial-ended-modal" id="trial-ended-modal" tabindex="-1" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" style="min-width: 900px">
            <div class="modal-content trial-ended-content">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <h2 class="trial-ended-title">
                            <?= $this->lang->line('footer_trial_ended_title'); ?>
                        </h2>
                        <p class="trial-ended-text">
                            <?= $this->lang->line('footer_trial_ended_subtitle'); ?>
                        </p>
                        <p class="trial-ended-subtitle">
                            <?= $this->lang->line('footer_trial_ended_select_button'); ?>
                        </p>
                        <div class="trial-ended-buttons">
                            <a href="https://talkall.com.br/pt-BR/precos" target="_blank">
                                <button class="btn trial-ended-btn-pricing"><?= $this->lang->line('footer_trial_ended_btn_pricing'); ?></button>
                            </a>
                            <a href="https://wa.me/554333753130?text=Olá%2C%20tudo%20bem%3F%20Gostaria%20de%20falar%20com%20o%20comercial." target="_blank">
                                <button class="btn trial-ended-btn-sales"><?= $this->lang->line('footer_trial_ended_btn_sales'); ?></button>
                            </a>
                        </div>
                        <p class="trial-ended-helper">
                            <?= $this->lang->line('footer_trial_ended_bottom_helper'); ?>
                        </p>
                    </div>
                    <div class="col-md-5 text-center">
                        <img src="<?php echo base_url('assets/icons/panel/business_illustration.svg'); ?>" alt="Ilustração" class="trial-ended-image">
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

</body>

</html>