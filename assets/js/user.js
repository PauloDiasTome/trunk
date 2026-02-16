"use strict";

const Filters = JSON.parse(localStorage.getItem("filters")) || null;


function checkFilter() {
    if (Filters) {

        if (!Filters.btn_back) return

        if (Filters.user.search) {
            document.getElementById("search").value = Filters.user.search;
        }

        if (Filters.user.input_search) {
            document.getElementById("input-search").value = Filters.user.search;
        }

        if (Filters.user.sector.length !== 0) {
            modalFilter();
            document.getElementById("check-select2").click();

            document.querySelectorAll("#mult-select2 .cust_").forEach((element, index) => {

                if (Filters.user.sector.includes(element.value))
                    document.querySelectorAll("#mult-select2 .cust_")[index].click();
            })
        }

        if (Filters.user.situation) {
            modalFilter();
            document.getElementById("check-situation").click();
            document.getElementById("select-situation").value = Filters.user.situation;
        }

        Filters.btn_back = null;
        localStorage.setItem("filters", JSON.stringify(Filters));
    }
}

checkFilter();


function find() {

    $('#datatable-basic').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "user/find",
            type: "POST",
            data: {
                text: $("#search").val(),
                sector: $('#verify-select2').val() == '2' ? '' : ($('#multiselect2').val().length == 0 ? '' : $('#multiselect2').val()),
                situation: $('#select-situation').val() == '' ? '' : $('#select-situation').val(),
                dt_start: $('#dt-start').val(),
                dt_end: $('#dt-end').val(),
                csrf_talkall: Cookies.get("csrf_cookie_talkall"),
            }
        },
        columns: [{
            mData: 'creation'
        },
        {
            mData: 'department'
        },
        {
            mData: 'email'
        },
        {
            mData: 'status'
        }
        ],
        columnDefs: [
            {
                targets: 0,
                render: function (data, type, full, meta) {
                    return "<div class='kt-user-card-v2'>" + "<div class='kt-user-card-v2__pic'>" +
                        "<img src='" + full.profile + "' class='avatar rounded-circle mr-3 m-img-rounded kt-marginless' alt='photo'>" +
                        "</div>" +
                        "<div class='kt-user-card-v2__details'>" +
                        "<b class='kt-user-card-v2__name'>" + full.name + "</b>" +
                        "</div>" +
                        "</div>";
                }
            },
            {
                targets: 1,
                render: function (data, type, full, meta) {
                    return "<b>" + data + "</b>";
                }
            },
            {
                orderable: false,
                targets: 3,
                render: function (data, type, full, meta) {
                    if (full.status == "1") {
                        return "<span style='background: #fff;' data-toggle='tooltip' data-placement='right' title='" + GLOBAL_LANG.user_datatle_column_verified_user + "'><i class='fas fa-check' style='color: green; font-size: 13pt;'></i></i></span>";
                    } else if (full.status == "4") {
                        return "<span style='background: #fff;' data-toggle='tooltip' data-placement='right' title='" + GLOBAL_LANG.user_datatle_column_blocked_user + "'><i class='fas fa-sharp fa-solid fa-ban' style='color: red; font-size: 13pt;'></i></i></span>";
                    } else {
                        return "<span style='background: #fff;' data-toggle='tooltip' data-placement='right' title='" + GLOBAL_LANG.user_datatle_column_unverified_user + "'><i class='fas fa-exclamation-triangle' style='color: orange; font-size: 13pt;'></i></i></span>";
                    }
                }
            },
            {
                orderable: false,
                targets: 4,
                render: function (data, type, full, meta) {

                    return `<div class="dropleft">
                                    <button class="btn btn-link mb-0 btn-dropleft" type="button" id="multiDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #525f7f">
                                            <i class="fa fa-ellipsis-v"></i>
                                    <div class="ripple-container"></div>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="multiDropdownMenu">
                                        <a id="` + full.id_user + `" href='#' class="dropdown-item table-btn-edit" style="cursor: pointer">
                                            <div style="width: 24px; display: inline-block">     
                                                    <i class="far fa-edit"></i>
                                                </div>
                                                <span>${GLOBAL_LANG.user_datatle_column_action_edit}</span>
                                            </a>
                                        <a id="` + full.id_user + `" href='#' class="dropdown-item table-btn-delete" style="cursor: pointer">
                                            <div style="width: 24px; display: inline-block"> 
                                                    <i class="far fa-trash-alt"></i>
                                                </div>
                                                <span>${GLOBAL_LANG.user_datatle_column_action_delete}</span>
                                            </a>
                                        <a href="` + location.origin + "/account/security/" + full.id_user + `" class="dropdown-item table-btn-security" style="cursor: pointer">
                                            <div style="width: 24px; display: inline-block"> 
                                                <i class="fas fa-shield-alt"></i>
                                            </div>
                                            <span>${GLOBAL_LANG.user_datatle_column_action_security}</span>
                                        </a>
                                    </div>
                                </div>`
                }
            },
            {
                orderable: true,
                targets: [0, 1, 2, 3]
            }
        ],
        pagingType: "numbers",
        pageLength: 10,
        destroy: true,
        fixedHeader: true,
        responsive: true,
        lengthChange: true,
        searching: false,
        paginate: true,
        info: true,
        language: {
            url: `${document.location.origin}/assets/lang/${localStorage.getItem("language")}.json`
        },

        drawCallback: function (settings) {
            if (settings.json.recordsTotal == 0) {
                $("#modalExport").attr('disabled', true);
            } else {
                $("#modalExport").attr('disabled', false);
            }

            const user = {
                search: $("#search").val(),
                input_search: $("#input-search").val(),
                sector: $("#multiselect2").val(),
                situation: $("#select-situation").val(),
            }

            let filter = localStorage.getItem("filters");

            filter = filter ? JSON.parse(filter) : {};
            filter.user = user;

            localStorage.setItem("filters", JSON.stringify(filter));
        }
    });
}


