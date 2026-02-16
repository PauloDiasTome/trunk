const Filters = JSON.parse(localStorage.getItem("filters")) || null;


$(document).ready(function () {
    var maskBehavior = function (val) {
        val = val.split(":");
        return (parseInt(val[0]) > 19) ? "HZ:M0" : "H0:M0";
    }

    spOptions = {
        onKeyPress: function (val, e, field, options) {
            field.mask(maskBehavior.apply({}, arguments), options);
        },
        translation: {
            'H': { pattern: /[0-2]/, optional: false },
            'Z': { pattern: /[0-3]/, optional: false },
            'M': { pattern: /[0-5]/, optional: false }
        }
    };

    $('.time').mask(maskBehavior, spOptions);

    $('.btn-success').click(function () {
        var msgChannelName = document.querySelector("#channel_name");

        if (msgChannelName.value == "") {

            msgChannelName.focus();
            msgChannelName.classList.add("is-invalid");

            Swal.fire(
                GLOBAL_LANG['informative_msg'],
                GLOBAL_LANG['config_validation_name_channel'],
                'info'
            );
            return false;
        } else {
            msgChannelName.classList.remove("is-invalid");

        }

        const channel_name = document.getElementById("channel_name");
        const ai_option = document.getElementById("ai_options");
        const ai_evaluation = document.getElementById("checkbox-on-off-aiEvaluation");

        if (channel_name) {
            if (channel_name != null && channel_name.value == "") {
                channel_name.focus();
                channel_name.classList.add("is-invalid");

                callAlerts("channel_name");
                return false;
            } else {
                channel_name.classList.remove("is-invalid");
            }
        }

        if (ai_evaluation) {
            if (ai_evaluation.value == 1 && ai_option.value == "") {
                ai_option.focus();
                ai_option.classList.add("is-invalid");

                callAlerts("ai_options_invalid");
                return false;
            } else {
                ai_option.classList.remove("is-invalid");
            }
        }
    });

    //////// VALIDAR SE POSSUI CADASTRO DE CHATBOT-OPTIONS
    $.ajax({
        type: "GET",
        url: "/config/validation",
        success: function (data) {

            if (data != "ok") {

                document.querySelector("#chatbot").setAttribute("hidden", "");
                document.querySelector("#automatic_transfer").setAttribute("hidden", "");
            }

        }

    });

    /////// VALIDAÇÃO PARA HABILITAR CHATBOT
    $('body').delegate('#checkbox-on-off-chatbot', 'lcs-statuschange', function () {

        var msgWelcome = document.querySelector("#welcome_message");

        if ($(this).is(':checked')) {

            if (msgWelcome.value == "") {

                msgWelcome.focus();
                msgWelcome.classList.add("is-invalid");

                Swal.fire(
                    'Informativo!',
                    "Obrigatório informar o Texto de boas-vindas! ",
                    'info'
                );

            }


        } else {
            msgWelcome.classList.remove("is-invalid");

        }

    });

    /////// VALIDAÇÃO PARA HABILITAR PROTOCOLO
    $('body').delegate('#checkbox-on-off-protocol', 'lcs-statuschange', function () {

        var msgProotocol = document.querySelector("#message_start_attendance");

        if ($(this).is(':checked') && msgProotocol.value == "") {

            msgProotocol.focus();
            msgProotocol.classList.add("is-invalid");

            Swal.fire(
                'Informativo!',
                "Obrigatorio informar o Texto de início do atendimento! ",
                'info'
            );
        } else {
            msgProotocol.classList.remove("is-invalid");

        }

    });

    /////// VALIDAÇÃO PARA HABILITAR TRANSFERÊNCIA ATUTOMÁTICA
    $('body').delegate('#checkbox-on-off-automaticTransfer', 'lcs-statuschange', function () {

        var transferTime = document.querySelector("#automatic_transfer_minute");

        if ($(this).is(':checked') && (transferTime.value == "" || transferTime.value == 0)) {

            transferTime.focus();
            transferTime.classList.add("is-invalid");

            Swal.fire(
                'Informativo!',
                "Obrigatorio informar o tempo para transferência automática! ",
                'info'
            );
        } else {
            transferTime.classList.remove("is-invalid");

        }

    });


    $("textarea").click(function () {

        this.classList.remove("is-invalid");
    })

    $("#automatic_transfer_minute").click(function () {

        this.classList.remove("is-invalid");

    })


    find();
    let timer = null;
    $('#search').keyup(function (e) {
        if (e.keyCode == 13) {
            table.search($('#search').val()).draw();
        }
        else {
            clearTimeout(timer);
            timer = setTimeout(function () {
                table.search($('#search').val()).draw();
            }, 1200);
        }
    });

    $("#integration-add").on("click", function () {
        $("#modal-notification").modal();
    });

    // script bellow to switch button
    $('input').lc_switch('ATIVO', 'INATIVO');

    let chatbot = $('#checkbox-on-off-chatbot');
    let automaticTransfer = $('#checkbox-on-off-automaticTransfer');
    let attendance = $('#checkbox-on-off-attendance');
    let protocol = $('#checkbox-on-off-protocol');
    let attendantName = $('#checkbox-on-off-attendantName');

    if (chatbot.val() == 1) {
        chatbot.lcs_on();
    };
    if (automaticTransfer.val() == 1) {
        automaticTransfer.lcs_on();
    };
    if (chatbot.val() == 1) {
        chatbot.lcs_on();
    };
    if (attendance.val() == 1) {
        attendance.lcs_on();
    };
    if (protocol.val() == 1) {
        protocol.lcs_on();
    };


    $("#chatbot .lcs_checkbox_switch").on("click", function () {

        if (chatbot.val() == 1) {
            chatbot.val(2);
            automaticTransfer.val(0);
            automaticTransfer.lcs_off();
        } else {
            chatbot.val(1);
        }
    });


    $("#automatic_transfer .lcs_checkbox_switch").on("click", function () {

        if (automaticTransfer.val() == 1) {
            automaticTransfer.val(0);
        } else {
            automaticTransfer.val(1);
            chatbot.val(1);
            chatbot.lcs_on();
        }
    });


    $("#attendance .lcs_checkbox_switch").on("click", function () {
        if (attendance.val() == 1) {
            attendance.val(2);
        } else {
            attendance.val(1);
        }
    });


    $("#protocol .lcs_checkbox_switch").on("click", function () {
        if (protocol.val() == 1) {
            protocol.val(2);
        } else {
            protocol.val(1);
        }
    });


    $("#attendant_name_enable .lcs_checkbox_switch").on("click", function () {

        if (attendantName.val() == 1) {
            attendantName.val(0);
        } else {
            attendantName.val(1);
        }
    });


    $(".form-group textarea").on("keyup keydown paste cut", function () {
        $(this).parent().find(".count-character-field").text(this.maxLength - this.value.length);
    });


    $("body").on("mouseover", ".transition-effect", () => {
        $("#addProfile").css({ "display": "block" });
        $(".picture-profile-title").css({ "display": "block" });
        $(".transition-effect img").css({ "opacity": "0.8" });
        $(".transition-effect img").css({ "filter": "brightness(50%)" });
    });


    $("body").on("mouseenter", ".transition-effect", () => {
        $("#addProfile").css({ "display": "block" });
        $(".picture-profile-title").css({ "display": "block" });
        $(".transition-effect img").css({ "opacity": "0.8" });
        $(".transition-effect img").css({ "opacity": "0.8" });
        $(".transition-effect img").css({ "filter": "brightness(40%)" });
    });


    $("body").on("mouseout", ".transition-effect", () => {
        $("#addProfile").css({ "display": "none" });
        $(".picture-profile-title").css({ "display": "none" });
        $(".transition-effect img").css({ "opacity": "1" });
        $(".transition-effect img").css({ "opacity": "1" });
        $(".transition-effect img").css({ "filter": "brightness(100%)" });
    });


    $("body").on("click", "#addProfile", () => {
        $("#inputFile").click();
    });


    $("body").on("change", "#inputFile", function () {

        const maxFileSize = 10 * 1024 * 1024;

        if (this.files[0].size > maxFileSize) {
            callAlerts("maxSize");

        } else if (this.files[0].type === "application/pdf") {
            callAlerts("pdf");

        } else if (this.files[0].type === "audio/ogg") {
            callAlerts("audio");

        } else if (this.files[0].type === "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
            callAlerts("spreadsheetml.sheet");

        } else {

            const formData = new FormData();
            const id_channel = $("#channel_number").val();
            const type = this.files[0].type;

            formData.append("filetoupload", this.files[0]);
            formData.append("media_id", id_channel);
            formData.append("media_mime_type", type);

            const settings = {
                "url": "https://files.talkall.com.br:3000",
                "method": "POST",
                "timeout": 0,
                "crossDomain": true,
                "processData": false,
                "mimeType": "multipart/form-data",
                "contentType": false,
                "data": formData
            };

            $.ajax(settings).done(function (response) {
                addthumbail(JSON.parse(response));
            });

        }

    });

    setInterval(() => {
        $("#video-opt-in").fadeIn(1000).fadeOut(1000);
    }, 3000);

    $("#minimum-credit").maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',', affixesStay: true });

    $("#dropdown_work_time .dropdown-item").each((idx, elm) => {
        if (elm.attributes.class.value == "dropdown-item opt OptSelected") {
            document.getElementById("input_work_time").value = elm.innerHTML;
            document.getElementById("to_recover_id_work_time").value = elm.id;
        }
    });


    $(".form-group textarea").each((idx, elm) => {
        $(elm).parent().find(".count-character-field").text(elm.maxLength - elm.value.length);
    });

    if (document.querySelector('.bootstrap-tagsinput input')) {
        document.querySelector('.bootstrap-tagsinput input').setAttribute('maxlength', 45)
    }


    $('body').on('lcs-statuschange', '#checkbox-on-off-aiEvaluation', async function () {
        const checkbox = $('#checkbox-on-off-aiEvaluation');
        const div_element = checkbox.next('div');

        if (checkbox.is(':checked')) {
            checkbox.val(1);
            try {
                div_element.addClass('lcs_disabled');
                const ai_channel_valid = await checkAiChannel();

                if (ai_channel_valid) {
                    callAlerts("ai_channel_valid");
                    $('#dropdown-ai-evaluation').show();
                    div_element.removeClass('lcs_disabled');
                } else {
                    callAlerts("ai_channel_invalid");
                    checkbox.val(0);
                    checkbox.lcs_off();
                    $('#dropdown-ai-evaluation').hide();
                    div_element.removeClass('lcs_disabled');
                }
            } catch (error) {
                console.error('Erro ao verificar o canal AI:', error);

                checkbox.val(0);
                checkbox.lcs_off();
                $('#dropdown-ai-evaluation').hide();
                div_element.removeClass('lcs_disabled');
            }
        } else {
            checkbox.val(0);
            $('#dropdown-ai-evaluation').hide();
        }
    });

    if (!$('#checkbox-on-off-aiEvaluation').is(':checked')) {
        $('#dropdown-ai-evaluation').hide();
    }
});

