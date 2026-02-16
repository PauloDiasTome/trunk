<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" />
    <link rel="stylesheet" href="<?php echo base_url("/assets/css/tv_login.css"); ?>" type="text/css" />


    <title>TalkAll TV</title>

</head>

<body>

    <script>
        const GLOBAL_LANG = <?php echo json_encode($this->lang->language); ?>;
        const tvToken = localStorage.getItem('tv_token');
        if (tvToken !== null && tvToken.length > 3) {
            const token = tvToken.replace('tv-', '');
            window.location.href = window.location.origin + '/tv/' + token;
        }
    </script>

    <div class="tv">
        <div class="main-content">
            <div class="left">
                <img src="<?php echo base_url('assets/icons/tv_login.svg') ?>" alt="">
            </div>

            <div class="right">
                <div class="container-code">
                    <div class="box-text">
                        <span><?php echo $this->lang->line("tv_login_span_connect") ?></span>
                        <span><?php echo $this->lang->line("tv_login_span_insert_code") ?></span>
                    </div>

                    <div class="box-input">
                        <input type="text" maxlength="1">
                        <input type="text" maxlength="1">
                        <input type="text" maxlength="1">
                        <input type="text" maxlength="1">
                        <input type="text" maxlength="1">
                        <input type="text" maxlength="1">
                    </div>
                    <div class="alert-field-validation"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/3.0.1/js.cookie.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url("/assets/js/tv_login.js"); ?>"></script>
    
</body>

</html>