function passwordRecovery() {

    var dados = new FormData();
    dados.append('email', $("#data-email-user").text());
    dados.append('key_remote_id', $("#data-user-key_remote_id").val());
    dados.append('origin_key_remote_id', USER_KEY_REMOTE_ID);
    dados.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

    var url = document.location.origin + "/account/ResetPassword/User";

    $.ajax({
        type: "POST",
        url: url,
        data: dados,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            swal({
                title: GLOBAL_LANG.user_alert_email_title,
                html: response,
                type: "success",
                buttonsStyling: !1,
                confirmButtonClass: "btn btn-success"
            });
        },
        error: function (response) {
            swal({
                title: GLOBAL_LANG.user_alert_email_two_title,
                html: response.responseText,
                type: "error",
                buttonsStyling: !1,
                confirmButtonClass: "btn btn-primary"
            });
        },

    });
}



function security() {
    $('#datatable-history').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: $(location).attr('href'),
            type: "POST",
            data: {}
        },
        columns: [
            {
                mData: 'date'
            },
            {
                mData: 'text'
            },
            {
                mData: 'system'
            },
            {
                mData: 'browser'
            },
            {
                mData: 'ip'
            }
        ],
        columnDefs: [
            {
                targets: 1,
                render: function (data, type, full, meta) {
                    switch (full.text) {
                        case 'login':
                        case 'login in':
                        case 'LOGIN_ACCESS_SUCCESS':
                            return GLOBAL_LANG.user_security_text_login_access_success;
                        case 'Alterou a senha':
                        case 'LOGIN_CHANGE_PASSWORD':
                            return GLOBAL_LANG.user_security_text_login_change_password;
                        case 'LOGIN_ERROR_PASSWORD':
                            return GLOBAL_LANG.user_security_text_login_error_password;
                        case 'LOGIN_USER_BLOCK':
                            return GLOBAL_LANG.user_security_text_login_user_block;
                        case 'Autenticação de dois fatores falhou':
                        case 'LOGIN_TWO_FACTOR_AUTHENTICATION_FAILED':
                            return GLOBAL_LANG.user_security_text_login_two_factor_authentication_failed;
                        case 'LOGIN_USER_BLOCK_TWO_FACTOR':
                            return GLOBAL_LANG.user_security_text_login_user_block_two_factor;
                        case 'LOGIN_CHANGE_DEFAULT_INTRANET':
                            return GLOBAL_LANG.user_security_text_login_change_default_intranet;
                        case 'LOGIN_UNLOCKED_INTRANET':
                            return GLOBAL_LANG.user_security_text_login_unlocked_intranet;
                        case 'Deletou um usuário':
                        case 'DELETE_USER':
                            return GLOBAL_LANG.user_security_text_deleted_user;
                    }
                }
            }
        ],
        pagingType: "numbers",
        pageLength: 5,
        destroy: true,
        fixedHeader: true,
        responsive: true,
        lengthChange: false,
        searching: false,
        paginate: true,
        info: true,
        language: {
            url: `${document.location.origin}/assets/lang/${localStorage.getItem("language")}.json`
        },

        drawCallback: function (settings) {
            if (settings.json.recordsTotal == 0) {
                $("#modalExport").attr('disabled', true);
            }
        }

    });

}