function checkAiChannel() {
    return new Promise(async (resolve, reject) => {
        try {
            const response = await fetch(`${document.location.origin}/config/check/ai/channel`);
            const data = await response.json();

            resolve(data.success);
        } catch (error) {
            console.error('Erro ao buscar dados:', error);
            reject(error);
        }
    });
}

function addthumbail(data) {
    $(".transition-effect img").attr("src", "data:image/jpeg;base64," + data.thumbnail);
    $("#url_picture").attr("value", data.url);
}


function callAlerts(alert) {

    switch (alert) {
        case "maxSize":
            swal({
                title: GLOBAL_LANG.config_alert_dropzone_maxSize_title,
                text: GLOBAL_LANG.config_alert_dropzone_maxSize_text,
                type: "warning",
                confirmButtonColor: '#3085d6',
                confirmButtonText: GLOBAL_LANG.config_alert_dropzone_maxSize_confirmButtonText,
                cancelButtonClass: "btn btn-secondary"
            });
            break;
        case "pdf":
            swal({
                title: GLOBAL_LANG.config_alert_dropzone_arquive_title,
                text: GLOBAL_LANG.config_alert_dropzone_arquive_text,
                type: "warning",
                confirmButtonColor: '#3085d6',
                confirmButtonText: GLOBAL_LANG.config_alert_dropzone_arquive_confirmButtonText,
                cancelButtonClass: "btn btn-secondary"
            });
        case "audio":
            swal({
                title: GLOBAL_LANG.config_alert_dropzone_arquive_title,
                text: GLOBAL_LANG.config_alert_dropzone_arquive_text,
                type: "warning",
                confirmButtonColor: '#3085d6',
                confirmButtonText: GLOBAL_LANG.config_alert_dropzone_arquive_confirmButtonText,
                cancelButtonClass: "btn btn-secondary"
            });
        case "spreadsheetml.sheet":
            swal({
                title: GLOBAL_LANG.config_alert_dropzone_arquive_title,
                text: GLOBAL_LANG.config_alert_dropzone_arquive_text,
                type: "warning",
                confirmButtonColor: '#3085d6',
                confirmButtonText: GLOBAL_LANG.config_alert_dropzone_arquive_confirmButtonText,
                cancelButtonClass: "btn btn-secondary"
            });
            break;
        case "ai_channel_valid":
            swal({
                title: GLOBAL_LANG.config_ai_evaluation_alert_title_attention,
                text: GLOBAL_LANG.config_ai_evaluation_alert_body_additional_costs,
                type: 'warning',
                confirmButtonColor: '#2dce89',
                confirmButtonText: GLOBAL_LANG.config_ai_evaluation_alert_button_ok,
            });
            break;
        case "ai_channel_invalid":
            swal({
                title: GLOBAL_LANG.config_ai_evaluation_alert_title_attention,
                text: GLOBAL_LANG.config_ai_evaluation_alert_body_no_ai_registered,
                type: 'warning',
                confirmButtonColor: '#2dce89',
                confirmButtonText: GLOBAL_LANG.config_ai_evaluation_alert_button_ok,
            });
            break;
        case "ai_options_invalid":
            swal({
                title: GLOBAL_LANG.config_ai_evaluation_alert_title_attention,
                text: GLOBAL_LANG.config_ai_evaluation_alert_body_select_option,
                type: 'warning',
                confirmButtonColor: '#2dce89',
                confirmButtonText: GLOBAL_LANG.config_ai_evaluation_alert_button_ok,
            });
            break;
        case "channel_name":
            swal({
                title: GLOBAL_LANG.informative_msg,
                text: GLOBAL_LANG.config_validation_name_channel,
                type: 'info',
                confirmButtonColor: '#2dce89',
                confirmButtonText: GLOBAL_LANG.config_ai_evaluation_alert_button_ok,
            });
            break;
        default:
            break;
    }

}


