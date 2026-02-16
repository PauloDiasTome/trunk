<html>
    <head>
        <title>TalkAll</title>
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/login.css">
        <link rel="icon" href="https://www.talkall.com.br/wp-content/uploads/2019/02/cropped-icon-32x32.png" sizes="32x32">
    </head>

    <body>
        <div class="main-email-confirm" id="content">  

            <div class="box-confirm">
                <div class="container-confirm">
                      
                    <div class="header">
                        <div class="logo">
                            <img src="<?php echo base_url("/assets/icons/panel/talkall-blue.svg"); ?> " alt="">
                        </div>
    
                        <div class="title">
                            <h1><?php echo $this->lang->line("email_confirmation") ?></h1>
                        </div>
                    </div>

                    <div class="body">
                        <div class="text">
                            <span><?php echo $this->lang->line("email_will_recieve_access") ?></span>
                        </div>
                    </div>

                    <div class="footer">
                        <span><?php echo $this->lang->line("email_thanks_for_using") ?></span>
                    </div>

                </div>
            </div>
        </div>
    </body>
</html>