$(document).ready(function () {

    $('#search').on('keyup', (e) => {
        if (e.which == 13) {
            document.getElementById("input-search").value = e.target.value;
            find();
        }
    });


    if ($("#search").val() !== undefined) {
        find();
    }


    $("#datatable-basic").on("click", ".table-btn-edit", function () {
        window.location.href = "user/edit/" + this.id;
    });


    $("#datatable-basic").on("click", ".table-btn-delete", function () {
        swal({
            title: GLOBAL_LANG.user_alert_delete_title,
            text: GLOBAL_LANG.user_alert_delete_text,
            type: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            reverseButtons: true,
            confirmButtonClass: "btn btn-success",
            confirmButtonText: GLOBAL_LANG.user_alert_delete_confirmButtonText,
            cancelButtonClass: "btn btn-danger",
            cancelButtonText: GLOBAL_LANG.user_alert_delete_cancelButtonText,
        }).then(t => {
            if (t.value == true) {
                $.post("user/delete/" + this.id, function (data) {

                    if (data.status == 1 || data.status == 3) {
                        Swal.fire({
                            title: GLOBAL_LANG.user_alert_warning_delete_title,
                            text: data.status == 1 ? GLOBAL_LANG.user_alert_warning_delete : GLOBAL_LANG.user_alert_warning_delete_contact_waiting,
                            type: 'warning',
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: GLOBAL_LANG.user_alert_export_confirmButtonText
                        });
                        $('.swal2-container').css('z-index', 10000);

                    } else if (data.status == 2) {

                        const name_user = document.getElementById("name-user");
                        name_user.innerHTML = data.user;

                        const id_user = document.getElementById("id-user");
                        id_user.value = data.id_user;

                        const select_user = document.querySelectorAll("#select-user option");

                        let group_name = [];

                        for (let i = 0; i < select_user.length; i++) {

                            group_name = select_user[i].innerHTML;

                            if (select_user[i].value == data.id_user) {
                                var elm_select_user = select_user[i];
                                elm_select_user.remove();
                            }
                        }

                        $("#modal-user-transfer").modal();

                    } else {
                        t.value && swal({
                            title: GLOBAL_LANG.user_alert_delete_two_title,
                            text: GLOBAL_LANG.user_alert_delete_two_text,
                            type: "success",
                            buttonsStyling: !1,
                            confirmButtonClass: "btn btn-success"
                        });
                        $("#datatable-basic").DataTable().ajax.reload(null, false);
                    }
                });
            }
        });
    });


    if ($("#alert_success").length) {

        if ($("#alert_success").val().length) {

            swal({
                title: GLOBAL_LANG.user_alert_notification_email_title,
                html: GLOBAL_LANG.user_alert_notification_email_text + "<strong>" + $("#alert_success").val() + "<strong>",
                type: "success",
                buttonsStyling: !1,
                reverseButtons: true,
                confirmButtonClass: "btn btn-success",
                confirmButtonText: GLOBAL_LANG.user_alert_export_confirmButtonText,

            }).then(t => {
                if (t.value == true) {

                    window.location.href = "/user#";
                }

            });
        };

    }



    $("#user-picture").on("click", function () {
        uploadUserProfilePicture($("#key_remote_id").val());
    });


    $('#reset-password').on("click", function (event) {
        event.preventDefault();
        passwordRecovery();
    });


    $("#input-email").on("keyup keydown", function () {
        document.getElementById("input-email").value = document.getElementById("input-email").value.toLowerCase();
        $("#email-msg").text($("#input-email").val());
    });


    security();


    $("#btn-alert-return").on("click", () => {
        $("#select-user").val(0);
        $("#modal-user-transfer").modal();
    });


    $("#btn-alert-confirm").on("click", function () {

        swal({
            title: GLOBAL_LANG.user_alert_delete_three_title,
            text: GLOBAL_LANG.user_alert_delete_three_text,
            type: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            reverseButtons: true,
            confirmButtonClass: "btn btn-success",
            confirmButtonText: GLOBAL_LANG.user_alert_delete_three_confirmButtonText,
            cancelButtonClass: "btn btn-danger",
            cancelButtonText: GLOBAL_LANG.user_alert_delete_three_cancelButtonText,
        }).then(t => {
            if (t.value == true) {

                let form = new FormData();
                form.append("id_user", $("#id-user").val());
                form.append("id_transfer", $("#select-user").val());
                form.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

                $.ajax({
                    type: 'POST',
                    url: document.location.origin + "/user/deleteUser",
                    data: form,
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        swal({
                            title: GLOBAL_LANG.user_alert_delete_two_title,
                            text: GLOBAL_LANG.user_alert_delete_two_text,
                            type: "success",
                            buttonsStyling: !1,
                            confirmButtonClass: "btn btn-success"
                        });
                        $("#datatable-basic").DataTable().ajax.reload(null, false);
                    },
                    error: function (error) {
                        return false;
                    }
                });
            }
            $("#select-user").val(0);
        });

    });


    $("#dropdown_user_group .dropdown-item").each((idx, elm) => {
        if (elm.attributes.class.value == "dropdown-item opt OptSelected") {
            document.getElementById("input_user_group").value = elm.innerHTML;
            document.getElementById("user_group").value = elm.id;
        }
    });

    $("#dropdown_user_call .dropdown-item").each((idx, elm) => {
        if (elm.attributes.class.value == "dropdown-item opt OptSelected") {
            document.getElementById("input_user_call").value = elm.innerHTML;
            document.getElementById("user_call").value = elm.id;
        }
    });

    $("#modalFilter").one("click", () => modalFilter());
    $("#sendEmailExport").on("click", () => modalExport());

    userLanguage();

    $("form").submit(event => event.preventDefault());
    if (document.querySelector(".btn-success")) {
        document.querySelector(".btn-success").addEventListener("click", submit);
    }

});


