/* FUNÇÕES QUE PODEM SER CHAMADAS EM QULAQUER TELA */
var timeCode = ""

function uploadUserProfilePicture(key_remote_id) {
    swal({
        title: GLOBAL_LANG.topnav_alert_photo_title,
        showCancelButton: !0,
        buttonsStyling: !1,
        reverseButtons: true,
        confirmButtonClass: "btn btn-success",
        confirmButtonText: GLOBAL_LANG.topnav_alert_photo_confirmButtonText,
        cancelButtonClass: "btn btn-danger",
        cancelButtonText: GLOBAL_LANG.topnav_alert_photo_cancelButtonText,
        closeModal: false,
        closeOnComfirm: false,
        allowOutsideClick: false,
        html: GLOBAL_LANG.topnav_alert_photo_html,
        onOpen: () => {

            let imgurl = "https://files.talkall.com.br:3000/p/" + key_remote_id + ".jpeg";

            $.ajax({
                type: "GET",
                url: imgurl,
                error: function (response) {
                    $uploadCrop = $('#upload-demo').croppie({
                        enableExif: true,
                        url: document.location.origin + "/assets/img/avatar.svg",
                        viewport: {
                            width: 200,
                            height: 200,
                        },
                        boundary: {
                            width: 300,
                            height: 300
                        },
                        format: 'jpeg'
                    });
                },
                success: function (response) {
                    $uploadCrop = $('#upload-demo').croppie({
                        enableExif: true,
                        url: imgurl,
                        viewport: {
                            width: 200,
                            height: 200,
                        },
                        boundary: {
                            width: 300,
                            height: 300
                        },
                        format: 'jpeg'
                    });
                }
            });

            $("#upload-btn").on("click", function () {
                $('#upload-user-picture').click();
            });

            $("#upload-user-picture").on("change", function () {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $uploadCrop.croppie('bind', {
                        url: e.target.result
                    }).then(function () {
                        console.log('jQuery bind complete');
                    });
                }
                reader.readAsDataURL(this.files[0]);
            });

        }
    }).then(t => {
        if (t.value == true) {
            $uploadCrop.croppie('result', {
                type: 'canvas',
                size: 'viewport',
                format: 'jpeg'
            }).then(function (resp) {

                swal({
                    title: GLOBAL_LANG.topnav_alert_photo_two_title,
                    text: GLOBAL_LANG.topnav_alert_photo_two_text,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    onOpen: () => {
                        swal.showLoading();
                    }
                });

                function b64toBlob(b64Data, contentType, sliceSize) {
                    contentType = contentType || '';
                    sliceSize = sliceSize || 512;

                    var byteCharacters = atob(b64Data);
                    var byteArrays = [];

                    for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
                        var slice = byteCharacters.slice(offset, offset + sliceSize);

                        var byteNumbers = new Array(slice.length);
                        for (var i = 0; i < slice.length; i++) {
                            byteNumbers[i] = slice.charCodeAt(i);
                        }

                        var byteArray = new Uint8Array(byteNumbers);

                        byteArrays.push(byteArray);
                    }

                    var blob = new Blob(byteArrays, { type: contentType });
                    return blob;
                }

                var ImageURL = resp;
                // Split the base64 string in data and contentType
                var block = ImageURL.split(";");
                // Get the content type of the image
                var contentType = block[0].split(":")[1];// In this case "image/gif"
                // get the real base64 content of the file
                var realData = block[1].split(",")[1];// In this case "R0lGODlhPQBEAPeoAJosM...."


                // Convert it to a blob to upload
                var blob = b64toBlob(realData, contentType);

                const formData = new FormData();

                formData.append("filetoupload", blob);
                formData.append("media_id", key_remote_id);
                formData.append("media_mime_type", "jpeg");

                const settings = {
                    "url": "https://files.talkall.com.br:3000/p",
                    "method": "POST",
                    "timeout": 0,
                    "crossDomain": true,
                    "processData": false,
                    "mimeType": "multipart/form-data",
                    "contentType": false,
                    "data": formData
                };

                $.ajax(settings).done(function (response) {
                    previewPictureProfile(JSON.parse(response), key_remote_id);
                });
            });

        }
    });
}


function previewPictureProfile(data, key_remote_id) {
    if (key_remote_id == USER_KEY_REMOTE_ID) {
        $('#profile-picture').attr('src', data.url);
        $('#user-picture').attr('src', data.url);
    } else {
        $('.profile_user').attr('src', data.url);
    }

    swal.close();
}


