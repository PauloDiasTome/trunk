const Filters = JSON.parse(localStorage.getItem("filters")) || null;


function checkFilter() {
    if (Filters) {

        if (!Filters.btn_back) return

        if (Filters.permission.search) {
            document.getElementById("search").value = Filters.permission.search;
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
            url: "permission/find",
            type: "POST",
            data: {
                "text": $("#search").val(),
                "csrf_talkall": Cookies.get("csrf_cookie_talkall")
            }
        },
        columns: [
            {
                mData: 'name'
            }
        ],
        columnDefs: [
            {
                targets: 1,
                render: function (data, type, full, meta) {

                    return `<div class="dropleft">
                                    <button class="btn btn-link mb-0 btn-dropleft" type="button" id="multiDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #525f7f">
                                        <i class="fa fa-ellipsis-v"></i>
                                        <div class="ripple-container"></div>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="multiDropdownMenu">
                                        <a href="permission/edit/` + full.id_permission + `" class="dropdown-item table-btn-edit" style="cursor: pointer">
                                            <div style="width: 24px; display: inline-block">     
                                                <i class="far fa-edit"></i>
                                            </div>
                                            <span>${GLOBAL_LANG.permission_column_action_edit}</span>
                                        </a>
                                        <a id="` + full.id_permission + `" href='#' class="dropdown-item table-btn-delete" style="cursor: pointer">
                                            <div style="width: 24px; display: inline-block"> 
                                                <i class="far fa-trash-alt"></i>
                                            </div>
                                            <span>${GLOBAL_LANG.permission_column_action_delete}</span>
                                        </a>
                                    </div>
                                </div>`
                }
            },
            {
                orderable: true,
                targets: [0]
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

            const permission = {
                search: $("#search").val(),
            }

            let filter = localStorage.getItem("filters");

            filter = filter ? JSON.parse(filter) : {};
            filter.permission = permission;

            localStorage.setItem("filters", JSON.stringify(filter));
        }
    });
}