async function submit() {

    const input_name = formValidation({ field: document.getElementById("input-name"), text: GLOBAL_LANG.user_first_name, required: true, min: 3, max: 30 });
    const input_last_name = formValidation({ field: document.getElementById("input-last-name"), text: GLOBAL_LANG.user_full_name, required: true, min: 3, max: 100 });
    const input_user_call = formValidation({ field: document.getElementById("input_user_call"), text: GLOBAL_LANG.user_service_profile, required: true });
    const input_user_group = formValidation({ field: document.getElementById("input_user_group"), text: GLOBAL_LANG.user_sector, required: true });
    let email_avaliable = true;
    let input_email = true;

    if (document.getElementById("input-email").disabled == false) {
        input_email = formValidation({ field: document.getElementById("input-email"), text: "Email", required: true, email: true });

        if (input_email) {
            if (await verifyEmailInUse()) {
                formValidation({ field: document.getElementById("input-email"), text: "Email", required: true, email_in_use: true });
                email_avaliable = false;
            }
        }
    }

    if (input_name && input_last_name && input_user_call && input_user_group && input_email && email_avaliable) {
        document.querySelector(".btn-success").disabled = true;
        $("form").unbind('submit').submit();
    }
}


async function verifyEmailInUse() {
    const formData = new FormData();
    formData.append("email", document.getElementById("input-email").value.trim());
    formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

    try {
        const response = await fetch(document.location.origin + '/user/checkEmail', {
            method: 'POST',
            body: formData
        });

        $("[name='csrf_token_talkall']").val(Cookies.get("csrf_cookie_talkall"));

        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error:', error);
    }
}



