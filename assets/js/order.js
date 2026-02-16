function convertToCurrency(value) {
    return value.toLocaleString("pt-BR", {
        style: "currency",
        currency: "BRL",
        minimumFractionDigits: 2
    })
}

function find() {
    $('#datatable-basic').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "order/find",
            type: "POST",
            data: {
                text: $("#search").val(),
                status: $('#verify-select2').val() == "2" ? "" : $('#multiselect2').val(),
                dt_start: $('#dt-start').val(),
                dt_end: $('#dt-end').val(),
                csrf_talkall: Cookies.get("csrf_cookie_talkall"),
            },
        },
        columns: [
            {
                mData: 'creation'
            },
            {
                mData: 'order_id'
            },
            {
                mData: 'seller_jid'
            },
            {
                mData: 'name_status'
            },
        ],
        columnDefs: [

            {
                targets: 3,
                render: function (data, type, full, meta) {
                    return "<span class='badge badge-pill badge-success' style='color: black; background-color:" + full.color + ";'>" + full.name_status + "</span>";
                }
            },
            {
                targets: 4,
                render: function (data, type, full, meta) {
                    return "<a href='#' id='" + full.id_messages_order + "' class='table-action table-action-edit' data-toggle='tooltip' data-original-title='" + GLOBAL_LANG.order_dt_columndefs_target4_title_edit + "'>" +
                        "<i class='fas fa-user-edit'></i></a>" +
                        "<a id='" + full.id_messages_order + "' href='#' class='table-action table-action-delete' data-toggle='tooltip' data-original-title='" + GLOBAL_LANG.order_dt_columndefs_target4_title_delete + "'>" +
                        "<i class='fas fa-trash'></i>" +
                        "</a>";
                }
            },
            {
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
            }
        }

    });


    $('#datatable-items').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "../items/find",
            type: "POST",
            data: {
                "order_id": $("#order_id").val(),
                "csrf_talkall": Cookies.get("csrf_cookie_talkall")
            }
        },
        columns: [
            {
                mData: 'code'
            },
            {
                mData: 'name'
            },
            {
                mData: 'quantity'
            },
            {
                mData: 'price'
            },
        ],
        columnDefs: [
            {
                targets: 2,
                render: function (data, type, full, meta) {
                    return full.quantity + "";
                }
            },
            {
                targets: 3,
                render: function (data, type, full, meta) {
                    return convertToCurrency(parseFloat(full.price) / 1000);
                }
            },
            {
                targets: 4,
                render: function (data, type, full, meta) {
                    return "<a href='#' id='" + full.id_messages_order_product + "' class='table-action table-action-edit' data-toggle='tooltip' data-original-title='" + GLOBAL_LANG.order_dt_columndefs_target4_title_edit + "'>" +
                        "<i class='fas fa-user-edit'></i></a>" +
                        "<a id='" + full.id_messages_order_product + "' href='#' class='table-action table-action-delete' data-toggle='tooltip' data-original-title='" + GLOBAL_LANG.order_dt_columndefs_target4_title_delete + "'>" +
                        "<i class='fas fa-trash'></i>" +
                        "</a>";
                }
            },
            {
                orderable: false,
                targets: [0]
            }
        ],
        pagingType: "numbers",
        pageLength: 10,
        destroy: true,
        fixedHeader: false,
        responsive: false,
        lengthChange: false,
        searching: false,
        paginate: false,
        info: false,
        language: {
            url: `${document.location.origin}/assets/lang/${localStorage.getItem("language")}.json`
        }
    });
}