function RequestPassword(email) {

    // TODO: COLOCAR SIM OU NÃO 
    swal({
        title: GLOBAL_LANG.topnav_alert_ordering,
        text: GLOBAL_LANG.topnav_alert_please,
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        onOpen: () => {
            swal.showLoading();
        }
    });

    var dt = new Date();
    window.localStorage.setItem('security_alert', dt.setMinutes(dt.getMinutes() + 30));

    var url = document.location.origin + "/account/ResetPassword";
    var dados = new FormData();
    dados.append('no_redirect', "true");
    dados.append('email', email);
    dados.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

    $.ajax({
        type: "POST",
        url: url,
        data: dados,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            swal({
                title: response.title,
                html: response.message,
                type: "success",
                buttonsStyling: !1,
                confirmButtonClass: "btn btn-success"
            });
        },
        error: function (response) {
            swal({
                title: GLOBAL_LANG.topnav_alert_email_not_send,
                html: response.responseText,
                type: "error",
                buttonsStyling: !1,
                confirmButtonClass: "btn btn-primary"
            });
        },
    });
}


function SendValidationEmail(id) {

    swal({
        title: GLOBAL_LANG.topnav_alert_ordering,
        text: GLOBAL_LANG.topnav_alert_please,
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        onOpen: () => {
            swal.showLoading();
        }
    });

    $('input[name=csrf_token_talkall]').val(Cookies.get("csrf_cookie_talkall"));

    var dt = new Date();
    window.localStorage.setItem('security_alert', dt.setMinutes(dt.getMinutes() + 30));

    var url = document.location.origin + `/email/resend_mail/`;
    var data = new FormData($('form')[0]);

    data.append('user_id', id);

    $.ajax({
        type: "POST",
        url: url,
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            console.log(url);
            swal({
                title: GLOBAL_LANG.topnav_alert_email_send,
                html: response,
                type: "success",
                buttonsStyling: !1,
                confirmButtonClass: "btn btn-primary"
            });
        },
        error: function (response) {
            console.log(url);
            swal({
                title: GLOBAL_LANG.topnav_alert_email_not_send,
                html: response.responseText,
                type: "error",
                buttonsStyling: !1,
                confirmButtonClass: "btn btn-primary"
            });
        },
    });
}


function RequestResendActivation(id) {

    // TODO: COLOCAR SIM OU NÃO 
    swal({
        title: GLOBAL_LANG.topnav_alert_ordering,
        text: GLOBAL_LANG.topnav_alert_please,
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        onOpen: () => {
            swal.showLoading();
        }
    });

    var dt = new Date();
    window.localStorage.setItem('security_alert', dt.setMinutes(dt.getMinutes() + 30));

    var url = document.location.origin + `/email/confirm/${id}`;

    $.ajax({
        type: "GET",
        url: url,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            swal({
                title: GLOBAL_LANG.topnav_alert_email_send,
                html: response,
                type: "success",
                buttonsStyling: !1,
                confirmButtonClass: "btn btn-primary"
            });
        },
        error: function (response) {
            swal({
                title: GLOBAL_LANG.topnav_alert_email_not_send,
                html: response.responseText,
                type: "error",
                buttonsStyling: !1,
                confirmButtonClass: "btn btn-primary"
            });
        },
    });
}