if ($("#search").val() === undefined) {

    let error1 = true, error2 = true, error3 = true;

    function onErrorImg(img) {
        if (error1) {
            error1 = false;
            return img.src = document.location.origin = "../../../../assets/img/avatar.svg";
        }
        if (error2) {
            error2 = false;
            return img.src = document.location.origin = "../../../../../assets/img/avatar.svg";
        }
        if (error3) {
            error3 = false;
            return img.src = document.location.origin = "../../../../../assets/img/avatar.svg";
        }
    }
}


document.querySelectorAll(".btn.btn-primary, .btn.btn-danger").forEach(function (button) {
    button.addEventListener("click", function () {
        if (Filters) {
            Filters.btn_back = true;
            localStorage.setItem("filters", JSON.stringify(Filters));
        }
    });
});


function modalExport() {
    let column = $("#datatable-basic").DataTable().order()[0][0];
    let order = $("#datatable-basic").DataTable().order()[0][1];

    switch (column) {
        case 0:
            column = "name";
            break;

        case 1:
            column = "sector";
            break;

        default:
            break;
    }

    $.get(`/export/xlsx?
    search=${$('#search').val() == "" ? $('#input-search').val() : $('#search').val()}
    &sector=${$('#verify-select2').val() == "2" ? "" : $('#multiselect2').val()}
    &situation=${$('#select-situation').val()}
    &column=${column}
    &order=${order}
    &type=user`, function (response) {

        if (response != "Error") {
            Swal.fire({
                title: GLOBAL_LANG.user_alert_export_title,
                text: GLOBAL_LANG.user_alert_export_text,
                type: 'success',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: GLOBAL_LANG.user_alert_export_confirmButtonText
            });
        }
    });
}


function modalFilter() {

    const msf_multiselect_container = document.querySelectorAll(".msf_multiselect_container");

    for (const elm of msf_multiselect_container) elm.remove();

    var select2 = new MSFmultiSelect(
        document.querySelector('#multiselect2'), {
        theme: 'theme2',
        selectAll: true,
        searchBox: true,
        width: 'auto',
        height: 47,
        onChange: function (checked, value, instance) {
            if (select2 == "") select2 = value;
        },
    });

    const search = document.getElementById("search");

    const check_search = document.getElementById("check-search");
    const input_search = document.getElementById("input-search");

    const check_select2 = document.getElementById("check-select2");
    const mult_select2 = document.getElementById("mult-select2");
    const verify_select2 = document.getElementById("verify-select2");

    const check_situation = document.getElementById("check-situation");
    const select_situation = document.getElementById("select-situation");

    const btn_search = document.getElementById("btn-search");

    const logger = document.querySelectorAll(".logger");

    check_search.checked = true;
    input_search.style.display = "block";

    check_search.addEventListener("click", () => {
        if (check_search.checked) {
            input_search.style.display = "block";
        }
        else {
            input_search.value = "";
            input_search.style.display = "none";
        }

    });

    check_select2.addEventListener("click", () => {
        if (check_select2.checked) {
            mult_select2.style.display = "block"
            verify_select2.value = "1";
        }
        else {
            mult_select2.style.display = "none";
            verify_select2.value = "2";
        }

    });

    check_situation.addEventListener("click", () => {
        if (check_situation.checked) {
            select_situation.style.display = "block";
        } else {
            select_situation.value = "";
            select_situation.style.display = "none";
        }

    });

    btn_search.addEventListener("click", () => {
        search.value = input_search.value;
        find();
    });

    for (const elm of logger) {
        elm.style.paddingLeft = "15px";
    }
}