$(document).ready(function () {

    $("#search").on("keyup", function (event) {
        if (event.which == 13) {
            find();
        }
    });


    if ($("#search").val() !== undefined) {
        find();
    }


    $("#datatable-basic").on("click", ".table-action-edit", function () {
        window.location.href = "order/edit/" + this.id;
    });


    $("#datatable-basic").on("click", ".table-action-delete", function () {
        swal({
            title: GLOBAL_LANG.order_alert_delete_title,
            text: GLOBAL_LANG.order_alert_delete_text,
            type: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            reverseButtons: true,
            confirmButtonClass: "btn btn-success",
            confirmButtonText: GLOBAL_LANG.order_alert_delete_confirmButtonText,
            cancelButtonClass: "btn btn-danger",
            cancelButtonText: GLOBAL_LANG.order_alert_delete_cancelButtonText,
        }).then(t => {
            if (t.value == true) {
                $.post("order/delete/" + this.id, function (data) {
                    t.value && swal({
                        title: GLOBAL_LANG.order_alert_delete_two_title,
                        text: GLOBAL_LANG.order_alert_delete_two_text,
                        type: "success",
                        buttonsStyling: !1,
                        confirmButtonClass: "btn btn-success"
                    });
                    $("#datatable-basic").DataTable().ajax.reload(null, false);
                });
            }
        });
    });


    $("#datatable-items").on("click", ".table-action-delete", function () {
        swal({
            title: GLOBAL_LANG.order_alert_delete_three_title,
            text: GLOBAL_LANG.order_alert_detete_three_text,
            type: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            reverseButtons: true,
            confirmButtonClass: "btn btn-danger",
            confirmButtonText: GLOBAL_LANG.order_alert_delete_three_confirmButtonText,
            cancelButtonClass: "btn btn-secondary",
            cancelButtonText: GLOBAL_LANG.order_alert_delete_three_cancelButtonText,
        }).then(t => {
            if (t.value == true) {
                $.post("../../order/items/delete/" + this.id, function (data) {
                    t.value && swal({
                        title: GLOBAL_LANG.order_alert_delete_four_title,
                        text: GLOBAL_LANG.order_alert_delete_four_text,
                        type: "success",
                        buttonsStyling: !1,
                        confirmButtonClass: "btn btn-success"
                    });
                    $("#datatable-items").DataTable().ajax.reload(null, false);
                });
            }
        });
    });


    $("#modalFilter").one("click", () => modalFilter());
    $("#sendEmailExport").on("click", () => modalExport());


    $("#datatable-items").on("click", ".table-action-edit", function () {
        $.post("../../order/items/get/" + this.id, function (data) {
            $("#input-product-code").val(data.code);
            $("#input-product-name").val(data.name);
            $("#input-product-quantity").val(data.quantity);
            $("#input-product-price").mask('#.##0,00');
            $("#input-product-price").val(data.price);
            $("#modal-edit-products").modal('show');
        });
    });


    $("#input-postal").mask('99999-999');


    $("#input-postal").on("keyup", function (event) {
        if (this.value.length == 9) {
            $.post("https://republicavirtual.com.br/web_cep.php?cep=" + this.value + "&formato=json", function (data) {
                $("#input-address").val(data.logradouro);
                $("#input-city").val(data.cidade);
                $("#input-district").val(data.bairro);
                $("#input-number").focus();
            });
        }
    });


    $("#btn-calculate-google-maps").on("click", function () {
        let cep = $("#input-postal").val();
        let destination = cep;
        let origin = "86015-810";
        $.post("../../order/maps/calculate/" + origin + "/" + destination, function (data) {
            $("#input-distance").val(data.rows[0].elements[0].distance.text);
            $("#input-distance-time").val(data.rows[0].elements[0].duration.text);
        });
    });


    $("#input-total").mask('#.##0,00', {
        reverse: true
    });


    let money = $("#input-subtotal").val();
    let val = money.substr(0, (money.length - 1));


    $("#input-subtotal").attr("value", val);

    $("#input-product-price").mask('#.##0,00', {
        reverse: true
    });


    $("#input-subtotal").mask('#.##0,00', {
        reverse: true
    });


    $('form').submit(function () {
        $('input[name=csrf_token_talkall]').val(Cookies.get("csrf_cookie_talkall"));
    });


    $("#status").on("change", function () {
        find();
    });


    $("#order_id").on("blur", function () {
        find();
    });

});


100, 300


function modalFilter() {

    const msf_multiselect_container = document.querySelectorAll(".msf_multiselect_container");

    for (elm of msf_multiselect_container) elm.remove();

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

    const dt_end = document.getElementById("dt-end");
    const dt_start = document.getElementById("dt-start");

    const check_date = document.getElementById("check-date");
    const btn_search = document.getElementById("btn-search");

    const logger = document.querySelectorAll(".logger");

    check_search.checked = true;
    input_search.style.display = "block";

    check_search.addEventListener("click", () => {
        if (check_search.checked) {
            input_search.style.display = "block";
            input_search.value = "";
        }
        else {
            input_search.style.display = "none";
        }

    });

    check_select2.addEventListener("click", () => {
        if (check_select2.checked) {
            mult_select2.style.display = "block";
            verify_select2.value = "1";
        }
        else {
            mult_select2.style.display = "none";
            verify_select2.value = "2";
        }
    });

    check_date.addEventListener("click", () => {
        if (check_date.checked) {

            dt_start.value = "";
            dt_start.type = "text";
            dt_start.style.display = "block";
            dt_start.placeholder = "Data inicío";

            dt_end.type = "text";
            dt_end.value = "";
            dt_end.disabled = true;
            dt_end.style.display = "block";

        } else {
            dt_start.style.display = "none";
            dt_end.style.display = "none";
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

    });

    dt_start.addEventListener("focus", () => {

        dt_start.type = "date";
        btn_search.disabled = true;
    });

    dt_start.addEventListener("change", () => {

        if (dt_start.value != "") dt_end.disabled = false; else dt_end.disabled = true;

        dt_end.type = "date";
        btn_search.disabled = false;

        let date = new Date();
        let day = String(date.getDate()).padStart(2, '0');
        let month = String(date.getMonth() + 1).padStart(2, '0');
        let year = date.getFullYear();
        current = year + '-' + month + '-' + day;

        dt_end.value = current;
    });

    dt_end.addEventListener("change", () => {
        if (dt_end.value != "") btn_search.disabled = false; else btn_search.disabled = true;
    });

    btn_search.addEventListener("click", () => {

        const contact = document.getElementById("input-search");
        search.value = contact.value;

        find();
        search.value = "";
    });

    for (elm of logger) {
        elm.style.paddingLeft = "15px";
    }

}


function modalExport() {
    let column = $("#datatable-basic").DataTable().order()[0][0];
    let order = $("#datatable-basic").DataTable().order()[0][1];

    switch (column) {
        case 0:
            column = "creation";
            break;
        case 1:
            column = "order_id";
            break;
        case 2:
            column = "seller_jid";
            break;
        case 3:
            column = "name";
            break;

        default:
            break;
    }

    $.get(`/export/xlsx?
        search=${$('#search').val() == "" ? $('#input-search').val() : $('#search').val()}
        &status=${$('#verify-select2').val() == "2" ? "" : $('#multiselect2').val()}
        &dt_start=${$('#dt-start').val()}
        &dt_end=${$('#dt-end').val()}
        &column=${column}
        &order=${order}
        &type=order`, function (response) {

        if (response != "Error") {
            Swal.fire({
                title: GLOBAL_LANG.order_alert_export_title,
                text: GLOBAL_LANG.order_alert_export_text,
                type: 'success',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: GLOBAL_LANG.order_alert_export_two_confirmButtonText
            });
        }
    });
}