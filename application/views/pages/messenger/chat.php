<html>

<head>
    <!-- teste máscaras para telefone -->
    <!-- <link rel="stylesheet" href="path-to/inputmask.css"> -->
    <link href="<?php echo base_url(); ?>assets/css/messenger.css?v=<?php echo $this->config->item('application_version'); ?>" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>assets/css/darkmodel.css?v=<?php echo $this->config->item('application_version'); ?>" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script type="text/javascript" src="assets/js/jquery-1.8.2.js"></script>
    <script src="assets/vendor/js-cookie/js.cookie.js"></script>
    <script type="text/javascript" src="assets/js/jquery-ui.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/bootstrap-clockpicker/dist/bootstrap-clockpicker.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/popmenu.css?v=<?php echo $this->config->item('application_version'); ?>">
    <link rel="stylesheet" href="assets/css/emojipicker.css?v=<?php echo $this->config->item('application_version'); ?>">
    <link rel="stylesheet" href="assets/css/emoji.css?v=<?php echo $this->config->item('application_version'); ?>">
    <link rel="stylesheet" href="assets/css/toggle.css">
    <script type="text/javascript" src="<?php echo base_url("assets/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?v={$this->config->item('application_version')}"); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url("assets/vendor/bootstrap-clockpicker/dist/bootstrap-clockpicker.min.js?v={$this->config->item('application_version')}"); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.maskedinput.min.js"></script>
    <script type="text/javascript" src="assets/js/randomColor.min.js"></script>
    <script src="assets/js/offline.min.js"></script>
    <script type="text/javascript" src="assets/js/upload.js"></script>
    <script type="text/javascript" src="assets/js/emoji.js"></script>
    <script type="text/javascript" src="assets/js/clipboard.js"></script>
    <link rel="stylesheet" href="assets/vendor/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="assets/css/lc_switch.css?v=<?php echo $this->config->item('application_version'); ?>">
    <script src="assets/vendor/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="assets/css/offline-theme-chrome.css">
    <link rel="stylesheet" href="assets/css/component_input.css">
    <!-- <link rel="stylesheet" href="assets/css/openAI_messenger.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css">
    <link rel="stylesheet" href="<?php echo base_url("assets/dist/intl-tell-input/css/intl-tell-input.css") ?>">
    <title></title>
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon-32x32.png">
</head>
<script>
    WebFont.load({
        google: {
            "families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]
        },
        active: function() {
            sessionStorage.fonts = true;
        }
    });
    if (localStorage.getItem("WebSessionToken") == null) {
        localStorage.setItem("WebSessionToken", "<?php echo $_SESSION['WebSessionToken'] ?>");
        location.reload();
    } else {
        if (localStorage.getItem("WebSessionToken") != "<?php echo $_SESSION['WebSessionToken']; ?>") {
            localStorage.setItem("WebSessionToken", "<?php echo $_SESSION['WebSessionToken']; ?>");
        }
    }
    WebSessionBowser = '<?php echo $_SESSION["WebSessionToken"] ?>';
    <?php if (isset($data['key_remote_id'])) { ?>
        let key_remote_id = '<?php echo $data['key_remote_id']; ?>';

        localStorage.setItem("chat_to", key_remote_id);
        location.href = "https://app.talkall.com.br/messenger";

    <?php } ?>
</script>
</head>