// function userLanguage() {

//     if (document.querySelector("#sector_language") != null) {
//         var languageSelected = document.querySelector("#sector_language").dataset.languagen;
//         var sels = $(".fake-sel");

//         var imgs_ = [
//             [
//                 "https://app.talkall.com.br/assets/img/panel/br.png",
//                 "https://app.talkall.com.br/assets/img/panel/us.png",
//             ]
//         ];

//         sels.each(function (x) {

//             var $t = $(this);
//             var cont = 1;
//             var opts_ = '', first;

//             $t.find("option").each(function (i) {

//                 if (i == 0 && languageSelected != "en_us") {
//                     first = "<li><img src='" + imgs_[x][i] + "'>" + $(this).text() + "</li>";
//                 } else {
//                     ////// altera o input caso for selecionado o en_us
//                     $t.find("option").each(function (i) {
//                         if (cont == 2 && languageSelected == "en_us") {
//                             first = "<li><img src='" + imgs_[x][i] + "'>" + $(this).text() + "</li>";
//                             $(".fake-sel").val("en_us");
//                         }
//                     });
//                 }
//                 opts_ += "<li class='item-lang'><img src='" + imgs_[x][i] + "'>" + $(this).text() + "</li>";
//                 cont += 1;
//             });

//             $t.wrap("<div class='fake-sel-wrap' id='input-language' style='z-index: 999;'></div>")
//                 .parent()
//                 .css("width", "100%")
//                 .append("<ul class='input-language'>" + first + opts_ + "</ul>")
//                 .append("<i class='fas fa-chevron-down'></i>")
//                 .find("ul")
//                 .on("click", function (e) {
//                     e.stopPropagation();
//                     $(".fake-sel-wrap ul")
//                         .not(this)
//                         .removeClass("ativo");
//                     $(this).toggleClass("ativo");
//                 })
//                 .find("li:not(:first)")
//                 .on("click", function () {
//                     $(this)
//                         .addClass("ativo")
//                         .siblings()
//                         .removeClass("ativo")
//                         .parent()
//                         .find("li:first")
//                         .html($(this).html());

//                     $t.val($(this).text());

//                 });
//         });

//         $(document).on("click", function () {
//             $(".fake-sel-wrap ul").removeClass("ativo");
//         });
//     }
// }


