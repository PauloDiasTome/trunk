<?php if ($this->session->userdata('is_in_trial_period') && $this->session->userdata('attendance') == 1) : ?>
    <div class="trial-alert-container">
        <div class="trial-alert-content">
            <div class="trial-alert-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="20" viewBox="0 0 15 20" fill="none">
                    <path d="M11.6657 20H3.33656C2.85457 19.9995 2.37835 19.8951 1.94043 19.6937C1.50251 19.4924 1.11319 19.1989 0.799059 18.8333C0.490036 18.4754 0.261872 18.055 0.130138 17.6008C-0.00159645 17.1467 -0.0337998 16.6694 0.0357252 16.2017C0.482805 13.7644 1.78292 11.566 3.70323 10C1.78289 8.43346 0.48305 6.23437 0.0365587 3.79667C-0.0328304 3.32924 -0.00063623 2.85235 0.130947 2.3985C0.262531 1.94464 0.490411 1.52449 0.799059 1.16667C1.11319 0.801105 1.50251 0.507633 1.94043 0.30629C2.37835 0.104946 2.85457 0.000471907 3.33656 0L11.6657 0C12.1476 0.000426786 12.6237 0.104885 13.0615 0.306236C13.4993 0.507587 13.8885 0.801083 14.2024 1.16667C14.5115 1.52412 14.7398 1.94404 14.8718 2.39777C15.0038 2.85149 15.0364 3.32836 14.9674 3.79583C14.516 6.23412 13.2133 8.43285 11.2916 10C13.2122 11.5685 14.5134 13.7681 14.9632 16.2067C15.0323 16.6743 14.9998 17.1513 14.8677 17.6052C14.7357 18.059 14.5074 18.4791 14.1982 18.8367C13.8844 19.2011 13.4958 19.4937 13.0588 19.6944C12.6218 19.8952 12.1466 19.9994 11.6657 20ZM11.6657 1.66667H3.33656C3.09483 1.66646 2.8559 1.71848 2.63613 1.81916C2.41637 1.91984 2.22094 2.0668 2.06323 2.25C1.90914 2.42531 1.79534 2.63226 1.72983 2.85628C1.66432 3.0803 1.6487 3.31596 1.68406 3.54667C1.99656 5.63 3.28573 7.58 5.51739 9.34417C5.61593 9.42219 5.69554 9.52148 5.75027 9.63462C5.805 9.74776 5.83342 9.87182 5.83342 9.9975C5.83342 10.1232 5.805 10.2472 5.75027 10.3604C5.69554 10.4735 5.61593 10.5728 5.51739 10.6508C3.28573 12.4167 1.99906 14.3683 1.68406 16.4508C1.64832 16.6819 1.66375 16.9181 1.72927 17.1426C1.79478 17.367 1.90879 17.5744 2.06323 17.75C2.22094 17.9332 2.41637 18.0802 2.63613 18.1808C2.8559 18.2815 3.09483 18.3335 3.33656 18.3333H11.6657C11.9075 18.3335 12.1464 18.2815 12.3661 18.1808C12.5859 18.0802 12.7813 17.9332 12.9391 17.75C13.0931 17.575 13.2069 17.3683 13.2725 17.1446C13.3382 16.9209 13.3541 16.6855 13.3191 16.455C13.0082 14.3825 11.7191 12.4308 9.48573 10.6533C9.3878 10.5753 9.30872 10.4762 9.25437 10.3633C9.20002 10.2505 9.17179 10.1269 9.17179 10.0017C9.17179 9.87644 9.20002 9.75282 9.25437 9.63999C9.30872 9.52717 9.3878 9.42805 9.48573 9.35C11.7199 7.5725 13.0091 5.62083 13.3191 3.5475C13.3539 3.31629 13.3376 3.08024 13.2712 2.85604C13.2048 2.63184 13.09 2.42495 12.9349 2.25C12.7776 2.06734 12.5829 1.92069 12.3639 1.82003C12.1449 1.71937 11.9068 1.66706 11.6657 1.66667Z" fill="white" />
                </svg>
            </div>
            <div class="trial-alert-title">
                <span><?php echo $this->lang->line('topnav_you_are_in_trial'); ?> <b>(<?= $this->session->userdata('trial_days_remaining') . ' ' . $this->lang->line('topnav_trial_dyas') ?>)</b></span>
            </div>
        </div>
        <div class="trial-alert-actions">
            <div class="btn-open-tutorial">
                <span>
                    <?php echo $this->lang->line('tutorial_open_button'); ?>
                </span>
            </div>

            <div class="btn-view-pricing">
                <span onclick="window.open('https://talkall.com.br/pt-BR/precos', '_blank')">
                    <?php echo $this->lang->line('topnav_btn_view_pricing'); ?>
                </span>
            </div>

            <div class="btn-contact-support">
                <span onclick="window.open('https://wa.me/554333753130?text=Olá%2C%20tudo%20bem%3F%20Gostaria%20de%20falar%20com%20o%20comercial.', '_blank')">
                    <?php echo $this->lang->line('topnav_btn_contact_support'); ?>
                </span>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php $this->load->view('/pages/tutorial/tutorial.php');  ?>

