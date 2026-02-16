<div class="container-fluid mt--6">
    <div class="row justify-content-center">
        <div class="col-lg-8 center-block col-centered">
            <div class="card card-pricing border-0 text-center mb-4">
                <div class="card-header bg-transparent">
                    <h4 class="text-uppercase ls-1 text-primary py-3 mb-0"><?php echo $this->lang->line("setting_integration_widget_topnav") ?></h4>
                </div>
                <div class="card-body px-lg-7">
                    <div class="display-2" style="margin-bottom: 20px;">
                        <img src="<?php echo base_url("assets/img/widget.svg"); ?>">
                    </div>
                    <span class="text-muted"><?php echo $this->lang->line("setting_integration_widget") ?></span>

                    <ul class="list-unstyled my-4">
                        <li>
                            <div class="d-flex align-items-center">
                                <div>
                                    <span class="pl-2 text-sm"><?php echo $this->lang->line("setting_integration_widget_insert_after_tag") ?>

                                        <body> <?php echo $this->lang->line("setting_integration_widget_text") ?>
                                    </span>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <!-- <?php echo '<pre>'; var_dump($queryWidget) ?> -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div style="background-color: rgb(245, 247, 248); margin-bottom: 16px; padding: 12px;">
                                <div maxheight="349" wrap="break-word" display="block" shade="medium" weight="normal" class="_2eae _3yw3 _1jef" style="max-height: 349px;">
                                    <div style="width: 100%; overflow: auto;text-align: left; height:160px">
                                        <code style="overflow: auto;text-align: left;white-space: pre-wrap;word-wrap: break-word;float: left;text-align: left!important;width: 100%;">&lt;!-- Start TalkAll Widget --&gt;
&lt;script&gt;
    (function(t, a, l, k, a) {
    var talkall = t.createElement('script');
    talkall.async = true;
    talkall.id = 'talkall';
    talkall.src = 'https://app.talkall.com.br/widget.js';
    talkall.setAttribute('init', l);
    talkall.setAttribute('color', "<?php echo $queryWidget[0]['button_color'] ?>");
    talkall.setAttribute('text', "<?php echo $queryWidget[0]['button_text'] ?>");
    talkall.setAttribute('position', "<?php echo $queryWidget[0]['position'] ?>");
    t.head.appendChild(talkall); }
    )(document, 'script', '<?php echo $data; ?>');
&lt;/script&gt;
&lt;!-- End TalkAll Widget --&gt;
                                        </code>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <a href="<?php echo base_url(); ?>integration"><button class="btn btn-primary mb-3" type="button"><?php echo $this->lang->line("setting_integration_widget_btn_return") ?></button></a>
                    <button type="button" id="copy-clipboard" class="btn btn-info mb-3"><?php echo $this->lang->line("setting_integration_widget_btn_copy") ?></button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