function userLanguage() {
    if (document.querySelector("#sector_language") != null) {
        var languageSelected = document.querySelector("#sector_language").dataset.languagen;
        var sels = $(".fake-sel");

        var brFlag = "https://app.talkall.com.br/assets/img/panel/br.png";
        var usFlag = "https://app.talkall.com.br/assets/img/panel/us.png";
        var esFlag = "http://app.talkall.com.br/assets/img/panel/espanha.svg";
        
        sels.each(function () {
            var $t = $(this);

            $t.find("option[data-language='pt_br']").val("pt_br");
            $t.find("option[data-language='en_us']").val("en_us");
            $t.find("option[data-language='es']").val("es");

            
            var opts_ = '';
            var first = '';

            // Determinar o item inicial baseado no idioma selecionado
            if (languageSelected == "en_us") {
                first = "<li><img src='" + usFlag + "'>" + $t.find("option[data-language='en_us']").text() + "</li>";
            } else if (languageSelected == "es") {
                first = "<li><img src='" + esFlag + "'>" + $t.find("option[data-language='es']").text() + "</li>";
            } else {
                // Default para português
                first = "<li><img src='" + brFlag + "'>" + $t.find("option[data-language='pt_br']").text() + "</li>";
            }

            // Gerar todas as opções de idioma explicitamente
            var ptOption = $t.find("option[data-language='pt_br']");
            var enOption = $t.find("option[data-language='en_us']");
            var esOption = $t.find("option[data-language='es']");
            
            if (ptOption.length) {
                opts_ += "<li class='item-lang'><img src='" + brFlag + "'>" + ptOption.text() + "</li>";
            }
            
            if (enOption.length) {
                opts_ += "<li class='item-lang'><img src='" + usFlag + "'>" + enOption.text() + "</li>";
            }
            
            if (esOption.length) {
                opts_ += "<li class='item-lang'><img src='" + esFlag + "'>" + esOption.text() + "</li>";
            }

            $t.wrap("<div class='fake-sel-wrap' id='input-language' style='z-index: 999;'></div>")
                .parent()
                .css("width", "100%")
                .append("<ul class='input-language'>" + first + opts_ + "</ul>")
                .append("<i class='fas fa-chevron-down'></i>")
                .find("ul")
                .on("click", function (e) {
                    e.stopPropagation();
                    $(".fake-sel-wrap ul")
                        .not(this)
                        .removeClass("ativo");
                    $(this).toggleClass("ativo");
                })
                .find("li:not(:first)")
                .on("click", function () {
                    $(this)
                        .addClass("ativo")
                        .siblings()
                        .removeClass("ativo")
                        .parent()
                        .find("li:first")
                        .html($(this).html());

                    // Identificar qual idioma foi selecionado baseado no texto/imagem
                    var imgSrc = $(this).find('img').attr('src');
                    var selectedLang;
                    
                    if (imgSrc === brFlag) {
                        selectedLang = "pt_br";
                    } else if (imgSrc === usFlag) {
                        selectedLang = "en_us";
                    } else if (imgSrc === esFlag) {
                        selectedLang = "es";
                    }
                    
                    $t.val(selectedLang);
                });
        });

        $(document).on("click", function () {
            $(".fake-sel-wrap ul").removeClass("ativo");
        });
    }
}



function clearFormModal() {

    document.getElementById("modalUserCall__name").value = "";
    document.getElementById("modalUserCall__limit").value = "";
    document.getElementById("modalSector__name").value = "";

    document.getElementById("alertUserCall__name").style.display = "none";
    document.getElementById("alertUserCall__limit").style.display = "none";
    document.getElementById("alertSector__name").style.display = "none";
}


function userCallSelected(e) {
    if (e.id != undefined) {
        if (e.id != 0) {
            $(e).parent().parent().find(".value-select").val(e.id);
            $(e).parent().parent().find(".dropdown-toggle").val(e.innerHTML);
        }
    } else {
        if (this.id != 0) {
            $(this).parent().parent().find(".value-select").val(this.id);
            $(this).parent().parent().find(".dropdown-toggle").val(this.innerHTML);
        }
    }
}