$(document).ready(function () {

    $("#search").on("keyup", function (event) {
        if (event.which == 13) {
            find();
        }
    });
    find();


    $("#datatable-basic").on("click", ".delete", function () {
        $.post('permission/delete', $(this).attr("id-delete"), function (e) {
            alert(e);
        });
    });


    $("#datatable-basic").on("click", ".table-btn-delete", function () {
        swal({
            title: GLOBAL_LANG.permission_alert_delete_title,
            text: GLOBAL_LANG.permission_alert_delete_text,
            type: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            reverseButtons: true,
            confirmButtonClass: "btn btn-success",
            confirmButtonText: GLOBAL_LANG.permission_alert_delete_confirmButtonText,
            cancelButtonClass: "btn btn-danger",
            cancelButtonText: GLOBAL_LANG.permission_alert_delete_cancelButtonText,
        }).then(t => {
            if (t.value == true) {
                $.post(document.location.origin + "/permission/delete/" + this.id, function (data) {
                    if (data.length < 1) {
                        t.value && swal({
                            title: GLOBAL_LANG.permission_alert_delete_two_title,
                            text: GLOBAL_LANG.permission_alert_delete_two_text,
                            type: "success",
                            buttonsStyling: !1,
                            confirmButtonClass: "btn btn-success"
                        });
                        $("#datatable-basic").DataTable().ajax.reload(null, false);
                    } else {

                        let name = "";
                        let total = "";
                        let i = 0;

                        for (i; i < data.length; i++) {
                            if (i < 2) {
                                name += data[i].name + ', ';
                            }
                        }

                        name = name.substring(0, name.length - 2);

                        if (data.length > 2) {
                            total = data.length - 2;
                            total = "+" + total;
                        }

                        t.value && swal({
                            title: GLOBAL_LANG.permission_alert_delete_three_title,
                            html: `${GLOBAL_LANG.permission_alert_delete_three_text_first_part}<span>  ${name} </span><span>${total == "" ? "" : GLOBAL_LANG.permission_alert_delete_three_text_second_part} ${total}  </span>${GLOBAL_LANG.permission_alert_delete_three_text_third_part}`,
                            type: "warning",
                            buttonsStyling: !1,
                            confirmButtonClass: "btn btn-primary"
                        });
                    }
                });
            }
        });
    });


    if ($(".ip_address").val() != "") {
        $("#collapseTwo").show();
        $("#advanced-settings").attr("checked", true);
    }


    $('#ip_list').removeAttr("style");
    $('#ip_list').css({ "visibility": "hidden" });


    $('#advancend').on('click', function () {
        if ($("#ip_list").prop("disabled")) {
            $("#ip_list").prop("disabled", false);
        } else {
            $("#ip_list").prop("disabled", true);
            $("#ip_list").prop("disabled", 'disabled');
        }
    });


    $('.bootstrap-tagsinput input').mask('0ZZ.0ZZ.0ZZ.0ZZ', {
        translation: {
            'Z': {
                pattern: /[0-9]/, optional: true
            }
        }
    });


    $("#advanced-settings").on("click", function () {

        if ($("#ip_list").prop("disabled")) {
            $("#ip_list").prop("disabled", false);
        } else {
            $("#ip_list").prop("disabled", true);
            $("#ip_list").prop("disabled", 'disabled');
        }

        if ($("#advanced-settings").is(":checked")) {
            $("#collapseTwo").show();
            $(".bootstrap-tagsinput input").focus();
        } else {
            $("#collapseTwo").hide();
        };
    });


    $(".predefined-groups").on("click", function () {

        let group = $(this).attr("class").split(" ")[1];
        let data = [];

        const management_group = document.getElementsByClassName("management-group")[0];
        const attendant_group = document.getElementsByClassName("attendant-group")[0];
        const financial_group = document.getElementsByClassName("financial-group")[0];

        switch (group) {
            case "management-group":

                data = [
                    "dashboard",
                    "contact",
                    "persona",
                    "label",
                    "block_list",
                    "community",
                    "user",
                    "replies",
                    "usergroup",
                    "permission",
                    "usercall",
                    "messenger",
                    "publication_whatsapp_waba",
                    "publication_whatsapp_newsletter",
                    "publication_whatsapp_community",
                    "publication_whatsapp_broadcast",
                    "publication_whatsapp_status",
                    "publication_facebook",
                    "publication_instagram",
                    "ticket",
                    "kanban_attendance",
                    "kanban_communication",
                    "report",
                    "evaluate_report",
                    "config",
                    "chatbot",
                    "report_call",
                    "work_time",
                    "shortlink",
                    "calendar",
                    "myinvoice",
                    "product",
                    "faq",
                    "group_contact",
                    "financial",
                    "block_access_work_time",
                    "conversation_billable",
                    "broadcast_sms",
                    "company",
                    "templates",
                    "integration"
                ]

                if (management_group != null) management_group.style.backgroundColor = "#2263d3";
                if (attendant_group != null) attendant_group.style.backgroundColor = "#008ce7";
                if (financial_group != null) financial_group.style.backgroundColor = "#008ce7";
                break;

            case "attendant-group":
                data = [
                    "dashboard",
                    "contact",
                    "messenger",
                    "ticket",
                    "report",
                    "config",
                    "chatbot",
                    "work_time",
                    "shortlink",
                    "faq",
                    "block_access_work_time",
                ]

                if (management_group != null) management_group.style.backgroundColor = "#008ce7";
                if (attendant_group != null) attendant_group.style.backgroundColor = "#2263d3";
                if (financial_group != null) financial_group.style.backgroundColor = "#008ce7";
                break;

            case "financial-group":
                data = [
                    "contact",
                    "block_list",
                    "user",
                    "replies",
                    "usergroup",
                    "usercall",
                    "messenger",
                    "kanban_attendance",
                    "kanban_communication",
                    "report",
                    "evaluate_report",
                    "config",
                    "chatbot",
                    "report_call",
                    "work_time",
                    "shortlink",
                    "calendar",
                    "myinvoice",
                    "product",
                    "faq",
                    "group_contact",
                    "financial",
                    "block_access_work_time",
                    "conversation_billable",
                    "broadcast_sms",
                    "integration"
                ]


                if (management_group != null) management_group.style.backgroundColor = "#008ce7";
                if (attendant_group != null) attendant_group.style.backgroundColor = "#008ce7";
                if (financial_group != null) financial_group.style.backgroundColor = "#2263d3";
                break;

            default:
                break;
        }

        clearPermisson();
        showPredefinedGroups(data);
    });


    $("form").submit(event => event.preventDefault());
    $('#sendEmailExport').on('click', () => modalExport());
    if (document.querySelector(".btn-success")) {
        document.querySelector(".btn-success").addEventListener("click", submit);
    }
});


