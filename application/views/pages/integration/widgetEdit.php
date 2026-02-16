<div class="container-fluid mt--6">
    <style>
        #phases {
            /* background-color: blue; */
            flex-direction: inherit !important;
        }

        #progress {
            margin-bottom: 21px;
            margin-top: 19px;
            overflow: hidden;
            counter-reset: step;
            margin-left: 5%;
        }

        #progress li {
            list-style-type: none;
            font-weight: bold;
            text-transform: initial;
            font-size: 12.5px;
            float: left;
            width: 19%;
            position: relative;
            margin-left: 11%;
        }

        #progress li::before {
            content: counter(step);
            counter-increment: step;
            width: 29px;
            height: 28px;
            display: block;
            line-height: 20px;
            background: #dedede;
            color: #333;
            border-radius: 10px;
            -moz-border-radius: 10px;
            -webkit-border-radius: 23px;
            margin-top: 6px;
            padding-left: 11px;
            padding-top: 4px;
            margin-bottom: 17px;
        }

        #progress li::after {
            content: "";
            width: 145%;
            height: 2px;
            background: #dedede;
            position: absolute;
            top: 19px;
            margin-left: -35.2px
        }

        #progress li:last-child:after {
            content: none;
        }

        #progress li.active-ball::before {
            background-color: #5e72e7;
            text-shadow: 0 1px 0;
            color: white;

        }

        #progress li.active-line::after {
            background-color: #5e72e7;
            text-shadow: 0 1px 0;
            color: white;

        }

        .container-options {
            background-color: white;
            flex-direction: inherit !important;
            padding-left: 10px !important;
            margin-bottom: -24px
        }

        .palette {
            width: 40px;
            height: 40px;
            cursor: pointer;
            position: absolute;
            margin-top: 28px;
            border-radius: 21px;
            background-color: yellow;
        }

        /* preview  button*/
        .container-preview-button {
            width: 267px;
            height: 63px;
            margin-left: 1px;
            border-radius: 72px;
            margin-top: 45px;
            background-color: rgb(37 211 102);
        }

        .container-preview-button .img {
            width: 30px;
        }

        .container-preview-button .button {
            margin-top: -5px;
            font-size: 15px;
            color: white;
        }

        /* preview widget  */
        .container-preview {
            background-color: white;
            flex-direction: tex inherit !important;
            margin-bottom: -24px;
            /* width: 295px; */
            margin-right: 10px;
            height: 333px;
            overflow-y: scroll;
            padding-bottom: 10px;
            padding-top: 10px;
            overflow-x: hidden;
        }

        .container-preview::-webkit-scrollbar,
        .container-preview::-webkit-scrollbar-thumb {
            width: 6px;
            color: #9e9e9f;
            border-radius: .375rem;
        }

        .container-preview::-webkit-scrollbar-thumb {
            box-shadow: inset 0 0 0 4px !important;
        }

        .container-preview-inner {
            width: 325px;
            height: 309px;
            margin-left: 12px;
            border-radius: 11px;
            /* box-shadow: 0 4px 4px rgb(0 0 0 / 33%); */
            background-color: #fbfcfc;
        }

        .container-preview-inner .card-info {
            background-color: rgb(18, 109, 162);
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            height: 213px;
            padding-left: 23px;
            padding-top: 10px;
            padding-right: 25px;
        }

        .container-preview-inner .card-info .img {
            float: left;
            margin-top: 9px;
        }

        .container-preview-inner .card-info .title {
            float: left;
            color: white;
            font-size: 20px;
        }

        .container-preview-inner .card-info .description {
            float: left;
            color: white;
            width: 279px;
            height: 69px;
            margin-top: 14px;
        }

        .container-preview-inner .card-info-user {
            width: 279px;
            margin-top: 9px;
            height: 123px;
            float: left;
            border-radius: 5px;
            background-color: #fbfcfc;
            box-shadow: 0 4px 4px rgb(0 0 0 / 20%);
        }

        .container-preview-inner .card-info-user .img {
            width: 60px;
            float: left;
            margin-left: 14px;
            margin-top: 10px;
            color: black
        }

        .container-preview-inner .card-info-user .title {
            float: left;
            color: black;
            margin-left: 14px;
            margin-top: 24px;
            font-size: 16px;
        }

        .container-preview-inner .card-info-user .subtitle {
            position: absolute;
            font-size: 13px;
            margin-left: 86px;
            margin-top: 50px;
        }

        .container-preview-inner .card-info-user .button {
            float: left;
            width: 230px;
            height: 38px;
            color: white;
            font-size: 16px;
            margin-top: 78px;
            margin-left: -109px;
            padding-left: 81px;
            padding-top: 7px;
            border-radius: 52px;
            background-color: #25d366;
        }

        .container-preview-inner .card-info-user .icon {
            margin-left: -174px;
            position: absolute;
            margin-top: 86px;
            font-size: 21px;
            color: white;
        }

        .box-position {
            display: inline;
            padding-right: 10%;
        }

        .box-position label,
        .box-position input {
            cursor: pointer;
        }

        @media (max-width: 1500px) {
            .box-position {
                padding-right: 5%;
            }
        }

        @media (max-width: 1390px) {
            .box-position {
                padding-right: 1%;
            }
        }

        @media (max-width: 1330px) {
            .box-position {
                padding-right: -10px;
            }
        }
    </style>


    <div class="row justify-content-center">
        <div class="col-lg-8 center-block col-centered" style="background-color:white; padding-left:0px; padding-right:0px ;">
            <div class="card card-pricing border-0 mb-0">

                <div class="card-header bg-transparen text-center" style="padding-bottom: 1px; padding-top: 9px;">
                    <h4 class="text-uppercase ls-1 text-primary py-3 mb-0"><?php echo $this->lang->line("setting_integration_widget_edit_topnav") ?></h4>
                </div>

                <ul id="progress">
                    <li id="button" class="active-ball"><?php echo $this->lang->line("setting_integration_widget_edit_button") ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
                    <li id="title"><?php echo $this->lang->line("setting_integration_widget_edit_title") ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
                    <li id="conclude"><?php echo $this->lang->line("setting_integration_widget_edit_complete") ?> &nbsp;&nbsp;&nbsp;</li>
                </ul>
            </div>

            <div class="card card-pricing mb-4" id="phases">
                <div class="card-body px-lg-12 container-options">

                    <!-- Etapa 01 -->

                    <div class="col-lg-12" id="etp_button" style="padding-top: 5px; display:block">

                        <div class="form-group">
                            <label class="form-control-label" for="input-button"><?php echo $this->lang->line("setting_integration_widget_edit_button_name") ?></label>
                            <input type="text" id="input-button" class="form-control" placeholder="<?php echo $this->lang->line("setting_integration_widget_edit_button_name_placeholder") ?>" maxlength="25" value="<?php echo $queryWidget[0]['button_text'] ?>">
                        </div>

                        <div class="form-group">
                            <label class="form-control-label" for="input-button" style="position: absolute; margin-top: 2px;"><?php echo $this->lang->line("setting_integration_widget_edit_button_color") ?></label>
                            <div id="color1" name="123" class="palette" style="background-color:#333399"></div>
                            <div id="color2" class="palette" style="margin-left: 50px;background-color:#0033cc"></div>
                            <div id="color3" class="palette" style="margin-left: 100px;background-color:#ac39ac"></div>
                            <div id="color4" class="palette" style="margin-left: 150px;background-color:#999966"></div>
                            <div id="color5" class="palette" style="margin-left: 200px;background-color:#009933"></div>
                            <div id="color6" class="palette" style="margin-left: 250px;background-color:rgb(37 211 102)"></div>
                            <div id="color7" class="palette" style="margin-left: 300px;background-color:#e6b800"></div>
                            <div id="color8" class="palette" style="margin-left: 350px;background-color:#ff3300"></div>
                            <div id="color9" class="palette" style="margin-left: 400px;background-color:#cc0000"></div>
                            <div id="color10" class="palette" style="margin-left: 450px;background-color:#ff0066"></div>
                        </div></br>

                        <div class="form-group">
                            <div style="margin-top:77px">

                                <label class="form-control-label"><?php echo $this->lang->line("setting_intergration_widget_edit_screen_position") ?></label><br>

                                <div class="box-position">
                                    <input type="radio" class="position" name="position" id="left" value="left" <?php echo $queryWidget[0]['position'] == "left" ? "checked" : "" ?>>
                                    <label for="left" style="font-weight:bold"><?php echo $this->lang->line("setting_intergration_widget_edit_position_left") ?></label>
                                </div>

                                <div class="box-position">
                                    <input class="position" type="radio" id="center" name="position" value="center" <?php echo $queryWidget[0]['position'] == "center" ? "checked" : "" ?>>
                                    <label for="center" style="font-weight:bold"><?php echo $this->lang->line("setting_intergration_widget_edit_position_center") ?></label>
                                </div>

                                <div class="box-position">
                                    <input class="position" type="radio" id="right" name="position" value="right" <?php echo $queryWidget[0]['position'] == "right" ? "checked" : "" ?>>
                                    <label for="right" style="font-weight:bold"><?php echo $this->lang->line("setting_intergration_widget_edit_position_right") ?></label>
                                </div>

                            </div>
                        </div>

                        <a href="<?php echo base_url() . '/integration' ?>"><button class="btn btn-primary" type="button" style="margin-top:14px"><?php echo $this->lang->line("setting_integration_widget_edit_btn_return") ?></button></a>
                        <a><button type="button" id="btn_etp_button" class="btn btn-success" style="margin-top:14px"><?php echo $this->lang->line("setting_integration_widget_edit_btn_next") ?></button></a>
                    </div>


                    <!-- Etapa 02 -->

                    <div class="col-lg-12" id="etp_title" style="padding-top: 5px; display:none">
                        <div class="form-group">
                            <label class="form-control-label" for="input-title"><?php echo $this->lang->line("setting_integration_widget_name") ?></label>
                            <input type="text" id="nameWidget" class="form-control" placeholder="<?php echo $this->lang->line("setting_integration_widget_name_placeholder") ?>" maxlength="23" value="<?php echo $queryWidget[0]['name'] ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-control-label" for="input-title"><?php echo $this->lang->line("setting_integration_widget_edit_title") ?></label>
                            <input type="text" id="input-title" class="form-control" placeholder="<?php echo $this->lang->line("setting_integration_widget_edit_title_placeholder") ?>" maxlength="23" value="<?php echo $queryWidget[0]['title'] ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-control-label" for="input-subtitle"><?php echo $this->lang->line("setting_integration_widget_edit_subtitle") ?></label>
                            <input type="text" id="input-subtitle" class="form-control" maxlength="100" placeholder="<?php echo $this->lang->line("setting_integration_widget_edit_subtitle_placeholder") ?>" value="<?php echo $queryWidget[0]['subtitle'] ?>">
                        </div>
                        <a><button type="button" id="btn_close_title" class="btn btn-primary"><?php echo $this->lang->line("setting_integration_widget_edit_btn_return") ?></button></a>
                        <a><button type="button" id="btn_etp_title" class="btn btn-success"><?php echo $this->lang->line("setting_integration_widget_edit_btn_save") ?></button></a>
                    </div>

                </div>

                <!-- Etapa 03 -->

                <div class="container-preview">
                    <div class="text-center container-preview-button" id="container-preview-button" style=" background-color: <?php echo $queryWidget[0]['button_color'] ?>">
                        <input type="hidden" name="<?php echo $queryWidget[0]['button_color'] ?>" id="input-color">
                        <div class="img">
                            <img src="https://whts.co/img/widget_logo.svg" alt="">
                        </div>
                        <div class="button">
                            <span id="button-msg"><?php echo $queryWidget[0]['button_text'] ?></span>
                        </div>
                    </div>

                    <div class="container-preview-inner" id="container-preview-inner" style="display:none">
                        <div class="card-info">
                            <div class="img">
                                <img src="<?php echo base_url("/assets/img/favicon.png"); ?>" alt="">
                            </div><br><br>
                            <div class="title">
                                <strong id="title-msg"><?php echo $queryWidget[0]['title'] ?></strong>
                            </div><br>
                            <div class="description">
                                <span id="subtitle-msg"><?php echo $queryWidget[0]['subtitle'] ?></span>
                            </div>
                            <div class="card-info-user">
                                <div class="img">
                                    <img width="100%" src="<?php echo base_url("/assets/img/avatar.png"); ?>" alt="">
                                </div>
                                <div class="title"><?php echo $this->lang->line("setting_integration_widget_edit_talkall") ?></div>
                                <div class="subtitle"><?php echo $this->lang->line("setting_integration_widget_edit_answers") ?></div>
                                <div class="button"><?php echo $this->lang->line("setting_integration_widget_edit_whatsapp") ?></div>
                                <i class="fab fa-whatsapp icon"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- modal loading -->

                <div class="modal fade" id="div_loading_req" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="loading-req">
                            <img src="<?php echo base_url("assets/img/loads/loading_1.gif") ?>" alt="">
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>