<?php if (!isset($topnav['approval'])) { ?>
    <nav class="navbar navbar-top navbar-expand navbar-dark color-navbar <?php echo $topnav['search'] ? "bg-primary" : "bg-default" ?> border-bottom">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <?php if ($topnav['search']) { ?>
                    <div class="navbar-search navbar-search-light form-inline mr-sm-3" id="navbar-search-main">
                        <div class="form-group mb-0" id="header-search">
                            <div class="input-group input-group-alternative input-group-merge">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                </div>
                                <input id="search" class="form-control" placeholder="<?php echo $this->lang->line('topnav_search'); ?>" type="text">
                            </div>
                        </div>
                        <button type="button" class="close" data-action="search-close" data-target="#navbar-search-main" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                <?php } ?>

                <script>
                    let error1 = true,
                        error2 = true,
                        error3 = true

                    function onErrorImg(img) {
                        if (error1) {
                            error1 = false;
                            return img.src = document.location.origin = "../../../../assets/img/avatar.svg";
                        }
                        if (error2) {
                            error2 = false;
                            return img.src = document.location.origin = "../../../../../assets/img/avatar.svg";
                        }
                        if (error3) {
                            error3 = false;
                            return img.src = document.location.origin = "../../../../../assets/img/avatar.svg";
                        }
                    }
                </script>

                <ul class="navbar-nav align-items-center ml-md-auto">
                    <li class="nav-item d-xl-none">

                        <div class="pr-3 sidenav-toggler sidenav-toggler-dark" data-action="sidenav-pin" data-target="#sidenav-main">
                            <div class="sidenav-toggler-inner">
                                <i class="sidenav-toggler-line"></i>
                                <i class="sidenav-toggler-line"></i>
                                <i class="sidenav-toggler-line"></i>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item d-sm-none">
                        <a class="nav-link" href="#" data-action="search-show" data-target="#navbar-search-main">
                            <i class="ni ni-zoom-split-in"></i>
                        </a>
                    </li>
                </ul>


                <!-- dropdown infomações de ajuda -->
                <ul class="navbar-nav align-items-center ml-auto ml-md-0 help-center">
                    <li class="nav-item dropdown">
                        <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="<?php echo base_url("assets/icons/panel/help_center.svg") ?>" title="<?php echo $this->lang->line('topnav_help_center'); ?>" style="height: 23px;">
                        </a>

                        <div class="dropdown-menu dropdown-menu-right topnav-menu">
                            <!-- Suporte -->
                            <a href="https://wa.me/4333753130" target="_blank" class="dropdown-item">
                                <img src="<?php echo base_url("assets/icons/panel/support.svg") ?>">
                                <span><?php echo $this->lang->line('topnav_support'); ?></span>
                            </a>

                            <!-- Tutorial -->
                            <a href="javascript:void(0);" class="dropdown-item" id="open-tutorial">
                                <img src="<?php echo base_url("assets/icons/panel/tutorial.svg") ?>">
                                <span>Tutorial</span>
                            </a>

                            <!-- Central de Ajuda -->
                            <a href="https://ajuda.talkall.com.br/portal/pt-br/home" target="_blank" class="dropdown-item">
                                <img src="<?php echo base_url("assets/icons/panel/help_center_access.svg") ?>">
                                <span><?php echo $this->lang->line('topnav_help_center'); ?></span>
                            </a>
                        </div>
                    </li>
                </ul>


                <!-- dropdown infomações do usuário -->
                <ul class="navbar-nav align-items-center ml-auto ml-md-0" style="margin-right: -49px;">
                    <li class="nav-item dropdown">
                        <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <div class="media align-items-center">
                                <span class="avatar avatar-sm rounded-circle">
                                    <img id="profile-picture" alt="Image placeholder" src="<?php echo "https://files.talkall.com.br:3000/p/"  . $this->session->userdata('key_remote_id') . ".jpeg" ?>" onerror="onErrorImg(this)">
                                </span>
                                <div class="media-body ml-2 d-none d-lg-block">
                                    <span class="mb-0 text-sm  font-weight-bold"><?php echo $this->session->userdata('name'); ?></span>
                                </div>
                            </div>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right topnav-menu">
                            <a href="#" onclick="uploadUserProfilePicture(localStorage.getItem('userToken'))" class="dropdown-item">
                                <img src="<?php echo base_url("assets/icons/panel/user.svg") ?>">
                                <span><?php echo $this->lang->line('topnav_change_photo'); ?></span>
                            </a>

                            <?php if ($this->session->userdata('status') < 3) : ?>
                                <a href="#" onclick="RequestPassword('<?php echo $this->session->userdata('email_user') ?>')" class="dropdown-item">
                                    <img src="<?php echo base_url("assets/icons/panel/password.svg") ?>">
                                    <span><?php echo $this->lang->line('topnav_change_password'); ?></span>
                                </a>
                            <?php else : ?>
                                <a href="#" onclick="RequestResendActivation('<?php echo $this->session->userdata('id_user') ?>')" class="dropdown-item">
                                    <i class="ni ni-send"></i>
                                    <span><?php echo $this->lang->line('topnav_resend_confirmation'); ?></span>
                                </a>
                            <?php endif; ?>

                            <a href="<?php echo base_url('account/security/' . $this->session->userdata('id_user')) ?>" class="dropdown-item">
                                <img src="<?php echo base_url("assets/icons/panel/security.svg") ?>">
                                <span><?php echo $this->lang->line('topnav_security'); ?></span>
                            </a>

                            <a href="#" class="dropdown-item" id="openLanguage">
                                <img src="<?php echo base_url("assets/icons/panel/language.svg") ?>">
                                <span><?php echo $this->lang->line('topnav_change_language'); ?></span>
                            </a>

                            <div class="dropdown-divider"></div>
                            <a id="CLOSE_AVISO" href="<?php echo base_url('account/logoff'); ?>" class="dropdown-item">
                                <img src="<?php echo base_url("assets/icons/panel/exit.svg") ?>">
                                <span><?php echo $this->lang->line('topnav_logout'); ?></span>
                            </a>
                        </div>
                    </li>
                </ul>


                <!-- dropdown infomações do linguagem -->
                <ul id="dropdownLanguage">
                    <li class="nav-item dropdown">
                        <a id="selectedLanguage" class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> </a>

                        <div class="dropdown-menu dropdown-menu-right" style="margin-top: 29px">
                            <div class="dropdown-header noti-title">

                                <h6 class="text-overflow m-0"><img src="<?php echo base_url("assets/icons/panel/language.svg") ?>">&nbsp&nbsp<?php echo $this->lang->line('topnav_change_language'); ?></h6>
                            </div>

                            <div class="dropdown-divider"></div>
                            <a href="javascript:void(0)" alt="🇺🇸" value="en_us" onclick="changeLanguage('en_us')" class="optionLanguage  dropdown-item selected_en_us">
                                <img src="<?php echo base_url("assets/img/panel/us.png") ?>" style="margin-bottom: -12px;" alt=""><span style="margin-top: 7px;position: absolute;">&nbsp;&nbsp;<?php echo $this->lang->line('topnav_english'); ?></span>
                            </a>
                            <a href="javascript:void(0)" alt="🇧🇷" value="pt_br" onclick="changeLanguage('pt_br')" class="optionLanguage dropdown-item selected_pt_br">
                                <img src="<?php echo base_url("assets/img/panel/brasil.svg") ?>" alt="">&nbsp;&nbsp;<?php echo $this->lang->line('topnav_portuguese'); ?>
                            </a>
                            <a href="javascript:void(0)" alt="es" value="es" onclick="changeLanguage('es')" class="optionLanguage dropdown-item selected_es">
                                <img src="<?php echo base_url("assets/img/panel/espanha.svg") ?>" alt="">&nbsp;&nbsp;<?php echo $this->lang->line('topnav_espanhol'); ?>
                            </a>
                        </div>
                    </li>
                </ul>


            </div>
        </div>
    </nav>

    <?php if (!$topnav['search'] && isset($topnav['header']) == true) { ?>
        <div class="header pb-6 d-flex align-items-center" style="min-height: 100px; background-size: cover; background-position: center top;"></div>
    <?php } ?>
<?php } ?>


<script>
    const openLanguage = document.getElementById("openLanguage");

    if (openLanguage != null) {
        openLanguage.addEventListener("click", function() {

            const imageLanguage = document.getElementById("imageLanguage");
            const dropdownLanguage = document.getElementById("dropdownLanguage");

            if (imageLanguage != null) {
                dropdownLanguage.style.display = "flex";
                setTimeout(() => imageLanguage.click(), 300);
            }
        });
    }

    const openUpdates = document.getElementById("openUpdates");

    if (openUpdates != null) {
        openUpdates.addEventListener("click", function() {

            const beamerSelector = document.getElementById("beamerSelector")

            if (beamerSelector != null) {
                setTimeout(() => beamerSelector.click(), 300);
            }
        });
    }

    window.COMPANY_FLAGS = {
        communication: <?= (int)$this->session->userdata('communication') ?>,
        attendance: <?= (int)$this->session->userdata('attendance') ?>,
        is_trial: <?= (int)$this->session->userdata('is_trial') ?>,
        is_in_trial_period: <?= (int)$this->session->userdata('is_in_trial_period') ?>
    };
</script>