function RequestTwoFa(key_remote_id, setTwoFa) {
    var phone;
    Swal.mixin({
        confirmButtonText: GLOBAL_LANG.topnav_alert_two_factor_authentication_btn_advance,
        cancelButtonText: GLOBAL_LANG.topnav_alert_two_factor_authentication_btn_cancel,
        showCancelButton: true,
        reverseButtons: true,
        buttonsStyling: !1,
        showCancelButton: !0,
        confirmButtonClass: "btn btn-success",
        cancelButtonClass: "btn btn-danger",
        progressSteps: ['1', '2']
    }).queue([
        ({
            title: GLOBAL_LANG.topnav_alert_two_factor_authentication,
            text: GLOBAL_LANG.topnav_alert_your_cell_phone,
            input: 'text',
            inputAttributes: {
                id: 'sp_celphones',
                style: 'text-align: center;'
            },
            inputValidator: (value) => {
                if (value.length < 13) {
                    return GLOBAL_LANG.topnav_alert_valid_cell_phone;
                }
            },
            onOpen: () => {
                $("#sp_celphones").focus();
                var SPMaskBehavior = function (val) {
                    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
                },
                    spOptions = {
                        onKeyPress: function (val, e, field, options) {
                            field.mask(SPMaskBehavior.apply({}, arguments), options);
                        }
                    };

                $('#sp_celphones').mask(SPMaskBehavior, spOptions);
            },
            preConfirm: () => {
                phone = "55" + $("#sp_celphones").val().replace(/\D/g, '');
                var url = document.location.origin + "/account/addtwofa";
                var dados = new FormData();
                dados.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));
                dados.append('key_remote_id', key_remote_id);
                dados.append('setTwoFa', setTwoFa);
                dados.append('resend', false);
                dados.append('phone', phone);

                $("body").append(`<input type='hidden' id='user-phone' value='${phone}'>`);
                $("body").append(`<input type='hidden' id='user-key-remote-id' value='${key_remote_id}'>`);

                $.ajax({
                    type: "POST",
                    url: url,
                    data: dados,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    success: function (data) {

                        if (data.type == "error") {
                            swal({
                                type: 'error',
                                title: GLOBAL_LANG.topnav_alert_sms_error_title,
                                text: GLOBAL_LANG.topnav_alert_sms_error_btn_text
                            });
                        }
                        return;
                    },
                    error: function (output, status, xhr) {
                        swal({
                            title: GLOBAL_LANG.topnav_alert_sms_not_sent,
                            type: "error",
                            buttonsStyling: !1,
                            confirmButtonClass: "btn btn-danger"
                        });
                    }
                });
            }
        }),
        {
            allowOutsideClick: false,
            showCancelButton: true,
            showConfirmButton: false,
            buttonsStyling: !1,
            cancelButtonClass: "btn btn-danger",
            title: GLOBAL_LANG.topnav_alert_two_factor_authentication,
            html: "<span style='display:block;font-size:16px;color:rgb(84, 84, 84);height:25px'><span id='resendCodeSMS' style='display:none;cursor:pointer;'>" + GLOBAL_LANG.topnav_alert_two_factor_authentication_html_code_resend + "</span></span>" +
                "<h3 class='mb-3'>" + GLOBAL_LANG.topnav_alert_two_factor_authentication_html_code + "</h3>" +
                "<div class='d-flex justify-content-center'>" +
                "<div class='form-inline'>" +
                "<b class='p-1'> </b><input type='text' class='form-control fild' maxlength='1' id='1-twofa'><b class='p-1'>-</b>" +
                "<input type='text' class='form-control fild' maxlength='1' id='2-twofa'><b class='p-1'>-</b>" +
                "<input type='text' class='form-control fild' maxlength='1' id='3-twofa'><b class='p-1'>-</b>" +
                "<input type='text' class='form-control fild' maxlength='1' id='4-twofa'><b class='p-1'>-</b>" +
                "<input type='text' class='form-control fild' maxlength='1' id='5-twofa'><b class='p-1'>-</b>" +
                "<input type='text' class='form-control fild' maxlength='1' id='6-twofa'><b class='p-1'> </b>" +
                "<label for='fild' class='invalid-feedback mt-2' id='error-label'>" + GLOBAL_LANG.topnav_alert_two_factor_authentication_html_code_invalid + "</label>" +
                "</div>" +
                "</div>" + "<style> .fild{ width: 13% !important; border: #696969 solid 0.5px; text-align: center;} .fild:focus{ border: #a0a0a0 solid 0.5px;}</style>",
            //timer: 120000,
            //timerProgressBar: true,
            onOpen: () => {
                var tentativas = 1;

                textResendCode();

                function onError() {
                    tentativas = (tentativas + 1)

                    if (tentativas <= 3) {
                        $('#error-label').show();
                        $(".fild").addClass('is-invalid');
                        $(".fild").val('');
                        $('#1').focus();
                    }
                    else {
                        swal({
                            type: "error",
                            title: GLOBAL_LANG.topnav_alert_two_factor_authentication_error_title,
                            text: GLOBAL_LANG.topnav_alert_two_factor_authentication_error_text,
                            buttonsStyling: !1,
                            confirmButtonClass: "btn btn-danger"
                        });
                    }
                }

                function CheckTwoFa() {
                    var dados = new FormData();
                    var url = document.location.origin + "/account/checktwofa";
                    dados.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));
                    dados.append('key_remote_id', key_remote_id);
                    dados.append('phone', phone);
                    dados.append('setTwoFa', setTwoFa);
                    dados.append('code', ($('#1-twofa').val() + $('#2-twofa').val() + $('#3-twofa').val() + $('#4-twofa').val() + $('#5-twofa').val() + $('#6-twofa').val()));

                    $.ajax({
                        url: url,
                        type: "POST",
                        data: dados,
                        processData: false,
                        contentType: false,
                        success: function (data, textStatus) {

                            if (data.type == "active") {
                                swal({
                                    type: 'success',
                                    title: GLOBAL_LANG.topnav_alert_check_two_factor_success_title,
                                    text: GLOBAL_LANG.topnav_alert_check_two_factor_success_text,
                                    buttonsStyling: !1,
                                    confirmButtonClass: "btn btn-success"
                                });

                            } else if (data.type == "inactive") {
                                swal({
                                    type: 'success',
                                    title: GLOBAL_LANG.topnav_alert_check_two_factor_inactive_title,
                                    text: GLOBAL_LANG.topnav_alert_check_two_factor_inactive_text,
                                    buttonsStyling: !1,
                                    confirmButtonClass: "btn btn-success"
                                });

                            } else if (data.type == "error") {
                                swal({
                                    type: 'error',
                                    title: GLOBAL_LANG.topnav_alert_check_two_factor_error_title,
                                    text: GLOBAL_LANG.topnav_alert_check_two_factor_error_text,
                                    buttonsStyling: !1,
                                    confirmButtonClass: "btn btn-danger"
                                });

                            } else if (data.type == "error_number") {
                                swal({
                                    type: 'error',
                                    title: GLOBAL_LANG.topnav_alert_check_two_factor_error_number_title,
                                    text: GLOBAL_LANG.topnav_alert_check_two_factor_error_number_text,
                                    buttonsStyling: !1,
                                    confirmButtonClass: "btn btn-danger"
                                });
                            }

                            window.location.reload();
                        },
                        error: function (data, textStatus) {
                            onError();
                        }
                    });
                }


                $('#error-label').hide();
                $('#1').focus();

                $(".fild").keyup(function () {
                    if (this.value.length == this.maxLength) {
                        if ($(this).attr('id') == "1-twofa") {
                            $(".fild").removeClass('is-invalid');
                            $('#error-label').hide();
                        }

                        if ($(this).attr('id') == "6-twofa") {
                            $.debounce(500, CheckTwoFa());
                        }
                        else {
                            $(this).nextAll('input').first().focus();
                        }
                    }
                });
            }
        }
    ]);
}