function submit() {

    document.getElementById("alert_name").style.display = "none";

    const input_name = document.getElementById("name").value;

    let formValid_name = true;
    let formValid_permission = true;

    // campo nome
    if (!emptyText(input_name)) {
        formValid_name = false;
        document.getElementById("alert_name").style.display = "block";
        document.getElementById("alert_name").innerHTML = GLOBAL_LANG.permission_validation_name_required;
    }

    if (!min_length(input_name, 3)) {
        formValid_name = false;
        document.getElementById("alert_name").style.display = "block";
        document.getElementById("alert_name").innerHTML = GLOBAL_LANG.permission_validation_name_min_length.replace("{param}", 3);
    }

    if (!max_length(input_name, 100)) {
        formValid_name = false;
        document.getElementById("alert_name").style.display = "block";
        document.getElementById("alert_name").innerHTML = GLOBAL_LANG.permission_validation_name_max_length.replace("{param}", 100);
    }

    const options = $('[id*=option__]');

    for (elm of options) if ($(elm).is(":hidden")) exist_option = false;
    for (elm of options) if (!$(elm).is(":hidden")) exist_option = true;

    if (exist_option == 0) {
        formValid_permission = false;
        swal({
            title: GLOBAL_LANG.permission_alert_validation_title,
            text: GLOBAL_LANG.permission_alert_validation_text,
            type: "warning",
            buttonsStyling: !1,
            confirmButtonClass: "btn btn-success",
            confirmButtonText: GLOBAL_LANG.permission_alert_validation_confirmButtonText
        });
    }

    if (formValid_name && formValid_permission) {
        document.querySelector(".btn-success").disabled = true;
        $("form").unbind('submit').submit();
    }
}


function Send() {
    if ($('#ip_list').val().length) {
        $("#ip_list").prop("disabled", false);
    }
}


function clearPermisson() {

    const data = [
        "option__dashboard",
        "option__contact",
        "option__persona",
        "option__label",
        "option__block_list",
        "option__community",
        "option__user",
        "option__replies",
        "option__usergroup",
        "option__permission",
        "option__usercall",
        "option__messenger",
        "option__publication_whatsapp_waba",
        "option__publication_whatsapp_newsletter",
        "option__publication_whatsapp_community",
        "option__publication_whatsapp_broadcast",
        "option__publication_whatsapp_status",
        "option__publication_facebook",
        "option__publication_instagram",
        "option__ticket",
        "option__kanban_attendance",
        "option__kanban_communication",
        "option__report",
        "option__evaluate_report",
        "option__config",
        "option__chatbot",
        "option__report_call",
        "option__work_time",
        "option__shortlink",
        "option__calendar",
        "option__myinvoice",
        "option__product",
        "option__faq",
        "option__group_contact",
        "option__financial",
        "option__block_access_work_time",
        "option__conversation_billable",
        "option__broadcast_sms",
        "option__integration",
        "option__templates",
        "option__company",
    ]

    data.forEach(element => {

        if ($("#" + element).parent().length > 0) {
            if ($("#" + element).parent()[0].attributes.class.value === "title-group single-option") {

                // Opção Unica
                $("#" + element).find("input").remove();
                $("#" + element).parent().parent().hide();

            } else {

                // Multi Opção
                const id = element.split("__")[1];
                const childNodes = $("#" + element).parent().parent()[0];
                const options = $(childNodes).find("div");
                let qtde = 0;

                if ($("#" + id).parent().parent().parent().find(".fa-angle-right").hasClass("selected__opt")) {
                    $("#" + id).parent().removeClass("selected_opt");
                    $("#" + id).parent().show();
                } else
                    $("#" + id).parent().removeClass("selected_opt");

                $("#" + element).parent().hide();
                $("#" + element).parent().removeClass("selected_opt");
                $("input[name='" + id + "']").remove();

                for (const elm of options) {
                    if (!$(elm).is(":hidden")) {
                        qtde++
                    }
                }

                if (qtde === 0) $("#" + element).parent().parent().parent().hide();
            }
        }
    });
}


function showPredefinedGroups(data) {

    $(".container-permission-preview .fa-angle-right").css({ "transform": "rotate(90deg)" });

    data.forEach(element => {

        const input = document.createElement("input");
        input.type = "hidden";
        input.className = "inputHidden";

        if ($("#option__" + element).parent().length > 0) {
            if ($("#option__" + element).parent()[0].attributes.class.value === "title-group single-option") {

                // Opção Unica
                $("input[name='" + element + "']").remove();

                input.setAttribute("name", element);
                $("#option__" + element).parent().parent().show();
                $("#option__" + element).append(input);

            } else {

                // Multi Opção
                $("input[name='" + element + "']").remove();

                input.setAttribute("name", element);

                $("#" + element).append(input);
                $("#" + element).parent().hide();
                $("#" + element).parent().addClass("selected_opt");

                $("#option__" + element).parent().show()
                $("#option__" + element).parent().addClass("selected_opt");
                $("#option__" + element).parent().parent().parent().show();
            }
        }
    });
}


