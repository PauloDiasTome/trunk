<?php
$this->load->view('templates/header');
if (isset($sidenav)) {
    $this->load->view('templates/sidenav', $sidenav);
}
?>
<div class="main-content" id="panel">
    <?php if (isset($topnav)) {
        $this->load->view('templates/topnav', isset($topnav));
    }
    $this->load->view($main_content, $view_name);
    ?>
</div>
<?php $this->load->view('templates/footer', $js); ?>