function saveUserCall() {

    document.getElementById("alertUserCall__name").style.display = "none";
    document.getElementById("alertUserCall__limit").style.display = "none";

    const input_name = document.getElementById("modalUserCall__name").value;
    const input_limit = document.getElementById("modalUserCall__limit").value;

    let formValid_name = true;
    let formValid_limit = true;

    // campo nome
    if (!emptyText(input_name)) {
        formValid_name = false;
        document.getElementById("alertUserCall__name").style.display = "block";
        document.getElementById("alertUserCall__name").innerHTML = GLOBAL_LANG.user_validation_modal_name_required;
    }

    if (!min_length(input_name, 3)) {
        formValid_name = false;
        document.getElementById("alertUserCall__name").style.display = "block";
        document.getElementById("alertUserCall__name").innerHTML = GLOBAL_LANG.user_validation_modal_name_min_length.replace("{param}", 3);
    }

    if (!max_length(input_name, 20)) {
        formValid_name = false;
        document.getElementById("alertUserCall__name").style.display = "block";
        document.getElementById("alertUserCall__name").innerHTML = GLOBAL_LANG.user_validation_modal_name_max_length.replace("{param}", 30);
    }

    // campo limite
    if (!emptyText(input_limit)) {
        formValid_name = false;
        document.getElementById("alertUserCall__limit").style.display = "block";
        document.getElementById("alertUserCall__limit").innerHTML = GLOBAL_LANG.user_validation_modal_limit_required;
    }

    if (checkNegativeNumber(input_limit)) {
        formValid_name = false;
        document.getElementById("alertUserCall__limit").style.display = "block";
        document.getElementById("alertUserCall__limit").innerHTML = GLOBAL_LANG.user_validation_modal_limit_negative;
    }

    if (max_lengthNumber(input_limit, 100)) {
        formValid_name = false;
        document.getElementById("alertUserCall__limit").style.display = "block";
        document.getElementById("alertUserCall__limit").innerHTML = GLOBAL_LANG.user_validation_modal_limit_max_lengthNumber;
    }

    if (formValid_name && formValid_limit) {

        const formData = new FormData();
        formData.append("input-name", input_name);
        formData.append("input-limit", input_limit);
        formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

        fetch(document.location.origin + '/user/save/userCall', {
            method: 'POST',
            body: formData
        }).then((response) => response.json())
            .then((data) => {

                $("#dropdown_user_call .opt").remove();

                for (let i = 0; i < data.length; i++) {

                    const a = document.createElement("a");
                    a.className = "dropdown-item opt";
                    a.id = data[i].id_user_call;
                    a.innerHTML = data[i].name;
                    a.addEventListener("click", userCallSelected);

                    if (data[i].name == input_name && data[i].limit == input_limit) {
                        document.getElementById("user_call").value = data[i].id_user_call;
                        document.getElementById("input_user_call").value = data[i].name;
                    }

                    document.getElementById("dropdown_user_call").append(a);
                    $('#modal-user-call').modal('hide');
                }

                $("[name='csrf_token_talkall']").val(Cookies.get("csrf_cookie_talkall"));

            }).catch((error) => {
                console.error('Error:', error);
            });
    }
}


function saveUserSector() {

    document.getElementById("alertSector__name").style.display = "none";

    const input_name = document.getElementById("modalSector__name").value;

    let formValid_name = true;
    let formValid_limit = true;

    // campo nome setor
    if (!emptyText(input_name)) {
        formValid_name = false;
        document.getElementById("alertSector__name").style.display = "block";
        document.getElementById("alertSector__name").innerHTML = GLOBAL_LANG.user_validation_modal_name_required;
    }

    if (!min_length(input_name, 3)) {
        formValid_name = false;
        document.getElementById("alertSector__name").style.display = "block";
        document.getElementById("alertSector__name").innerHTML = GLOBAL_LANG.user_validation_modal_name_min_length.replace("{param}", 3);
    }

    if (!max_length(input_name, 20)) {
        formValid_name = false;
        document.getElementById("alertSector__name").style.display = "block";
        document.getElementById("alertSector__name").innerHTML = GLOBAL_LANG.user_validation_modal_name_max_length.replace("{param}", 20);
    }

    if (formValid_name && formValid_limit) {

        const formData = new FormData();
        formData.append("input-name", input_name);
        formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

        fetch(document.location.origin + '/user/save/userSector', {
            method: 'POST',
            body: formData
        }).then((response) => response.json())
            .then((data) => {

                $("#dropdown_user_group .opt").remove();

                for (let i = 0; i < data.length; i++) {

                    const a = document.createElement("a");
                    a.className = "dropdown-item opt";
                    a.id = data[i].id_user_call;
                    a.innerHTML = data[i].name;
                    a.addEventListener("click", userCallSelected);

                    if (data[i].name == input_name) {
                        document.getElementById("user_group").value = data[i].id_user_group;
                        document.getElementById("input_user_group").value = data[i].name;
                    }

                    document.getElementById("dropdown_user_group").append(a);
                    $('#modal-user-sector').modal('hide');
                }

                $("[name='csrf_token_talkall']").val(Cookies.get("csrf_cookie_talkall"));

            }).catch((error) => {
                console.error('Error:', error);
            });
    }
}