const Filters = JSON.parse(localStorage.getItem("filters")) || null;


function checkFilter() {
    if (Filters) {

        if (!Filters.btn_back) return

        if (Filters.template.search) {
            document.getElementById("search").value = Filters.template.search;
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
            url: "templates/find",
            type: "POST",
            data: {
                text: $("#search").val(),
                csrf_talkall: Cookies.get("csrf_cookie_talkall")
            }
        },
        columns: [
            {
                mData: 'creation'
            },
            {
                mData: 'name'
            },
            {
                mData: 'channel_name'
            },
            {
                mData: 'category'
            },
            {
                mData: 'status'
            }
        ],
        columnDefs: [
            {
                targets: 0,
                render: function (data) {
                    if (!data) return '-';
                    const date = new Date(data * 1000);
                    return date.toLocaleDateString('pt-BR') + ' ' + date.toLocaleTimeString('pt-BR');
                }
            },
            {
                targets: 1,
                render: function (data, type, full, meta) {
                    return full.name;
                }
            },
            {
                targets: 2,
                render: function (data, type, full, meta) {
                    return full.channel_name;
                }
            },
            {
                targets: 3,
                render: function (data, type, full, meta) {
                    if (full.language === 'pt_BR') {
                        let category = full.category.replace("_", " ");
                        switch (category) {
                            case 'AUTHENTICATION':
                                return 'AUTENTICAÇÃO';
                                break;

                            case 'PAYMENT UPDATE':
                                return 'ATUALIZAÇÃO DE PAGAMENTO';
                                break;

                            case 'PERSONAL FINANCE UPDATE':
                                return 'ATUALIZAÇÃO DE FINANÇAS PESSOAIS';
                                break;

                            case 'SHIPPING UPDATE':
                                return 'ATUALIZAÇÃO DE REMESSA';
                                break;

                            case 'RESERVATION UPDATE':
                                return 'ATUALIZAÇÃO DE RESERVA';
                                break;

                            case 'ISSUE RESOLUTION':
                                return 'RESOLUÇÃO DE PROBLEMAS';
                                break;

                            case 'APPOINTMENT UPDATE':
                                return 'ATUALIZAÇÃO DE COMPROMISSO';
                                break;

                            case 'TRANSPORTATION UPDATE':
                                return 'ATUALIZAÇÃO DE TRANSPORTE';
                                break;

                            case 'TICKET UPDATE':
                                return 'ATUALIZAÇÃO DE TICKET';
                                break;

                            case 'TRANSACTIONAL':
                                return 'TRANSACIONAL';
                                break;

                            case 'MARKETING':
                                return 'MARKETING';
                                break;

                            case 'OTP':
                                return 'SENHAS DESCARTÁVEIS';
                                break;

                            case 'UTILITY':
                                return 'UTILIDADE';
                                break;

                            default: 'ALERT UPDATE'
                                return 'ATUALIZAÇÃO DE ALERTA';
                                break;
                        }
                    } else {
                        return full.category.replace("_", " ");
                    }
                }
            },
            {
                targets: 4,
                render: function (data, type, full, meta) {
                    switch (full.status) {
                        case "1":

                            echo = "<span style='background: #fff;' data-toggle='tooltip' data-placement='right' title='" + GLOBAL_LANG.template_datatable_column_status_title_review + "'><i class='fas fa-clock' style='color: orange; font-size: 13pt;'></i></span>";
                            break;
                        case "2":

                            echo = "<span style='background: #fff;' data-toggle='tooltip' data-placement='right' title='" + GLOBAL_LANG.template_datatable_column_status_title_approved + "'><i class='fas fa-check' style='color: green; font-size: 13pt;'></i></span>";
                            break;
                        case "3":

                            echo = "<span style='background: #fff;' data-toggle='tooltip' data-placement='right' title='" + GLOBAL_LANG.template_datatable_column_status_title_rejected + "'><i class='fas fa-times' style='color: red; font-size: 13pt;'></i></i></span>";
                            break;
                        default:

                            echo = "<span style='background: #fff;' data-toggle='tooltip' data-placement='right' title='" + GLOBAL_LANG.template_datatable_column_status_title_called + "'><i class='fas fa-times' style='color: red; font-size: 13pt;'></i></i></span>";
                            break;
                    }
                    return echo;
                }
            },
            {
                targets: 5,
                render: function (data, type, full, meta) {

                    return `<div class="dropleft">
                                    <button class="btn btn-link mb-0 btn-dropleft" type="button" id="multiDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #525f7f">
                                        <i class="fa fa-ellipsis-v"></i>
                                    <div class="ripple-container"></div>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="multiDropdownMenu">
                                        <span style="display:${full.status == 2 ? '' : 'none'}">
                                            <a id="` + full.id_template + `" href='#' class="dropdown-item table-btn-view" style="cursor: pointer">
                                                <div style="width: 24px; display: inline-block"> 
                                                    <i class="fas fa-eye" style="margin-left: -2px"></i>
                                                </div>
                                                <span>${GLOBAL_LANG.template_datatable_column_status_title_view}</span>
                                            </a>
                                        </span>    
                                            <a id="` + full.id_template + `" href='#' class="dropdown-item table-btn-edit" style="cursor: pointer">
                                                <div style="width: 24px; display: inline-block"> 
                                                    <i class="far fa-edit"></i>
                                                </div>
                                                <span>${GLOBAL_LANG.template_datatable_column_status_title_edit}</span>
                                            </a>
                                            <a id="` + full.id_template + `" href='#' class="dropdown-item table-btn-delete" style="cursor: pointer">
                                                <div style="width: 24px; display: inline-block"> 
                                                    <i class="far fa-trash-alt"></i>
                                                </div>
                                                <span>${GLOBAL_LANG.template_datatable_column_status_title_delete}</span>
                                            </a>
                                    </div>
                                </div>`
                }
            },
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

            const template = {
                search: $("#search").val(),
            }

            let filter = localStorage.getItem("filters");

            filter = filter ? JSON.parse(filter) : {};
            filter.template = template;

            localStorage.setItem("filters", JSON.stringify(filter));
        }
    });
}



