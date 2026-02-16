"use strict";

function find(route) {
    $('#datatable-basic').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: route,
            type: "POST",
            data: {
                text: $("#search").val(),
                community: $("#select-community").val(),
                dt_start: $('#dt-start').val(),
                dt_end: $('#dt-end').val(),
                csrf_talkall: Cookies.get("csrf_cookie_talkall"),
            }
        },
        columns: [
            {
                mData: 'key_remote_id'
            },
            {
                mData: 'key_remote_id'
            },
            {
                mData: 'name'
            },
            {
                mData: 'accession_date'
            },
        ],
        columnDefs: [
            {
                targets: 0,
                render: function (data, type, full, meta) {

                    return "<div class='kt-user-card-v2'>" + "<div class='kt-user-card-v2__pic'>" +
                        "<img src='" + full.profile + "' class='avatar rounded-circle mr-3 m-img-rounded kt-marginless' alt='photo'>" +
                        "</div>" +
                        "<div class='kt-user-card-v2__details'>" +
                        "<b class='kt-user-card-v2__name'>" + full.key_remote_id + "</b>" +
                        "</div>" +
                        "</div>";
                }
            },
            {
                targets: 3,
                render: function (data, type, full, meta) {
                    return full.base_time + " Dias";
                }
            },
            {
                orderable: true,
                targets: 4,
                render: function (data, type, full, meta) {
                    return full.accession_date + "h"
                }
            },
        ],
        pagingType: "numbers",
        pageLength: 10,
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
            } else {
                $("#modalExport").attr('disabled', false);
            }
        }

    });
}

$(document).ready(function () {

    $('#search').on('keyup', (e) => {
        if (e.which == 13) {
            document.getElementById("input-search").value = e.target.value;
            find(getCommunity().route);
        }
    });

    $("#select-community").val(getCommunity().community);

    find(getCommunity().route);
    countParticipant();

    $("#select-community").on("change", function () {
        countParticipant();
        find(getCommunity().route);
    });

    $("#modalFilter").one("click", () => modalFilter());
    $("#sendEmailExport").on("click", () => modalExport());

});

function countParticipant() {

    const community = $("#select-community").val();

    $("#select-community option").each((idx, elm) => {
        if (elm.value == community) {
            $(".management-rectangle .text .participant").text(`${elm.dataset.countParticipant} ${GLOBAL_LANG.community_participant}`);
        }
    });
}

function getCommunity() {

    const query = location.search.slice(1);
    const partes = query.split('&');
    const data = {};

    partes.forEach(function (parte) {
        let key_value = parte.split('=');
        let key = key_value[0];
        let value = key_value[1];
        data[key] = value;
    });

    if (data.id_community != undefined) {
        return {
            community: data.id_community,
            route: "find",
            filter: true
        }

    } else {
        return {
            community: "",
            route: "participants/find",
            filter: false
        }
    }
}

function modalFilter() {

    const search = document.getElementById("search");

    const check_search = document.getElementById("check-search");
    const input_search = document.getElementById("input-search");

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
        }
        else {
            input_search.value = "";
            input_search.style.display = "none";
        }

    });

    check_date.addEventListener("click", () => {
        if (check_date.checked) {

            dt_start.value = "";
            dt_start.type = "text";
            dt_start.style.display = "block";
            dt_start.placeholder = GLOBAL_LANG.community_participant_filter_period_placeholder_date_start;

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
            dt_start.placeholder = GLOBAL_LANG.community_participant_filter_period_placeholder_date_start;

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
        search.value = input_search.value;
        find(getCommunity().route);
    });

    for (let elm of logger) {
        elm.style.paddingLeft = "15px";
    }

}

function modalExport() {
    let column = $("#datatable-basic").DataTable().order()[0][0];
    let order = $("#datatable-basic").DataTable().order()[0][1];

    switch (column) {
        case 0:
        case 1:
            column = "key_remote_id";
            break;
        case 2:
            column = "name";
            break;
        case 3:
            column = "base_time";
            break;

        default:
            column = "key_remote_id";
            break;
    }

    $.get(`/export/xlsx?
        search=${$('#search').val() == "" ? $('#input-search').val() : $('#search').val()}
        &community=${$("#select-community").val()}
        &column=${column}
        &order=${order}
        &dt_start=${$('#dt-start').val()}
        &dt_end=${$('#dt-end').val()}
        &type=community_participant`, function (response) {

        if (response != "Error") {
            Swal.fire({
                title: GLOBAL_LANG.community_participant_alert_export_title,
                text: GLOBAL_LANG.community_participant_alert_export_text,
                type: 'success',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: GLOBAL_LANG.community_participant_alert_export_confirmButtonText
            });

        }

    });
}