function showGroupPreview(event) {

    if (event.attributes.class.value === "title-group multiple-options") {
        if (!$(event).siblings().find(".selected_opt").is(":visible")) {
            $(event).siblings().find(".selected_opt").show();
            $(event).find(".fa-angle-right").css({ "transform": "rotate(90deg)" });
        } else {
            $(event).siblings().find(".selected_opt").hide();
            $(event).find(".fa-angle-right").css({ "transform": "rotate(0deg)" });
        }
    }
}


function selectOption(event) {

    $("input[name='" + event.id + "']").remove();

    var input = document.createElement("input");
    input.type = "hidden";
    input.className = "inputHidden";
    input.setAttribute("name", event.id);

    $("#" + event.id).append(input);
    $("#" + event.id).parent().hide();
    $("#" + event.id).parent().addClass("selected_opt");

    $("#option__" + event.id).parent().show()
    $("#option__" + event.id).parent().addClass("selected_opt");
    $("#option__" + event.id).parent().parent().parent().show();
}


function deselectOption(event) {

    const id = event.id.split("__")[1];
    const childNodes = $(event).parent().parent()[0];
    const options = $(childNodes).find("div");
    let qtde = 0;

    if ($("#" + id).parent().parent().parent().find(".fa-angle-right").hasClass("selected__opt")) {
        $("#" + id).parent().removeClass("selected_opt");
        $("#" + id).parent().show();
    } else
        $("#" + id).parent().removeClass("selected_opt");

    $("#" + event.id).parent().hide();
    $("#" + event.id).parent().removeClass("selected_opt");
    $("input[name='" + id + "']").remove();

    for (const elm of options) {
        if (!$(elm).is(":hidden")) {
            qtde++
        }
    }

    if (qtde === 0) $("#" + event.id).parent().parent().parent().hide();

    const management_group = document.getElementsByClassName("management-group")[0];
    const attendant_group = document.getElementsByClassName("attendant-group")[0];
    const financial_group = document.getElementsByClassName("financial-group")[0];

    if (management_group != null) management_group.style.backgroundColor = "#008ce7";
    if (attendant_group != null) attendant_group.style.backgroundColor = "#008ce7";
    if (financial_group != null) financial_group.style.backgroundColor = "#008ce7";
}


function showGroup(event) {

    if (event.attributes.class.value === "title-group single-option") {

        const typePermission = $(event).find("label").attr("id");
        $("input[name='" + typePermission + "']").remove();

        const input = document.createElement("input");
        input.type = "hidden";
        input.className = "inputHidden";

        input.setAttribute("name", typePermission);
        $("#option__" + typePermission).parent().parent().show();
        $("#option__" + typePermission).append(input);

    } else {

        const form_check = $(event).siblings().find(".form-check");

        for (const elm of form_check) {

            if (elm.attributes.class.value.trim() === "form-check") {
                if (!$(elm).is(":visible")) {
                    elm.style.display = "block";
                    $(event).find(".fa-angle-right").css({ "transform": "rotate(90deg)" });
                    $(event).find(".fa-angle-right").addClass("selected__opt");
                } else {
                    $("input[name='" + elm.children[0].attributes.id.value + "']").remove();
                    elm.style.display = "none";
                    $(event).find(".fa-angle-right").css({ "transform": "rotate(0deg)" });
                    $(event).find(".fa-angle-right").removeClass("selected__opt");
                }
            }
        }
    }
}


function deselectGroup(event) {

    $(event).find("input").remove();
    $(event).parent().parent().hide();

    const management_group = document.getElementsByClassName("management-group")[0];
    const attendant_group = document.getElementsByClassName("attendant-group")[0];
    const financial_group = document.getElementsByClassName("financial-group")[0];

    if (management_group != null) management_group.style.backgroundColor = "#007AFF";
    if (attendant_group != null) attendant_group.style.backgroundColor = "#007AFF";
    if (financial_group != null) financial_group.style.backgroundColor = "#007AFF";
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
    }

    $.get(`/export/xlsx?
        search=${$('#search').val()}
        &column=${column}
        &order=${order}
        &type=permission`, function (response) {
        if (response != "Error") {
            Swal.fire({
                title: GLOBAL_LANG.permission_alert_export_two_title,
                text: GLOBAL_LANG.permission_alert_export_two_text,
                type: 'success',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: GLOBAL_LANG.permission_alert_export_two_confirmButtonText,
            });
        }
    });
}