$(document).ready(function () {

    const verifyFind = $("#verifyFind").val();
    const verifyView = $("#verifyView").val();
    const verifyAdd = $("#verifyAdd").val();
    const verifyEdit = $("#verifyEdit").val();

    if (verifyFind != null) {
        //FIND

        $("#search").on("keyup", function (event) {
            if (event.which == 13) {
                find();
            }
        });
        find();

        $("#datatable-basic").on("click", ".table-btn-view", function () {
            window.location.href = "templates/view/" + this.id;
        });


        $("#datatable-basic").on("click", ".table-btn-edit", function () {
            window.location.href = "templates/edit/" + this.id;
        });


        $("#datatable-basic").on("click", ".table-btn-delete", function () {
            swal({
                title: GLOBAL_LANG.template_alert_delete_title,
                text: GLOBAL_LANG.template_alert_delete_text,
                type: "warning",
                showCancelButton: !0,
                buttonsStyling: !1,
                reverseButtons: true,
                confirmButtonClass: "btn btn-success",
                confirmButtonText: GLOBAL_LANG.template_alert_delete_confirmButtonText,
                cancelButtonClass: "btn btn-danger",
                cancelButtonText: GLOBAL_LANG.template_alert_delete_cancelButtonText,
            }).then(t => {
                if (t.value == true) {
                    let requestOptions = {
                        method: 'DELETE',
                        redirect: 'follow'
                    };

                    fetch(document.location.origin + `/templates/delete/${this.id}`, requestOptions)
                        .then(response => response.json())
                        .then((result) => {
                            t.value && swal({
                                title: GLOBAL_LANG.template_alert_delete_two_title,
                                text: GLOBAL_LANG.template_alert_delete_two_text,
                                type: "success",
                                buttonsStyling: !1,
                                confirmButtonClass: "btn btn-success"
                            });
                            $("#datatable-basic").DataTable().ajax.reload(null, false);
                        })
                        .catch(error => {
                            t.value && swal({
                                title: GLOBAL_LANG.template_alert_delete_two_title_error,
                                text: GLOBAL_LANG.template_alert_delete_two_text_error,
                                type: "error",
                                buttonsStyling: !1,
                                confirmButtonClass: "btn btn-danger"
                            });
                            $("#datatable-basic").DataTable().ajax.reload(null, false);
                        });
                }
            });
        });


        $('#sendEmailExport').on('click', () => modalExport());

    } else if (verifyAdd != null) {
        //ADD

        addChannelType();
        setCategories();

        let requestOptions = {
            method: 'GET',
            redirect: 'follow'
        };

        $('#text-footer').on("change keyup keydown paste cut", function (e) {

            this.value = this.value.replace(/(\r\n|\n|\r)/gm, " ");

            var key = e.which;

            if (key == 13) {
                e.preventDefault();
            }

        });

        $("#btn-save").on("click", () => {
            let settings = {
                method: 'POST',
                redirect: 'follow'
            };

            fetch(document.location.origin + '/templates/save/', settings)
                .then(response => response.json())
                .then((result) => {
                    console.log(result);
                })
                .catch(error => {
                    console.log('error', error);
                });
        });


        $("textarea[name=text_body]").on("keyup", function () {
            $("label[for=text_body]").find("span").text(1024 - $(this).val().length);
        }).trigger("keyup");


        $("#channel").change(addChannelType)

        $("#channel").change(setCategories)
        $("#languageSelect").change(setCategories)

        $("#select_header").change(headerOptions);

        $(".div_media_radio").on("click", selectMediaRadio)

        $("#add_variable").on("click", addVariable)

        $("#text-body").on("change keyup keydown paste cut", validateVariables)
        $("#text-body").on("change keyup keydown paste cut", countBodyCharacters)

        $("#select_button").on("change", showButtons)

        phoneValidation();

        $('input[name=url_button]').on("blur", validateURL)

        $("select[name=select_button_action_type]").on("click", showButtonType)

        $("select[name=select_url_type_button]").on("change", showDynamic)

        $("#add_new_button").on("click", () => {

            var select_button = $("#select_button").val()

            switch (select_button) {
                case "option_button_cta":
                    addNewButtonCallToAction()
                    break;
                case "option_button_quick_answer":
                    addNewButtonQuickAnswer()
                    break;
            }

        })

        const draggables = document.querySelectorAll('.draggable')
        const containers = document.querySelectorAll('.button-container')

        makeInputNotDraggable()

        $("#btn_submit").on('click', submit)

        // Permite a ordenação dos botões
        draggables.forEach(draggable => {
            draggable.addEventListener('dragstart', () => {
                draggable.classList.add('dragging')
            })

            draggable.addEventListener('dragend', () => {
                draggable.classList.remove('dragging')
            })
        })

        containers.forEach(container => {
            container.addEventListener('dragover', e => {
                e.preventDefault()
                const afterElement = getDragAfterElement(container, e.clientY)
                const draggable = document.querySelector('.dragging')

                if (afterElement == null) {
                    container.appendChild(draggable)
                } else {
                    container.insertBefore(draggable, afterElement)
                }
            })
        })

        $("#select_button").on("change", function () {
            $("#url_button").val("");
            $("#text-button").val("");
            $("#phone-button").val("");

            $("#alert_cta_button").css({ display: "none" });
            $("#alert_url_button").css({ display: "none" });
            $("#alert_text_button2").css({ display: "none" });
            $("#alert_phone_button").css({ display: "none" });
        });

    } else if (verifyView != null) {
        // VIEW

        $('.wa-body').scrollTop($("#tex_area").height());

        $('#tex_footer').each(function () {
            if (this.scrollHeight <= 45) {
                this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
            } else {
                this.setAttribute('style', 'height: 45px;overflow-y:hidden;');
            }

        }).on('input', function () {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        setLayoutButtons()

    } else if (verifyEdit != null) {

        if ($("#languageSelect").val() == "pt_BR") {
            let category = $("#category").val();
            switch (category) {
                case 'ACCOUNT UPDATE':
                    $('#category option').text('ATUALIZAÇÃO DA CONTA');
                    break;

                case 'PAYMENT UPDATE':
                    $('#category option').text('ATUALIZAÇÃO DE PAGAMENTO');
                    break;

                case 'PERSONAL FINANCE UPDATE':
                    $('#category option').text('ATUALIZAÇÃO DE FINANÇAS PESSOAIS');
                    break;

                case 'SHIPPING UPDATE':
                    $('#category option').text('ATUALIZAÇÃO DE REMESSA');
                    break;

                case 'RESERVATION UPDATE':
                    $('#category option').text('ATUALIZAÇÃO DE RESERVA');
                    break;

                case 'ISSUE RESOLUTION':
                    $('#category option').text('RESOLUÇÃO DE PROBLEMAS');
                    break;

                case 'APPOINTMENT UPDATE':
                    $('#category option').text('ATUALIZAÇÃO DE COMPROMISSO');
                    break;

                case 'TRANSPORTATION UPDATE':
                    $('#category option').text('ATUALIZAÇÃO DE TRANSPORTE');
                    break;

                case 'TICKET UPDATE':
                    $('#category option').text('ATUALIZAÇÃO DE TICKET');
                    break;

                case 'TRANSACTIONAL':
                    $('#category option').text('TRANSACIONAL');
                    break;

                case 'MARKETING':
                    $('#category option').text('MARKETING');
                    break;

                case 'OTP':
                    $('#category option').text('SENHAS DESCARTÁVEIS');
                    break;

                default: 'ALERT UPDATE'
                    $('#category option').text('ATUALIZAÇÃO DE ALERTA');
                    break;
            }
        }

        switch ($("#select_header").val()) {

            case '':

                break;
            case 'option_header_text':

                $("#div_text_header").show();
                break;
            case 'option_header_media':

                $("#div_media_header").show();
                break;

        }
        // .addClass("div_media_radio_selected")
        switch ($("#header_media_input_value").val()) {

            case '3':
                $("#div_media_image").addClass("div_media_radio_selected")
                break;
            case '5':
                $("#div_media_video").addClass("div_media_radio_selected")
                break;
            case '10':
                $("#div_media_document").addClass("div_media_radio_selected")

                break;
        }

    }

});


function countBodyCharacters(e) {

    var text_body = document.getElementById("text-body")
    var count_caracter = document.getElementById("count-caracter")
    count_caracter.innerHTML = 1024 - text_body.value.length
}


function setCategories() {

    let lang = $("#languageSelect").val();

    switch (lang) {
        case 'pt_BR':
            $("#category_pt_br").show().val($("#category_en_us").val());
            $("#category_en_us").hide()
            break;

        case 'en_US':
            $("#category_en_us").show().val($("#category_pt_br").val());
            $("#category_pt_br").hide()
            break;
    }
}


function setLayoutButtons() {

    let buttons = document.getElementsByClassName('btn-quick-reply');
    let buttons_qtd = buttons.length

    switch (buttons_qtd) {
        case 1:
            break;
        case 2:
            buttons[0].style.width = "140px";
            buttons[1].style.width = "140px";
            break;
        case 3:
            buttons[0].style.width = "140px";
            buttons[1].style.width = "140px";
            break;
    }

}


function buildButtonObject() {

    // Monta o Json dos botões
    var select_button = $("#select_button").val()
    let buttons = [];

    switch (select_button) {
        case "option_button_cta":

            var buttons_selected = document.querySelectorAll('.draggable');

            for (var i = 0; i < buttons_selected.length; i++) {

                let select_button_action_type = buttons_selected[i].querySelector(':nth-child(2)').getElementsByTagName('select')[0].value
                let text_button = buttons_selected[i].querySelector(':nth-child(3)').getElementsByTagName('input')[0].value
                let select_country = buttons_selected[i].querySelector(':nth-child(4)').getElementsByTagName('select')[0].value
                let input_phone = buttons_selected[i].querySelector(':nth-child(4)').getElementsByTagName('input')[0].value
                let select_url_type_button = buttons_selected[i].querySelector(':nth-child(5)').getElementsByTagName('select')[0].value
                let url_button = buttons_selected[i].querySelector(':nth-child(5)').getElementsByTagName('input')[0].value
                url_button = url_button.slice(-1) != '/' ? url_button + '/' : url_button

                switch (select_button_action_type) {
                    case 'PHONE_NUMBER':
                        var button = {
                            "type": select_button_action_type,
                            "text": text_button,
                            "phone_number": select_country + input_phone,
                        }
                        break;
                    case 'URL':
                        var button = {
                            "type": select_button_action_type,
                            "text": text_button,
                            "url": url_button + (select_url_type_button == "option_url_type_dynamic" ? '{{1}}' : '')
                        }
                        break;
                }

                buttons.push(button)
            }
            return buttons;

            break;

        case "option_button_quick_answer":

            var buttons_selected = document.querySelectorAll('.div_quick_answer_button');

            for (var i = 0; i < buttons_selected.length; i++) {

                let text_button = buttons_selected[i].getElementsByTagName('input')[0].value

                let button = {
                    type: "QUICK_REPLY",
                    text: text_button
                }

                buttons.push(button)
            }
            return buttons;
            break;
    }

}


function submit() {

    $("#div_loading_req").modal({ backdrop: 'static', keyboard: false, show: true });

    validateVariablesSubmit()

    let buttons = buildButtonObject()
    var name_to_request = nameFormat($("#name").val())

    var language = $("#languageSelect").val()
    var category;

    switch (language) {
        case 'pt_BR':
            category = $("#category_pt_br").val()
            break;
        case 'en_US':
            category = $("#category_en_us").val()
            break;
    }

    const data = {
        info: {
            input_creation: $("#input-creation").val(),
            channel: $("#channel").val(),
            channel_type: $("#channel_type").val(),
            category: category,
            language: language,
            name: $("#name").val(),
            name_to_request: name_to_request
        },
        header: {
            format: $("#select_header").val() == 'option_header_text' ? 'TEXT' : (typeof $('input[name=media_header]:checked').val() !== 'undefined' ? $('input[name=media_header]:checked').val() : ''),
            select_header: $("#select_header").val(),
            text_header: $("#text-header").val(),
            media_header: typeof $('input[name=media_header]:checked').val() !== 'undefined' ? $('input[name=media_header]:checked').val() : '',
        },
        body: {
            text_body: $("#text-body").val(),
        },
        footer: {
            text_footer: $("#text-footer").val(),
        },
        button: {
            select_button: $("#select_button").val(),
            buttons
        }
    }

    var validation = validateFields(data);

    if (validation) {
        submitTemplate(data)
    }

}


function submitTemplate(data) {

    var body
    var header
    var footer
    var button
    var components = [];

    body = {
        "type": "BODY",
        "text": data.body.text_body
    }
    components.push(body)

    if (data.header.select_header != '') {

        switch (data.header.format) {

            case 'TEXT':
                header = {
                    "type": "HEADER",
                    "format": data.header.format,
                    "text": data.header.text_header
                }
                break;

            default:
                header = {
                    "type": "HEADER",
                    "format": data.header.format,
                }
                break;
        }

        components.push(header)

    }

    if (data.footer.text_footer != '') {

        footer = {
            "type": "FOOTER",
            "text": data.footer.text_footer
        }
        components.push(footer)

    }

    if (data.button.select_button != '') {
        button = {
            "type": "BUTTONS",
            "buttons": data.button.buttons
        }
        components.push(button)
    }

    var content = JSON.stringify(
        {
            "name": data.info.name_to_request,
            "category": data.info.category,
            "components": components,
            "language": data.info.language
        }
    );

    var contentToSave = JSON.stringify(
        {
            "name": data.info.name,
            "channel_type": data.info.channel_type,
            "channel_id": data.info.channel
        }
    )

    //Substitui % por 1&&& para passar pela validação de carácter do codeigniter global_xss_filtering//
    content = content.replace(/%/gi, "1&&&");

    switch (data.info.channel_type) {
        case '12':
            var url = document.location.origin + "/templates/submitTemplateWaba";
            break;
        case '16':
            var url = document.location.origin + "/templates/submitTemplateCloud";
            break;
    }

    var dados = new FormData();
    dados.append('no_redirect', "true");
    dados.append('data', content);
    dados.append('contentToSave', contentToSave);
    dados.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

    $.ajax({
        type: "POST",
        url: url,
        data: dados,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            if (response == true) {
                window.location.href = window.location.origin + "/templates";
            }
            handleError(response)
        },
        error: function (response) {
            handleError(response)
        },
    });

}


function handleError(json) {

    if (typeof json == 'undefined') return;

    if (typeof json.responseJSON !== 'undefined') {

        if (json.responseJSON.meta.success == false) {

            if (json.responseJSON.meta.developer_message == "Template with that name already exists.") {

                document.getElementById("alert_input_name").style.display = "block";
                document.getElementById("alert_input_name").innerHTML = GLOBAL_LANG.template_validation_same_name;

            } else if (json.responseJSON.meta.developer_message.includes("Message template language is being deleted")) {

                document.getElementById("alert_input_name").style.display = "block";
                document.getElementById("alert_input_name").innerHTML = GLOBAL_LANG.template_validation_same_name_deleted;

            } else if (json.responseJSON.meta.developer_message.indexOf('Header Format is Incorrect') > 0) {

                document.getElementById("alert_input_header").style.display = "block";
                document.getElementById("alert_input_header").innerHTML = GLOBAL_LANG.template_validation_invalid_parameter_header;

            } else if (json.responseJSON.meta.developer_message.indexOf("Direct links to WhatsApp aren't allowed for buttons.") > 0) {

                document.getElementById("alert_url_button").style.display = "block";
                document.getElementById("alert_url_button").innerHTML = GLOBAL_LANG.template_validation_invalid_parameter_button;

            } else {
                showGeneralRequestError()
            }

        }

    }

    if (typeof json.error !== 'undefined') {

        switch (json.error.error_user_title) {
            case "Já existe conteúdo nesse idioma":

                document.getElementById("alert_input_name").style.display = "block";
                document.getElementById("alert_input_name").innerHTML = GLOBAL_LANG.template_validation_same_name;

                break;
            case "O formato do título está incorreto":

                document.getElementById("alert_input_header").style.display = "block";
                document.getElementById("alert_input_header").innerHTML = GLOBAL_LANG.template_validation_invalid_parameter_header;

                break;
            case "Erro ao adicionar a URL do botão":

                document.getElementById("alert_url_button").style.display = "block";
                document.getElementById("alert_url_button").innerHTML = GLOBAL_LANG.template_validation_invalid_parameter_button;

                break;
            default:
                showGeneralRequestError();
                break;
        }
    }

    $('#div_loading_req').modal('hide');
}


function showGeneralRequestError() {
    document.getElementById("alert_input_general_request").style.display = "block";
    document.getElementById("alert_input_general_request").innerHTML = GLOBAL_LANG.template_validation_general_error;
}


function nameFormat(string) {

    return string.replace(/[ |:|;|=|-]/g, '_').replace(/[ç]/g, 'c').replace(/[&]/g, 'e').replace(/[^\w ]/g, '').toLowerCase();

}


function validateFields(data) {

    $("#alert_cta_button").hide();
    $("#alert_phone_button").hide();

    var general_validation = true;
    var name_validation = true;
    var text_header_validation = true;
    var media_header_validation = true;
    var text_body_validation = true;
    var cta_button_validation = true;
    var quick_answer_button_validation = true;
    var variables_validation = true;


    if (data.info.name == '') {

        name_validation = false

        document.getElementById("alert_input_name").style.display = "block";
        document.getElementById("alert_input_name").innerHTML = GLOBAL_LANG.template_validation_name;

    } else {
        document.getElementById("alert_input_name").style.display = "none";
    }

    switch (data.header.select_header) {

        case 'option_header_text':

            if (data.header.text_header == '') {

                text_header_validation = false
                general_validation = false

                document.getElementById("alert_input_header").style.display = "block";
                document.getElementById("alert_input_header").innerHTML = GLOBAL_LANG.template_validation_header_text;

            } else {
                document.getElementById("alert_input_header").style.display = "none";
            }

            break;

        case 'option_header_media':

            if (data.header.media_header == '') {

                media_header_validation = false
                general_validation = false

                document.getElementById("alert_input_media").style.display = "block";
                document.getElementById("alert_input_media").innerHTML = GLOBAL_LANG.template_validation_header_media;

            } else {
                document.getElementById("alert_input_media").style.display = "none";
            }

            break;
    }

    if (data.body.text_body == '') {

        text_body_validation = false
        general_validation = false

        document.getElementById("alert_input_body").style.display = "block";
        document.getElementById("alert_input_body").innerHTML = GLOBAL_LANG.template_validation_body;

    } else {
        document.getElementById("alert_input_body").style.display = "none";
    }

    switch (data.button.select_button) {

        case 'option_button_cta':

            for (let i = 0; i < data.button.buttons.length; i++) {

                switch (data.button.buttons[i]["type"]) {
                    case 'PHONE_NUMBER':
                        if (data.button.buttons[i]["text"] == '' || data.button.buttons[i]["phone_number"].length < 4) {

                            cta_button_validation = false
                            general_validation = false

                            document.getElementById("alert_cta_button").style.display = "block";
                            document.getElementById("alert_cta_button").innerHTML = GLOBAL_LANG.template_validation_button;

                        } else if (data.button.buttons[i]["phone_number"].length < 13) {

                            cta_button_validation = false
                            general_validation = false

                            document.getElementById("alert_phone_button").style.display = "block";
                            document.getElementById("alert_phone_button").innerHTML = GLOBAL_LANG.template_validation_url_button_phone;

                        } else {
                            document.getElementById("alert_cta_button").style.display = "none";
                        }

                        break;
                    case 'URL':

                        if (cta_button_validation) {

                            if (data.button.buttons[i]["text"] == '' || data.button.buttons[i]["url"] == '') {

                                cta_button_validation = false
                                general_validation = false

                                document.getElementById("alert_cta_button").style.display = "block";
                                document.getElementById("alert_cta_button").innerHTML = GLOBAL_LANG.template_validation_button;

                            } else {
                                document.getElementById("alert_cta_button").style.display = "none";
                            }
                        }

                        if (data.button.buttons[i]["url"].slice(0, 8) != 'https://') {
                            cta_button_validation = false
                            general_validation = false

                            document.getElementById("alert_url_button").style.display = "block";
                            document.getElementById("alert_url_button").innerHTML = GLOBAL_LANG.template_validation_url_button;

                        } else {

                            document.getElementById("alert_url_button").style.display = "none";
                        }

                        break;
                }

            }

            break;
        case 'option_button_quick_answer':

            for (let i = 0; i < data.button.buttons.length; i++) {

                if (data.button.buttons[i]["text_quick_answer_button"] == '') {

                    quick_answer_button_validation = false
                    general_validation = false

                    document.getElementById("alert_text_quick_reply_button").style.display = "block";
                    document.getElementById("alert_text_quick_reply_button").innerHTML = GLOBAL_LANG.template_validation_button;

                } else {
                    document.getElementById("alert_text_quick_reply_button").style.display = "none";
                }

            }
            break;
    }

    if ($("#variable_error_list li").length > 0) {
        variables_validation = false
        general_validation = false
    }

    let url_buttom = document.getElementById("url_button").value;

    if (url_buttom != null || url_buttom != '') {

        if (url_buttom.includes("//wa.me/") || url_buttom.includes("whatsapp")) {

            document.getElementById("alert_url_button").style.display = "block";
            document.getElementById("alert_url_button").innerHTML = GLOBAL_LANG.template_validation_invalid_parameter_button;

            setTimeout(() => $('#div_loading_req').modal('hide'), 1000);
            return false;

        }
    }

    if (general_validation) {
        return true
    }

    setTimeout(() => {

        $('#div_loading_req').modal('hide');
    }, 1000);

    return false

}


function countElements() {

    var select_button = $("#select_button").val()

    switch (select_button) {
        case "option_button_cta":
            return document.getElementsByClassName('draggable').length;
            break;
        case "option_button_quick_answer":
            return document.getElementsByClassName('div_quick_answer_button').length;
            break;
    }

}


function validateURL() {

    let url = this.value
    let url_button_alert = document.getElementsByName("alert_url_button");

    $("#alert_url_button").css({ display: "none" });

    if (url.slice(0, 8) != "https://") {

        for (var i = 0; i < url_button_alert.length; i++) {

            url_button_alert[i].style.display = "block";
            url_button_alert[i].innerHTML = GLOBAL_LANG.template_validation_url_button;

        }

    } else {

        for (var i = 0; i < url_button_alert.length; i++) {
            url_button_alert[i].style.display = "none";
        }
    }


}


function phoneValidation() {

    $('input[name="phone-button"]').keyup(function () {
        $('input[name="phone-button"]').val(this.value.match(/[0-9]*/));
    });
}


function checkOptionSelected(clone) {
    var select_button_action_type = document.querySelectorAll('.select_button_action_type')[0];

    var secondSelect = clone.children[1].children[1]

    switch (select_button_action_type.value) {

        case "PHONE_NUMBER":
            clone.children[3].style.display = "none";
            clone.children[4].style.display = "block";
            secondSelect.value = "URL"
            break;
        case "URL":
            clone.children[3].style.display = "block";
            clone.children[4].style.display = "none";
            secondSelect.value = "PHONE_NUMBER"
            break;
    }
}


function addChannelType() {

    var element = $("#channel").find('option:selected');
    var myTag = element.attr("channel_type");

    $('#channel_type').val(myTag);

}


function selectMediaRadio() {

    $(".div_media_radio").removeClass("div_media_radio_selected")

    switch (this.id) {
        case "div_media_image":
            $("#media_header_image").prop('checked', true)
            $(`#${this.id}`).addClass("div_media_radio_selected")
            break;
        case "div_media_video":
            $("#media_header_video").prop('checked', true)
            $(`#${this.id}`).addClass("div_media_radio_selected")
            break;
        case "div_media_document":
            $("#media_header_document").prop('checked', true)
            $(`#${this.id}`).addClass("div_media_radio_selected")
            break;
    }
}


function headerOptions() {

    var element = $("#select_header").find('option:selected').val();

    switch (element) {
        case "option_header_text":
            $("#div_text_header").show();
            $("#div_media_header").hide();
            break;
        case "option_header_media":
            $("#div_media_header").show();
            $("#div_text_header").hide();
            break;
        default:
            $("#div_media_header").hide();
            $("#div_text_header").hide();
            break;
    }
}


String.prototype.replaceAt = function (index, replacement) {
    if (index >= this.length) {
        return this.valueOf();
    }

    return this.substring(0, index) + replacement + this.substring(index + 1);
}


function addVariable() {

    var data = orderVariables()

    var indexes = data[0]
    var variables = data[1]
    var newText = data[2]

    var text_body = $("#text-body");
    var text_body_content = $("#text-body").val();

    var new_var = indexes.length + 1
    var new_text_body;

    // Verifica se atingiu o limite de variáveis e adiciona 1
    if (variables.slice(-1) < 10) {

        if (text_body_content != "") {

            if (newText.length > 0) {

                new_text_body = newText.slice(-1) + ` {{${new_var}}}`
            } else {
                new_text_body = text_body_content + ` {{${new_var}}}`
            }

        } else {
            new_text_body = `{{${new_var}}}`
        }

    } else {
        new_text_body = text_body_content
    }

    text_body.val(new_text_body)
    text_body.focus()

    validateVariables()
}


function orderVariables() {

    var text_body_content = $("#text-body").val();
    var data = [];

    // Monta array com os index das variáveis
    var indexes = [];
    for (var i = 0; i < text_body_content.length; i++) {

        if (text_body_content[i] === "{" && text_body_content[i + 1] != "{") {
            indexes.push(i + 1);
        }

    }

    data.push(indexes);
    // Monta array com algarismos da quatidade de indexes obtidos acima
    var variables = [];
    for (var i = 0; i < indexes.length; i++) {
        variables[i] = i + 1
    }

    data.push(variables)

    // Substitui as variáveis vindas pela ordem correta contida no array "variables"
    var newText = [text_body_content];
    for (var i = 0; i < indexes.length; i++) {

        // Verifica se a variável a ser substitída possui mais de 1 algarismo
        if (newText[i][indexes[i] + 1] != "}") {
            newText[i + 1] = newText[i].replaceAt(indexes[i] + 1, '').replaceAt(indexes[i], variables[i])
        } else {
            newText[i + 1] = newText[i].replaceAt(indexes[i], variables[i])
        }
    }

    data.push(newText)

    return data;
}


function validateVariablesSubmit() {

    // Faz a validação das variáveis após o usuário clicar em salvar (para caso estejam desordenadas)

    var data = orderVariables()

    var newText = data[2]

    var text_body = $("#text-body");
    var new_text_body = newText.slice(-1)

    text_body.val(new_text_body)
}


function validateVariables() {

    $("#div_variables_error").show()

    var text_body_content = $("#text-body").val().trim();
    var variable_error_list = $("#variable_error_list")

    var li_variable_first_last_error = $("#li_variable_first_last_error")
    var variable_first_last_error_in_screen = false

    if (li_variable_first_last_error.length == 1) {
        variable_first_last_error_in_screen = true
    }

    var li_variable_qtd_error = $("#li_variable_qtd_error")
    var variable_qtd_error_in_screen = false

    if (li_variable_qtd_error.length == 1) {
        variable_qtd_error_in_screen = true
    }


    // Valida se o início ou o final do template estão com variáveis
    if (text_body_content.slice(0, 2) == "{{" || text_body_content.slice(-2) == "}}") {

        if (!variable_first_last_error_in_screen) {

            var newError = document.createElement('li')
            newError.setAttribute("id", "li_variable_first_last_error");
            newError.innerHTML = ('<i class="fas fa-exclamation-triangle"></i>' + GLOBAL_LANG.template_body_button_variable_first_last_error)
            variable_error_list.append(newError)

            variable_first_last_error_in_screen = true

        }

    } else {

        if (text_body_content.slice(0, 2) != "{{" || text_body_content.slice(-2) != "}}") {
            $("#li_variable_first_last_error").remove()

            if (!variable_qtd_error_in_screen) {

                $("#div_variables_error").hide()
            }
        }
    }


    // Valida se a qtd de variáveis é maior que a qtd de caracteres
    var indexes = [];
    for (var i = 0; i < text_body_content.length; i++) {

        if (text_body_content[i] === "{" && text_body_content[i + 1] != "{") {
            indexes.push(i + 1);
        }
    }

    var qtd_var = indexes.length

    var text_body_content = text_body_content.split(" ")

    var qtd_text_body_content_without_var = text_body_content.filter((element) => {
        return element != ""
    }).length - qtd_var

    if (qtd_text_body_content_without_var - 2 < qtd_var && qtd_var != 0) {

        if (!variable_qtd_error_in_screen) {

            var newError = document.createElement('li')
            newError.setAttribute("id", "li_variable_qtd_error");
            newError.innerHTML = ('<i class="fas fa-exclamation-triangle"></i>' + GLOBAL_LANG.template_body_button_variable_qtd_error)
            variable_error_list.append(newError)

            variable_qtd_error_in_screen = true

        }

    } else {

        $("#li_variable_qtd_error").remove()
        if (!variable_first_last_error_in_screen) {

            $("#div_variables_error").hide()
        }

    }

}


function checkIfCanShowDeleteButton() {

    var counter = countElements();

    if (counter == 1) {
        $('.fa-times').hide()
    } else {
        $('.fa-times').show()

    }
}


function checkIfCanShowAddButton() {

    var counter = countElements();

    var select_button = $("#select_button").val()

    switch (select_button) {
        case "option_button_cta":
            if (counter < 2) {
                $("#add_new_button").prop('disabled', false)
            } else {

                $("#add_new_button").prop('disabled', true)
            }
            break;
        case "option_button_quick_answer":
            if (counter < 3) {
                $("#add_new_button").prop('disabled', false)
            } else {

                $("#add_new_button").prop('disabled', true)
            }
            break;
    }
}


function countButtonTexts() {
    return document.getElementsByClassName('div_quick_answer_button').length;
}


function removeButtonType(button_close) {
    button_close.parentElement.remove()

    var select = document.querySelectorAll('.select_button_action_type')[0]
    var counter = countElements();
    if (counter < 2) {
        $("#add_new_button").prop('disabled', false)

        select.disabled = false;

    }

    checkIfCanShowDeleteButton()
}


function removeButtonQuickAnswer(button_close) {
    var button_parent = button_close.parentElement
    button_parent.parentElement.remove()

    var counter = countElements();
    if (counter < 3) {
        $("#add_new_button").prop('disabled', false)

    }

    checkIfCanShowDeleteButton()

}


function addNewButtonCallToAction() {

    const lastElement = Array.from(
        document.querySelectorAll('.draggable')
    ).pop();

    var container = document.getElementsByClassName("button-container")[0]

    const clone = lastElement.cloneNode(true)
    const third = clone.querySelector(':nth-child(3)');

    const input = third.getElementsByTagName('input')[0];

    var counter = countElements() + 1
    input.id = 'text-button' + counter
    input.name = 'text-button' + counter
    input.value = ''

    let div_validations = clone.querySelectorAll('.alert-field-validation');
    for (let i = 0; i < div_validations.length; i++) {
        div_validations[i].id = "alert_text_button" + counter
    }

    container.appendChild(clone);

    var counter = countElements();
    if (counter >= 2) {
        $("#add_new_button").prop('disabled', true)

        var selects = document.querySelectorAll(".select_button_action_type")

        selects.forEach(select => {
            select.disabled = "true"
        })

    }

    $("select[name=select_button_action_type]").on("click", showButtonType)
    $('input[name="url_button"]').on("blur", validateURL)
    $("select[name=select_url_type_button]").on("change", showDynamic)
    phoneValidation()
    makeInputNotDraggable()
    checkIfCanShowDeleteButton()
    checkOptionSelected(clone)
}


function addNewButtonQuickAnswer() {

    const lastElement = Array.from(
        document.querySelectorAll('.div_quick_answer_button')
    ).pop();

    var container = document.getElementsByClassName('container_quick_answer')[0]

    const clone = lastElement.cloneNode(true)

    const input = clone.getElementsByTagName('input')[0];

    var counter = countElements() + 1
    input.id = 'text_quick_answer_button' + counter
    input.name = 'text_quick_answer_button' + counter
    input.value = ''

    container.appendChild(clone)

    var counter = countElements();
    if (counter >= 3) {
        $("#add_new_button").prop('disabled', true)

    }

    checkIfCanShowDeleteButton()
}


function showButtons() {

    switch (this.value) {
        case "option_button_cta":
            $("#div_call_to_action").show();
            $("#div_quick_answer").hide();
            $("#add_new_button").show();
            break;
        case "option_button_quick_answer":
            $('.fa-times').show()
            $("#div_quick_answer").show();
            $("#div_call_to_action").hide();
            $("#add_new_button").show();
            break;
        default:
            $("#div_call_to_action").hide();
            $("#div_quick_answer").hide();
            $("#add_new_button").hide();
            break;
    }

    checkIfCanShowDeleteButton()
    checkIfCanShowAddButton()
    showButtonType()

}


function showDynamic() {
    var select_url_type_button = $("#select_url_type_button").val()
    var span_dynamic_url = $("#span_dynamic_url")

    if (select_url_type_button == "option_url_type_dynamic") {

        span_dynamic_url.show()

    } else {

        span_dynamic_url.hide()
    }
}


function showButtonType() {

    switch (this.value) {

        case "PHONE_NUMBER":
            $("#div_select_call").show();
            $("#div_select_url").hide();
            break;
        case "URL":
            $("#div_select_url").show();
            $("#div_select_call").hide();
            break;
    }
}


function getDragAfterElement(container, y) {

    const draggableElements = [...container.querySelectorAll('.draggable:not(.dragging)')]

    return draggableElements.reduce((closest, child) => {
        const box = child.getBoundingClientRect()
        const offset = y - box.top - box.height / 2

        if (offset < 0 && offset > closest.offset) {
            return { offset: offset, element: child }

        } else {
            return closest
        }
    }, { offset: Number.NEGATIVE_INFINITY }).element
}


function makeInputNotDraggable() {

    $('input')
        .on('focus', function (e) {
            $(this).closest('.draggable').attr("draggable", false);
        })
        .on('blur', function (e) {
            $(this).closest('.draggable').attr("draggable", true);
        });

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
            column = "account_key_remote_id";
            break;

        case 2:
            column = "category";
            break;

        case 3:
            column = "status";
            break;
    }

    $.get(`/export/xlsx?
        search=${$('#search').val()}
        &column=${column}
        &order=${order}
        &type=templateMsg`, function (response) {

        if (response != "Error") {
            Swal.fire({
                title: GLOBAL_LANG.template_alert_export_title,
                text: GLOBAL_LANG.template_alert_export_text,
                type: 'success',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: GLOBAL_LANG.template_alert_export_confirmButtonText
            });

        }

    });
}