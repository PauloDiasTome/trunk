const Filters = JSON.parse(localStorage.getItem("filters")) || {};


function checkFilter() {
    if (Filters) {

        if (!Filters.btn_back) return

        if (Filters.ticket.search) {
            document.getElementById("search").value = Filters.ticket.search;
        }

        if (Filters.ticket.input_search) {
            document.getElementById("input-search").value = Filters.ticket.input_search;
        }

        if (Filters.ticket.type.length !== 0) {
            modalFilter();

            document.getElementById("check-select").click();

            document.querySelectorAll("#mult-select .cust_").forEach((element, index) => {

                if (Filters.ticket.type.includes(element.value))
                    document.querySelectorAll("#mult-select .cust_")[index].click();
            })
        }

        if (Filters.ticket.status.length !== 0) {
            modalFilter();

            document.getElementById("check-select2").click();

            document.querySelectorAll("#mult-select2 .cust_").forEach((element, index) => {

                if (Filters.ticket.status.includes(element.value))
                    document.querySelectorAll("#mult-select2 .cust_")[index].click();
            })
        }

        if (Filters.ticket.dt_start) {
            modalFilter();

            document.getElementById("check-date").click();

            document.getElementById("dt-end").disabled = false;
            document.getElementById("dt-end").type = "date";
            document.getElementById("dt-start").type = "date";

            document.getElementById("dt-start").value = Filters.ticket.dt_start;
            document.getElementById("dt-end").value = Filters.ticket.dt_end;
        }

        Filters.btn_back = null;
        localStorage.setItem("filters", JSON.stringify(Filters));
    }
}

checkFilter();