function resendCodeSMS() {

    var url = document.location.origin + "/account/addtwofa";
    var dados = new FormData();
    dados.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));
    dados.append('phone', $("#user-phone").val().replace(/\D/g, ''));
    dados.append('key_remote_id', $("#user-key-remote-id").val());
    dados.append('setTwoFa', true);
    dados.append('resend', true);

    $.ajax({
        type: "POST",
        url: url,
        data: dados,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (data) {

            console.log(data);

            if (data.type == "success") {
                textResendCode(false);
            } else if (data.type == "expired_attempts") {
                textResendCode(true);
            }
            return;
        },
        error: function (output, status, xhr) {
            swal({
                title: GLOBAL_LANG.topnav_alert_sms_not_sent,
                type: "error",
                buttonsStyling: !1,
                confirmButtonClass: "btn btn-primary"
            });
        }
    });

    document.getElementById("resendCodeSMS").style.display = "none";
}


function textResendCode(expired) {

    if (expired) {

        const elem = document.getElementById("resendCodeSMS");
        elem.innerHTML = GLOBAL_LANG.topnav_alert_resendCodeSMS;
        elem.style.display = "block";
        elem.removeEventListener("click", resendCodeSMS, false);

    } else {

        timeCode = setTimeout(() => {
            const elem = document.getElementById("resendCodeSMS");
            elem.style.display = "inline-block";

        }, 12000);

        document.getElementById("resendCodeSMS").addEventListener("click", resendCodeSMS);
    }
}


function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}


function changeLanguage(value) {

    $(".bgboxLoad").fadeIn("fast");
    $(".languageLoad").fadeIn("fast");


    let data = new FormData();
    data.append("language", value);
    data.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

    $.ajax({
        type: "POST",
        url: document.location.origin + "/account/changeLanguage",
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            localStorage.setItem('language', value);
            location.reload();
        },
        error: function (response) {
            swal.close();
            swal({
                type: 'error',
                title: 'Oops...',
                text: 'Ocorreu um erro na operação!'
            });
        }
    });
}
