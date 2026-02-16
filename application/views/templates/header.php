<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="TalkAll">

    <title>TalkAll - <?php echo $view_name ?></title>
    <link rel="icon" href="/assets/img/favicon-32x32.png" sizes="32x32">
    <script src="<?php echo base_url('assets/js/pace.min.js'); ?>"></script>

    <link rel="stylesheet" href="https://res.cloudinary.com/dxfq3iotg/raw/upload/v1569006288/BBBootstrap/choices.min.css?version=7.0.0">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/dropzone/dropzone.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/@fortawesome/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/select2.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/argon.min.css?v=1.0.0">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/nucleo/css/nucleo.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap-select.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/bootstrap-clockpicker/dist/bootstrap-clockpicker.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/contact-list.css" />
    <!-- <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/messenger.css" /> -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/vendor/datatables.net-scroller/css/scroller.bootstrap4.min.css?v={$this->config->item('application_version')}"); ?>" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/croppie.css?v={$this->config->item('application_version')}"); ?>" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/product.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/util.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/lc_switch.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/painelLight.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/painel.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/msFmultiSelectModal.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/emojionearea.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/openAI.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/slimselect.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/tutorial.css" />
    <?php
    if (!empty($css)) {
        foreach ($css as $estilos) {
            echo  "<link rel='stylesheet' type='text/css' href='" . base_url("assets/css/{$estilos}?v={$this->config->item('application_version')}") . "'/>";
        }
    }
    ?>
</head>

<div class="bgboxLoad"></div>
<div class="languageLoad">
    <div class="load">
        <img src="<?php echo base_url("assets/img/loads/loading_3.gif") ?>" alt="">
    </div>
</div>

<body class="g-sidenav pace-done g-sidenav-hidden">