<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
<link href="<?php echo base_url('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/css/without_permission.css') ?>" rel="stylesheet">

<body>
    <div class="main-without-permission">
        <div class="box-content">
            <div class="box-inner">

                <div class="without-permission">
                    <div class="logo">
                        <img src="<?php echo base_url("/assets/icons/panel/without_permission.svg"); ?> " title="<?= $this->lang->line("account_without_permission_img_title") ?>" alt="<?= $this->lang->line("account_without_permission_img_alt") ?>">
                    </div>

                    <div class="row">
                        <div class="title text-title">
                            <span><?= $this->lang->line("account_without_permission_text_title") ?></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group text-body">
                            <span><?= $this->lang->line("account_without_permission_text_body") ?></span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>