const Filters = JSON.parse(localStorage.getItem("filters")) || null;

function checkFilter() {
    if (Filters) {

        if (!Filters.btn_back) return

        if (Filters.category.search) {
            document.getElementById("search").value = Filters.category.search;
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
            url: "category/find",
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
                                        <a href="category/edit/` + full.id_category + `" class="dropdown-item table-btn-edit" style="cursor: pointer">
                                            <div style="width: 24px; display: inline-block">     
                                                <i class="far fa-edit"></i>
                                            </div>
                                            <span>${GLOBAL_LANG.category_dt_columndefs_target2_title_edit}</span>
                                        </a>
                                        <a id="` + full.id_category + `" href='#' class="dropdown-item table-btn-delete" style="cursor: pointer">
                                            <div style="width: 24px; display: inline-block"> 
                                                <i class="far fa-trash-alt"></i>
                                            </div>
                                            <span>${GLOBAL_LANG.category_dt_columndefs_target2_title_delete}</span>
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

            const category = {
                search: $("#search").val(),
            }

            let filter = localStorage.getItem("filters");

            filter = filter ? JSON.parse(filter) : {};
            filter.category = category;

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
        $.post('category/delete', $(this).attr("id-delete"), function (e) {
            alert(e);
        });
    });


    $("#datatable-basic").on("click", ".table-btn-delete", function () {
        const categoryId = this.id;

        swal({
            title: GLOBAL_LANG.category_alert_delete_title,
            text: GLOBAL_LANG.category_alert_delete_text,
            type: "warning",
            showCancelButton: true,
            buttonsStyling: false,
            reverseButtons: true,
            confirmButtonClass: "btn btn-success",
            confirmButtonText: GLOBAL_LANG.category_alert_delete_confirmButtonText,
            cancelButtonClass: "btn btn-danger",
            cancelButtonText: GLOBAL_LANG.category_alert_delete_cancelButtonText,
        }).then((result) => {
            if (result.value) {
                // Requisição de exclusão (soft delete)
                $.post(`/category/delete/${categoryId}`, function (response) {
                    if (response.status === true) {
                        swal({
                            title: GLOBAL_LANG.category_alert_delete_two_title,
                            text: GLOBAL_LANG.category_alert_delete_two_text,
                            type: "success",
                            buttonsStyling: false,
                            confirmButtonClass: "btn btn-success"
                        });
                        $("#datatable-basic").DataTable().ajax.reload(null, false);
                    } else {
                        // Caso queira tratar algum retorno de erro
                        swal({
                            title: GLOBAL_LANG.category_alert_delete_three_title,
                            html: `${GLOBAL_LANG.category_alert_delete_three_text_first_part} <span>${name}</span> <span>${total ? GLOBAL_LANG.category_alert_delete_three_text_second_part + " " + total : ""}</span> ${GLOBAL_LANG.category_alert_delete_three_text_third_part}`,
                            type: "warning",
                            buttonsStyling: false,
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
                    "category",
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
    // Esconde alertas anteriores
    document.getElementById("alert__input-name").style.display = "none";
    document.getElementById("alert_multi_selects").style.display = "none";

    const input_name = document.getElementById("name").value.trim();
    const selectedGroups = $('#multiselect').val();
    const input_data = document.getElementById("input-data").value.trim();

    let formValid_name = true;
    let formValid_category = true;
    let formValid_description = true;

    // Validação do campo nome
    if (!emptyText(input_name)) {
        formValid_name = false;
        document.getElementById("alert__input-name").style.display = "block";
        document.getElementById("alert__input-name").innerHTML = GLOBAL_LANG.category_validation_name_required;
    } else if (!min_length(input_name, 3)) {
        formValid_name = false;
        document.getElementById("alert__input-name").style.display = "block";
        document.getElementById("alert__input-name").innerHTML =
            GLOBAL_LANG.category_validation_name_min_length.replace("{param}", 3);
    } else if (!max_length(input_name, 100)) {
        formValid_name = false;
        document.getElementById("alert__input-name").style.display = "block";
        document.getElementById("alert__input-name").innerHTML =
            GLOBAL_LANG.category_validation_name_max_length.replace("{param}", 100);
    }

    // Validação do multiselect
    if (!selectedGroups || selectedGroups.length === 0) {
        formValid_category = false;
        document.getElementById("alert_multi_selects").style.display = "block";
        document.getElementById("alert_multi_selects").innerHTML = GLOBAL_LANG.category_validation_sector_required;
    }

    // Envia o formulário se tudo estiver válido
    if (formValid_name && formValid_category && formValid_description) {
        document.querySelector(".btn-success").disabled = true;
        $("form").unbind('submit').submit();
    }
}

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
        &type=category`, function (response) {
        if (response != "Error") {
            Swal.fire({
                title: GLOBAL_LANG.category_alert_export_two_title,
                text: GLOBAL_LANG.category_alert_export_two_text,
                type: 'success',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: GLOBAL_LANG.category_alert_export_two_confirmButtonText,
            });
        }
    });
}

// Remove containers anteriores
document.querySelectorAll(".msf_multiselect_container").forEach(elm => elm.remove());

select = new MSFmultiSelect(
    document.querySelector('#multiselect'), {
    theme: 'theme1',
    selectAll: true,
    searchBox: true,
    width: "100%",
    height: 47,
    placeholder: GLOBAL_LANG.category_select,
    onChange: function (checked, value, instance) {
        if (select == "") select = value;

        if (checked == false) {
            $(".ignore.add").children().prop('checked', false);
        };
    },
});