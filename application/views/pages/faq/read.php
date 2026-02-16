<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0"><?php echo $data['title']; ?></i></h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="pl-lg-1">
                        <div class="row">
                            <div class="col-lg-12 faq_posts">                                
                                <?php echo $data['content']; ?>                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8">
                                <a href="<?= base_url() ?>/faq" class="btn btn-primary"><?php echo $this->lang->line("setting_faq_read_return") ?></a>       
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
