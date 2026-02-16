<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" />
    <link rel="stylesheet" href="<?php echo base_url("/assets/css/argon.min.css?v=1.0.0"); ?>" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url("/assets/css/tv.css"); ?>" type="text/css" />

    <title><?php echo $this->lang->line("tv_index_title") ?></title>
</head>

<body>
    <div class="main-content">
        <div class="loading-load">
            <img src="<?php echo base_url("/assets/img/loads/loading_1.gif"); ?>" alt="image">
        </div>

    </div>

    <div class="modal" id="modal-alert" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo $this->lang->line("tv_index_modal_alert_title") ?></h5>
                </div>
                <div class="modal-body">
                    <span></span>
                </div>

            </div>
        </div>
    </div>

    <script>
        const GLOBAL_LANG = <?php echo json_encode($this->lang->language); ?>;
    </script>
    <script type="text/javascript" src="<?php echo base_url("/assets/js/tv.js"); ?>"></script>

</body>

</html>