var table;

function find() {
    table = $('#datatable-basic').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "config",
            type: "POST",
            data: {
                text: $("#search").val(),
                csrf_talkall: Cookies.get("csrf_cookie_talkall")
            }
        },
        columns: [
            {
                mData: 'channel_name' // 0
            },
            {
                mData: 'channel_number' // 1
            },
            {
                mData: 'id_config' // 2
            }
        ],
        columnDefs: [
            {
                targets: 2,
                render: function (data, type, full, meta) {
                    return "<a href='/config/edit/" + full.id_config + "' class='table-action table-action-edit' data-toggle='tooltip' data-original-title='Editar'>" +
                        "<i class='fas fa-user-edit'></i></a>";
                }
            },
            {
                orderable: false,
                targets: [0]
            }
        ],
        pagingType: "numbers",
        pageLength: 5,
        destroy: true,
        fixedHeader: true,
        responsive: true,
        lengthChange: false,
        paginate: true,
        info: true,
        ordering: false,
        language: {
            url: `${document.location.origin}/assets/lang/${localStorage.getItem("language")}.json`
        }
    });
}


function inputGroup(id) {

    var start = "#" + id + "-start";
    var end = "#" + id + "-end";

    if ($(start).attr("disabled") == "disabled") {
        $(start).removeAttr("disabled");
    }
    else {
        $(start).attr("disabled", "disabled");
        $(start).removeClass('is-invalid');
    }

    if ($(end).attr("disabled") == "disabled") {
        $(end).removeAttr("disabled");
    }
    else {
        $(end).attr("disabled", "disabled");
        $(end).removeClass('is-invalid');
    }
}