<body>
    <div class="conn" id="conn-replace" style="display: none; background: #eee;z-index: 9999;position: fixed;width: 100%;height: 100%;top: 0px;left: 0px;">
        <div class="box">
            <div class="info" style="background-color: #fff;border-radius: 3px;box-shadow: 0 17px 50px 0 rgba(0,0,0,.19), 0 12px 15px 0 rgba(0,0,0,.24);padding: 22px 24px 20px;box-sizing: border-box;display: -webkit-flex;display: flex;overflow: hidden;width: 400px;-webkit-flex-direction: column;flex-direction: column;-webkit-flex: none;flex: none;margin: 19% auto;">
                <span style="font: inherit;font-size: 100%;vertical-align: baseline;outline: none;margin: 0;padding: 23px;border: 0"><?php echo $this->lang->line('messenger_talkall_is_open'); ?></span>
                <div style="display: -webkit-flex;display: flex;-webkit-flex-wrap: wrap-reverse;flex-wrap: wrap-reverse;-webkit-justify-content: flex-end;justify-content: flex-end;overflow: hidden;white-space: nowrap;">
                    <input type="button" class="conn-close" value="<?php echo $this->lang->line('messenger_close'); ?>" style="cursor:pointer; color: #07bc4c;padding: 10px 24px;text-transform: uppercase;position: relative;font-size: 14px;transition: box-shadow .18s ease-out,background .18s ease-out,color .18s ease-out;margin-right: 10px;background: white;border: 0px;">
                    <input type="button" class="conn-refresh" value="<?php echo $this->lang->line('messenger_use_here'); ?>" style="cursor:pointer; text-transform: uppercase;position: relative;font-size: 14px;transition: box-shadow .18s ease-out,background .18s ease-out,color .18s ease-out;">
                </div>
            </div>
        </div>
    </div>
    <div class="conn" id="loading">
        <div class="box">
            <div style="margin-left: 46%;margin-top: 16%;width: 128px;height: 128px;">
                <img src="./assets/img/loads/loading_1.gif" style="width: 100%;float: left; padding-left: calc(50% - 35px); padding-bottom: 2rem; max-width: 70px;">
                <span style="width: 100%; float: left; text-align: center;"><?php echo $this->lang->line('messenger_loading'); ?>...</span>
            </div>
        </div>
    </div>
    <script>
        (() => {
            if (localStorage.getItem('mode_load') !== null) {
                document.querySelector('#loading').classList.add("dark");
            }
        })();
    </script>
    <div class="modal" id="modal">
        <div class="box">
            <div class="body">
                <div class="head"><span></span>
                </div>
                <div class="items" style="width: auto;"></div>
            </div>
        </div>
    </div>

    <div class="messenger">
        <div class="left" id="leftMessenger">
            <div class="find">
                <div class="search-contact">
                    <input type="text" class="input-search-contact" placeholder="<?php echo $this->lang->line('messenger_list_chat_search_placeholder'); ?>">
                    <div class="icon-search-contact show">
                        <svg width="14" height="14" viewBox="0 0 129 129" xmlns="http://www.w3.org/2000/svg">
                            <path d="M51.6,96.7c11,0,21-3.9,28.8-10.5l35,35c0.8,0.8,1.8,1.2,2.9,1.2s2.1-0.4,2.9-1.2c1.6-1.6,1.6-4.2,0-5.8l-35-35   c6.5-7.8,10.5-17.9,10.5-28.8c0-24.9-20.2-45.1-45.1-45.1C26.8,6.5,6.5,26.8,6.5,51.6C6.5,76.5,26.8,96.7,51.6,96.7z M51.6,14.7   c20.4,0,36.9,16.6,36.9,36.9C88.5,72,72,88.5,51.6,88.5c-20.4,0-36.9-16.6-36.9-36.9C14.7,31.3,31.3,14.7,51.6,14.7z" />
                        </svg>
                    </div>

                    <div class="icon-clear">
                        <svg width="14" height="14" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.9999 6C17.8124 5.81253 17.5581 5.70721 17.2929 5.70721C17.0278 5.70721 16.7735 5.81253 16.5859 6L11.9999 10.586L7.41394 6C7.22641 5.81253 6.9721 5.70721 6.70694 5.70721C6.44178 5.70721 6.18747 5.81253 5.99994 6C5.81247 6.18753 5.70715 6.44184 5.70715 6.707C5.70715 6.97217 5.81247 7.22647 5.99994 7.414L10.5859 12L5.99994 16.586C5.81247 16.7735 5.70715 17.0278 5.70715 17.293C5.70715 17.5582 5.81247 17.8125 5.99994 18C6.18747 18.1875 6.44178 18.2928 6.70694 18.2928C6.9721 18.2928 7.22641 18.1875 7.41394 18L11.9999 13.414L16.5859 18C16.7735 18.1875 17.0278 18.2928 17.2929 18.2928C17.5581 18.2928 17.8124 18.1875 17.9999 18C18.1874 17.8125 18.2927 17.5582 18.2927 17.293C18.2927 17.0278 18.1874 16.7735 17.9999 16.586L13.4139 12L17.9999 7.414C18.1874 7.22647 18.2927 6.97217 18.2927 6.707C18.2927 6.44184 18.1874 6.18753 17.9999 6Z" />
                        </svg>
                    </div>
                </div>

                <div class="icons">
                    <div id="contact-add">
                        <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M23 11H13V1C13 0.734784 12.8946 0.48043 12.7071 0.292893C12.5196 0.105357 12.2652 0 12 0V0C11.7348 0 11.4804 0.105357 11.2929 0.292893C11.1054 0.48043 11 0.734784 11 1V11H1C0.734784 11 0.48043 11.1054 0.292893 11.2929C0.105357 11.4804 0 11.7348 0 12H0C0 12.2652 0.105357 12.5196 0.292893 12.7071C0.48043 12.8946 0.734784 13 1 13H11V23C11 23.2652 11.1054 23.5196 11.2929 23.7071C11.4804 23.8946 11.7348 24 12 24C12.2652 24 12.5196 23.8946 12.7071 23.7071C12.8946 23.5196 13 23.2652 13 23V13H23C23.2652 13 23.5196 12.8946 23.7071 12.7071C23.8946 12.5196 24 12.2652 24 12C24 11.7348 23.8946 11.4804 23.7071 11.2929C23.5196 11.1054 23.2652 11 23 11Z" fill="none" />
                        </svg>
                    </div>

                    <div id="options-setting">
                        <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12.0001 4C13.1047 4 14.0001 3.10457 14.0001 2C14.0001 0.89543 13.1047 0 12.0001 0C10.8956 0 10.0001 0.89543 10.0001 2C10.0001 3.10457 10.8956 4 12.0001 4Z" />
                            <path d="M12.0001 14.0003C13.1047 14.0003 14.0001 13.1049 14.0001 12.0003C14.0001 10.8957 13.1047 10.0003 12.0001 10.0003C10.8956 10.0003 10.0001 10.8957 10.0001 12.0003C10.0001 13.1049 10.8956 14.0003 12.0001 14.0003Z" />
                            <path d="M12.0001 23.9997C13.1047 23.9997 14.0001 23.1043 14.0001 21.9997C14.0001 20.8951 13.1047 19.9997 12.0001 19.9997C10.8956 19.9997 10.0001 20.8951 10.0001 21.9997C10.0001 23.1043 10.8956 23.9997 12.0001 23.9997Z" />
                        </svg>
                    </div>
                </div>

                <div id="settings_tooltip" class="settings-box">
                    <ul>
                        <li>
                            <div id="profileMessenger" class="tool-setting-list">
                                <svg width="22" height="22" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M19 0H5C3.67441 0.00158786 2.40356 0.528882 1.46622 1.46622C0.528882 2.40356 0.00158786 3.67441 0 5L0 19C0.00158786 20.3256 0.528882 21.5964 1.46622 22.5338C2.40356 23.4711 3.67441 23.9984 5 24H19C20.3256 23.9984 21.5964 23.4711 22.5338 22.5338C23.4711 21.5964 23.9984 20.3256 24 19V5C23.9984 3.67441 23.4711 2.40356 22.5338 1.46622C21.5964 0.528882 20.3256 0.00158786 19 0V0ZM7 22V21C7 19.6739 7.52678 18.4021 8.46447 17.4645C9.40215 16.5268 10.6739 16 12 16C13.3261 16 14.5979 16.5268 15.5355 17.4645C16.4732 18.4021 17 19.6739 17 21V22H7ZM22 19C22 19.7956 21.6839 20.5587 21.1213 21.1213C20.5587 21.6839 19.7956 22 19 22V21C19 19.1435 18.2625 17.363 16.9497 16.0503C15.637 14.7375 13.8565 14 12 14C10.1435 14 8.36301 14.7375 7.05025 16.0503C5.7375 17.363 5 19.1435 5 21V22C4.20435 22 3.44129 21.6839 2.87868 21.1213C2.31607 20.5587 2 19.7956 2 19V5C2 4.20435 2.31607 3.44129 2.87868 2.87868C3.44129 2.31607 4.20435 2 5 2H19C19.7956 2 20.5587 2.31607 21.1213 2.87868C21.6839 3.44129 22 4.20435 22 5V19Z" />
                                    <path d="M12 4.00031C11.2089 4.00031 10.4355 4.2349 9.77772 4.67443C9.11993 5.11395 8.60723 5.73867 8.30448 6.46957C8.00173 7.20048 7.92252 8.00474 8.07686 8.78067C8.2312 9.55659 8.61216 10.2693 9.17157 10.8287C9.73098 11.3881 10.4437 11.7691 11.2196 11.9234C11.9956 12.0778 12.7998 11.9986 13.5307 11.6958C14.2616 11.3931 14.8864 10.8804 15.3259 10.2226C15.7654 9.56479 16 8.79143 16 8.00031C16 6.93944 15.5786 5.92202 14.8284 5.17188C14.0783 4.42173 13.0609 4.00031 12 4.00031ZM12 10.0003C11.6044 10.0003 11.2178 9.88301 10.8889 9.66324C10.56 9.44348 10.3036 9.13112 10.1522 8.76567C10.0009 8.40022 9.96126 7.99809 10.0384 7.61012C10.1156 7.22216 10.3061 6.8658 10.5858 6.58609C10.8655 6.30639 11.2219 6.1159 11.6098 6.03873C11.9978 5.96156 12.3999 6.00117 12.7654 6.15255C13.1308 6.30392 13.4432 6.56027 13.6629 6.88916C13.8827 7.21806 14 7.60474 14 8.00031C14 8.53074 13.7893 9.03945 13.4142 9.41452C13.0391 9.78959 12.5304 10.0003 12 10.0003Z" />
                                </svg>
                                <label><?php echo $this->lang->line('messenger_tooltip_settings_profile'); ?></label>
                            </div>
                        </li>
                        <li>
                            <div id="modalTean" class="tool-setting-list">
                                <svg width="22" height="22" viewBox="0 0 26 22" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8.41357 10.9131C9.39389 10.9131 10.3522 10.6224 11.1673 10.0778C11.9824 9.53313 12.6177 8.75902 12.9928 7.85333C13.368 6.94764 13.4661 5.95105 13.2749 4.98957C13.0836 4.0281 12.6116 3.14493 11.9184 2.45174C11.2252 1.75856 10.342 1.28649 9.38055 1.09524C8.41907 0.903992 7.42248 1.00215 6.51679 1.3773C5.6111 1.75245 4.83699 2.38774 4.29236 3.20284C3.74773 4.01794 3.45703 4.97623 3.45703 5.95654C3.45834 7.2707 3.98097 8.53065 4.91022 9.4599C5.83947 10.3891 7.09942 10.9118 8.41357 10.9131ZM8.41357 2.65218C9.06711 2.65218 9.70598 2.84598 10.2494 3.20907C10.7928 3.57216 11.2163 4.08823 11.4664 4.69202C11.7165 5.29581 11.7819 5.96021 11.6544 6.60119C11.5269 7.24218 11.2122 7.83096 10.7501 8.29308C10.288 8.75521 9.69921 9.06991 9.05822 9.19741C8.41724 9.32491 7.75284 9.25948 7.14905 9.00938C6.54526 8.75928 6.02919 8.33575 5.6661 7.79235C5.30301 7.24895 5.10921 6.61009 5.10921 5.95654C5.10921 5.08017 5.45735 4.2397 6.07704 3.62001C6.69673 3.00032 7.5372 2.65218 8.41357 2.65218Z" />
                                    <path d="M8.41333 12.5654C6.44217 12.5676 4.55236 13.3516 3.15854 14.7455C1.76471 16.1393 0.980702 18.0291 0.978516 20.0002C0.978516 20.2193 1.06555 20.4295 1.22047 20.5844C1.37539 20.7393 1.58551 20.8263 1.80461 20.8263C2.0237 20.8263 2.23382 20.7393 2.38874 20.5844C2.54366 20.4295 2.6307 20.2193 2.6307 20.0002C2.6307 18.4666 3.23994 16.9958 4.32439 15.9113C5.40884 14.8269 6.87968 14.2176 8.41333 14.2176C9.94698 14.2176 11.4178 14.8269 12.5023 15.9113C13.5867 16.9958 14.196 18.4666 14.196 20.0002C14.196 20.2193 14.283 20.4295 14.4379 20.5844C14.5928 20.7393 14.803 20.8263 15.0221 20.8263C15.2411 20.8263 15.4513 20.7393 15.6062 20.5844C15.7611 20.4295 15.8481 20.2193 15.8481 20.0002C15.846 18.0291 15.0619 16.1393 13.6681 14.7455C12.2743 13.3516 10.3845 12.5676 8.41333 12.5654Z" />
                                    <path d="M18.7398 12.5646C19.5568 12.5646 20.3553 12.3224 21.0346 11.8685C21.7138 11.4146 22.2432 10.7696 22.5559 10.0148C22.8685 9.26008 22.9503 8.42958 22.7909 7.62835C22.6315 6.82712 22.2382 6.09115 21.6605 5.51349C21.0828 4.93584 20.3469 4.54245 19.5456 4.38308C18.7444 4.2237 17.9139 4.3055 17.1592 4.61812C16.4044 4.93075 15.7593 5.46016 15.3055 6.13941C14.8516 6.81866 14.6094 7.61724 14.6094 8.43416C14.6105 9.52929 15.046 10.5793 15.8204 11.3536C16.5947 12.128 17.6447 12.5635 18.7398 12.5646ZM18.7398 5.68053C19.2844 5.68053 19.8168 5.84203 20.2697 6.1446C20.7225 6.44717 21.0754 6.87723 21.2839 7.38039C21.4923 7.88356 21.5468 8.43722 21.4406 8.97137C21.3343 9.50552 21.072 9.99618 20.6869 10.3813C20.3018 10.7664 19.8112 11.0286 19.277 11.1349C18.7429 11.2411 18.1892 11.1866 17.6861 10.9782C17.1829 10.7698 16.7528 10.4168 16.4503 9.964C16.1477 9.51117 15.9862 8.97878 15.9862 8.43416C15.9862 7.70386 16.2763 7.00346 16.7927 6.48705C17.3091 5.97064 18.0095 5.68053 18.7398 5.68053Z" />
                                    <path d="M16.1232 16.09C16.8961 15.591 17.8024 15.3197 18.7367 15.3197C20.0147 15.3197 21.2404 15.8274 22.1441 16.7311C23.0479 17.6348 23.5556 18.8605 23.5556 20.1385C23.5556 20.3211 23.6281 20.4962 23.7572 20.6253C23.8863 20.7544 24.0614 20.827 24.244 20.827C24.4265 20.827 24.6016 20.7544 24.7307 20.6253C24.8598 20.4962 24.9324 20.3211 24.9324 20.1385C24.9306 18.4959 24.2772 16.9211 23.1157 15.7596C21.9542 14.598 20.3793 13.9447 18.7367 13.9429C17.5175 13.9442 16.3356 14.3045 15.3324 14.9651C15.6452 15.3 15.9121 15.6783 16.1232 16.09Z" />
                                    <path d="M8.41357 10.9131C9.39389 10.9131 10.3522 10.6224 11.1673 10.0778C11.9824 9.53313 12.6177 8.75902 12.9928 7.85333C13.368 6.94764 13.4661 5.95105 13.2749 4.98957C13.0836 4.0281 12.6116 3.14493 11.9184 2.45174C11.2252 1.75856 10.342 1.28649 9.38055 1.09524C8.41907 0.903992 7.42248 1.00215 6.51679 1.3773C5.6111 1.75245 4.83699 2.38774 4.29236 3.20284C3.74773 4.01794 3.45703 4.97623 3.45703 5.95654C3.45834 7.2707 3.98097 8.53065 4.91022 9.4599C5.83947 10.3891 7.09942 10.9118 8.41357 10.9131ZM8.41357 2.65218C9.06711 2.65218 9.70598 2.84598 10.2494 3.20907C10.7928 3.57216 11.2163 4.08823 11.4664 4.69202C11.7165 5.29581 11.7819 5.96021 11.6544 6.60119C11.5269 7.24218 11.2122 7.83096 10.7501 8.29308C10.288 8.75521 9.69921 9.06991 9.05822 9.19741C8.41724 9.32491 7.75284 9.25948 7.14905 9.00938C6.54526 8.75928 6.02919 8.33575 5.6661 7.79235C5.30301 7.24895 5.10921 6.61009 5.10921 5.95654C5.10921 5.08017 5.45735 4.2397 6.07704 3.62001C6.69673 3.00032 7.5372 2.65218 8.41357 2.65218Z" />
                                    <path d="M8.41333 12.5654C6.44217 12.5676 4.55236 13.3516 3.15854 14.7455C1.76471 16.1393 0.980702 18.0291 0.978516 20.0002C0.978516 20.2193 1.06555 20.4295 1.22047 20.5844C1.37539 20.7393 1.58551 20.8263 1.80461 20.8263C2.0237 20.8263 2.23382 20.7393 2.38874 20.5844C2.54366 20.4295 2.6307 20.2193 2.6307 20.0002C2.6307 18.4666 3.23994 16.9958 4.32439 15.9113C5.40884 14.8269 6.87968 14.2176 8.41333 14.2176C9.94698 14.2176 11.4178 14.8269 12.5023 15.9113C13.5867 16.9958 14.196 18.4666 14.196 20.0002C14.196 20.2193 14.283 20.4295 14.4379 20.5844C14.5928 20.7393 14.803 20.8263 15.0221 20.8263C15.2411 20.8263 15.4513 20.7393 15.6062 20.5844C15.7611 20.4295 15.8481 20.2193 15.8481 20.0002C15.846 18.0291 15.0619 16.1393 13.6681 14.7455C12.2743 13.3516 10.3845 12.5676 8.41333 12.5654Z" />
                                    <path d="M18.7398 12.5646C19.5568 12.5646 20.3553 12.3224 21.0346 11.8685C21.7138 11.4146 22.2432 10.7696 22.5559 10.0148C22.8685 9.26008 22.9503 8.42958 22.7909 7.62835C22.6315 6.82712 22.2382 6.09115 21.6605 5.51349C21.0828 4.93584 20.3469 4.54245 19.5456 4.38308C18.7444 4.2237 17.9139 4.3055 17.1592 4.61812C16.4044 4.93075 15.7593 5.46016 15.3055 6.13941C14.8516 6.81866 14.6094 7.61724 14.6094 8.43416C14.6105 9.52929 15.046 10.5793 15.8204 11.3536C16.5947 12.128 17.6447 12.5635 18.7398 12.5646ZM18.7398 5.68053C19.2844 5.68053 19.8168 5.84203 20.2697 6.1446C20.7225 6.44717 21.0754 6.87723 21.2839 7.38039C21.4923 7.88356 21.5468 8.43722 21.4406 8.97137C21.3343 9.50552 21.072 9.99618 20.6869 10.3813C20.3018 10.7664 19.8112 11.0286 19.277 11.1349C18.7429 11.2411 18.1892 11.1866 17.6861 10.9782C17.1829 10.7698 16.7528 10.4168 16.4503 9.964C16.1477 9.51117 15.9862 8.97878 15.9862 8.43416C15.9862 7.70386 16.2763 7.00346 16.7927 6.48705C17.3091 5.97064 18.0095 5.68053 18.7398 5.68053Z" />
                                    <path d="M16.1232 16.09C16.8961 15.591 17.8024 15.3197 18.7367 15.3197C20.0147 15.3197 21.2404 15.8274 22.1441 16.7311C23.0479 17.6348 23.5556 18.8605 23.5556 20.1385C23.5556 20.3211 23.6281 20.4962 23.7572 20.6253C23.8863 20.7544 24.0614 20.827 24.244 20.827C24.4265 20.827 24.6016 20.7544 24.7307 20.6253C24.8598 20.4962 24.9324 20.3211 24.9324 20.1385C24.9306 18.4959 24.2772 16.9211 23.1157 15.7596C21.9542 14.598 20.3793 13.9447 18.7367 13.9429C17.5175 13.9442 16.3356 14.3045 15.3324 14.9651C15.6452 15.3 15.9121 15.6783 16.1232 16.09Z" />
                                </svg>
                                <label><?php echo $this->lang->line('messenger_tooltip_settings_team'); ?></label>
                            </div>
                        </li>
                        <li>
                            <div id="setting-messenger" class="tool-setting-list">
                                <svg width="22" height="22" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 8.00061C11.2089 8.00061 10.4355 8.23521 9.77772 8.67473C9.11993 9.11426 8.60723 9.73897 8.30448 10.4699C8.00173 11.2008 7.92252 12.005 8.07686 12.781C8.2312 13.5569 8.61216 14.2696 9.17157 14.829C9.73098 15.3884 10.4437 15.7694 11.2196 15.9238C11.9956 16.0781 12.7998 15.9989 13.5307 15.6961C14.2616 15.3934 14.8864 14.8807 15.3259 14.2229C15.7654 13.5651 16 12.7917 16 12.0006C16 10.9397 15.5786 9.92233 14.8284 9.17218C14.0783 8.42204 13.0609 8.00061 12 8.00061ZM12 14.0006C11.6044 14.0006 11.2178 13.8833 10.8889 13.6635C10.56 13.4438 10.3036 13.1314 10.1522 12.766C10.0009 12.4005 9.96126 11.9984 10.0384 11.6104C10.1156 11.2225 10.3061 10.8661 10.5858 10.5864C10.8655 10.3067 11.2219 10.1162 11.6098 10.039C11.9978 9.96187 12.3999 10.0015 12.7654 10.1529C13.1308 10.3042 13.4432 10.5606 13.6629 10.8895C13.8827 11.2184 14 11.605 14 12.0006C14 12.531 13.7893 13.0398 13.4142 13.4148C13.0391 13.7899 12.5304 14.0006 12 14.0006Z" />
                                    <path d="M21.294 13.9L20.85 13.644C21.0499 12.5564 21.0499 11.4416 20.85 10.354L21.294 10.098C21.6355 9.90102 21.9348 9.63871 22.1748 9.32606C22.4149 9.01341 22.591 8.65654 22.6932 8.27582C22.7953 7.8951 22.8215 7.49799 22.7702 7.10716C22.7188 6.71633 22.591 6.33944 22.394 5.998C22.1971 5.65656 21.9348 5.35727 21.6221 5.1172C21.3095 4.87714 20.9526 4.70101 20.5719 4.59886C20.1911 4.49672 19.794 4.47056 19.4032 4.52189C19.0124 4.57321 18.6355 4.70102 18.294 4.898L17.849 5.155C17.0086 4.43692 16.0427 3.88025 15 3.513V3C15 2.20435 14.684 1.44129 14.1214 0.87868C13.5588 0.31607 12.7957 0 12 0C11.2044 0 10.4413 0.31607 9.87872 0.87868C9.31611 1.44129 9.00004 2.20435 9.00004 3V3.513C7.95743 3.88157 6.99189 4.4396 6.15204 5.159L5.70504 4.9C5.01548 4.50218 4.19612 4.39457 3.42723 4.60086C2.65833 4.80715 2.00287 5.31044 1.60504 6C1.20722 6.68956 1.09962 7.50892 1.30591 8.27782C1.5122 9.04672 2.01548 9.70218 2.70504 10.1L3.14904 10.356C2.94915 11.4436 2.94915 12.5584 3.14904 13.646L2.70504 13.902C2.01548 14.2998 1.5122 14.9553 1.30591 15.7242C1.09962 16.4931 1.20722 17.3124 1.60504 18.002C2.00287 18.6916 2.65833 19.1948 3.42723 19.4011C4.19612 19.6074 5.01548 19.4998 5.70504 19.102L6.15004 18.845C6.99081 19.5632 7.95702 20.1199 9.00004 20.487V21C9.00004 21.7956 9.31611 22.5587 9.87872 23.1213C10.4413 23.6839 11.2044 24 12 24C12.7957 24 13.5588 23.6839 14.1214 23.1213C14.684 22.5587 15 21.7956 15 21V20.487C16.0427 20.1184 17.0082 19.5604 17.848 18.841L18.295 19.099C18.9846 19.4968 19.804 19.6044 20.5729 19.3981C21.3418 19.1918 21.9972 18.6886 22.395 17.999C22.7929 17.3094 22.9005 16.4901 22.6942 15.7212C22.4879 14.9523 21.9846 14.2968 21.295 13.899L21.294 13.9ZM18.746 10.124C19.0847 11.3511 19.0847 12.6469 18.746 13.874C18.6869 14.0876 18.7004 14.3147 18.7844 14.5198C18.8684 14.7249 19.0181 14.8963 19.21 15.007L20.294 15.633C20.5239 15.7656 20.6916 15.9841 20.7603 16.2403C20.829 16.4966 20.7932 16.7697 20.6605 16.9995C20.5279 17.2293 20.3095 17.397 20.0532 17.4658C19.7969 17.5345 19.5239 17.4986 19.294 17.366L18.208 16.738C18.0159 16.6267 17.7923 16.5826 17.5723 16.6124C17.3523 16.6423 17.1485 16.7445 16.993 16.903C16.103 17.8117 14.9816 18.46 13.75 18.778C13.5351 18.8333 13.3446 18.9585 13.2086 19.1339C13.0727 19.3094 12.9989 19.525 12.999 19.747V21C12.999 21.2652 12.8937 21.5196 12.7062 21.7071C12.5186 21.8946 12.2643 22 11.999 22C11.7338 22 11.4795 21.8946 11.2919 21.7071C11.1044 21.5196 10.999 21.2652 10.999 21V19.748C10.9992 19.526 10.9254 19.3104 10.7894 19.1349C10.6535 18.9595 10.463 18.8343 10.248 18.779C9.01639 18.4597 7.89537 17.81 7.00604 16.9C6.85057 16.7415 6.64678 16.6393 6.4268 16.6094C6.20682 16.5796 5.98315 16.6237 5.79104 16.735L4.70704 17.362C4.59327 17.4287 4.46743 17.4722 4.33677 17.4901C4.2061 17.508 4.0732 17.4998 3.9457 17.4661C3.8182 17.4324 3.69862 17.3738 3.59386 17.2937C3.4891 17.2136 3.40122 17.1135 3.33528 16.9993C3.26934 16.8851 3.22664 16.759 3.20964 16.6282C3.19264 16.4974 3.20168 16.3646 3.23623 16.2373C3.27079 16.11 3.33017 15.9909 3.41098 15.8866C3.49178 15.7824 3.5924 15.6952 3.70704 15.63L4.79104 15.004C4.98299 14.8933 5.13272 14.7219 5.2167 14.5168C5.30069 14.3117 5.31417 14.0846 5.25504 13.871C4.9164 12.6439 4.9164 11.3481 5.25504 10.121C5.31311 9.90788 5.29898 9.68153 5.21486 9.47729C5.13074 9.27305 4.98136 9.10241 4.79004 8.992L3.70604 8.366C3.47623 8.23339 3.30851 8.01492 3.23978 7.75865C3.17105 7.50239 3.20693 7.22931 3.33954 6.9995C3.47215 6.76969 3.69062 6.60197 3.94689 6.53324C4.20316 6.46451 4.47623 6.50039 4.70604 6.633L5.79204 7.261C5.98362 7.37251 6.20682 7.41721 6.42657 7.38807C6.64632 7.35893 6.85015 7.25759 7.00604 7.1C7.89613 6.19134 9.01747 5.54302 10.249 5.225C10.4647 5.16956 10.6556 5.04375 10.7917 4.8675C10.9277 4.69125 11.001 4.47464 11 4.252V3C11 2.73478 11.1054 2.48043 11.2929 2.29289C11.4805 2.10536 11.7348 2 12 2C12.2653 2 12.5196 2.10536 12.7071 2.29289C12.8947 2.48043 13 2.73478 13 3V4.252C12.9999 4.47396 13.0737 4.68964 13.2096 4.86508C13.3456 5.04052 13.5361 5.16573 13.751 5.221C14.9831 5.54015 16.1044 6.18988 16.994 7.1C17.1495 7.25847 17.3533 7.36069 17.5733 7.39057C17.7933 7.42044 18.0169 7.37626 18.209 7.265L19.293 6.638C19.4068 6.5713 19.5327 6.52777 19.6633 6.5099C19.794 6.49204 19.9269 6.50019 20.0544 6.5339C20.1819 6.56761 20.3015 6.6262 20.4062 6.70631C20.511 6.78642 20.5989 6.88646 20.6648 7.00067C20.7307 7.11488 20.7734 7.24101 20.7904 7.37179C20.8074 7.50257 20.7984 7.63542 20.7639 7.76269C20.7293 7.88997 20.6699 8.00915 20.5891 8.11337C20.5083 8.2176 20.4077 8.30482 20.293 8.37L19.209 8.996C19.0181 9.10671 18.8691 9.27748 18.7854 9.48169C18.7016 9.68591 18.6878 9.9121 18.746 10.125V10.124Z" />
                                </svg>
                                <label><?php echo $this->lang->line('messenger_tooltip_settings_general_settings'); ?></label>
                            </div>
                        </li>
                        <li>
                            <div id="openModalClose" class="tool-setting-list last-item">
                                <svg width="18" height="18" viewBox="0 0 23 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M20.7557 2.24448C20.5994 2.08825 20.3875 2.00049 20.1665 2.00049C19.9455 2.00049 19.7336 2.08825 19.5773 2.24448L10.9998 10.822L2.42232 2.24448C2.26605 2.08825 2.05413 2.00049 1.83316 2.00049C1.61219 2.00049 1.40026 2.08825 1.24399 2.24448C1.08776 2.40075 1 2.61267 1 2.83364C1 3.05461 1.08776 3.26654 1.24399 3.42281L9.82149 12.0003L1.24399 20.5778C1.08776 20.7341 1 20.946 1 21.167C1 21.3879 1.08776 21.5999 1.24399 21.7561C1.40026 21.9124 1.61219 22.0001 1.83316 22.0001C2.05413 22.0001 2.26605 21.9124 2.42232 21.7561L10.9998 13.1786L19.5773 21.7561C19.7336 21.9124 19.9455 22.0001 20.1665 22.0001C20.3875 22.0001 20.5994 21.9124 20.7557 21.7561C20.9119 21.5999 20.9996 21.3879 20.9996 21.167C20.9996 20.946 20.9119 20.7341 20.7557 20.5778L12.1782 12.0003L20.7557 3.42281C20.9119 3.26654 20.9996 3.05461 20.9996 2.83364C20.9996 2.61267 20.9119 2.40075 20.7557 2.24448Z" />
                                </svg>
                                <label><?php echo $this->lang->line('messenger_tooltip_settings_exit'); ?></label>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="list list-find" id="list-find"></div>

            <div class="tabs">
                <div class="tab" id="chat-active">
                    <div class="title">0</div>
                    <span><?php echo $this->lang->line('messenger_list_chat_conversations'); ?></span>
                    <div class="no-read">
                        <label></label>
                    </div>
                </div>
                <div class="tab" id="chat-internal">
                    <div class="title" style="color: #9a9a9a">0</div>
                    <span style="border-bottom: 0px; color: #9a9a9a"><?php echo $this->lang->line('messenger_list_chat_team'); ?></span>
                    <div class="no-read">
                        <label></label>
                    </div>
                </div>
                <div class="tab" id="chat-wait">
                    <div class="title" style="color: #9a9a9a">0</div>
                    <span style="border-bottom: 0px; color: #9a9a9a"><?php echo $this->lang->line('messenger_list_chat_wait'); ?></span>
                    <div class="no-read">
                        <label></label>
                    </div>
                </div>
            </div>

            <div id="list-notification" class="" style="display: block"></div>
            <div id="list-active" class="list" style="display: block"></div>
            <div id="list-comment" class="list" style="display: none"></div>
            <div id="list-internal" class="list" style="display: none"></div>
            <div id="list-wait" class="list" style="display: none"></div>

            <div class="footer">
                <div>
                    <span><?php echo $this->lang->line('messenger_list_chat_status'); ?></span>
                    <input type="checkbox" id="checkbox-on-off" name="checkbox-on-off" value="0" />
                </div>
            </div>
        </div>


        <!-- Menu de configurações PERFIL -->
        <div class="left settings-profile" id="left-settings-profile" style="display:none">
            <div class="settings-header">
                <div class="settings-return-icon">
                    <i class="fas fa-arrow-up icon-settings-close"></i>
                </div>
                <span id="settings-header-close" class="settings-header-span"><?php echo $this->lang->line('messenger_profile_settings_header'); ?></span>
            </div>

            <div class="body-settings">
                <div class="profile">
                    <div class="container_picture">
                        <img class="img-profile" src="<?php echo base_url(); ?>assets/img/avatar.svg" id="imgProfile">
                    </div>
                    <span class="user-name" id="profile_name"></span>
                </div>
                <div class="containerProfile">
                    <div class="font-settings">
                        <div class="list_profile" style="font-family: system-ui;margin-top: -10px;">
                            <strong><span><?php echo $this->lang->line('messenger_profile_settings_sector'); ?>:</span></strong><br />
                            <span id="profile_sector"></span><br /><br />
                        </div>
                        <div class="list_profile" style="font-family: system-ui; padding-top: 10px;">
                            <strong><span><?php echo $this->lang->line('messenger_profile_settings_service_profile'); ?>:</span></strong><br />
                            <span id="profile_attendance"></span><br /><br />
                        </div>
                        <div style="font-family: system-ui;padding-top: 10px;">
                            <strong><span><?php echo $this->lang->line('messenger_profile_settings_email'); ?>:</span></strong><br />
                            <span id="profile_email"></span><br /><br />
                        </div>

                    </div>
                </div>
            </div>
        </div>


        <!-- Menu de configurações do messenger  OPIÇÕES-->
        <div class="left settings" id="left-settings" style="display: none;">
            <div class="settings-header">
                <div class="settings-return-icon">
                    <i class="fas fa-arrow-up icon-settings-close"></i>
                </div>
                <span id="settings-header-close" class="settings-header-span"><?php echo $this->lang->line('messenger_modal_settings_header'); ?></span>
            </div>

            <div class="body-settings">
                <div>
                    <ul class="font-settings" style="list-style: none; padding-top: 25px; padding-left:1px;">

                        <i class="fas fa-moon icon-settings" style="margin-top:15px"></i>
                        <li class="list-settings-messenger" id="mod-dark-messenger">
                            <span><?php echo $this->lang->line('messenger_modal_settings_theme'); ?></span>
                        </li>

                        <i class="fas fa-font icon-settings" style="margin-top:15px"></i>
                        <li class="list-settings-messenger" id="font-messenger">
                            <span><?php echo $this->lang->line('messenger_modal_settings_fonts'); ?></span>
                        </li>

                        <i class="fas fa-bell icon-settings" style="margin-top:15px"></i>
                        <li class="list-settings-messenger" id="notification">
                            <span><?php echo $this->lang->line('messenger_modal_settings_notifications'); ?></span>
                        </li>

                        <i class="far fa-image icon-settings" style="margin-top:15px"></i>
                        <li class="list-settings-messenger" id="wallpaper">
                            <span><?php echo $this->lang->line('messenger_modal_settings_wallpaper'); ?></span>
                        </li>

                        <i class="fab fa-adn icon-settings" style="margin-top:15px"></i>
                        <li class="list-settings-messenger" id="shortcut">
                            <span><?php echo $this->lang->line('messenger_modal_settings_keyboard_shortcuts'); ?></span>
                        </li>

                        <i class="fas fa-question-circle icon-settings" style="margin-top:15px"></i>
                        <li class="list-settings-messenger" id="helpSettings">
                            <span><?php echo $this->lang->line('messenger_modal_settings_help'); ?></span>
                        </li>
                    </ul>
                </div>
            </div>

        </div>

        <!-- Sub-Menu de configurações TEMA -->
        <div class="left sub-settings-tema" id="sub-settings-tema" style="display:none">

            <div class="settings-header">
                <div class="sub-settings-return-icon">
                    <i class="fas fa-arrow-up icon-settings-close"></i>
                </div>
                <span class="sub-settings-header-span"><?php echo $this->lang->line('messenger_settings_submenu_theme_header'); ?></span>
            </div>

            <div class="settings-theme">
                <div class="checkbox-treme-clear font-settings">
                    <input type="checkbox" id="checkedClear" class="select-custom-checkbox">
                    <span id="checkedClearSpan" style="margin-left: 30px"><?php echo $this->lang->line('messenger_settings_submenu_theme_light'); ?></span>
                </div>
                <div class="checkbox-treme-dark font-settings">
                    <input type="checkbox" id="checkedDark" class="select-custom-checkbox">
                    <span id="checkedDarkSpan" style="margin-left: 30px"><?php echo $this->lang->line('messenger_settings_submenu_theme_dark'); ?></span>
                </div>
            </div>
        </div>


        <!-- Sub-Menu de configurações FONTE -->
        <div class="left sub-settings-font" id="sub-settings-font" style="display:none">

            <div class="settings-header">
                <div class="sub-settings-return-icon">
                    <i class="fas fa-arrow-up icon-settings-close"></i>
                </div>
                <span class="sub-settings-header-span"><?php echo $this->lang->line('messenger_settings_submenu_font_header'); ?></span>
            </div>

            <div class="settings-font-size">
                <div class="checkbox-treme-clear font-settings">
                    <input type="checkbox" id="checkedSmall" class="select-custom-checkbox">
                    <span id="checkedSmallSpan" style="margin-left: 30px"><?php echo $this->lang->line('messenger_settings_submenu_font_small'); ?></span>
                </div>
                <div class="checkbox-treme-dark font-settings">
                    <input type="checkbox" id="checkedAverage" class="select-custom-checkbox">
                    <span id="checkedAverageSpan" style="margin-left: 30px"><?php echo $this->lang->line('messenger_settings_submenu_font_middle'); ?></span>
                </div>
                <div class="checkbox-treme-font font-settings">
                    <input type="checkbox" id="checkedBig" class="select-custom-checkbox">
                    <span id="checkedBigSpan" style="margin-left: 30px"><?php echo $this->lang->line('messenger_settings_submenu_font_large'); ?></span>
                </div>
                <div class="checkbox-treme-xtraBig font-settings">
                    <input type="checkbox" id="checkedXtraBig" class="select-custom-checkbox">
                    <span id="checkedExtraBigSpan" style="margin-left: 30px"><?php echo $this->lang->line('messenger_settings_submenu_font_extra_large'); ?></span>
                </div>
            </div>
        </div>


        <!-- Sub-Menu de configurações NOTIFICAÇÃO -->
        <div class="left sub-settings-notification" id="sub-settings-notification" style="display:none">
            <div class="settings-header">
                <div class="sub-settings-return-icon">
                    <i class="fas fa-arrow-up icon-settings-close"></i>
                </div>
                <span class="sub-settings-header-span"><?php echo $this->lang->line('messenger_settings_submenu_notification_header'); ?></span>
            </div>

            <div class="body-settings">
                <div class="notify-form" id="notifyForm">
                    <div id="container_sounds">
                        <div class="box">
                            <label for="notify-song">
                                <input type="file" name="notify-song" id="notify-song" accept="audio/wav, audio/mpeg" />
                                <i class="fas fa-plus"></i>
                                <div class="title">
                                    <span style="font-weight: 500;"><?php echo $this->lang->line('messenger_settings_submenu_notification_load_sound'); ?></span>
                                    <i class="fas fa-music"></i>
                                </div>
                            </label>
                        </div>
                        <span class="text-format">
                            <?php echo $this->lang->line('messenger_settings_submenu_notification_format_sound'); ?>
                        </span>
                    </div>

                    <div id="load_sound" class="hide">
                        <i>
                            <img src="./assets/img/loads/loading_1.gif" class="load-img">
                        </i><br>
                        <span>
                            <?php echo $this->lang->line('messenger_loading'); ?> ...
                        </span>
                    </div>

                    <div class="col-12 col-md-4 text-right hide" id="sound_remove">
                        <span><i class="fas fa-times"></i><br><?php echo $this->lang->line('messenger_settings_submenu_notification_remove_alert'); ?></span>
                    </div>
                    <input type="hidden" name="url_sound" id="url_sound">
                    <div id="sound_player"></div>
                </div>
            </div>
        </div>


        <!-- Sub-Menu de configurações PAPEL DE PAREDE -->
        <div class="left sub-settings-color" id="sub-settings-color" style="display:none">
            <div class="settings-header">
                <div class="sub-settings-return-icon">
                    <i class="fas fa-arrow-up icon-settings-close"></i>
                </div>
                <span class="sub-settings-header-span"><?php echo $this->lang->line('messenger_settings_submenu_wallpaper_header'); ?></span>
            </div>

            <div id="colorForm"></div>
        </div>

        <!-- Modal de LISTA DE CARRINHO -->
        <div class="bgboxCardList">
            <div class="modalCardList font-card" style="margin-top: 20px">
                <div style="margin-top: 28px;text-align: center; font-size: 18px; float: left; width: 100%">
                    <strong style="margin-left:28px"><?php echo $this->lang->line('messenger_cart_list'); ?></strong>
                    <i class="fas fa-times closeCard" style="float: right; margin-top: -14px; margin-right: 20px;cursor:pointer;font-size:15px"></i>
                </div>

                <div class="hearder">
                    <div id="item_count" style="position: absolute; margin-top: 20px;font-weight: bold; font-size:17px"></div>
                    <div id="total_product" style="position: absolute; margin-top: 47px; font-size: 14.3px;"></div>
                    <div id="order_date" style="float: right; margin-right: 20px; font-size: 14.2px;margin-top:22px"></div>
                    <input id="id_messages_order" type="hidden">
                </div>

                <div class="itensCardList" id="itensCardList"></div>
                <div class="footer">
                    <span style="position: absolute; margin-left: 20px; margin-top: 20px; font-size: 15.5; font-weight: bold;"><?php echo $this->lang->line('messenger_order_status'); ?></span>
                    <div class="select">
                        <select id="order_status">
                        </select>
                    </div>
                    <button class="btnSaveCard" width="30px" height="30px"><?php echo $this->lang->line('messenger_save'); ?></button>
                    <!-- <button class="btnCloseCard">Sair</button> -->
                </div>
            </div>
        </div>


        <!-- Sub-Menu de configurações AJUDA -->
        <div class="left sub-settings-help" id="sub-settings-help" style="display:none">
            <div class="settings-header">
                <div class="sub-settings-return-icon">
                    <i class="fas fa-arrow-up icon-settings-close"></i>
                </div>
                <span class="sub-settings-header-span"><?php echo $this->lang->line('messenger_settings_submenu_help_header'); ?></span>
            </div>

            <div class="body-settings">
                <div style="margin-top: 50; margin-left: 26px; width: 80%;">
                    <img src="<?php echo base_url(); ?>assets/img/brand/blue.png" alt="">
                </div>

                <strong class="font-settings" style="font-size: 18px; float: left; margin-top: 98px; margin-left: 120px;"><?php echo $this->lang->line('messenger_settings_submenu_help_support'); ?></strong><br /><br />
                <div class="font-settings containerHelp">
                    <div class="list_help" style="padding-top: 25;">
                        <div class="font-settings" style="padding-bottom: 10px; margin-left: 30px;">
                            <strong><?php echo $this->lang->line('messenger_settings_submenu_help_email_support'); ?></strong><br />
                        </div>
                        <div style="margin-left: 30px;">
                            <span>atendimento@talkall.com.br</span>
                        </div>
                    </div><br />
                    <div class="list_help" style="padding-bottom:9px;padding-top: 22px; margin-top: -10px;">
                        <div class="font-settings" style="padding-bottom: 10px; margin-left: 30px;">
                            <strong><?php echo $this->lang->line('messenger_settings_submenu_help_whatsapp_support'); ?></strong><br />
                        </div>
                        <img src="<?php echo base_url(); ?>assets/img/Whatsapp.ico" style="width: 30px; margin-left:29px; float:left" alt="">
                        <div class="font-settings" style="margin-top: -17px; margin-left: 62px;">
                            <span>+55 43 3375-3130</span>
                        </div>
                    </div><br />
                </div>
                <div style="FONT-WEIGHT: 300; float: right; margin-right: 20px; font-size: 12px; margin-top:48px">
                    <span class="font-settings">Versão 2.2027.16</span>
                </div>
            </div>
        </div>

        <!-- Sub-Menu de configurações ATALHOS -->
        <div class="left sub-settings-shortcut" id="sub-settings-shortcut" style="display:none">
            <div class="settings-header">
                <div class="sub-settings-return-icon">
                    <i class="fas fa-arrow-up icon-settings-close"></i>
                </div>
                <span class="sub-settings-header-span"><?php echo $this->lang->line('messenger_settings_submenu_shortcuts_header'); ?></span>
            </div>

            <div class="body-settings">

                <div class="container-inner" style="height: 35px;"></div>
                <div class="container-shortcut">
                    <div class="title">
                        <span><?php echo $this->lang->line('messenger_settings_submenu_shortcuts_close_conversation'); ?></span>
                    </div>
                    <div class="_btn">
                        <span>Ctrl</span>
                    </div>
                    <div class="_btn _alt" style="margin-left: 55px;">
                        <span>Alt</span>
                    </div>
                    <div class="_btn" style="margin-left: 104px; width: 76px;">
                        <span>Backspace</span>
                    </div>
                </div>

                <div class="container-shortcut">
                    <div class="title">
                        <span><?php echo $this->lang->line('messenger_settings_submenu_shortcuts_contact_information'); ?></span>
                    </div>
                    <div class="_btn">
                        <span>Ctrl</span>
                    </div>
                    <div class="_btn _alt" style="margin-left: 55px;">
                        <span>Alt</span>
                    </div>
                    <div class="_btn" style="margin-left: 104px; width: 13px;">
                        <span>I</span>
                    </div>
                </div>

                <div class="container-shortcut">
                    <div class="title">
                        <span><?php echo $this->lang->line('messenger_settings_submenu_shortcuts_put_on_hold'); ?></span>
                    </div>
                    <div class="_btn">
                        <span>Ctrl</span>
                    </div>
                    <div class="_btn _alt" style="margin-left: 55px;">
                        <span>Alt</span>
                    </div>
                    <div class="_btn" style="margin-left: 104px; width: 20px;">
                        <span>W</span>
                    </div>
                </div>

                <div class="container-shortcut">
                    <div class="title">
                        <span><?php echo $this->lang->line('messenger_settings_submenu_shortcuts_fix_conversation'); ?></span>
                    </div>
                    <div class="_btn">
                        <span>Ctrl</span>
                    </div>
                    <div class="_btn _alt" style="margin-left: 55px;">
                        <span>Alt</span>
                    </div>
                    <div class="_btn" style="margin-left: 104px; width: 15px;">
                        <span>P</span>
                    </div>
                </div>

                <div class="container-shortcut">
                    <div class="title">
                        <span><?php echo $this->lang->line('messenger_settings_submenu_shortcuts_transfer_service'); ?></span>
                    </div>
                    <div class="_btn">
                        <span>Ctrl</span>
                    </div>
                    <div class="_btn _alt" style="margin-left: 55px;">
                        <span>Alt</span>
                    </div>
                    <div class="_btn" style="margin-left: 104px; width: 16px;">
                        <span>T</span>
                    </div>
                </div>

                <div class="container-shortcut">
                    <div class="title">
                        <span><?php echo $this->lang->line('messenger_settings_submenu_shortcuts_search_messages'); ?></span>
                    </div>
                    <div class="_btn">
                        <span>Ctrl</span>
                    </div>
                    <div class="_btn _alt" style="margin-left: 55px;">
                        <span>Alt</span>
                    </div>
                    <div class="_btn" style="margin-left: 104px; width: 16px;">
                        <span>S</span>
                    </div>
                </div>

                <div class="container-shortcut">
                    <div class="title">
                        <span><?php echo $this->lang->line('messenger_settings_submenu_shortcuts_open_gallery'); ?></span>
                    </div>
                    <div class="_btn">
                        <span>Ctrl</span>
                    </div>
                    <div class="_btn _alt" style="margin-left: 55px;">
                        <span>Alt</span>
                    </div>
                    <div class="_btn" style="margin-left: 104px; width: 18px;">
                        <span>G</span>
                    </div>
                </div>

                <div class="container-shortcut">
                    <div class="title">
                        <span><?php echo $this->lang->line('messenger_settings_submenu_shortcuts_open_tags'); ?></span>
                    </div>
                    <div class="_btn">
                        <span>Ctrl</span>
                    </div>
                    <div class="_btn _alt" style="margin-left: 55px;">
                        <span>Alt</span>
                    </div>
                    <div class="_btn" style="margin-left: 104px; width: 16px;">
                        <span>E</span>
                    </div>
                </div>

            </div>
        </div>


        <!-- CHAT -->
        <div class="right">

            <!-- chat Header -->
            <div class="head hide_max">
                <div class="head-left">
                    <div class="picture">
                        <img src="assets/img/avatar.svg">
                    </div>
                    <div id="description-info-contact" class="description-info-contact">
                        <div class="caption"></div>
                        <div class="status"></div>
                        <div class="label-contact"></div>
                    </div>
                </div>

                <div class="head-right">
                    <div class="openSearch" id="openSearch">
                        <svg id="iconSearchMessage" class="icon-search-message" width="23" height="23" viewBox="0 0 23 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M22.719 21.3647L16.9987 15.6444C18.5576 13.7379 19.324 11.3052 19.1395 8.84941C18.955 6.39366 17.8336 4.10276 16.0074 2.45058C14.1812 0.798407 11.7898 -0.0886438 9.32786 -0.0270883C6.86595 0.0344672 4.52188 1.03992 2.7805 2.7813C1.03913 4.52268 0.0336738 6.86675 -0.0278817 9.32866C-0.0894372 11.7906 0.797614 14.182 2.44979 16.0082C4.10197 17.8344 6.39286 18.9558 8.84862 19.1403C11.3044 19.3248 13.7371 18.5583 15.6436 16.9995L21.3639 22.7198C21.5447 22.8944 21.7867 22.991 22.038 22.9888C22.2893 22.9866 22.5296 22.8858 22.7073 22.7081C22.885 22.5304 22.9858 22.2901 22.988 22.0388C22.9902 21.7875 22.8936 21.5455 22.719 21.3647ZM9.58312 17.2506C8.0668 17.2506 6.58453 16.8009 5.32375 15.9585C4.06298 15.1161 3.08032 13.9187 2.50005 12.5178C1.91978 11.1169 1.76795 9.57541 2.06377 8.08823C2.35959 6.60104 3.08977 5.23497 4.16197 4.16277C5.23417 3.09056 6.60024 2.36038 8.08743 2.06456C9.57462 1.76874 11.1161 1.92057 12.517 2.50084C13.9179 3.08111 15.1153 4.06377 15.9577 5.32455C16.8001 6.58532 17.2498 8.06759 17.2498 9.58392C17.2475 11.6165 16.439 13.5653 15.0018 15.0025C13.5645 16.4398 11.6158 17.2483 9.58312 17.2506Z" fill="#666666" />
                        </svg>
                    </div>
                    <div class="button" id="close-chat">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 14 14" fill="none">
                            <path d="M12.9999 1C12.8124 0.81253 12.5581 0.707214 12.2929 0.707214C12.0278 0.707214 11.7735 0.81253 11.5859 1L6.99994 5.586L2.41394 1C2.22641 0.81253 1.9721 0.707214 1.70694 0.707214C1.44178 0.707214 1.18747 0.81253 0.99994 1C0.812469 1.18753 0.707153 1.44184 0.707153 1.707C0.707153 1.97217 0.812469 2.22647 0.99994 2.414L5.58594 7L0.99994 11.586C0.812469 11.7735 0.707153 12.0278 0.707153 12.293C0.707153 12.5582 0.812469 12.8125 0.99994 13C1.18747 13.1875 1.44178 13.2928 1.70694 13.2928C1.9721 13.2928 2.22641 13.1875 2.41394 13L6.99994 8.414L11.5859 13C11.7735 13.1875 12.0278 13.2928 12.2929 13.2928C12.5581 13.2928 12.8124 13.1875 12.9999 13C13.1874 12.8125 13.2927 12.5582 13.2927 12.293C13.2927 12.0278 13.1874 11.7735 12.9999 11.586L8.41394 7L12.9999 2.414C13.1874 2.22647 13.2927 1.97217 13.2927 1.707C13.2927 1.44184 13.1874 1.18753 12.9999 1Z" fill="#666666" />
                        </svg>
                        <label><?php echo $this->lang->line('messenger_close'); ?></label>
                    </div>
                </div>
            </div>

            <div id="alert_container"></div>

            <!-- Chat Body -->
            <div class="body">
                <div class="chat hide_max">
                    <span class="buttonBottomScroll fas fa-chevron-down fa-3x" style="line-height: 1.7; display:none"></span>
                    <div class="reply-message">
                        <div class="message" style="margin-left: 10px;"></div>
                        <div id="close" style="margin-right: 17px;margin-top: 4px;float: right;margin-right: 17px;margin-top: 4px;float: right;width: 32px;height: 32px;cursor: pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M19.1 17.2l-5.3-5.3 5.3-5.3-1.8-1.8-5.3 5.4-5.3-5.3-1.8 1.7 5.3 5.3-5.3 5.3L6.7
                                 19l5.3-5.3 5.3 5.3 1.8-1.8z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="product">
                        <div class="head">
                            <b style="font-size: 14px;float: left;margin-left: 10px;margin-top: 10px;"><?php echo $this->lang->line('messenger_products'); ?></b>
                            <span>X</span>
                        </div>
                        <div class="search" style="border-bottom: 1px solid rgba(0, 0, 0, .20);float: left; width: 100%;">
                            <input type="text" id="search-product" name="search-product" placeholder="Digite o nome do produto" value="" style="width: 60%;padding: 10px;margin: 0 17%;float: left;margin-top: 10px;margin-bottom: 10px;">
                        </div>
                        <div class="items"></div>
                    </div>
                    <div class="quick">
                        <div class="head">
                            <b style="font-size: 14px;float: left;margin-left: 10px;margin-top: 10px;"><?php echo $this->lang->line('messenger_quick_answers'); ?></b>
                            <span>X</span>
                        </div>
                        <div class="items"></div>
                    </div>
                    <div class="ticket">
                        <div class="head">
                            <b style="font-size: 14px;float: left;margin-left: 10px;margin-top: 10px;"><?php echo $this->lang->line('messenger_open_tickets'); ?></b>
                        </div>
                        <div class="items"></div>
                    </div>

                    <div class="media-preview clipboard">
                        <div class="media-preview-close" id="clipboard-close"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 14 14" fill="none">
                                <path d="M12.9999 1C12.8124 0.81253 12.5581 0.707214 12.2929 0.707214C12.0278 0.707214 11.7735 0.81253 11.5859 1L6.99994 5.586L2.41394 1C2.22641 0.81253 1.9721 0.707214 1.70694 0.707214C1.44178 0.707214 1.18747 0.81253 0.99994 1C0.812469 1.18753 0.707153 1.44184 0.707153 1.707C0.707153 1.97217 0.812469 2.22647 0.99994 2.414L5.58594 7L0.99994 11.586C0.812469 11.7735 0.707153 12.0278 0.707153 12.293C0.707153 12.5582 0.812469 12.8125 0.99994 13C1.18747 13.1875 1.44178 13.2928 1.70694 13.2928C1.9721 13.2928 2.22641 13.1875 2.41394 13L6.99994 8.414L11.5859 13C11.7735 13.1875 12.0278 13.2928 12.2929 13.2928C12.5581 13.2928 12.8124 13.1875 12.9999 13C13.1874 12.8125 13.2927 12.5582 13.2927 12.293C13.2927 12.0278 13.1874 11.7735 12.9999 11.586L8.41394 7L12.9999 2.414C13.1874 2.22647 13.2927 1.97217 13.2927 1.707C13.2927 1.44184 13.1874 1.18753 12.9999 1Z" fill="#666666"></path>
                            </svg>
                        </div>
                        <div class="media-preview-body">
                            <div class="media-preview-content">
                                <canvas class="clipboard" id="clipboard"></canvas>
                            </div>
                            <div class="media-preview-input-container">
                                <input id="caption" class="media-preview-caption" type="text" placeholder="<?php echo $this->lang->line('messenger_legend'); ?>" value="" style="border-radius: 30px 0px 0px 30px;">
                                <input type="button" class="media-preview-send" id="clipboard-send" value="<?php echo $this->lang->line('messenger_send'); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="media-preview quick-answer">
                        <div class="media-preview-head">
                            <div class="media-preview-close" id="media-preview-close"><span>x</span></div>
                        </div>
                        <div class="media-preview-body">
                            <div id="media-preview-content" class="media-preview-content">

                            </div>
                            <div class="media-preview-input-container">
                                <input id="caption-quick-reply" class="media-preview-caption" type="text" placeholder="<?php echo $this->lang->line('messenger_legend'); ?>" value="">
                                <input type="button" id="quick-answer-send" class="media-preview-send" value="<?php echo $this->lang->line('messenger_send'); ?>">
                            </div>
                        </div>
                    </div>


                    <!-- Modal de contatos para encaminhar mensagens -->
                    <div class="bgboxContactForward def__closeModal" id="bgboxContactForward"></div>
                    <div class="modalContactForward" id="modalContactForward">
                        <div class="header">
                            <div class="title"><span><?php echo $this->lang->line('messenger_forward_message_to'); ?></span></div>
                            <div class="close def__closeModal">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 22 22" class="icon-close-right">
                                    <path d="M21.7316 0.268754C21.5597 0.0969053 21.3266 0.000366211 21.0835 0.000366211C20.8404 0.000366211 20.6073 0.0969053 20.4354 0.268754L11.0002 9.704L1.56492 0.268754C1.39302 0.0969053 1.1599 0.000366211 0.916837 0.000366211C0.67377 0.000366211 0.440654 0.0969053 0.268754 0.268754C0.0969053 0.440654 0.000366211 0.67377 0.000366211 0.916837C0.000366211 1.1599 0.0969053 1.39302 0.268754 1.56492L9.704 11.0002L0.268754 20.4354C0.0969053 20.6073 0.000366211 20.8404 0.000366211 21.0835C0.000366211 21.3266 0.0969053 21.5597 0.268754 21.7316C0.440654 21.9034 0.67377 22 0.916837 22C1.1599 22 1.39302 21.9034 1.56492 21.7316L11.0002 12.2963L20.4354 21.7316C20.6073 21.9034 20.8404 22 21.0835 22C21.3266 22 21.5597 21.9034 21.7316 21.7316C21.9034 21.5597 22 21.3266 22 21.0835C22 20.8404 21.9034 20.6073 21.7316 20.4354L12.2963 11.0002L21.7316 1.56492C21.9034 1.39302 22 1.1599 22 0.916837C22 0.67377 21.9034 0.440654 21.7316 0.268754Z" fill="#666666"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="search">
                            <i class="fas fa-search icon-search-catalog"></i>
                            <i class="fas fa-arrow-left icon-close-text-catalog"></i>
                            <input type="text" name="" placeholder="<?php echo $this->lang->line('messenger_search'); ?>..." id="search-contact-forward">
                        </div>
                        <div class="body">
                        </div>
                        <div class="footer">
                            <span></span>
                            <div class="sendContactForward" id="sendContactForward">
                                <img src="assets/img/forward_icon.svg" alt="">
                            </div>
                        </div>
                    </div>


                    <!-- Modal de CATALAGO DE PRODUTOS -->
                    <div class="bgboxCatalog"></div>
                    <div class="modalCatalog">
                        <div class="header">
                            <div class="title">
                                <i class="fas fa-times closeCatalog"></i>
                                <span><?php echo $this->lang->line('messenger_send_products'); ?></span>
                            </div>
                            <div class="select">
                                <img class="all-catalog" id="iconHomeCatalog" src="assets/img/iconHomeCatalog.png" alt="">
                                <span class="all-catalog"><?php echo $this->lang->line('messenger_send_catalog_link'); ?></span>
                            </div>
                            <div class="search">
                                <i class="fas fa-search icon-search-catalog"></i>
                                <i class="fas fa-arrow-left icon-close-text-catalog"></i>
                                <input type="text" name="" placeholder="<?php echo $this->lang->line('messenger_search'); ?>..." id="search-catalog">
                            </div>
                        </div>
                        <div class="body"></div>
                        <div class="footer">
                            <div class="iconSendCatalog" id="iconSendCatalog" style="display: none;">
                                <img src="assets/img/iconSendCatalog.png" alt="">
                            </div>
                        </div>
                    </div>


                    <!-- Modal de CATALOGO PRODUTO EM ANALISE -->
                    <div class="modalClockCatalog">
                        <div class="header">
                            <i class="fas fa-arrow-left close-clock-catalog"></i>
                            <div class="title">
                                <span><?php echo $this->lang->line('messenger_product_under_review'); ?></span>
                            </div>
                        </div>
                        <div class="body"></div>
                    </div>


                    <!-- Modal de CATALOGO PRODUTO REJEITADO -->
                    <div class="modalRejectedCatalog">
                        <div class="header">
                            <i class="fas fa-arrow-left close-rejected-catalog"></i>
                            <div class="title">
                                <span><?php echo $this->lang->line('messenger_product_not_approved'); ?></span>
                            </div>
                        </div>
                        <div class="body"></div>
                    </div>


                    <div class="emojipicker" id="emojipicker">
                        <div class="emoji-header">
                            <div class="emoji" id="emoji">
                                <svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                                    <path d="M9.153 11.603c.795 0 1.439-.879 1.439-1.962s-.644-1.962-1.439-1.962-1.439.879-1.439 1.962.644 1.962 1.439 1.962zm-3.204 1.362c-.026-.307-.131 5.218 6.063 5.551 6.066-.25 6.066-5.551 6.066-5.551-6.078 1.416-12.129 0-12.129 0zm11.363 1.108s-.669 1.959-5.051 1.959c-3.505 0-5.388-1.164-5.607-1.959 0 0 5.912 1.055 10.658 0zM11.804 1.011C5.609 1.011.978 6.033.978 12.228s4.826 10.761 11.021 10.761S23.02 18.423 23.02 12.228c.001-6.195-5.021-11.217-11.216-11.217zM12 21.354c-5.273 0-9.381-3.886-9.381-9.159s3.942-9.548 9.215-9.548 9.548 4.275 9.548 9.548c-.001 5.272-4.109 9.159-9.382 9.159zm3.108-9.751c.795 0 1.439-.879 1.439-1.962s-.644-1.962-1.439-1.962-1.439.879-1.439 1.962.644 1.962 1.439 1.962z"></path>
                                </svg>
                            </div>
                            <div class="btn_close" id="emoji">
                                <img class="img-close" src="<?php echo base_url("assets/icons/messenger/close3.svg") ?>">
                            </div>
                            <span class="titleDark"><?php echo $this->lang->line('messenger_emojis_people'); ?></span>
                        </div>
                        <div class="items" style="height: auto;">
                            <div style="float: left;">
                                <div class="b69 item" style="background-position: -33px -33px;" data-emoji="0"></div>
                                <div class="b69 item" style="background-position: -129px -33px;" data-emoji="1"></div>
                                <div class="b69 item" style="background-position:  0px -65px;" data-emoji="2"></div>
                                <div class="b69 item" style="background-position: -64px -33px;" data-emoji="3"></div>
                                <div class="b69 item" style="background-position: -64px -65px;" data-emoji="4"></div>
                                <div class="b69 item" style="background-position: -33px -65px;" data-emoji="5"></div>
                                <div class="b69 item" style="background-position: -97px -33px;" data-emoji="6"></div>
                                <div class="b83 item" style="background-position: -33px -129px;" data-emoji="7"></div>
                                <div class="b3 item" style="background-position: -97 -0px;" data-emoji="8"></div>
                                <div class="b69 item" style="background-position: -33px -97px;" data-emoji="9"></div>
                                <div class="b69 item" style="background-position: -97px -65px;" data-emoji="10"></div>
                                <div class="b71 item" style="background-position: -64px -129px;" data-emoji="11"></div>
                                <div class="b71 item" style="background-position: -97px -129px;" data-emoji="12"></div>
                                <div class="b69 item" style="background-position: 0px -97px;" data-emoji="13"></div>
                                <div class="b69 item" style="background-position: -97px -97px;" data-emoji="14"></div>
                                <div class="b69 item" style="background-position: -129px -97px;" data-emoji="15"></div>
                                <div class="b90 item" style="background-position: -64px -0px;" data-emoji="16"></div>
                                <div class="b70 item" style="background-position: -0px -33px;" data-emoji="17"></div>
                                <div class="b70 item" style="background-position: -129 0px;" data-emoji="18"></div>
                                <div class="b70 item" style="background-position: -33px -33px;" data-emoji="19"></div>
                                <div class="b70 item" style="background-position: -64px -33px;" data-emoji="20"></div>
                                <div class="b69 item" style="background-position: -64px -97px;" data-emoji="21"></div>
                                <div class="b70 item" style="background-position: -97px -32px;" data-emoji="22"></div>
                                <div class="b70 item" style="background-position: 0px -64px;" data-emoji="23"></div>
                                <div class="b70 item" style="background-position: -129 -32px;" data-emoji="24"></div>
                                <div class="b84 item" style="background-position: -129 -64px;" data-emoji="25"></div>
                                <div class="b84 item" style="background-position: -64px -65px;" data-emoji="26"></div>
                                <div class="b94 item" style="background-position: -33px -129px;" data-emoji="27"></div>
                                <div class="b81 item" style="background-position: 0px -97px;" data-emoji="28"></div>
                                <div class="b69 item" style="background-position: 0px -129px;" data-emoji="29"></div>
                                <div class="b84 item" style="background-position: -97px -65px;" data-emoji="30"></div>
                                <div class="b90 item" style="background-position: -129 -0px;" data-emoji="31"></div>
                                <div class="b69 item" style="background-position: -33px -129px;" data-emoji="32"></div>
                                <div class="b69 item" style="background-position: -129px -129px;" data-emoji="33"></div>
                                <div class="b70 item" style="background-position: -33px -65px;" data-emoji="34"></div>
                                <div class="b70 item" style="background-position: -33px 0px;" data-emoji="35"></div>
                                <div class="b70 item" style="background-position: -64px -65px;" data-emoji="36"></div>
                                <div class="b70 item" style="background-position: -64px 0px;" data-emoji="37"></div>
                                <div class="b71 item" style="background-position: -33px -129px;" data-emoji="38"></div>
                                <div class="b3 item" style="background-position: -64px 0px;" data-emoji="39"></div>
                                <div class="b70 item" style="background-position: -33px -97px;" data-emoji="40"></div>
                                <div class="b70 item" style="background-position: -97px 0px;" data-emoji="41"></div>
                                <div class="b70 item" style="background-position: -129 -129px;" data-emoji="42"></div>
                                <div class="b70 item" style="background-position: -64 -129px;" data-emoji="43"></div>
                                <div class="b90 item" style="background-position: -97px -33px;" data-emoji="44"></div>
                                <div class="b70 item" style="background-position: 0px -97px;" data-emoji="45"></div>
                                <div class="b71 item" style="background-position: -33px 0px;" data-emoji="46"></div>
                                <div class="b70 item" style="background-position: -64px -97px;" data-emoji="47"></div>
                                <div class="b70 item" style="background-position: -97px -65px;" data-emoji="48"></div>
                                <div class="b70 item" style="background-position: -129px -65px;" data-emoji="49"></div>
                                <div class="b84 item" style="background-position: -33px -97px;" data-emoji="50"></div>
                                <div class="b84 item" style="background-position: -129px -97px;" data-emoji="51"></div>
                                <div class="b71 item" style="background-position: -64px -33px;" data-emoji="52"></div>
                                <div class="b90 item" style="background-position: -33px -33px;" data-emoji="53"></div>
                                <div class="b90 item" style="background-position: -64px -33px;" data-emoji="54"></div>
                                <div class="b71 item" style="background-position: 0px -33px;" data-emoji="55"></div>
                                <div class="b70 item" style="background-position: -33px -129px;" data-emoji="56"></div>
                                <div class="b71 item" style="background-position: -129px 0px;" data-emoji="57"></div>
                                <div class="b70 item" style="background-position: -97 -97px;" data-emoji="58"></div>
                                <div class="b70 item" style="background-position: -0px 0px;" data-emoji="59"></div>
                                <div class="b81 item" style="background-position: -129px -97px;" data-emoji="60"></div>
                                <div class="b81 item" style="background-position: -33 -97px;" data-emoji="61"></div>
                                <div class="b84 item" style="background-position: -64px -97px;" data-emoji="62"></div>
                                <div class="b84 item" style="background-position:  0px -97px;" data-emoji="63"></div>
                                <div class="b83 item" style="background-position: -97px -129px;" data-emoji="64"></div>
                                <div class="b71 item" style="background-position:  0px -65px;" data-emoji="65"></div>
                                <div class="b69 item" style="background-position: -65px -129px;" data-emoji="66"></div>
                                <div class="b69 item" style="background-position: -97px -129px;" data-emoji="67"></div>
                                <div class="b71 item" style="background-position: 0px 0px;" data-emoji="68"></div>
                                <div class="b71 item" style="background-position: -129px -129px;" data-emoji="69"></div>
                                <div class="b71 item" style="background-position: -97px 0px;" data-emoji="70"></div>
                                <div class="b70 item" style="background-position: -129px -97px;" data-emoji="71"></div>
                                <div class="b70 item" style="background-position: -0px -129px;" data-emoji="72"></div>
                                <div class="b71 item" style="background-position: -64px 0px;" data-emoji="73"></div>
                                <div class="b71 item" style="background-position: -33px -33px;" data-emoji="74"></div>
                                <div class="b90 item" style="background-position: -97px 0px;" data-emoji="75"></div>
                                <div class="b71 item" style="background-position: -97px -33px;" data-emoji="76"></div>
                                <div class="b83 item" style="background-position: -64px -129px;" data-emoji="77"></div>
                                <div class="b70 item" style="background-position: -97px -129px;" data-emoji="78"></div>
                                <div class="b71 item" style="background-position: -129px -33px;" data-emoji="79"></div>
                                <div class="b81 item" style="background-position: -64px -65px;" data-emoji="80"></div>
                                <div class="b90 item" style="background-position: 0px -33px;" data-emoji="81"></div>
                                <div class="b83 item" style="background-position: -0px -129px;" data-emoji="82"></div>
                                <div class="b84 item" style="background-position: -97px -97px;" data-emoji="83"></div>
                                <div class="b84 item" style="background-position: -33px -65px;" data-emoji="84"></div>
                                <div class="b71 item" style="background-position: -33px -65px;" data-emoji="85"></div>
                                <div class="b81 item" style="background-position: -129px -65px;" data-emoji="86"></div>
                                <div class="b81 item" style="background-position: -64px -97px;" data-emoji="87"></div>
                                <div class="b81 item" style="background-position: -97px -65px;" data-emoji="88"></div>
                                <div class="b83 item" style="background-position: -97px -97px;" data-emoji="89"></div>
                                <div class="b69 item" style="background-position: -129px -65px;" data-emoji="90"></div>
                                <div class="b54 item" style="background-position: -64px -129px;" data-emoji="91"></div>
                                <div class="b54 item" style="background-position: -33px -65px;" data-emoji="92"></div>
                                <div class="b54 item" style="background-position: -64px -65px;" data-emoji="93"></div>
                                <div class="b83 item" style="background-position: -129px -97px;" data-emoji="94"></div>
                                <div class="b58 item" style="background-position: -97px -97px;" data-emoji="95"></div>
                                <div class="b54 item" style="background-position: -97px -65px;" data-emoji="96"></div>
                                <div class="b54 item" style="background-position: -97px -129px;" data-emoji="97"></div>
                                <div class="b2 item" style="background-position: -129px -97px;" data-emoji="98"></div>
                                <div class="b54 item" style="background-position: 0px -129px;" data-emoji="99"></div>
                                <div class="b54 item" style="background-position: -33px -129px;" data-emoji="100"></div>
                                <div class="b81 item" style="background-position: -97px -97px;" data-emoji="101"></div>
                                <div class="b24 item" style="background-position: -97px -65px;" data-emoji="102"></div>
                                <div class="b71 item" style="background-position: -129px -65px;" data-emoji="103"></div>
                                <div class="b71 item" style="background-position: -64px -65px;" data-emoji="104"></div>
                                <div class="b71 item" style="background-position: -97px -65px;" data-emoji="105"></div>
                                <div class="b71 item" style="background-position: 0px -97px;" data-emoji="106"></div>
                                <div class="b71 item" style="background-position: -33px -97px;" data-emoji="107"></div>
                                <div class="b71 item" style="background-position: -64px -97px;" data-emoji="108"></div>
                                <div class="b71 item" style="background-position: 0px -129px;" data-emoji="109"></div>
                                <div class="b71 item" style="background-position: -129px -97px;" data-emoji="110"></div>
                                <div class="b71 item" style="background-position: -97px -97px;" data-emoji="111"></div>
                                <div class="b20 item" style="background-position: -64px -33px;" data-emoji="112"></div>
                                <div class="b38 item" style="background-position: -97px -129px;" data-emoji="113"></div>
                                <div class="b74 item" style="background-position: -33px 0px;" data-emoji="114"></div>
                                <div class="b38 item" style="background-position: -64px -97px;" data-emoji="115"></div>
                                <div class="b83 item" style="background-position: 0px -0px;" data-emoji="116"></div>
                                <div class="b38 item" style="background-position: 0px -33px;" data-emoji="117"></div>
                                <div class="b38 item" style="background-position: -33px -65px;" data-emoji="118"></div>
                                <div class="b4 item" style="background-position: -64px -65px;" data-emoji="119"></div>
                                <div class="b5 item" style="background-position: -64px -33px;" data-emoji="120"></div>
                                <div class="b82 item" style="background-position: -97px -65px;" data-emoji="121"></div>
                                <div class="b82 item" style="background-position: -129px -97px;" data-emoji="122"></div>
                                <div class="b83 item" style="background-position: -33px -33px;" data-emoji="123"></div>
                                <div class="b5 item" style="background-position: -129px -129px;" data-emoji="124"></div>
                                <div class="b83 item" style="background-position: -64px -65px;" data-emoji="125"></div>
                                <div class="b81 item" style="background-position: -0px -129px;" data-emoji="126"></div>
                                <div class="b4 item" style="background-position: -129px -129px;" data-emoji="127"></div>
                                <div class="b81 item" style="background-position: -33px -33px;" data-emoji="128"></div>
                                <div class="b4 item" style="background-position:  0px  0px;" data-emoji="129"></div>
                                <div class="b4 item" style="background-position:  -33px -33px;" data-emoji="130"></div>
                                <div class="b6 item" style="background-position: -97px -65px;" data-emoji="131"></div>
                                <div class="b6 item" style="background-position: -129px -97px;" data-emoji="132"></div>
                                <div class="b7 item" style="background-position: -97px -97px;" data-emoji="133"></div>
                                <div class="b5 item" style="background-position: -97px -65px;" data-emoji="134"></div>
                                <div class="b82 item" style="background-position: -64px -33px;" data-emoji="135"></div>
                                <div class="b8 item" style="background-position: -64px -97px;" data-emoji="136"></div>
                                <div class="b8 item" style="background-position: -129px -129px;" data-emoji="137"></div>
                                <div class="b4 item" style="background-position: -97px -97px;" data-emoji="138"></div>
                                <div class="b82 item" style="background-position: -33px 0px;" data-emoji="139"></div>
                                <div class="b58 item" style="background-position: -129px -97px;" data-emoji="140"></div>
                                <div class="b9 item" style="background-position: -129px -0px;" data-emoji="141"></div>
                                <div class="b8 item" style="background-position: -97px -97px;" data-emoji="142"></div>
                                <div class="b10 item" style="background-position: 0px -33px;" data-emoji="143"></div>
                                <div class="b72 item" style="background-position: -33px -33px;" data-emoji="144"></div>
                                <div class="b11 item" style="background-position: -129px -65px;" data-emoji="145"></div>
                                <div class="b11 item" style="background-position: -97px -33px;" data-emoji="146"></div>
                                <div class="b9 item" style="background-position: 0px -33px;" data-emoji="147"></div>
                                <div class="b92 item" style="background-position: -129px -0px;" data-emoji="148"></div>
                                <div class="b57 item" style="background-position: -97px -65px;" data-emoji="149"></div>
                                <div class="b6 item" style="background-position: -33px -65px;" data-emoji="150"></div>
                                <div class="b11 item" style="background-position: 0px -129px;" data-emoji="151"></div>
                                <div class="b6 item" style="background-position: -64px -65px;" data-emoji="152"></div>
                                <div class="b12 item" style="background-position: -129px -129px;" data-emoji="153"></div>
                                <div class="b43 item" style="background-position: -33px -129px;" data-emoji="154"></div>
                                <div class="b6 item" style="background-position: -0px -33px;" data-emoji="155"></div>
                                <div class="b13 item" style="background-position: -64px -129px;" data-emoji="156"></div>
                                <div class="b12 item" style="background-position: -64px -129px;" data-emoji="157"></div>
                                <div class="b12 item" style="background-position: -33px -129px;" data-emoji="158"></div>
                                <div class="b14 item" style="background-position: -33px -129px;" data-emoji="159"></div>
                                <div class="b15 item" style="background-position: -33px -129px;" data-emoji="160"></div>
                                <div class="b13 item" style="background-position: -97px -129px;" data-emoji="161"></div>
                                <div class="b13 item" style="background-position: -129px -129px;" data-emoji="162"></div>
                                <div class="b53 item" style="background-position: -64px -65px;" data-emoji="163"></div>
                                <div class="b16 item" style="background-position: -33px -33px;" data-emoji="164"></div>
                                <div class="b17 item" style="background-position: -129px -97px;" data-emoji="165"></div>
                                <div class="b16 item" style="background-position: 0px 0px;" data-emoji="166"></div>
                                <div class="b22 item" style="background-position: -97px -33px;" data-emoji="167"></div>
                                <div class="b94 item" style="background-position: -64px -129px;" data-emoji="168"></div>
                                <div class="b16 item" style="background-position: -64px -65px;" data-emoji="169"></div>
                                <div class="b18 item" style="background-position: -33px -97px;" data-emoji="170"></div>
                                <div class="b19 item" style="background-position: -97px -129px;" data-emoji="171"></div>
                                <div class="b18 item" style="background-position:  0px -97px;" data-emoji="172"></div>
                                <div class="b19 item" style="background-position: -64px -129px;" data-emoji="173"></div>
                                <div class="b21 item" style="background-position: 0px  -129px;" data-emoji="174"></div>
                                <div class="b21 item" style="background-position: -33px -129px;" data-emoji="175"></div>
                                <div class="b18 item" style="background-position: -97px -97px;" data-emoji="176"></div>
                                <div class="b23 item" style="background-position: -129px -129px;" data-emoji="177"></div>
                                <div class="b18 item" style="background-position: -64px -97px;" data-emoji="178"></div>
                                <div class="b19 item" style="background-position: -129px -129px;" data-emoji="179"></div>
                                <div class="b25 item" style="background-position: -33px -33px;" data-emoji="180"></div>
                                <div class="b53 item" style="background-position: -33px -33px;" data-emoji="181"></div>
                                <div class="b25 item" style="background-position: 0px 0px;" data-emoji="182"></div>
                                <div class="b53 item" style="background-position: 0px 0px;" data-emoji="183"></div>
                            </div>
                        </div>
                    </div>

                    <div class="field-ai">
                        <div class="head">
                            <svg class="icon-ai" width="25px" height="19px" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill="#000000" class="bi bi-stars">
                                <path d="M7.657 6.247c.11-.33.576-.33.686 0l.645 1.937a2.89 2.89 0 0 0 1.829 1.828l1.936.645c.33.11.33.576 0 .686l-1.937.645a2.89 2.89 0 0 0-1.828 1.829l-.645 1.936a.361.361 0 0 1-.686 0l-.645-1.937a2.89 2.89 0 0 0-1.828-1.828l-1.937-.645a.361.361 0 0 1 0-.686l1.937-.645a2.89 2.89 0 0 0 1.828-1.828l.645-1.937zM3.794 1.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387A1.734 1.734 0 0 0 4.593 5.69l-.387 1.162a.217.217 0 0 1-.412 0L3.407 5.69A1.734 1.734 0 0 0 2.31 4.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387A1.734 1.734 0 0 0 3.407 2.31l.387-1.162zM10.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732L9.1 2.137a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L10.863.1z" />
                            </svg>
                            <b style="font-size: 15px;float: left;margin-left: 10px;"><?php echo $this->lang->line('messenger_response_ai'); ?></b>
                            <svg class="close-ai" xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 14 14" fill="none">
                                <path d="M12.9999 1C12.8124 0.81253 12.5581 0.707214 12.2929 0.707214C12.0278 0.707214 11.7735 0.81253 11.5859 1L6.99994 5.586L2.41394 1C2.22641 0.81253 1.9721 0.707214 1.70694 0.707214C1.44178 0.707214 1.18747 0.81253 0.99994 1C0.812469 1.18753 0.707153 1.44184 0.707153 1.707C0.707153 1.97217 0.812469 2.22647 0.99994 2.414L5.58594 7L0.99994 11.586C0.812469 11.7735 0.707153 12.0278 0.707153 12.293C0.707153 12.5582 0.812469 12.8125 0.99994 13C1.18747 13.1875 1.44178 13.2928 1.70694 13.2928C1.9721 13.2928 2.22641 13.1875 2.41394 13L6.99994 8.414L11.5859 13C11.7735 13.1875 12.0278 13.2928 12.2929 13.2928C12.5581 13.2928 12.8124 13.1875 12.9999 13C13.1874 12.8125 13.2927 12.5582 13.2927 12.293C13.2927 12.0278 13.1874 11.7735 12.9999 11.586L8.41394 7L12.9999 2.414C13.1874 2.22647 13.2927 1.97217 13.2927 1.707C13.2927 1.44184 13.1874 1.18753 12.9999 1Z" fill="#666666"></path>
                            </svg>
                        </div>
                        <div class="items"></div>
                    </div>

                    <div class="input" id="bottomEntryRectangle" style="display: none;">
                        <div class="message-send">

                            <div class="file-upload" id="file-upload">
                                <svg width="24" height="24" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M23 11.5H13V1.5C13 1.23478 12.8946 0.98043 12.7071 0.792893C12.5196 0.605357 12.2652 0.5 12 0.5V0.5C11.7348 0.5 11.4804 0.605357 11.2929 0.792893C11.1054 0.98043 11 1.23478 11 1.5V11.5H1C0.734784 11.5 0.48043 11.6054 0.292893 11.7929C0.105357 11.9804 0 12.2348 0 12.5H0C0 12.7652 0.105357 13.0196 0.292893 13.2071C0.48043 13.3946 0.734784 13.5 1 13.5H11V23.5C11 23.7652 11.1054 24.0196 11.2929 24.2071C11.4804 24.3946 11.7348 24.5 12 24.5C12.2652 24.5 12.5196 24.3946 12.7071 24.2071C12.8946 24.0196 13 23.7652 13 23.5V13.5H23C23.2652 13.5 23.5196 13.3946 23.7071 13.2071C23.8946 13.0196 24 12.7652 24 12.5C24 12.2348 23.8946 11.9804 23.7071 11.7929C23.5196 11.6054 23.2652 11.5 23 11.5Z" fill="#666666" />
                                </svg>

                                <div id="box-clip" class="box-clip">

                                    <?php if (isset($waba) == 1) { ?>

                                        <div id="send-catalog" class="verify_send_catalog" style="float: left; width: 100%; border-radius: 50px; margin-bottom: 10px;">
                                            <img src="assets/img/iconCatalog.png" width="55px">
                                            <div class="title-send-catalog">
                                                <span><?php echo $this->lang->line('messenger_send_catalog'); ?></span>
                                            </div>
                                        </div>

                                    <?php } ?>

                                    <div id="send-document" style="float: left; width: 100%; border-radius: 50px; margin-bottom: 10px;">
                                        <img src="assets/img/document.svg" width="55px">
                                        <div class="title-send-document">
                                            <span><?php echo $this->lang->line('messenger_send_document'); ?></span>
                                        </div>
                                    </div>

                                    <div id="send-image" style="float: left; width: 100%; border-radius: 50px; margin-bottom: 10px;">
                                        <img src="assets/img/foto.svg" width="55px">
                                        <div class="title-send-image">
                                            <span><?php echo $this->lang->line('messenger_send_photo_video'); ?></span>
                                        </div>
                                    </div>

                                    <div id="send-template" style="float: left; width: 100%; border-radius: 50px; margin-bottom: 10px;">
                                        <img src="assets/icons/kanban/chat_green.svg" width="55px" style="border-radius: 40px;">
                                        <div class="title-send-template">
                                            <span><?php echo $this->lang->line('messenger_send_template'); ?></span>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="characters-limit">
                                <span></span>
                            </div>

                            <div class="ai-bg-box" id="bgboxAI"></div>
                            <div class="list-ai"></div>

                            <div class="text">
                                <div class="input-text" placeholder="<?php echo $this->lang->line('messenger_input_placeholder'); ?>" contenteditable="true" data-tab="1" dir="ltr" spellcheck="true"></div>
                                <div id="emoji">
                                    <svg xmlns="https://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                        <path d="M12 24C9.62663 24 7.30655 23.2962 5.33316 21.9776C3.35977 20.6591 1.8217 18.7849 0.913451 16.5922C0.00519943 14.3995 -0.232441 11.9867 0.230582 9.65891C0.693605 7.33114 1.83649 5.19295 3.51472 3.51472C5.19295 1.83649 7.33115 0.693597 9.65892 0.230574C11.9867 -0.232448 14.3995 0.0051918 16.5922 0.913443C18.7849 1.82169 20.6591 3.35976 21.9776 5.33315C23.2962 7.30654 24 9.62662 24 12C23.9966 15.1815 22.7312 18.2318 20.4815 20.4815C18.2318 22.7312 15.1815 23.9966 12 24ZM12 2C10.0222 2 8.08879 2.58649 6.4443 3.6853C4.79981 4.78412 3.51809 6.3459 2.76121 8.17316C2.00433 10.0004 1.8063 12.0111 2.19215 13.9509C2.578 15.8907 3.53041 17.6725 4.92894 19.0711C6.32746 20.4696 8.10929 21.422 10.0491 21.8079C11.9889 22.1937 13.9996 21.9957 15.8268 21.2388C17.6541 20.4819 19.2159 19.2002 20.3147 17.5557C21.4135 15.9112 22 13.9778 22 12C21.9971 9.34873 20.9426 6.80688 19.0679 4.93215C17.1931 3.05741 14.6513 2.00291 12 2ZM8.00001 14C7.84394 13.9993 7.68988 14.0351 7.55015 14.1046C7.41042 14.1742 7.2889 14.2754 7.19531 14.4003C7.10171 14.5252 7.03865 14.6702 7.01116 14.8239C6.98368 14.9775 6.99253 15.1354 7.03701 15.285C7.3953 16.3325 8.06136 17.2475 8.94809 17.9103C9.83482 18.5731 10.9009 18.9529 12.007 19C13.1135 18.9564 14.1808 18.5778 15.0675 17.9145C15.9542 17.2511 16.6187 16.3342 16.973 15.285C17.013 15.1356 17.0186 14.9791 16.9893 14.8272C16.96 14.6754 16.8966 14.5322 16.8038 14.4084C16.7111 14.2847 16.5914 14.1836 16.4539 14.1129C16.3164 14.0421 16.1646 14.0035 16.01 14H8.00001ZM6.00001 10C6.00001 11 6.89501 11 8.00001 11C9.10501 11 10 11 10 10C10 9.46957 9.78929 8.96086 9.41422 8.58578C9.03915 8.21071 8.53044 8 8.00001 8C7.46957 8 6.96086 8.21071 6.58579 8.58578C6.21072 8.96086 6.00001 9.46957 6.00001 10ZM14 10C14 11 14.895 11 16 11C17.105 11 18 11 18 10C18 9.46957 17.7893 8.96086 17.4142 8.58578C17.0391 8.21071 16.5304 8 16 8C15.4696 8 14.9609 8.21071 14.5858 8.58578C14.2107 8.96086 14 9.46957 14 10Z" fill="#666666" />
                                    </svg>
                                </div>
                            </div>

                            <div id="record-audio">
                                <svg xmlns="https://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 25">
                                    <path d="M11.9998 20.5C14.1208 20.4976 16.1543 19.654 17.654 18.1542C19.1538 16.6544 19.9974 14.621 19.9998 12.5V8.49999C19.9998 6.37826 19.157 4.34343 17.6567 2.84314C16.1564 1.34285 14.1215 0.499992 11.9998 0.499992C9.87809 0.499992 7.84325 1.34285 6.34296 2.84314C4.84267 4.34343 3.99982 6.37826 3.99982 8.49999V12.5C4.0022 14.621 4.84582 16.6544 6.34559 18.1542C7.84537 19.654 9.87882 20.4976 11.9998 20.5ZM11.9998 2.49999C13.4164 2.50262 14.7864 3.00586 15.8678 3.92079C16.9493 4.83572 17.6725 6.10345 17.9098 7.49999H14.9998C14.7346 7.49999 14.4802 7.60535 14.2927 7.79289C14.1052 7.98042 13.9998 8.23478 13.9998 8.49999C13.9998 8.76521 14.1052 9.01956 14.2927 9.2071C14.4802 9.39464 14.7346 9.49999 14.9998 9.49999H17.9998V11.5H14.9998C14.7346 11.5 14.4802 11.6053 14.2927 11.7929C14.1052 11.9804 13.9998 12.2348 13.9998 12.5C13.9998 12.7652 14.1052 13.0196 14.2927 13.2071C14.4802 13.3946 14.7346 13.5 14.9998 13.5H17.9098C17.6748 14.8975 16.9521 16.1665 15.8702 17.0817C14.7882 17.9969 13.4169 18.499 11.9998 18.499C10.5827 18.499 9.21143 17.9969 8.12946 17.0817C7.0475 16.1665 6.32485 14.8975 6.08982 13.5H8.99982C9.26503 13.5 9.51939 13.3946 9.70692 13.2071C9.89446 13.0196 9.99982 12.7652 9.99982 12.5C9.99982 12.2348 9.89446 11.9804 9.70692 11.7929C9.51939 11.6053 9.26503 11.5 8.99982 11.5H5.99982V9.49999H8.99982C9.26503 9.49999 9.51939 9.39464 9.70692 9.2071C9.89446 9.01956 9.99982 8.76521 9.99982 8.49999C9.99982 8.23478 9.89446 7.98042 9.70692 7.79289C9.51939 7.60535 9.26503 7.49999 8.99982 7.49999H6.08982C6.32709 6.10345 7.05034 4.83572 8.13179 3.92079C9.21323 3.00586 10.5833 2.50262 11.9998 2.49999Z" fill="#666666" />
                                    <path d="M23 12.5C22.7348 12.5 22.4804 12.6054 22.2929 12.7929C22.1054 12.9804 22 13.2348 22 13.5C21.9974 15.8861 21.0483 18.1738 19.361 19.861C17.6738 21.5483 15.3861 22.4974 13 22.5H11C8.61395 22.4971 6.32645 21.5479 4.63925 19.8607C2.95206 18.1735 2.00291 15.8861 2 13.5C2 13.2348 1.89464 12.9804 1.70711 12.7929C1.51957 12.6054 1.26522 12.5 1 12.5C0.734784 12.5 0.48043 12.6054 0.292893 12.7929C0.105357 12.9804 0 13.2348 0 13.5C0.00344047 16.4163 1.16347 19.2122 3.22563 21.2744C5.28778 23.3365 8.08367 24.4966 11 24.5H13C15.9163 24.4966 18.7122 23.3365 20.7744 21.2744C22.8365 19.2122 23.9966 16.4163 24 13.5C24 13.2348 23.8946 12.9804 23.7071 12.7929C23.5196 12.6054 23.2652 12.5 23 12.5Z" fill="#666666" />
                                </svg>
                            </div>
                            <div id="stop-record" style="display: none;">
                                <svg xmlns="https://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 16 22" class="x1kky2od">
                                    <path d="M5,0,3,2H0V4H16V2H13L11,0ZM15,5H1V19.5A2.5,2.5,0,0,0,3.5,22h9A2.5,2.5,0,0,0,15,19.5Z" fill="#666666"></path>
                                </svg>
                            </div>

                            <div id="recording-time" style="display: none;">
                                0:00
                            </div>

                            <div id="ok-record" style="display: none;">
                                <svg xmlns="https://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 35 35">
                                    <path d="M17.5,0h0A17.51,17.51,0,0,1,35,17.5h0A17.51,17.51,0,0,1,17.5,35h0A17.51,17.51,0,0,1,0,17.5H0A17.51,17.51,0,0,1,17.5,0Z" fill="#666666"></path>
                                    <path class="arrow" d="M25.64,18.55,11.2,24.93a.86.86,0,0,1-1.13-.44.83.83,0,0,1-.06-.44l.48-4.11a1.36,1.36,0,0,1,1.24-1.19l7.51-.6a.16.16,0,0,0,.14-.16.16.16,0,0,0-.14-.14l-7.51-.6a1.36,1.36,0,0,1-1.24-1.19L10,12a.84.84,0,0,1,.74-.94.87.87,0,0,1,.45.06l14.44,6.38a.61.61,0,0,1,.31.79A.59.59,0,0,1,25.64,18.55Z" fill="#ffffff"></path>
                                </svg>
                            </div>

                            <div class="icon-ia" id="icon-IA" style="display: none;">
                                <svg xmlns="https://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 16 16" class="bi bi-stars">
                                    <path d="M7.657 6.247c.11-.33.576-.33.686 0l.645 1.937a2.89 2.89 0 0 0 1.829 1.828l1.936.645c.33.11.33.576 0 .686l-1.937.645a2.89 2.89 0 0 0-1.828 1.829l-.645 1.936a.361.361 0 0 1-.686 0l-.645-1.937a2.89 2.89 0 0 0-1.828-1.828l-1.937-.645a.361.361 0 0 1 0-.686l1.937-.645a2.89 2.89 0 0 0 1.828-1.828l.645-1.937zM3.794 1.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387A1.734 1.734 0 0 0 4.593 5.69l-.387 1.162a.217.217 0 0 1-.412 0L3.407 5.69A1.734 1.734 0 0 0 2.31 4.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387A1.734 1.734 0 0 0 3.407 2.31l.387-1.162zM10.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732L9.1 2.137a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L10.863.1z" fill="#666666" />
                                </svg>
                            </div>

                            <div style="display: none;">
                                <svg xmlns="https://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                    <path d="M1.101 21.757L23.8 12.028 1.101 2.3l.011 7.912 13.623 1.816-13.623 1.817-.011 7.912z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="message-forward">
                            <span id="close-message-forward" class="fas fa-close close-message-forward"></span>

                            <div class="selected-message-forward">
                                <span id="count-message-forward">0</span> <span id="select-message-forward"><?php echo $this->lang->line('messenger_selected'); ?></span>
                            </div>

                            <span id="send-message-forward" class="fas fa-share send-message-forward" name="Chat"></span>
                        </div>
                    </div>

                    <div class="inputMsg"></div>

                    <?php echo form_open_multipart('upload/file'); ?>
                    <input id="arq" name="arq" type="file" accept="image/*" style="display:none" onclick="this.value=null" ; onchange="onFileUpload();" />
                    </form>

                </div>

                <div id="alert-credit-minimum" class="alert-credit-minimum">
                    <span>
                        AVISO: O crédito de conversas está abaixo do valor mínimo definido!
                    </span>
                </div>
            </div>

        </div>


        <!-- MODAL DE EQUIPE -->
        <div class="team-bg-box" id="bgboxTeam"></div>
        <div class="team-modal" id="modalQueryTeam">

            <div class="team-header">
                <div class="inner-header">
                    <div class="title"><span><?php echo $this->lang->line('messenger_modal_team_title'); ?></span></div>
                    <div class="close def__closeModal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 22 22" class="icon-close-right">
                            <path d="M21.7316 0.268754C21.5597 0.0969053 21.3266 0.000366211 21.0835 0.000366211C20.8404 0.000366211 20.6073 0.0969053 20.4354 0.268754L11.0002 9.704L1.56492 0.268754C1.39302 0.0969053 1.1599 0.000366211 0.916837 0.000366211C0.67377 0.000366211 0.440654 0.0969053 0.268754 0.268754C0.0969053 0.440654 0.000366211 0.67377 0.000366211 0.916837C0.000366211 1.1599 0.0969053 1.39302 0.268754 1.56492L9.704 11.0002L0.268754 20.4354C0.0969053 20.6073 0.000366211 20.8404 0.000366211 21.0835C0.000366211 21.3266 0.0969053 21.5597 0.268754 21.7316C0.440654 21.9034 0.67377 22 0.916837 22C1.1599 22 1.39302 21.9034 1.56492 21.7316L11.0002 12.2963L20.4354 21.7316C20.6073 21.9034 20.8404 22 21.0835 22C21.3266 22 21.5597 21.9034 21.7316 21.7316C21.9034 21.5597 22 21.3266 22 21.0835C22 20.8404 21.9034 20.6073 21.7316 20.4354L12.2963 11.0002L21.7316 1.56492C21.9034 1.39302 22 1.1599 22 0.916837C22 0.67377 21.9034 0.440654 21.7316 0.268754Z" fill="#666666"></path>
                        </svg>
                    </div>
                </div>
                <div class="team-search">
                    <input type="text" id="teamSearch" placeholder="<?php echo $this->lang->line('messenger_modal_team_placeholder'); ?>">
                    <img class="icon-search" id="teamIconSearch" alt="search">
                    <img class="icon-close" src="<?php echo base_url("assets/icons/messenger/close3.svg") ?>" alt="">
                </div>
            </div>
            <div class="team-body"></div>
            <div class="team-footer"></div>

        </div>


        <!-- MODAL DE PERFIL DE USUÁRIO -->
        <div class="profile-user-bg-box" id="bgboxProfileUser"></div>
        <div class="profile-user-modal" id="modalProfileUser">
            <div>
                <img class="image" id="imgPreviewsProfile" src="" alt="TALKALL">
            </div>
        </div>


        <!-- Janela de informações do contato -->
        <div class="option window__option" style="display: none;">
            <div class="head">
                <div id="close_settings_toolbar" class="left-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" class="icon-close-right">
                        <path d="M21.7316 0.268754C21.5597 0.0969053 21.3266 0.000366211 21.0835 0.000366211C20.8404 0.000366211 20.6073 0.0969053 20.4354 0.268754L11.0002 9.704L1.56492 0.268754C1.39302 0.0969053 1.1599 0.000366211 0.916837 0.000366211C0.67377 0.000366211 0.440654 0.0969053 0.268754 0.268754C0.0969053 0.440654 0.000366211 0.67377 0.000366211 0.916837C0.000366211 1.1599 0.0969053 1.39302 0.268754 1.56492L9.704 11.0002L0.268754 20.4354C0.0969053 20.6073 0.000366211 20.8404 0.000366211 21.0835C0.000366211 21.3266 0.0969053 21.5597 0.268754 21.7316C0.440654 21.9034 0.67377 22 0.916837 22C1.1599 22 1.39302 21.9034 1.56492 21.7316L11.0002 12.2963L20.4354 21.7316C20.6073 21.9034 20.8404 22 21.0835 22C21.3266 22 21.5597 21.9034 21.7316 21.7316C21.9034 21.5597 22 21.3266 22 21.0835C22 20.8404 21.9034 20.6073 21.7316 20.4354L12.2963 11.0002L21.7316 1.56492C21.9034 1.39302 22 1.1599 22 0.916837C22 0.67377 21.9034 0.440654 21.7316 0.268754Z" fill="black" />
                    </svg>
                </div>
                <span><?php echo $this->lang->line('messenger_window_option_information'); ?></span>
                <div class="icon-contact-edit right-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="29" height="25" viewBox="0 0 29 25" id="contact-edit">
                        <path d="M27.291 8.70929C26.8362 8.2551 26.2196 8 25.5768 8C24.934 8 24.3174 8.2551 23.8626 8.70929L12.9802 19.5916C12.6686 19.9015 12.4215 20.2701 12.2533 20.6761C12.085 21.0821 11.9989 21.5174 12 21.9569V23.3306C12 23.508 12.0705 23.6782 12.196 23.8037C12.3215 23.9292 12.4917 23.9997 12.6691 23.9997H14.0428C14.4822 24.0009 14.9176 23.915 15.3236 23.7468C15.7296 23.5787 16.0982 23.3317 16.4081 23.0201L27.291 12.1371C27.745 11.6823 28 11.0659 28 10.4232C28 9.78055 27.745 9.16415 27.291 8.70929ZM15.462 22.074C15.0846 22.4488 14.5747 22.6599 14.0428 22.6614H13.3382V21.9569C13.3375 21.6932 13.3892 21.432 13.4901 21.1884C13.5911 20.9448 13.7394 20.7236 13.9264 20.5377L22.1851 12.279L23.724 13.8179L15.462 22.074ZM26.3443 11.191L24.6675 12.8684L23.1285 11.3328L24.806 9.65541C24.907 9.55458 25.027 9.47464 25.1589 9.42015C25.2908 9.36567 25.4322 9.33771 25.575 9.33786C25.7177 9.33802 25.859 9.36629 25.9909 9.42106C26.1227 9.47583 26.2424 9.55603 26.3433 9.65708C26.4441 9.75813 26.524 9.87804 26.5785 10.01C26.633 10.1419 26.661 10.2833 26.6608 10.4261C26.6606 10.5688 26.6324 10.7101 26.5776 10.8419C26.5228 10.9738 26.4426 11.0935 26.3416 11.1943L26.3443 11.191Z" fill="black" />
                        <g clip-path="url(#clip0_96_402)">
                            <path d="M12 11.9999C13.1867 11.9999 14.3467 11.648 15.3334 10.9888C16.3201 10.3295 17.0892 9.3924 17.5433 8.29604C17.9974 7.19969 18.1162 5.99329 17.8847 4.8294C17.6532 3.66551 17.0818 2.59642 16.2426 1.7573C15.4035 0.918186 14.3344 0.346741 13.1705 0.11523C12.0067 -0.116281 10.8003 0.00253868 9.7039 0.456664C8.60754 0.91079 7.67047 1.67983 7.01118 2.66652C6.35189 3.65321 6 4.81325 6 5.99994C6.00159 7.59075 6.63424 9.11595 7.75911 10.2408C8.88399 11.3657 10.4092 11.9984 12 11.9999ZM12 1.99994C12.7911 1.99994 13.5645 2.23454 14.2223 2.67406C14.8801 3.11359 15.3928 3.7383 15.6955 4.46921C15.9983 5.20011 16.0775 6.00438 15.9231 6.7803C15.7688 7.55623 15.3878 8.26896 14.8284 8.82837C14.269 9.38778 13.5563 9.76874 12.7804 9.92308C12.0044 10.0774 11.2002 9.99821 10.4693 9.69546C9.73836 9.39271 9.11365 8.88002 8.67412 8.22222C8.2346 7.56443 8 6.79107 8 5.99994C8 4.93908 8.42143 3.92166 9.17157 3.17151C9.92172 2.42137 10.9391 1.99994 12 1.99994Z" fill="black" />
                            <mask id="mask0_96_402" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="3" y="14" width="18" height="11">
                                <path d="M12 14.0005C9.61386 14.0031 7.32622 14.9522 5.63896 16.6395C3.95171 18.3267 3.00265 20.6144 3 23.0005C3 23.2657 3.10536 23.5201 3.29289 23.7076C3.48043 23.8951 3.73478 24.0005 4 24.0005C4.26522 24.0005 4.51957 23.8951 4.70711 23.7076C4.89464 23.5201 5 23.2657 5 23.0005C5 21.144 5.7375 19.3635 7.05025 18.0507C8.36301 16.738 10.1435 16.0005 12 16.0005C13.8565 16.0005 15.637 16.738 16.9497 18.0507C18.2625 19.3635 19 21.144 19 23.0005C19 23.2657 19.1054 23.5201 19.2929 23.7076C19.4804 23.8951 19.7348 24.0005 20 24.0005C20.2652 24.0005 20.5196 23.8951 20.7071 23.7076C20.8946 23.5201 21 23.2657 21 23.0005C20.9974 20.6144 20.0483 18.3267 18.361 16.6395C16.6738 14.9522 14.3861 14.0031 12 14.0005Z" fill="black" />
                            </mask>
                            <g mask="url(#mask0_96_402)">
                                <path d="M16 14L13.5 16.5L7 26L1 24L4.5 -0.5L13.5 -1.5L21.5 4L16 14Z" fill="black" />
                            </g>
                        </g>
                    </svg>
                </div>
            </div>

            <div class="body">
                <div class="profile">
                    <img class="avatar" src="assets/img/avatar.svg">
                    <div class="icon-channel" id="iconChannel"></div>
                </div>
                <div class="info">
                    <div class="name">
                        <div class="div-svg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 18 19">
                                <path d="M9.06618 9.95762C9.96274 9.95762 10.8392 9.69176 11.5846 9.19365C12.3301 8.69555 12.9111 7.98758 13.2542 7.15926C13.5973 6.33095 13.6871 5.41949 13.5122 4.54016C13.3373 3.66082 12.9055 2.8531 12.2716 2.21913C11.6376 1.58517 10.8299 1.15343 9.95054 0.978523C9.07121 0.803612 8.15975 0.893383 7.33144 1.23648C6.50312 1.57958 5.79515 2.1606 5.29705 2.90606C4.79894 3.65153 4.53308 4.52796 4.53308 5.42452C4.53428 6.6264 5.01226 7.77872 5.86212 8.62858C6.71198 9.47844 7.8643 9.95642 9.06618 9.95762ZM9.06618 2.40245C9.66389 2.40245 10.2482 2.57969 10.7452 2.91176C11.2421 3.24383 11.6295 3.71581 11.8582 4.26802C12.0869 4.82024 12.1468 5.42787 12.0302 6.01409C11.9136 6.60032 11.6257 7.1388 11.2031 7.56144C10.7805 7.98409 10.242 8.27191 9.65576 8.38852C9.06953 8.50512 8.4619 8.44528 7.90969 8.21654C7.35748 7.98781 6.88549 7.60046 6.55342 7.10349C6.22136 6.60651 6.04411 6.02223 6.04411 5.42452C6.04411 4.62302 6.36251 3.85434 6.92926 3.2876C7.496 2.72085 8.26468 2.40245 9.06618 2.40245Z" fill="black" />
                                <path d="M9.06619 11.4692C7.26342 11.4712 5.53507 12.1883 4.26032 13.463C2.98557 14.7378 2.26854 16.4661 2.26654 18.2689C2.26654 18.4693 2.34614 18.6614 2.48783 18.8031C2.62951 18.9448 2.82168 19.0244 3.02206 19.0244C3.22243 19.0244 3.4146 18.9448 3.55629 18.8031C3.69797 18.6614 3.77757 18.4693 3.77757 18.2689C3.77757 16.8663 4.33476 15.5211 5.32657 14.5293C6.31838 13.5375 7.66356 12.9803 9.06619 12.9803C10.4688 12.9803 11.814 13.5375 12.8058 14.5293C13.7976 15.5211 14.3548 16.8663 14.3548 18.2689C14.3548 18.4693 14.4344 18.6614 14.5761 18.8031C14.7178 18.9448 14.9099 19.0244 15.1103 19.0244C15.3107 19.0244 15.5029 18.9448 15.6446 18.8031C15.7862 18.6614 15.8658 18.4693 15.8658 18.2689C15.8638 16.4661 15.1468 14.7378 13.8721 13.463C12.5973 12.1883 10.869 11.4712 9.06619 11.4692Z" fill="black" />
                            </svg>
                        </div>
                        <label id="name"></label>
                        <input id="full_name_info" type="hidden" value="">
                    </div>

                    <div class="number" id="optionNumber" style="display: none;">
                        <div class="div-svg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 18 19">
                                <mask id="mask0_96_436" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="20" height="19">
                                    <g clip-path="url(#clip0_96_436)">
                                        <path d="M18.1171 2.41995V5.44202C18.1171 5.64239 18.0375 5.83456 17.8959 5.97625C17.7542 6.11794 17.562 6.19753 17.3616 6.19753C17.1612 6.19753 16.9691 6.11794 16.8274 5.97625C16.6857 5.83456 16.6061 5.64239 16.6061 5.44202V3.50563L12.6019 7.48871C12.459 7.62599 12.2679 7.70164 12.0698 7.69938C11.8717 7.69712 11.6824 7.61713 11.5427 7.47663C11.403 7.33614 11.324 7.14639 11.3229 6.94826C11.3218 6.75013 11.3985 6.55948 11.5366 6.41739L15.5559 2.41995H13.584C13.3837 2.41995 13.1915 2.34035 13.0498 2.19867C12.9081 2.05698 12.8285 1.86481 12.8285 1.66444C12.8285 1.46406 12.9081 1.27189 13.0498 1.13021C13.1915 0.988518 13.3837 0.90892 13.584 0.90892H16.6061C17.0069 0.90892 17.3912 1.06812 17.6746 1.35149C17.9579 1.63486 18.1171 2.0192 18.1171 2.41995ZM17.4334 13.5555C17.8712 13.9946 18.1171 14.5893 18.1171 15.2093C18.1171 15.8294 17.8712 16.4241 17.4334 16.8632L16.7444 17.6565C10.5559 23.5782 -4.50076 8.52604 1.33183 2.31947L2.19992 1.56395C2.6397 1.13842 3.22919 0.902922 3.84112 0.908293C4.45304 0.913665 5.03831 1.15948 5.47055 1.59266C5.49322 1.61608 6.89319 3.43537 6.89319 3.43537C7.30847 3.87164 7.53971 4.45113 7.53887 5.05346C7.53802 5.65578 7.30516 6.23463 6.88865 6.66973L6.01301 7.77052C6.49718 8.94694 7.20904 10.0161 8.10769 10.9166C9.00633 11.817 10.074 12.531 11.2495 13.0176L12.3563 12.1374C12.7914 11.7211 13.3701 11.4884 13.9722 11.4875C14.5744 11.4867 15.1537 11.7178 15.5899 12.1329C15.5899 12.1329 17.41 13.5328 17.4334 13.5555ZM16.3946 14.654C16.3946 14.654 14.5866 13.2624 14.5632 13.2397C14.4075 13.0854 14.1972 12.9988 13.978 12.9988C13.7589 12.9988 13.5485 13.0854 13.3929 13.2397C13.3725 13.2593 11.8486 14.4742 11.8486 14.4742C11.7459 14.556 11.6237 14.6095 11.494 14.6297C11.3643 14.6498 11.2316 14.6358 11.109 14.5891C9.58525 14.0224 8.20127 13.1346 7.05098 11.9859C5.90068 10.8372 5.01099 9.45439 4.44229 7.93144C4.39254 7.80763 4.37647 7.67285 4.39571 7.5408C4.41495 7.40876 4.46882 7.28417 4.55184 7.17971C4.55184 7.17971 5.76596 5.65583 5.78636 5.63543C5.94068 5.47978 6.02727 5.26947 6.02727 5.05028C6.02727 4.83109 5.94068 4.62078 5.78636 4.46514C5.76369 4.44247 4.37203 2.63376 4.37203 2.63376C4.21415 2.49201 4.00802 2.41602 3.79591 2.42135C3.58379 2.42669 3.38175 2.51296 3.2312 2.66247L2.36311 3.41799C-1.90102 8.5419 11.149 20.8689 15.639 16.6259L16.3281 15.8326C16.4905 15.6836 16.5884 15.477 16.6008 15.2568C16.6132 15.0367 16.5392 14.8204 16.3946 14.654Z" fill="black" />
                                    </g>
                                </mask>
                                <g mask="url(#mask0_96_436)">
                                    <path d="M8.68844 -0.241798L-1.13328 -0.997314L-1.88879 11.8465L6.42189 20.1571H18.5102L20.0212 12.602L15.8658 9.57992L8.68844 9.20216V-0.241798Z" fill="black" />
                                </g>
                            </svg>
                        </div>
                        <label id="talkall_id"></label>
                    </div>

                    <div class="number" id="optionChannel" style="display: none;">
                        <div class="div-svg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 18 20">
                                <path d="M13.4422 6.34044C13.1065 4.89687 12.252 3.62697 11.0413 2.77213C9.83053 1.91729 8.3479 1.53711 6.8752 1.70385C5.4025 1.87059 4.0424 2.57263 3.05343 3.67653C2.06446 4.78042 1.51557 6.20921 1.51108 7.69131C1.51043 8.65715 1.74373 9.60879 2.19104 10.4648C1.37594 10.9016 0.730176 11.5983 0.356361 12.4441C-0.0174539 13.2899 -0.0978813 14.2365 0.127857 15.1332C0.353595 16.03 0.872551 16.8257 1.60227 17.3937C2.33199 17.9618 3.23062 18.2697 4.15539 18.2685H12.0883C13.5684 18.2617 14.9946 17.7119 16.0963 16.7235C17.1981 15.7351 17.8989 14.3768 18.0658 12.9061C18.2328 11.4354 17.8542 9.95461 17.002 8.74443C16.1498 7.53426 14.8831 6.67886 13.4422 6.34044ZM12.0883 16.7575H4.15539C3.51201 16.7594 2.89011 16.5261 2.40676 16.1015C1.9234 15.6769 1.61193 15.0902 1.53096 14.4519C1.44999 13.8137 1.60512 13.1678 1.96713 12.6359C2.32915 12.1041 2.87309 11.7229 3.49658 11.5641C3.61648 11.5332 3.72697 11.4734 3.81831 11.3898C3.90965 11.3062 3.97904 11.2014 4.02038 11.0847C4.06173 10.968 4.07375 10.8429 4.0554 10.7204C4.03704 10.598 3.98887 10.4819 3.91513 10.3825C3.33425 9.60559 3.02091 8.66133 3.02211 7.69131C3.03073 6.54744 3.47148 5.44913 4.25598 4.61662C5.04047 3.78412 6.1107 3.27898 7.25204 3.20251C8.39337 3.12603 9.52142 3.48389 10.41 4.2043C11.2985 4.92471 11.8818 5.95441 12.043 7.08689C12.0647 7.25134 12.14 7.40405 12.2571 7.52148C12.3743 7.63891 12.5268 7.71452 12.6912 7.73664C13.8236 7.89774 14.8532 8.48095 15.5736 9.36934C16.294 10.2577 16.652 11.3856 16.5757 12.5268C16.4995 13.6681 15.9946 14.7383 15.1624 15.5229C14.3301 16.3075 13.2321 16.7485 12.0883 16.7575Z" fill="black" />
                            </svg>
                        </div>
                        <label id="type_channel"></label>
                    </div>

                    <div class="number" id="nameChannel" style="display: none;">
                        <i class="fa fa-whatsapp" aria-hidden="true" style="font-size: 20px;"></i>
                        <label id="talkall_channel"></label>
                    </div>

                    <div class="email" id="optionEmail" style="display: none;">
                        <div class="div-svg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 19 19">
                                <path d="M15.0417 1.29153H3.95833C2.9089 1.29279 1.90282 1.71023 1.16076 2.45229C0.418698 3.19435 0.00125705 4.20044 0 5.24987L0 14.7499C0.00125705 15.7993 0.418698 16.8054 1.16076 17.5474C1.90282 18.2895 2.9089 18.7069 3.95833 18.7082H15.0417C16.0911 18.7069 17.0972 18.2895 17.8392 17.5474C18.5813 16.8054 18.9987 15.7993 19 14.7499V5.24987C18.9987 4.20044 18.5813 3.19435 17.8392 2.45229C17.0972 1.71023 16.0911 1.29279 15.0417 1.29153ZM3.95833 2.87487H15.0417C15.5157 2.8758 15.9786 3.01857 16.3708 3.2848C16.7631 3.55102 17.0666 3.92853 17.2425 4.36874L11.1799 10.4321C10.7338 10.8765 10.1297 11.126 9.5 11.126C8.87029 11.126 8.26624 10.8765 7.82008 10.4321L1.7575 4.36874C1.93337 3.92853 2.23694 3.55102 2.62916 3.2848C3.02138 3.01857 3.48429 2.8758 3.95833 2.87487ZM15.0417 17.1249H3.95833C3.32844 17.1249 2.72435 16.8746 2.27895 16.4292C1.83356 15.9838 1.58333 15.3798 1.58333 14.7499V6.43737L6.70067 11.5515C7.44375 12.2927 8.45045 12.709 9.5 12.709C10.5495 12.709 11.5563 12.2927 12.2993 11.5515L17.4167 6.43737V14.7499C17.4167 15.3798 17.1664 15.9838 16.721 16.4292C16.2756 16.8746 15.6716 17.1249 15.0417 17.1249Z" fill="black" />
                            </svg>
                        </div>
                        <label id="email"></label>
                    </div>

                    <div class="hover-item-label" id="labelContact" style="display: flex;">

                    </div>
                </div>


                <div id="info-participants" class="info">
                    <table style="margin-bottom: 0px;margin-top: 10px;margin-bottom: 10px;">
                        <tr>
                            <td><label><?php echo $this->lang->line('messenger_participants'); ?><i id="participant-edit" class="fas fa-user-edit" style="float: right; cursor: pointer; margin-right: 11px; "></i></label></td>
                        </tr>
                    </table>
                </div>

                <div id="participants" class="participants"></div>


                <div class="options" id="optionGroupOne">
                    <div class="button-chat-waiting" id="chat-waiting">
                        <div class="div-svg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                                <path d="M8.92406 0C7.15905 0 5.43367 0.523387 3.96612 1.50397C2.49857 2.48456 1.35475 3.87831 0.679307 5.50897C0.00386667 7.13962 -0.172859 8.93395 0.171477 10.6651C0.515814 12.3961 1.36575 13.9863 2.6138 15.2343C3.86185 16.4824 5.45196 17.3323 7.18306 17.6766C8.91416 18.021 10.7085 17.8442 12.3391 17.1688C13.9698 16.4934 15.3636 15.3495 16.3441 13.882C17.3247 12.4144 17.8481 10.6891 17.8481 8.92406C17.8456 6.55803 16.9045 4.28965 15.2315 2.61662C13.5585 0.943591 11.2901 0.00255903 8.92406 0V0ZM8.92406 16.3608C7.45322 16.3608 6.0154 15.9246 4.79244 15.1075C3.56948 14.2903 2.6163 13.1288 2.05343 11.77C1.49057 10.4111 1.34329 8.91581 1.63024 7.47322C1.91719 6.03064 2.62547 4.70555 3.66551 3.66551C4.70555 2.62546 6.03065 1.91718 7.47323 1.63024C8.91581 1.34329 10.4111 1.49056 11.77 2.05343C13.1288 2.6163 14.2903 3.56948 15.1075 4.79244C15.9246 6.0154 16.3608 7.45321 16.3608 8.92406C16.3586 10.8957 15.5744 12.786 14.1802 14.1802C12.786 15.5744 10.8957 16.3586 8.92406 16.3608Z" fill="black" />
                                <path d="M8.92403 4.46204C8.72679 4.46204 8.53764 4.54039 8.39817 4.67985C8.25871 4.81932 8.18035 5.00847 8.18035 5.20571V8.42208L5.67344 9.99271C5.50579 10.0974 5.38661 10.2645 5.34212 10.4571C5.29763 10.6497 5.33148 10.8521 5.43621 11.0197C5.54094 11.1874 5.70798 11.3065 5.90058 11.351C6.09318 11.3955 6.29557 11.3617 6.46322 11.2569L9.31892 9.47214C9.42681 9.40454 9.51554 9.31037 9.57661 9.19864C9.63768 9.08692 9.66905 8.96139 9.6677 8.83407V5.20571C9.6677 5.00847 9.58935 4.81932 9.44988 4.67985C9.31042 4.54039 9.12126 4.46204 8.92403 4.46204Z" fill="black" />
                            </svg>
                        </div>
                        <label><?php echo $this->lang->line('messenger_window_option_put_on_hold'); ?></label>
                    </div>
                    <div class="button-chat-waiting" id="chat-attendence">
                        <div class="div-svg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                                <path d="M8.92406 0C7.15905 0 5.43367 0.523387 3.96612 1.50397C2.49857 2.48456 1.35475 3.87831 0.679307 5.50897C0.00386667 7.13962 -0.172859 8.93395 0.171477 10.6651C0.515814 12.3961 1.36575 13.9863 2.6138 15.2343C3.86185 16.4824 5.45196 17.3323 7.18306 17.6766C8.91416 18.021 10.7085 17.8442 12.3391 17.1688C13.9698 16.4934 15.3636 15.3495 16.3441 13.882C17.3247 12.4144 17.8481 10.6891 17.8481 8.92406C17.8456 6.55803 16.9045 4.28965 15.2315 2.61662C13.5585 0.943591 11.2901 0.00255903 8.92406 0V0ZM8.92406 16.3608C7.45322 16.3608 6.0154 15.9246 4.79244 15.1075C3.56948 14.2903 2.6163 13.1288 2.05343 11.77C1.49057 10.4111 1.34329 8.91581 1.63024 7.47322C1.91719 6.03064 2.62547 4.70555 3.66551 3.66551C4.70555 2.62546 6.03065 1.91718 7.47323 1.63024C8.91581 1.34329 10.4111 1.49056 11.77 2.05343C13.1288 2.6163 14.2903 3.56948 15.1075 4.79244C15.9246 6.0154 16.3608 7.45321 16.3608 8.92406C16.3586 10.8957 15.5744 12.786 14.1802 14.1802C12.786 15.5744 10.8957 16.3586 8.92406 16.3608Z" fill="black" />
                                <path d="M8.92403 4.46204C8.72679 4.46204 8.53764 4.54039 8.39817 4.67985C8.25871 4.81932 8.18035 5.00847 8.18035 5.20571V8.42208L5.67344 9.99271C5.50579 10.0974 5.38661 10.2645 5.34212 10.4571C5.29763 10.6497 5.33148 10.8521 5.43621 11.0197C5.54094 11.1874 5.70798 11.3065 5.90058 11.351C6.09318 11.3955 6.29557 11.3617 6.46322 11.2569L9.31892 9.47214C9.42681 9.40454 9.51554 9.31037 9.57661 9.19864C9.63768 9.08692 9.66905 8.96139 9.6677 8.83407V5.20571C9.6677 5.00847 9.58935 4.81932 9.44988 4.67985C9.31042 4.54039 9.12126 4.46204 8.92403 4.46204Z" fill="black" />
                            </svg>
                        </div>
                        <label><?php echo $this->lang->line('messenger_window_option_resume_chat'); ?></label>
                    </div>
                    <div class="button-chat-trans" id="chat-trans">
                        <div class="div-svg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                                <path d="M8.92419 1.48733C9.91068 1.49057 10.8867 1.68942 11.7959 2.07237C12.705 2.45532 13.5291 3.01477 14.2206 3.71835H11.8989C11.7016 3.71835 11.5125 3.7967 11.373 3.93616C11.2336 4.07563 11.1552 4.26479 11.1552 4.46202C11.1552 4.65925 11.2336 4.84841 11.373 4.98787C11.5125 5.12734 11.7016 5.20569 11.8989 5.20569H14.9799C15.3461 5.20549 15.6973 5.05993 15.9562 4.80099C16.2151 4.54204 16.3607 4.1909 16.3609 3.82469V0.743663C16.3609 0.546429 16.2826 0.357273 16.1431 0.217808C16.0036 0.0783425 15.8145 -8.39638e-06 15.6172 -8.39638e-06C15.42 -8.39638e-06 15.2308 0.0783425 15.0914 0.217808C14.9519 0.357273 14.8736 0.546429 14.8736 0.743663V2.28901C13.6444 1.18202 12.1313 0.439062 10.5038 0.143334C8.8763 -0.152395 7.19859 0.0107837 5.65857 0.614597C4.11855 1.21841 2.77702 2.23902 1.78421 3.56212C0.791407 4.88522 0.186531 6.45858 0.0373195 8.10601C0.0277137 8.20957 0.0397853 8.314 0.072765 8.41264C0.105745 8.51128 0.158909 8.60196 0.228872 8.67892C0.298834 8.75588 0.384059 8.81742 0.479118 8.85962C0.574177 8.90183 0.676985 8.92377 0.780991 8.92405C0.962884 8.92636 1.13909 8.86071 1.27513 8.73995C1.41116 8.61918 1.49723 8.45199 1.51648 8.2711C1.68203 6.41992 2.5338 4.69754 3.90447 3.44233C5.27513 2.18711 7.06562 1.48978 8.92419 1.48733Z" fill="black" />
                                <path d="M17.0682 8.92419C16.8863 8.92187 16.7101 8.98752 16.5741 9.10829C16.438 9.22906 16.352 9.39625 16.3327 9.57714C16.2098 10.9925 15.6834 12.3429 14.8161 13.4682C13.9488 14.5934 12.7769 15.4463 11.4395 15.9255C10.102 16.4048 8.6552 16.4904 7.2706 16.1721C5.886 15.8538 4.62175 15.1451 3.62783 14.1299H5.94958C6.14681 14.1299 6.33597 14.0516 6.47543 13.9121C6.6149 13.7726 6.69325 13.5835 6.69325 13.3862C6.69325 13.189 6.6149 12.9998 6.47543 12.8604C6.33597 12.7209 6.14681 12.6426 5.94958 12.6426H2.86855C2.68716 12.6425 2.50754 12.6781 2.33995 12.7475C2.17235 12.8168 2.02007 12.9186 1.89182 13.0468C1.76356 13.1751 1.66184 13.3274 1.59247 13.495C1.52311 13.6626 1.48745 13.8422 1.48755 14.0236V17.1046C1.48755 17.3018 1.5659 17.491 1.70537 17.6305C1.84483 17.7699 2.03399 17.8483 2.23122 17.8483C2.42845 17.8483 2.61761 17.7699 2.75708 17.6305C2.89654 17.491 2.97489 17.3018 2.97489 17.1046V15.5592C4.20405 16.6662 5.71711 17.4092 7.34463 17.7049C8.97215 18.0007 10.6499 17.8375 12.1899 17.2337C13.7299 16.6298 15.0714 15.6092 16.0642 14.2861C17.057 12.963 17.6619 11.3897 17.8111 9.74223C17.8207 9.63867 17.8087 9.53424 17.7757 9.4356C17.7427 9.33696 17.6895 9.24628 17.6196 9.16932C17.5496 9.09236 17.4644 9.03082 17.3693 8.98861C17.2743 8.94641 17.1715 8.92447 17.0675 8.92419H17.0682Z" fill="black" />
                            </svg>
                        </div>
                        <label><?php echo $this->lang->line('messenger_window_option_transfer_service'); ?></label>
                    </div>
                    <div class="button-chat-ticket" id="chat-ticket">
                        <div class="div-svg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                                <path d="M11.8987 0H11.8021C11.4692 0.00316195 11.1469 0.116766 10.8856 0.322976C10.6244 0.529186 10.439 0.816323 10.3586 1.1393C10.2725 1.45376 10.0855 1.73121 9.82627 1.92898C9.56708 2.12674 9.25008 2.23387 8.92406 2.23387C8.59803 2.23387 8.28104 2.12674 8.02184 1.92898C7.76265 1.73121 7.57561 1.45376 7.48952 1.1393C7.40912 0.816323 7.22374 0.529186 6.96248 0.322976C6.70122 0.116766 6.37887 0.00316195 6.04605 0L5.94937 0C4.96357 0.00118084 4.01847 0.393314 3.3214 1.09039C2.62433 1.78746 2.2322 2.73255 2.23102 3.71836V15.6171C2.23102 16.2088 2.46607 16.7763 2.88447 17.1947C3.30286 17.6131 3.87033 17.8481 4.46203 17.8481H6.04605C6.37887 17.8449 6.70122 17.7313 6.96248 17.5251C7.22374 17.3189 7.40912 17.0318 7.48952 16.7088C7.57561 16.3944 7.76265 16.1169 8.02184 15.9191C8.28104 15.7214 8.59803 15.6142 8.92406 15.6142C9.25008 15.6142 9.56708 15.7214 9.82627 15.9191C10.0855 16.1169 10.2725 16.3944 10.3586 16.7088C10.439 17.0318 10.6244 17.3189 10.8856 17.5251C11.1469 17.7313 11.4692 17.8449 11.8021 17.8481H13.3861C13.9778 17.8481 14.5453 17.6131 14.9637 17.1947C15.382 16.7763 15.6171 16.2088 15.6171 15.6171V3.71836C15.6159 2.73255 15.2238 1.78746 14.5267 1.09039C13.8296 0.393314 12.8846 0.00118084 11.8987 0V0ZM13.3861 16.3608L11.7924 16.3139C11.6177 15.6828 11.2397 15.127 10.717 14.7327C10.1943 14.3384 9.55602 14.1275 8.90125 14.1328C8.24648 14.1382 7.61176 14.3594 7.09553 14.7622C6.57931 15.165 6.21041 15.7269 6.04605 16.3608H4.46203C4.2648 16.3608 4.07564 16.2824 3.93618 16.143C3.79671 16.0035 3.71836 15.8143 3.71836 15.6171V12.6424H5.2057C5.40294 12.6424 5.59209 12.5641 5.73156 12.4246C5.87102 12.2851 5.94937 12.096 5.94937 11.8987C5.94937 11.7015 5.87102 11.5123 5.73156 11.3729C5.59209 11.2334 5.40294 11.1551 5.2057 11.1551H3.71836V3.71836C3.71836 3.12665 3.95341 2.55919 4.37181 2.14079C4.79021 1.72239 5.35767 1.48734 5.94937 1.48734L6.05572 1.53419C6.2299 2.16143 6.60454 2.71443 7.12245 3.10881C7.64036 3.50318 8.27309 3.71726 8.92406 3.71836C9.58381 3.71274 10.2237 3.49176 10.7463 3.08903C11.2689 2.68631 11.6456 2.12388 11.8192 1.48734H11.8987C12.4904 1.48734 13.0579 1.72239 13.4763 2.14079C13.8947 2.55919 14.1298 3.12665 14.1298 3.71836V11.1551H12.6424C12.4452 11.1551 12.256 11.2334 12.1166 11.3729C11.9771 11.5123 11.8987 11.7015 11.8987 11.8987C11.8987 12.096 11.9771 12.2851 12.1166 12.4246C12.256 12.5641 12.4452 12.6424 12.6424 12.6424H14.1298V15.6171C14.1298 15.8143 14.0514 16.0035 13.9119 16.143C13.7725 16.2824 13.5833 16.3608 13.3861 16.3608Z" fill="black" />
                                <path d="M9.66772 11.1551H8.18038C7.98314 11.1551 7.79399 11.2334 7.65452 11.3729C7.51506 11.5123 7.43671 11.7015 7.43671 11.8987C7.43671 12.096 7.51506 12.2851 7.65452 12.4246C7.79399 12.5641 7.98314 12.6424 8.18038 12.6424H9.66772C9.86495 12.6424 10.0541 12.5641 10.1936 12.4246C10.333 12.2851 10.4114 12.096 10.4114 11.8987C10.4114 11.7015 10.333 11.5123 10.1936 11.3729C10.0541 11.2334 9.86495 11.1551 9.66772 11.1551Z" fill="black" />
                            </svg>
                        </div>
                        </i><label><?php echo $this->lang->line('messenger_window_option_open_ticket'); ?></label>
                    </div>
                </div>

                <div class="options" id="optionGroupTwo">
                    <div class="button-chat-search openSearch">
                        <div class="div-svg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                                <path d="M17.63 16.5792L13.1911 12.1402C14.4007 10.6607 14.9955 8.77293 14.8523 6.86725C14.7091 4.96157 13.839 3.18383 12.4218 1.90173C11.0046 0.619633 9.1489 -0.0687226 7.23845 -0.0209553C5.32799 0.0268121 3.50898 0.807048 2.15766 2.15837C0.806346 3.50968 0.0261102 5.32869 -0.0216572 7.23915C-0.0694246 9.1496 0.618931 11.0053 1.90103 12.4225C3.18312 13.8397 4.96087 14.7098 6.86655 14.853C8.77223 14.9962 10.66 14.4014 12.1395 13.1918L16.5785 17.6307C16.7187 17.7662 16.9066 17.8412 17.1016 17.8395C17.2966 17.8378 17.4831 17.7596 17.621 17.6217C17.7589 17.4838 17.8371 17.2973 17.8388 17.1023C17.8405 16.9073 17.7655 16.7194 17.63 16.5792ZM7.43653 13.3866C6.25986 13.3866 5.10961 13.0377 4.13124 12.384C3.15287 11.7302 2.39032 10.8011 1.94003 9.71396C1.48973 8.62685 1.37192 7.43063 1.60147 6.27657C1.83103 5.1225 2.39766 4.06243 3.22969 3.23039C4.06172 2.39836 5.1218 1.83173 6.27587 1.60218C7.42993 1.37262 8.62615 1.49044 9.71325 1.94073C10.8004 2.39102 11.7295 3.15357 12.3833 4.13194C13.037 5.11031 13.3859 6.26056 13.3859 7.43723C13.3841 9.01456 12.7568 10.5268 11.6414 11.6421C10.5261 12.7575 9.01386 13.3848 7.43653 13.3866Z" fill="black" />
                            </svg>
                        </div>
                        <label><?php echo $this->lang->line('messenger_window_option_search'); ?></label>
                    </div>
                    <div class="button-chat-favorite" id="openStarred">
                        <div class="div-svg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                                <path d="M17.7259 6.54008C17.5738 6.05588 17.2699 5.63348 16.8591 5.33536C16.4484 5.03724 15.9526 4.87922 15.4451 4.88467H12.196L11.2091 1.80885C11.0539 1.32468 10.749 0.902321 10.3383 0.60266C9.92754 0.302998 9.43225 0.141525 8.92383 0.141525C8.4154 0.141525 7.92011 0.302998 7.50939 0.60266C7.09866 0.902321 6.79371 1.32468 6.63852 1.80885L5.65167 4.88467H2.40257C1.89669 4.88539 1.40397 5.04596 0.994789 5.34344C0.585612 5.64093 0.28091 6.06011 0.124204 6.54111C-0.032502 7.02212 -0.0331956 7.54034 0.122222 8.02176C0.27764 8.50319 0.581219 8.92318 0.989598 9.22176L3.63409 11.1553L2.62865 14.2691C2.46617 14.752 2.46411 15.2745 2.62278 15.7587C2.78145 16.2429 3.09236 16.6628 3.50916 16.9559C3.91881 17.2585 4.41524 17.4205 4.92448 17.418C5.43371 17.4154 5.9285 17.2484 6.33511 16.9418L8.92383 15.0365L11.5133 16.9396C11.9222 17.2404 12.416 17.4037 12.9236 17.4062C13.4312 17.4087 13.9265 17.2501 14.3383 16.9533C14.7501 16.6566 15.0573 16.2368 15.2155 15.7545C15.3737 15.2722 15.375 14.7521 15.219 14.2691L14.2136 11.1553L16.861 9.22176C17.2741 8.92693 17.5812 8.50694 17.737 8.02396C17.8928 7.54098 17.8889 7.02068 17.7259 6.54008ZM15.9835 8.02073L12.9017 10.2733C12.7751 10.3657 12.681 10.4956 12.6326 10.6447C12.5843 10.7937 12.5842 10.9542 12.6325 11.1032L13.8038 14.7249C13.8631 14.9086 13.8626 15.1064 13.8024 15.2898C13.7421 15.4731 13.6253 15.6327 13.4687 15.7455C13.3121 15.8583 13.1237 15.9186 12.9307 15.9176C12.7377 15.9166 12.55 15.8544 12.3945 15.74L9.36408 13.509C9.23644 13.4153 9.0822 13.3647 8.92383 13.3647C8.76545 13.3647 8.61121 13.4153 8.48357 13.509L5.45311 15.74C5.29775 15.856 5.10949 15.9194 4.91563 15.9212C4.72178 15.923 4.5324 15.8629 4.37496 15.7498C4.21752 15.6367 4.10019 15.4764 4.03999 15.2921C3.97978 15.1078 3.97984 14.9092 4.04014 14.7249L5.21514 11.1032C5.26341 10.9542 5.26338 10.7937 5.21503 10.6447C5.16669 10.4956 5.07251 10.3657 4.94593 10.2733L1.86416 8.02073C1.70895 7.90709 1.59363 7.74732 1.53467 7.56422C1.4757 7.38113 1.4761 7.18408 1.53581 7.00123C1.59553 6.81838 1.7115 6.65907 1.86716 6.54607C2.02282 6.43307 2.21022 6.37215 2.40257 6.37201H6.1953C6.35274 6.37201 6.50613 6.32203 6.63335 6.22928C6.76058 6.13653 6.85509 6.0058 6.90327 5.8559L8.05596 2.26323C8.11513 2.07938 8.2311 1.91905 8.38719 1.80531C8.54328 1.69157 8.73144 1.63029 8.92457 1.63029C9.1177 1.63029 9.30585 1.69157 9.46194 1.80531C9.61803 1.91905 9.73401 2.07938 9.79318 2.26323L10.9459 5.8559C10.994 6.0058 11.0886 6.13653 11.2158 6.22928C11.343 6.32203 11.4964 6.37201 11.6538 6.37201H15.4466C15.6389 6.37215 15.8263 6.43307 15.982 6.54607C16.1376 6.65907 16.2536 6.81838 16.3133 7.00123C16.373 7.18408 16.3734 7.38113 16.3145 7.56422C16.2555 7.74732 16.1402 7.90709 15.985 8.02073H15.9835Z" fill="black" />
                            </svg>
                        </div>
                        <label><?php echo $this->lang->line("messenger_window_option_favorite_messages") ?></label>
                    </div>
                    <div class="button-chat-spam" id="chat-report-span">
                        <div class="div-svg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                                <path d="M17.8117 9.14716L17.2874 5.4288C17.1585 4.54379 16.7164 3.73436 16.0415 3.14757C15.3666 2.56078 14.5035 2.23557 13.6092 2.23102H3.71836C2.73255 2.2322 1.78746 2.62433 1.09039 3.3214C0.393314 4.01847 0.00118084 4.96357 0 5.94937L0 9.66773C0.00118084 10.6535 0.393314 11.5986 1.09039 12.2957C1.78746 12.9928 2.73255 13.3849 3.71836 13.3861H5.73519L7.15858 16.27C7.39787 16.7561 7.8039 17.14 8.30263 17.3516C8.80135 17.5632 9.35953 17.5885 9.87536 17.4229C10.3912 17.2573 10.8303 16.9117 11.1126 16.4493C11.3949 15.9869 11.5015 15.4384 11.4131 14.9039L11.1632 13.3861H14.1298C14.6621 13.3861 15.1882 13.2718 15.6725 13.051C16.1568 12.8302 16.5881 12.508 16.9372 12.1061C17.2862 11.7042 17.5449 11.2321 17.6958 10.7216C17.8467 10.2111 17.8862 9.67422 17.8117 9.14716ZM3.71836 3.71836H5.2057V11.8987H3.71836C3.12665 11.8987 2.55919 11.6637 2.14079 11.2453C1.7224 10.8269 1.48734 10.2594 1.48734 9.66773V5.94937C1.48734 5.35767 1.7224 4.79021 2.14079 4.37181C2.55919 3.95341 3.12665 3.71836 3.71836 3.71836ZM15.8134 11.1313C15.604 11.3722 15.3454 11.5654 15.0549 11.6978C14.7644 11.8302 14.449 11.8987 14.1298 11.8987H10.2872C10.1793 11.8987 10.0728 11.9222 9.97486 11.9674C9.87696 12.0127 9.79008 12.0788 9.72025 12.161C9.65042 12.2432 9.59931 12.3396 9.57046 12.4435C9.54162 12.5475 9.53573 12.6564 9.5532 12.7629L9.94586 15.1426C9.96472 15.2531 9.95897 15.3664 9.92901 15.4743C9.89906 15.5823 9.84565 15.6824 9.77258 15.7673C9.68691 15.8597 9.58054 15.9304 9.46222 15.9736C9.34389 16.0168 9.217 16.0313 9.09197 16.0159C8.96694 16.0005 8.84736 15.9557 8.74305 15.8851C8.63874 15.8144 8.55269 15.72 8.49198 15.6097L6.86409 12.3137C6.82114 12.2313 6.76313 12.1576 6.69304 12.0966V3.71836H13.6092C14.1463 3.72032 14.6648 3.91523 15.0703 4.26756C15.4757 4.61989 15.741 5.10617 15.8179 5.63778L16.3429 9.35613C16.387 9.67268 16.3625 9.99499 16.2711 10.3013C16.1798 10.6075 16.0237 10.8906 15.8134 11.1313Z" fill="#D60000" />
                            </svg>
                        </div>
                        <label><?php echo $this->lang->line("messenger_window_option_spam") ?></label>
                    </div>
                </div>

                <div class="preview-gallery">
                    <div class="top-section">
                        <div class="header openGallery">
                            <span><?php echo $this->lang->line('messenger_window_option_media_documents'); ?></span>
                        </div>
                        <div class="openGallery">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path d="M6.99982 24C6.86821 24.0007 6.73775 23.9755 6.61591 23.9258C6.49407 23.876 6.38326 23.8027 6.28982 23.71C6.19609 23.617 6.12169 23.5064 6.07093 23.3846C6.02016 23.2627 5.99402 23.132 5.99402 23C5.99402 22.868 6.02016 22.7373 6.07093 22.6154C6.12169 22.4936 6.19609 22.383 6.28982 22.29L14.4598 14.12C15.0216 13.5575 15.3372 12.795 15.3372 12C15.3372 11.205 15.0216 10.4425 14.4598 9.88001L6.28982 1.71002C6.10151 1.52171 5.99573 1.26632 5.99573 1.00002C5.99573 0.733715 6.10151 0.478321 6.28982 0.290017C6.47812 0.101714 6.73352 -0.0040741 6.99982 -0.0040741C7.26612 -0.0040741 7.52151 0.101714 7.70982 0.290017L15.8798 8.46001C16.3454 8.92446 16.7149 9.47622 16.9669 10.0837C17.219 10.6911 17.3488 11.3423 17.3488 12C17.3488 12.6577 17.219 13.3089 16.9669 13.9163C16.7149 14.5238 16.3454 15.0755 15.8798 15.54L7.70982 23.71C7.61638 23.8027 7.50556 23.876 7.38372 23.9258C7.26189 23.9755 7.13142 24.0007 6.99982 24Z" fill="#000000" />
                            </svg>
                        </div>
                    </div>
                    <div class="bottom-section openGallery" id="previewGallery"></div>
                </div>

                <div class="note-contact">
                    <span class="title"><?php echo $this->lang->line('messenger_window_option_notes'); ?></span>
                    <div class="add-note-contact" onclick="showBoxNote(this.clear = true)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="-0.5 -0.5 15 15.5" id="addNoteContact">
                            <path d="M13.6698 6.90566H7.72641V0.96226C7.72641 0.804631 7.6638 0.653458 7.55234 0.541998C7.44088 0.430538 7.2897 0.36792 7.13208 0.36792V0.36792C6.97445 0.36792 6.82327 0.430538 6.71181 0.541998C6.60035 0.653458 6.53774 0.804631 6.53774 0.96226V6.90566H0.59434C0.436711 6.90566 0.285538 6.96827 0.174078 7.07973C0.0626178 7.19119 0 7.34237 0 7.5H0C0 7.65762 0.0626178 7.8088 0.174078 7.92026C0.285538 8.03172 0.436711 8.09433 0.59434 8.09433H6.53774V14.0377C6.53774 14.1954 6.60035 14.3465 6.71181 14.458C6.82327 14.5695 6.97445 14.6321 7.13208 14.6321C7.2897 14.6321 7.44088 14.5695 7.55234 14.458C7.6638 14.3465 7.72641 14.1954 7.72641 14.0377V8.09433H13.6698C13.8274 8.09433 13.9786 8.03172 14.0901 7.92026C14.2015 7.8088 14.2642 7.65762 14.2642 7.5C14.2642 7.34237 14.2015 7.19119 14.0901 7.07973C13.9786 6.96827 13.8274 6.90566 13.6698 6.90566Z" fill="black"></path>
                        </svg>
                        <span class="info-note-contact-span"><?php echo $this->lang->line('messenger_window_option_add_notes'); ?></span>
                    </div>
                    <div class="subtitle" style="display:none">
                        <textarea class="info-note-contact" disabled id="infoNoteContact" cols="30" rows="5" maxlength="1024"></textarea>
                        <i class="far fa-edit" id="editNote" onclick="showBoxNote(this.clear = false)"></i>
                    </div>
                    <div class="box-note" style="display:none">
                        <textarea class="info-note-contact" id="note" cols="30" rows="5" maxlength="1024"></textarea>
                        <button id="save-note" onclick="saveNote()"><?php echo $this->lang->line('messenger_window_option_btn_save_note'); ?></button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Janela de pesquisa de mensagens -->
        <div class="option window__search" style="display: none;">
            <div class="head">
                <div id="close_settings_search" class="left-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" class="icon-close-right">
                        <path d="M21.7316 0.268754C21.5597 0.0969053 21.3266 0.000366211 21.0835 0.000366211C20.8404 0.000366211 20.6073 0.0969053 20.4354 0.268754L11.0002 9.704L1.56492 0.268754C1.39302 0.0969053 1.1599 0.000366211 0.916837 0.000366211C0.67377 0.000366211 0.440654 0.0969053 0.268754 0.268754C0.0969053 0.440654 0.000366211 0.67377 0.000366211 0.916837C0.000366211 1.1599 0.0969053 1.39302 0.268754 1.56492L9.704 11.0002L0.268754 20.4354C0.0969053 20.6073 0.000366211 20.8404 0.000366211 21.0835C0.000366211 21.3266 0.0969053 21.5597 0.268754 21.7316C0.440654 21.9034 0.67377 22 0.916837 22C1.1599 22 1.39302 21.9034 1.56492 21.7316L11.0002 12.2963L20.4354 21.7316C20.6073 21.9034 20.8404 22 21.0835 22C21.3266 22 21.5597 21.9034 21.7316 21.7316C21.9034 21.5597 22 21.3266 22 21.0835C22 20.8404 21.9034 20.6073 21.7316 20.4354L12.2963 11.0002L21.7316 1.56492C21.9034 1.39302 22 1.1599 22 0.916837C22 0.67377 21.9034 0.440654 21.7316 0.268754Z" fill="black" />
                    </svg>
                </div>
                <span id="title_form"></span>
            </div>

            <div class="body">
                <div class="box-search">
                    <input class="search-input" placeholder="<?php echo $this->lang->line('messenger_search'); ?>..." type="text">
                    <div id="iconSearchLeft" class="icon-search-left show">
                        <svg width="14" height="14" viewBox="0 0 129 129" xmlns="http://www.w3.org/2000/svg">
                            <path d="M51.6,96.7c11,0,21-3.9,28.8-10.5l35,35c0.8,0.8,1.8,1.2,2.9,1.2s2.1-0.4,2.9-1.2c1.6-1.6,1.6-4.2,0-5.8l-35-35   c6.5-7.8,10.5-17.9,10.5-28.8c0-24.9-20.2-45.1-45.1-45.1C26.8,6.5,6.5,26.8,6.5,51.6C6.5,76.5,26.8,96.7,51.6,96.7z M51.6,14.7   c20.4,0,36.9,16.6,36.9,36.9C88.5,72,72,88.5,51.6,88.5c-20.4,0-36.9-16.6-36.9-36.9C14.7,31.3,31.3,14.7,51.6,14.7z" fill="#666666"></path>
                        </svg>
                    </div>
                    <div id="iconArrowLeft" class="icon-clear-left">
                        <svg width="14" height="14" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.9999 6C17.8124 5.81253 17.5581 5.70721 17.2929 5.70721C17.0278 5.70721 16.7735 5.81253 16.5859 6L11.9999 10.586L7.41394 6C7.22641 5.81253 6.9721 5.70721 6.70694 5.70721C6.44178 5.70721 6.18747 5.81253 5.99994 6C5.81247 6.18753 5.70715 6.44184 5.70715 6.707C5.70715 6.97217 5.81247 7.22647 5.99994 7.414L10.5859 12L5.99994 16.586C5.81247 16.7735 5.70715 17.0278 5.70715 17.293C5.70715 17.5582 5.81247 17.8125 5.99994 18C6.18747 18.1875 6.44178 18.2928 6.70694 18.2928C6.9721 18.2928 7.22641 18.1875 7.41394 18L11.9999 13.414L16.5859 18C16.7735 18.1875 17.0278 18.2928 17.2929 18.2928C17.5581 18.2928 17.8124 18.1875 17.9999 18C18.1874 17.8125 18.2927 17.5582 18.2927 17.293C18.2927 17.0278 18.1874 16.7735 17.9999 16.586L13.4139 12L17.9999 7.414C18.1874 7.22647 18.2927 6.97217 18.2927 6.707C18.2927 6.44184 18.1874 6.18753 17.9999 6Z" fill="#666666"></path>
                        </svg>
                    </div>
                    <div id="load_search" class=""></div>
                </div>
                <div class="container-information"></div>
                <div class="list-message"></div>
                <div class="load-search-bottom"></div>
            </div>
        </div>


        <!-- Janela de Galeria do messenger -->
        <div class="option window__gallery" style="display:none;">
            <div class="head">
                <div id="close_settings_gallery" class="left-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" id="close-gallery" class="icon-close-right">
                        <path d="M21.7316 0.268754C21.5597 0.0969053 21.3266 0.000366211 21.0835 0.000366211C20.8404 0.000366211 20.6073 0.0969053 20.4354 0.268754L11.0002 9.704L1.56492 0.268754C1.39302 0.0969053 1.1599 0.000366211 0.916837 0.000366211C0.67377 0.000366211 0.440654 0.0969053 0.268754 0.268754C0.0969053 0.440654 0.000366211 0.67377 0.000366211 0.916837C0.000366211 1.1599 0.0969053 1.39302 0.268754 1.56492L9.704 11.0002L0.268754 20.4354C0.0969053 20.6073 0.000366211 20.8404 0.000366211 21.0835C0.000366211 21.3266 0.0969053 21.5597 0.268754 21.7316C0.440654 21.9034 0.67377 22 0.916837 22C1.1599 22 1.39302 21.9034 1.56492 21.7316L11.0002 12.2963L20.4354 21.7316C20.6073 21.9034 20.8404 22 21.0835 22C21.3266 22 21.5597 21.9034 21.7316 21.7316C21.9034 21.5597 22 21.3266 22 21.0835C22 20.8404 21.9034 20.6073 21.7316 20.4354L12.2963 11.0002L21.7316 1.56492C21.9034 1.39302 22 1.1599 22 0.916837C22 0.67377 21.9034 0.440654 21.7316 0.268754Z" fill="black" />
                    </svg>
                </div>
                <div class="media-options">
                    <div class="left-items">
                        <span class="titleSelected"></span>
                    </div>

                    <div class="options_gallery right-items">
                        <i class="far fa-comment-alt" id="view-tem-gallery"></i>
                        <div class="icon-arrow-download" id="arrow-download-gallery">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 18h16v-2H2v2zM10 15l5-5h-3V4H8v6H5l5 5z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="submenu-media-docs">
                <div class="submenu-media">
                    <div><?php echo $this->lang->line('messenger_window_gallery_media'); ?></div>
                    <span></span>
                </div>
                <div class="submenu-documents">
                    <div style="color: #9a9a9a"><?php echo $this->lang->line('messenger_window_gallery_document'); ?></div>
                    <span style="border-bottom: 0px;"></span>
                </div>
            </div>

            <div class="body">
                <div class="gridGallery" id="galleryBox">
                    <div id="col-gallery"></div>
                </div>

                <div class="gridDocument" id="documentBox">
                    <div id="col-document"></div>
                </div>
            </div>
        </div>


        <!-- Janela de Mensagens Favoritas -->
        <div class="option window__favorite" style="display:none">
            <div class="head">
                <div id="close_settings_favorite" class="left-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" id="close-gallery" class="icon-close-right">
                        <path d="M21.7316 0.268754C21.5597 0.0969053 21.3266 0.000366211 21.0835 0.000366211C20.8404 0.000366211 20.6073 0.0969053 20.4354 0.268754L11.0002 9.704L1.56492 0.268754C1.39302 0.0969053 1.1599 0.000366211 0.916837 0.000366211C0.67377 0.000366211 0.440654 0.0969053 0.268754 0.268754C0.0969053 0.440654 0.000366211 0.67377 0.000366211 0.916837C0.000366211 1.1599 0.0969053 1.39302 0.268754 1.56492L9.704 11.0002L0.268754 20.4354C0.0969053 20.6073 0.000366211 20.8404 0.000366211 21.0835C0.000366211 21.3266 0.0969053 21.5597 0.268754 21.7316C0.440654 21.9034 0.67377 22 0.916837 22C1.1599 22 1.39302 21.9034 1.56492 21.7316L11.0002 12.2963L20.4354 21.7316C20.6073 21.9034 20.8404 22 21.0835 22C21.3266 22 21.5597 21.9034 21.7316 21.7316C21.9034 21.5597 22 21.3266 22 21.0835C22 20.8404 21.9034 20.6073 21.7316 20.4354L12.2963 11.0002L21.7316 1.56492C21.9034 1.39302 22 1.1599 22 0.916837C22 0.67377 21.9034 0.440654 21.7316 0.268754Z" fill="black"></path>
                    </svg>
                </div>
            </div>
            <div class="starred-body" id="starred_box"></div>
        </div>


        <!-- Modal de TEMPLATE -->
        <div class="bgbox"></div>
        <div class="modalBoxTemplete">
            <div class="header">
                <div class="title"><span><?php echo $this->lang->line('messenger_modal_send_notification_title'); ?></span></div>
                <div class="close def__closeModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 22 22" class="icon-close-right">
                        <path d="M21.7316 0.268754C21.5597 0.0969053 21.3266 0.000366211 21.0835 0.000366211C20.8404 0.000366211 20.6073 0.0969053 20.4354 0.268754L11.0002 9.704L1.56492 0.268754C1.39302 0.0969053 1.1599 0.000366211 0.916837 0.000366211C0.67377 0.000366211 0.440654 0.0969053 0.268754 0.268754C0.0969053 0.440654 0.000366211 0.67377 0.000366211 0.916837C0.000366211 1.1599 0.0969053 1.39302 0.268754 1.56492L9.704 11.0002L0.268754 20.4354C0.0969053 20.6073 0.000366211 20.8404 0.000366211 21.0835C0.000366211 21.3266 0.0969053 21.5597 0.268754 21.7316C0.440654 21.9034 0.67377 22 0.916837 22C1.1599 22 1.39302 21.9034 1.56492 21.7316L11.0002 12.2963L20.4354 21.7316C20.6073 21.9034 20.8404 22 21.0835 22C21.3266 22 21.5597 21.9034 21.7316 21.7316C21.9034 21.5597 22 21.3266 22 21.0835C22 20.8404 21.9034 20.6073 21.7316 20.4354L12.2963 11.0002L21.7316 1.56492C21.9034 1.39302 22 1.1599 22 0.916837C22 0.67377 21.9034 0.440654 21.7316 0.268754Z" fill="#666666"></path>
                    </svg>
                </div>
            </div>

            <div class="body" id="bodyTemplate">
                <div class="description">
                    <span><?php echo $this->lang->line('messenger_modal_send_notification_description'); ?></span>
                </div>

                <div class="box-select">
                    <label class="label-select"><?php echo $this->lang->line('messenger_modal_send_notification_select_label'); ?></label>
                    <input type="text" class="select" id="selecTemplate" autocomplete="off">
                    <img class="icon-close" src="<?php echo base_url("assets/icons/messenger/close.svg"); ?>" alt="close">
                    <input type="hidden" id="templateSelected">
                </div>

                <div class="list-template" id="listTemplate"></div>
            </div>

            <div class="main-variable">
                <div class="variable-template"></div>

                <div class="preview-template">
                    <div class="box-preview">
                        <div class="template-message-preview"></div>
                    </div>
                </div>
            </div>

            <div class="footer">
                <div class="sendTemplate" id="sendTemplete">
                    <img src="assets/img/forward_icon.svg" alt="">
                </div>
            </div>
        </div>

        <script type="text/javascript">
            const GLOBAL_LANG = <?php echo json_encode($this->lang->language); ?>;
        </script>

        <script src="assets/js/OpusMediaRecorder.umd.js"></script>
        <script src="assets/js/encoderWorker.umd.js"></script>
        <script type="text/javascript" src="<?php echo base_url("assets/dist/components_dom.js?v={$this->config->item('application_version')}"); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("assets/js/util.js?v={$this->config->item('application_version')}"); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("assets/js/app.js?v={$this->config->item('application_version')}"); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("assets/js/app2.js?v={$this->config->item('application_version')}"); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("assets/js/app3.js?v={$this->config->item('application_version')}"); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("assets/js/lc_switch.js?v={$this->config->item('application_version')}"); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("assets/js/custom-file-input.js?v={$this->config->item('application_version')}"); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("assets/dist/intl-tell-input/js/intl-tell-input.js") ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("assets/dist/intl-tell-input/js/mask.js") ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("assets/js/appAI.js?v={$this->config->item('application_version')}"); ?>"></script>
        <script src="<?php echo base_url('/assets/lib/build_pdf/build_pdf.js') ?>"></script>
</body>

</html>