function find() {
    $('#datatable-basic').DataTable({
        order: [[0, 'desc']],
        processing: true,
        serverSide: true,
        ajax: {
            url: "ticket/find",
            type: "POST",
            data: {
                text: $("#search").val(),
                ticketType: $('#verify-select').val() == '2' ? 'NULL' : ($('#multiselect').val().length == 0 ? 'NULL' : $('#multiselect').val()),
                ticketStatus: $('#verify-select2').val() == '2' ? 'NULL' : ($('#multiselect2').val().length == 0 ? 'NULL' : $('#multiselect2').val()),
                situation: $('#select-situation').val(),
                dt_start: $('#dt-start').val(),
                dt_end: $('#dt-end').val(),
                csrf_talkall: Cookies.get("csrf_cookie_talkall"),
            }
        },
        columns: [
            {
                mData: 'creation'
            },
            {
                mData: 'fantasy_name'
            },
            {
                mData: 'user'
            },
            {
                mData: 'type'
            },
            {
                mData: 'status'
            },
        ],
        columnDefs: [
            {
                targets: 3,
                render: function (data, type, full, meta) {
                    return "<span class='badge badge-pill badge-success' style='color: black; background-color:" + full.type_color + ";'>" + full.type + "</span>";
                }
            },
            {
                targets: 4,
                render: function (data, type, full, meta) {
                    return "<span class='badge badge-pill badge-success' style='color: black; background-color:" + full.status_color + ";'>" + full.status + "</span>";
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
                                        <a id="` + full.id_ticket + `" href='#' class="dropdown-item table-btn-history" style="cursor: pointer">
                                            <div style="width: 24px; display: inline-block">     
                                                <i class="fas fa-history"></i>
                                            </div>
                                            <span>${GLOBAL_LANG.ticket_dt_columndefs_target5_title_history}</span>
                                        </a>
                                        <a id="` + full.id_ticket + `" href='#' class="dropdown-item table-btn-edit" style="cursor: pointer">
                                            <div style="width: 24px; display: inline-block"> 
                                                <i class="far fa-edit"></i>
                                            </div>
                                            <span>${GLOBAL_LANG.ticket_dt_columndefs_target5_title_edit}</span>
                                        </a>
                                        <a id="` + full.id_ticket + `" href='#' class="dropdown-item table-btn-delete" style="cursor: pointer">
                                            <div style="width: 24px; display: inline-block"> 
                                                <i class="far fa-trash-alt"></i>
                                            </div>
                                            <span>${GLOBAL_LANG.ticket_dt_columndefs_target5_title_delete}</span>
                                        </a>
                                    </div>
                                </div>`
                }
            },
            {
                orderable: true,
                targets: [0, 1, 2, 3, 4]
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

            const ticket = {
                search: $("#search").val(),
                input_search: $("#input-search").val(),
                type: $("#multiselect").val(),
                status: $("#multiselect2").val(),
                dt_start: $("#dt-start").val(),
                dt_end: $("#dt-end").val(),
            }

            let filter = localStorage.getItem("filters");

            filter = filter ? JSON.parse(filter) : {};
            filter.ticket = ticket;

            localStorage.setItem("filters", JSON.stringify(filter));
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
        window.location.href = "ticket/edit/" + this.id;
    });


    $("#datatable-basic").on("click", ".table-btn-history", function () {
        window.location.href = "ticket/history/" + this.id;
    });


    $("#datatable-basic").on("click", ".table-btn-delete", function () {
        swal({
            title: GLOBAL_LANG.ticket_alert_delete_title,
            text: GLOBAL_LANG.ticket_alert_delete_text,
            type: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            reverseButtons: true,
            confirmButtonClass: "btn btn-success",
            confirmButtonText: GLOBAL_LANG.ticket_alert_delete_confirmButtonText,
            cancelButtonClass: "btn btn-danger",
            cancelButtonText: GLOBAL_LANG.ticket_alert_delete_cancelButtonText,
        }).then(t => {
            if (t.value == true) {
                $.post("ticket/delete/" + this.id, function (data) {
                    if (data.msg == 'not') {
                        t.value && swal({
                            title: GLOBAL_LANG.ticket_alert_delete_two_title,
                            text: GLOBAL_LANG.ticket_alert_delete_two_text,
                            type: "warning",
                            buttonsStyling: !1,
                            confirmButtonClass: "btn btn-success"
                        });
                    } else {
                        t.value && swal({
                            title: GLOBAL_LANG.ticket_alert_delete_three_title,
                            text: GLOBAL_LANG.ticket_alert_delete_three_text,
                            type: "success",
                            buttonsStyling: !1,
                            confirmButtonClass: "btn btn-primary"
                        });
                        $("#datatable-basic").DataTable().ajax.reload(null, false);
                    }
                });
            }
        });
    });


    $("#input-contact").on("keyup", function () {

        const search = $("#input-contact").val();

        if (search != "" && search.length > 2) {
            fetch(document.location.origin + "/ticket/list/contact/" + search)
                .then((res) => {
                    res.json()
                        .then((res) => listContact(res))
                });
        }

        if (search.length == 0) {
            $(".contact-box").remove();
            $(".input-contact").remove();
        }
    });


    $("body").on("click", ".contact-box .item", function (e) {

        $(".input-contact").remove();
        $(".contact-box").remove();

        const name = $(this).find("span")[0].innerHTML;
        const id = $(this).find("span")[0].attributes.id.nodeValue;

        document.getElementById("input-contact").value = name;

        $("#add-contact").append(`<input type="hidden" class='input-contact' name="input-contact" value='${id}'>`);
    });


    $("#input-comment").on("click change keyup keydown paste cut", function () {

        let character = $("#input-comment").val();
        let amount = document.getElementById("count_character");

        amount.textContent = 1500 - character.length;
        amount.style.color = "red";
        amount.style.fontSize = ".875rem";

        document.getElementById("input-comment").maxLength = 1500;

    });


    $("#dropdown_ticket_company .dropdown-item").each((idx, elm) => {
        if (elm.attributes.class.value == "dropdown-item opt OptSelected") {
            document.getElementById("input_name_company").value = elm.innerHTML;
            document.getElementById("input_ticket_company").value = elm.id;
        }
    });

    $("#dropdown_ticket_type .dropdown-item").each((idx, elm) => {
        if (elm.attributes.class.value == "dropdown-item opt ticket_type_opt OptSelected") {
            document.getElementById("input_type_name").value = elm.innerHTML;
            document.getElementById("input_ticket_type").value = elm.id;
        }
    });

    $("#dropdown_ticket_status .dropdown-item").each((idx, elm) => {
        if (elm.attributes.class.value == "dropdown-item opt OptSelected") {
            document.getElementById("input_status_name").value = elm.innerHTML;
            document.getElementById("input_ticket_status").value = elm.id;
        }
    });


    $("#modalStatus__is_open").on("click", function () {
        $('#is_close').prop('checked', false);
    });


    $("#is_close").on("click", function () {
        $('#modalStatus__is_open').prop('checked', false);
    });

    $("#modalCompany__cnpj").mask("99.999.999/9999-99");

    $("#modalFilter").one("click", () => modalFilter());
    $('#sendEmailExport').on('click', () => modalExport());
    $("form").submit(event => event.preventDefault());
    if (document.querySelector(".btn-success")) {
        document.querySelector(".btn-success").addEventListener("click", submit);
    }

});


function submit() {

    const input_company = formValidation({ field: document.getElementById("input_ticket_company"), text: GLOBAL_LANG.ticket_company, required: true });
    const input_type = formValidation({ field: document.getElementById("input_ticket_type"), text: GLOBAL_LANG.ticket_type, required: true });
    const input_status = formValidation({ field: document.getElementById("input_ticket_status"), text: GLOBAL_LANG.ticket_status, required: true });

    if (input_company && input_type && input_status) {
        document.querySelector(".btn-success").disabled = true;
        $("form").unbind('submit').submit();
    }
}


async function listSubtype(key_id) {

    try {

        const restul = await fetch(document.location.origin + "/ticket/list/subtype/" + key_id);
        const resp = await restul.json();

        return resp;

    } catch (err) {
        console.log(err);
    }
}


function listContact(json) {

    $(".contact-box").remove();

    const box = document.createElement("div");
    box.className = "contact-box";
    box.style.zIndex = "9";
    box.style.maxHeight = "305px";
    box.style.position = "absolute";
    box.style.background = "#fff";
    box.style.overflowY = "scroll";
    box.style.width = "100%";
    box.style.borderRadius = "5px";
    box.style.boxShadow = " 0 4px 4px rgb(0 0 0 / 62%)";

    for (let i = 0; i < json.length; i++) {

        const id = Math.floor(Math.random() * 100000);

        const item = document.createElement("div");
        item.className = "item";
        item.style = "padding: 6px 15px 4px 15px; cursor:pointer";

        const contact = document.createElement("span");
        contact.id = json[i].id_contact;
        contact.style.marginLeft = "12px"
        contact.setAttribute("for", id);

        const check = document.createElement("input");
        check.type = "checkbox";
        check.id = id;
        check.style.cursor = "pointer";

        if (json[i].full_name == "") {
            contact.innerHTML = json[i].key_remote_id;

        } else if (json[i].full_name == null) {
            contact.innerHTML = json[i].key_remote_id;
        } else {
            contact.innerHTML = json[i].full_name;
        }

        item.appendChild(check);
        item.appendChild(contact);
        box.appendChild(item);

        document.getElementById("add-contact").appendChild(box);
    }
}


function modalFilter() {

    if (!$(".msf_multiselect_container").hasClass("msf_multiselect_container")) {
        const msf_multiselect_container = document.querySelectorAll(".msf_multiselect_container");

        for (elm of msf_multiselect_container) elm.remove();

        var select = new MSFmultiSelect(
            document.querySelector('#multiselect'), {
            theme: 'theme1',
            selectAll: true,
            searchBox: true,
            width: "100%",
            height: 47,
            placeholder: GLOBAL_LANG.ticket_filter_type_ticket_placeholder,
            onChange: function (checked, value, instance) {
                if (select == "") select = value;
            },
        });

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
    }

    const search = document.getElementById("search");

    const check_search = document.getElementById("check-search");
    const input_search = document.getElementById("input-search");

    const check_select = document.getElementById("check-select");
    const mult_select = document.getElementById("mult-select");
    const verify_select = document.getElementById("verify-select");

    const check_select2 = document.getElementById("check-select2");
    const mult_select2 = document.getElementById("mult-select2");
    const verify_select2 = document.getElementById("verify-select2");

    const dt_end = document.getElementById("dt-end");
    const dt_start = document.getElementById("dt-start");

    const check_date = document.getElementById("check-date");
    const btn_search = document.getElementById("btn-search");

    const logger = document.querySelectorAll(".logger");
    const alert_filter_period = document.getElementById("alert-filter-period");

    check_search.checked = true;
    input_search.style.display = "block";

    const localDate = new Date();
    const dt_max = localDate.toISOString().split("T")[0];
    const millisecond_per_day = 1000 * 60 * 60 * 24;
    const difDate = new Date(localDate.getTime() - 90 * millisecond_per_day)

    const dt_min = difDate.toISOString().split("T")[0];

    check_search.addEventListener("click", () => {
        if (check_search.checked) {
            input_search.style.display = "block";
        }
        else {
            input_search.value = "";
            input_search.style.display = "none";
        }
    });

    check_select.addEventListener("click", () => {
        if (check_select.checked) {
            mult_select.style.display = "block";
            verify_select.value = "1";
        } else {
            mult_select.style.display = "none";
            verify_select.value = "2";
        }
    });

    check_select2.addEventListener("click", () => {
        if (check_select2.checked) {
            mult_select2.style.display = "block";
            verify_select2.value = "1";
        } else {
            mult_select2.style.display = "none";
            verify_select2.value = "2";
        }
    });

    check_date.addEventListener("click", () => {
        if (check_date.checked) {
            dt_start.value = "";
            dt_start.type = "text";
            dt_start.style.display = "block";
            dt_start.placeholder = GLOBAL_LANG.ticket_modal_filter_dt_start_placeholder_initial_date;

            dt_end.type = "text";
            dt_end.value = "";
            // dt_end.disabled = true;
            dt_end.style.display = "block";

        } else {
            dt_start.style.display = "none";
            dt_end.style.display = "none";
            alert_filter_period.style.display = "none";
        }
    });

    check_date.addEventListener("change", () => {
        if (check_date.checked == false) {

            dt_start.value = "";
            dt_start.type = "text";
            dt_start.placeholder = GLOBAL_LANG.contact_filter_period_placeholder_date_start;

            dt_end.type = "text";
            dt_end.value = "";

            btn_search.disabled = false;
        }

        if (check_date.checked && dt_end.value == '') {
            btn_search.disabled = true;
        }

    });

    dt_start.addEventListener("focus", () => {

        dt_start.type = "date";
        btn_search.disabled = true;
    });

    dt_start.addEventListener("change", () => {
        /*
            const validDt_start = validDate(dt_start)
            if (!validDt_start) dt_end.disabled = false; else dt_end.disabled = true;
            dt_end.type = "date";
            if (!validDt_start) {
                btn_search.disabled = false;
            } else {
                btn_search.disabled = true;
            }
        */

        dt_end.disabled = false;
        dt_end.type = "date";
        btn_search.disabled = false;

        let date = new Date();
        let day = String(date.getDate()).padStart(2, '0');
        let month = String(date.getMonth() + 1).padStart(2, '0');
        let year = date.getFullYear();
        current = year + '-' + month + '-' + day;

        dt_end.value = current;
    });
    /*
        dt_end.addEventListener("change", () => {
    
            const validDt_end = validDate(dt_end)
    
            if (validDt_end) {
                btn_search.disabled = true;
            } else {
                btn_search.disabled = false;
            }
    
        });
    
        const validDate = (date) => {
            if (Date.parse(date.value) < Date.parse(dt_min) || Date.parse(date.value) > Date.parse(dt_max)) {
                alert_filter_period.style.display = "block";
                return true
            } else {
                alert_filter_period.style.display = "none";
            }
        }
    */
    btn_search.addEventListener("click", () => {
        search.value = input_search.value;
        find();
    });

    for (elm of logger) {
        elm.style.paddingLeft = "15px";
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
            column = "creation";
            break;

        case 1:
            column = "full_name";
            break;

        case 2:
            column = "user_name";
            break;

        case 3:
            column = "ticket_type_name";
            break;

        case 4:
            column = "ticket_status_name";
            break;

        default:
            break;
    }

    $.get(`/export/xlsx?
        search=${$('#search').val() == "" ? $('#input-search').val() : $('#search').val()}
        &ticket_type=${$('#verify-select').val() == "2" ? "" : $('#multiselect').val()}
        &ticket_status=${$('#verify-select2').val() == "2" ? "" : $('#multiselect2').val()}
        &column=${column}
        &order=${order}
        &dt_start=${$('#dt-start').val()}
        &dt_end=${$('#dt-end').val()}
        &type=ticket`, function (response) {

        if (response != "Error") {
            Swal.fire({
                title: GLOBAL_LANG.ticket_alert_export_title,
                text: GLOBAL_LANG.ticket_alert_export_text,
                type: 'success',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: GLOBAL_LANG.ticket_alert_export_confirmButtonText
            });
        }
    });
}

function clearFormModal() {

    $("#modalStatus__is_open").prop('checked', true) && $("#is_close").prop('checked', false);

    document.getElementById("modalCompany__corporate_name").value = "";
    document.getElementById("modalCompany__cnpj").value = "";
    document.getElementById("modalCompany__fantasy_name").value = "";
    document.getElementById("modalTicketType__name").value = "";
    document.getElementById("modalTicketType__group").value = 0;
    document.getElementById("modalTicketType__sla").value = 0;
    document.getElementById("modalStatus__name").value = "";
    document.getElementById("modalStatus__color").value = "";

    document.getElementById("alertCompany__corporate_name").style.display = "none";
    document.getElementById("alertCompany__cnpj").style.display = "none";
    document.getElementById("alertCompany__fantasy_name").style.display = "none";
    document.getElementById("alertTicketType__name").style.display = "none";
    document.getElementById("alertTicketType__sla").style.display = "none";
    document.getElementById("alertTicketType__group").style.display = "none";
    document.getElementById("alertStatus__name").style.display = "none";
}


function optSelected(e) {

    if (e.id != undefined) {
        if (e.id != 0) {
            $(e).parent().parent().find(".value-select").val(e.id);
            $(e).parent().parent().find(".dropdown-toggle").val(e.innerHTML);
            $('.opt').removeClass("OptSelected");
            $(e).addClass("OptSelected");
        }
    } else {
        if (this.id != 0) {
            $(this).parent().parent().find(".value-select").val(this.id);
            $(this).parent().parent().find(".dropdown-toggle").val(this.innerHTML);
        }
    }

    let type_class = "";
    if (e.classList != undefined) type_class = e.classList[2]; else type_class = this.classList[2];

    if (type_class == "ticket_type_opt") {

        listSubtype($("#input_ticket_type").val()).then((res) => {

            $(".ticket-subtype").remove();
            $("#input-ticket-subtype").append(`<option class='ticket-subtype' value='0'>${GLOBAL_LANG.ticket_select}</option>`);

            for (let i = 0; i < res.length; i++) {
                $("#input-ticket-subtype").append(`<option class='ticket-subtype' value='${res[i]['id_ticket_type']}'>${res[i]['name']}</option>`);
            }
        });
    }

}


function saveCompany() {

    document.getElementById("alertCompany__corporate_name").style.display = "none";
    document.getElementById("alertCompany__cnpj").style.display = "none";
    document.getElementById("alertCompany__fantasy_name").style.display = "none";

    const corporate_name = document.getElementById("modalCompany__corporate_name").value;
    const cnpj = document.getElementById("modalCompany__cnpj").value;
    const fantasy_name = document.getElementById("modalCompany__fantasy_name").value;

    let formValid_corporate_name = true;
    let formValid_cnpj = true;
    let formValid_fantasy_name = true;

    // campo nome corporativo
    if (!emptyText(corporate_name)) {
        formValid_corporate_name = false;
        document.getElementById("alertCompany__corporate_name").style.display = "block";
        document.getElementById("alertCompany__corporate_name").innerHTML = GLOBAL_LANG.ticket_validation_modal_corporate_name_required;
    }

    if (!min_length(corporate_name, 3)) {
        formValid_corporate_name = false;
        document.getElementById("alertCompany__corporate_name").style.display = "block";
        document.getElementById("alertCompany__corporate_name").innerHTML = GLOBAL_LANG.ticket_validation_modal_corporate_name_min_length.replace("{param}", 3);
    }

    if (!max_length(corporate_name, 100)) {
        formValid_corporate_name = false;
        document.getElementById("alertCompany__corporate_name").style.display = "block";
        document.getElementById("alertCompany__corporate_name").innerHTML = GLOBAL_LANG.ticket_validation_modal_corporate_name_max_length.replace("{param}", 100);
    }

    // campo cnpj
    if (!emptyText(cnpj)) {
        formValid_cnpj = false;
        document.getElementById("alertCompany__cnpj").style.display = "block";
        document.getElementById("alertCompany__cnpj").innerHTML = GLOBAL_LANG.ticket_validation_modal_cnpj_required;
    }

    if (!min_length(cnpj, 14)) {
        formValid_cnpj = false;
        document.getElementById("alertCompany__cnpj").style.display = "block";
        document.getElementById("alertCompany__cnpj").innerHTML = GLOBAL_LANG.ticket_validation_modal_cnpj_length.replace("{param}", 14);
    }

    // campo nome fantasia
    if (!emptyText(fantasy_name)) {
        formValid_fantasy_name = false;
        document.getElementById("alertCompany__fantasy_name").style.display = "block";
        document.getElementById("alertCompany__fantasy_name").innerHTML = GLOBAL_LANG.ticket_validation_modal_fantasy_name_required;
    }

    if (!min_length(fantasy_name, 3)) {
        formValid_fantasy_name = false;
        document.getElementById("alertCompany__fantasy_name").style.display = "block";
        document.getElementById("alertCompany__fantasy_name").innerHTML = GLOBAL_LANG.ticket_validation_modal_fantasy_name_min_length.replace("{param}", 3);
    }

    if (!max_length(fantasy_name, 100)) {
        formValid_fantasy_name = false;
        document.getElementById("alertCompany__fantasy_name").style.display = "block";
        document.getElementById("alertCompany__fantasy_name").innerHTML = GLOBAL_LANG.ticket_validation_modal_fantasy_name_max_length.replace("{param}", 100);
    }

    if (formValid_corporate_name && formValid_cnpj && formValid_fantasy_name) {

        const formData = new FormData();
        formData.append("input-corporate-name", corporate_name);
        formData.append("input-cnpj", cnpj);
        formData.append("input-fantasy-name", fantasy_name);
        formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

        fetch(document.location.origin + '/ticket/save/company', {
            method: 'POST',
            body: formData
        }).then((response) => response.json())
            .then((data) => {

                $("#dropdown_ticket_company .opt").remove();

                for (let i = 0; i < data.length; i++) {

                    const a = document.createElement("a");
                    a.className = "dropdown-item opt";
                    a.id = data[i].id_company;
                    a.innerHTML = data[i].fantasy_name;
                    a.addEventListener("click", optSelected);

                    if (data[i].fantasy_name == fantasy_name) {
                        document.getElementById("input_ticket_company").value = data[i].id_company;
                        document.getElementById("input_name_company").value = data[i].fantasy_name;
                    }

                    document.getElementById("dropdown_ticket_company").append(a);
                    $('#modal-company').modal('hide');
                }

                $("[name='csrf_token_talkall']").val(Cookies.get("csrf_cookie_talkall"));

            }).catch((error) => {
                console.error('Error:', error);
            });
    }
}


function saveTicketType() {

    document.getElementById("alertTicketType__name").style.display = "none";
    document.getElementById("alertTicketType__sla").style.display = "none";
    document.getElementById("alertTicketType__group").style.display = "none";

    const ticket_type_name = document.getElementById("modalTicketType__name").value;
    const ticket_type_group = document.getElementById("modalTicketType__group").value;
    const ticket_type_sla = document.getElementById("modalTicketType__sla").value;

    let formValid_ticket_type_name = true;
    let formValid_ticket_type_group = true;
    let formValid_ticket_type_sla = true;


    // campo nome
    if (!emptyText(ticket_type_name)) {
        formValid_ticket_type_name = false;
        document.getElementById("alertTicketType__name").style.display = "block";
        document.getElementById("alertTicketType__name").innerHTML = GLOBAL_LANG.ticket_validation_modal_ticket_type_name_required;
    }

    if (!min_length(ticket_type_name, 3)) {
        formValid_ticket_type_name = false;
        document.getElementById("alertTicketType__name").style.display = "block";
        document.getElementById("alertTicketType__name").innerHTML = GLOBAL_LANG.ticket_validation_modal_ticket_type_name_min_length.replace("{param}", 3);
    }

    if (!max_length(ticket_type_name, 100)) {
        formValid_ticket_type_name = false;
        document.getElementById("alertTicketType__name").style.display = "block";
        document.getElementById("alertTicketType__name").innerHTML = GLOBAL_LANG.ticket_validation_modal_ticket_type_name_max_length.replace("{param}", 100);
    }

    // campo setor
    if (ticket_type_group == '0') {
        formValid_ticket_type_group = false;
        document.getElementById("alertTicketType__group").style.display = "block";
        document.getElementById("alertTicketType__group").innerHTML = GLOBAL_LANG.ticket_validation_modal_ticket_type_group_required;
    }

    // campo SLA do ticket
    if (ticket_type_sla == '0') {
        formValid_ticket_type_sla = false;
        document.getElementById("alertTicketType__sla").style.display = "block";
        document.getElementById("alertTicketType__sla").innerHTML = GLOBAL_LANG.ticket_validation_modal_ticket_type_sla_required;
    }

    if (formValid_ticket_type_name && formValid_ticket_type_sla && formValid_ticket_type_group) {

        const formData = new FormData();
        formData.append("input-name", ticket_type_name);
        formData.append("user_group", ticket_type_group);
        formData.append("ticket_sla", ticket_type_sla);
        formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

        fetch(document.location.origin + '/ticket/save/type', {
            method: 'POST',
            body: formData
        }).then((response) => response.json())
            .then((data) => {

                $("#dropdown_ticket_type .opt").remove();

                for (let i = 0; i < data.length; i++) {

                    const a = document.createElement("a");
                    a.className = "dropdown-item opt ticket_type_opt";
                    a.id = data[i].id_ticket_type;
                    a.innerHTML = data[i].name;
                    a.addEventListener("click", optSelected);

                    if (data[i].name == ticket_type_name) {
                        document.getElementById("input_ticket_type").value = data[i].id_ticket_type;
                        document.getElementById("input_type_name").value = data[i].name;
                    }

                    document.getElementById("dropdown_ticket_type").append(a);
                    $('#modal-ticket-type').modal('hide');
                }

                $("[name='csrf_token_talkall']").val(Cookies.get("csrf_cookie_talkall"));

            }).catch((error) => {
                console.error('Error:', error);
            });
    }

    $(".ticket-subtype").remove();
    $("#input-ticket-subtype").append(`<option class='ticket-subtype' value='0'>${GLOBAL_LANG.ticket_select}</option>`);
}

function saveTicketStatus() {

    document.getElementById("alertStatus__name").style.display = "none";

    const status_name = document.getElementById("modalStatus__name").value;
    const status_color = document.getElementById("modalStatus__color").value;
    let is_open = 'false';


    let formValid_status_name = true;
    let formValid_status_color = true;

    if ($('#modalStatus__is_open').prop('checked')) {
        is_open = 'true';
    }

    // campo nome
    if (!emptyText(status_name)) {
        formValid_status_name = false;
        document.getElementById("alertStatus__name").style.display = "block";
        document.getElementById("alertStatus__name").innerHTML = GLOBAL_LANG.ticket_validation_modal_status_name_required;
    }

    if (!min_length(status_name, 3)) {
        formValid_status_name = false;
        document.getElementById("alertStatus__name").style.display = "block";
        document.getElementById("alertStatus__name").innerHTML = GLOBAL_LANG.ticket_validation_modal_status_name_min_length.replace("{param}", 3);
    }

    if (!max_length(status_name, 100)) {
        formValid_status_name = false;
        document.getElementById("alertStatus__name").style.display = "block";
        document.getElementById("alertStatus__name").innerHTML = GLOBAL_LANG.ticket_validation_modal_status_name_max_length.replace("{param}", 100);
    }

    if (formValid_status_name && formValid_status_color) {

        const formData = new FormData();
        formData.append("input-name", status_name);
        formData.append("input-color", status_color);
        formData.append("is_open", is_open);
        formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

        fetch(document.location.origin + '/ticket/save/status', {
            method: 'POST',
            body: formData
        }).then((response) => response.json())
            .then((data) => {

                $("#dropdown_ticket_status .opt").remove();

                for (let i = 0; i < data.length; i++) {

                    const a = document.createElement("a");
                    a.className = "dropdown-item opt";
                    a.id = data[i].id_ticket_status;
                    a.innerHTML = data[i].name;
                    a.addEventListener("click", optSelected);

                    if (data[i].name == status_name) {
                        document.getElementById("input_ticket_status").value = data[i].id_ticket_status;
                        document.getElementById("input_status_name").value = data[i].name;
                    }

                    document.getElementById("dropdown_ticket_status").append(a);
                    $('#modal-ticket-status').modal('hide');
                }

                $("[name='csrf_token_talkall']").val(Cookies.get("csrf_cookie_talkall"));

            }).catch((error) => {
                console.error('Error:', error);
            });
    }
}