function clearFormModal() {

    const input_work_time = document.querySelectorAll("#modal-work-time .input-group");

    document.getElementById("modalWorkTime__name").value = "";
    document.getElementById("alertWorkTime__name").style.display = "none";
    document.getElementById("alertWorkTime__fields").style.display = "none";

    for (const elm of input_work_time) {
        elm.children[0].children[0].checked = false;
        elm.children[1].value = '';
        elm.children[2].value = '';
        elm.children[1].setAttribute("disabled", true);
        elm.children[2].setAttribute("disabled", true);
    }
}


function workTimeSelected(e) {
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


function saveWorkTime() {

    document.getElementById("btn-work-time").disabled = true;
    setTimeout(function () { document.getElementById("btn-work-time").disabled = false; }, 1000);
    document.getElementById("alertWorkTime__name").style.display = "none";

    const input_name = document.getElementById("modalWorkTime__name").value;

    let formValid_name = true;
    let formValid_start = true;
    let formValid_end = true;
    let formValid_check = false;

    // campo nome setor
    if (!emptyText(input_name)) {
        formValid_name = false;
        document.getElementById("alertWorkTime__name").style.display = "block";
        document.getElementById("alertWorkTime__name").innerHTML = GLOBAL_LANG.config_validation_modal_name_required;
    }

    if (!min_length(input_name, 3)) {
        formValid_name = false;
        document.getElementById("alertWorkTime__name").style.display = "block";
        document.getElementById("alertWorkTime__name").innerHTML = GLOBAL_LANG.config_validation_modal_name_min_length.replace("{param}", 3);
    }

    if (!max_length(input_name, 20)) {
        formValid_name = false;
        document.getElementById("alertWorkTime__name").style.display = "block";
        document.getElementById("alertWorkTime__name").innerHTML = GLOBAL_LANG.config_validation_modal_name_max_length.replace("{param}", 20);
    }

    // tabela de horários
    const input_work_time = document.querySelectorAll("#modal-work-time .input-group");

    for (const elm of input_work_time) {

        if (elm.children[0].children[0].checked) {

            if (elm.children[1].value == '') {
                formValid_start = false;
            }

            if (elm.children[2].value == '') {
                formValid_end = false;
            }

            formValid_check = true;
        }
    }

    if (formValid_start == false || formValid_check == false) {
        document.getElementById("alertWorkTime__fields").style.display = "block";
        document.getElementById("alertWorkTime__fields").innerHTML = GLOBAL_LANG.config_validation_modal_start_end_required;
    }

    if (formValid_name && formValid_start && formValid_end && formValid_check) {

        const formData = new FormData();
        formData.append("name", input_name);
        formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

        for (const elm of input_work_time) {
            if (elm.children[0].children[0].checked) {
                formData.append(elm.children[1].id, elm.children[1].value);
                formData.append(elm.children[2].id, elm.children[2].value);
            }
        }

        fetch(document.location.origin + '/config/worktime/save', {
            method: 'POST',
            body: formData
        }).then((response) => response.json())
            .then((data) => {

                $("#dropdown_work_time .opt").remove();

                for (let i = 0; i < data.length; i++) {

                    const a = document.createElement("a");
                    a.className = "dropdown-item opt";
                    a.id = data[i].id_work_time;
                    a.innerHTML = data[i].name;
                    a.addEventListener("click", workTimeSelected);

                    if (data[i].name == input_name) {
                        document.getElementById("to_recover_id_work_time").value = data[i].id_work_time;
                        document.getElementById("input_work_time").value = data[i].name;
                    }

                    document.getElementById("dropdown_work_time").append(a);
                    $('#modal-work-time').modal('hide');
                }

                $("[name='csrf_token_talkall']").val(Cookies.get("csrf_cookie_talkall"));

            }).catch((error) => {
                console.error('Error:', error);
            });
    }

}


function validNumber(evt) {
    if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
        evt.preventDefault();
    }
}

if (document.querySelector("#automatic_transfer_minute") != null) {
    document.querySelector("#automatic_transfer_minute").addEventListener("keypress", validNumber);
}


document.querySelectorAll(".btn.btn-primary").forEach(function (button) {
    button.addEventListener("click", function () {
        if (Filters) {
            Filters.btn_back = true;
            localStorage.setItem("filters", JSON.stringify(Filters));
        }
    });
});