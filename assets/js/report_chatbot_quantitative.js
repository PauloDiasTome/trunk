function find() {
    $('#datatable-basic').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "quantitative/find",
            type: "POST",
            data: {
                text: $("#search").val(),
                dt_start: $('#dt-start').val(),
                dt_end: $('#dt-end').val(),
                csrf_talkall: Cookies.get("csrf_cookie_talkall"),
            }
        },
        columns: [
            { mData: 'options' },
            { mData: 'total' },
            { mData: 'adhered' },
            { mData: 'adherence_rate' },
        ],
        columnDefs: [
            {
                targets: 0,
                render: function (data, type, full) {
                    return full.options === 'Main_menu' ? 'Menu Principal' : full.options;
                }
            },
            {
                targets: 3,
                render: function (data, type, full) {
                    return full.adherence_rate + '%';
                }
            },
        ],
        order: [[0, 'asc']],
        pagingType: "numbers",
        pageLength: 10,
        destroy: true,
        fixedHeader: true,
        responsive: true,
        lengthChange: true,
        searching: true,
        paginate: true,
        info: true,
        language: {
            url: `${document.location.origin}/assets/lang/${localStorage.getItem("language")}.json`
        },
        drawCallback: function (settings) {
            $("#modalExport").attr('disabled', settings.json.recordsTotal == 0);
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
    find();

    $(".chat").on("scroll", async function () {

        const dt_start = document.getElementById("dt-start-chat").value;

        // ? se não existir scroll 
        if (this.scrollTop == 0 && this.clientHeight == this.scrollHeight) return;

        if (this.scrollTop == 0) {
            // ? scrool top

            if (dt_start === "") {

                const creation = $(".chat").find(".item").first()[0].attributes[1].nodeValue;
                const tokenMessage = $(".chat").find(".item")[0].attributes[2].nodeValue.length === 10 ? $(".chat").find(".item")[1].attributes[2].nodeValue : $(".chat").find(".item")[0].attributes[2].nodeValue;

                if (checkScroll && endMessages) {
                    addLoad("top");
                    let data = await getMessages(id_chat, creation, false);

                    checkScroll = true;
                    if (data.length <= 3) endMessages = false;

                    if (data.length <= 1) {
                        load = false;
                    } else {
                        for (let i = 0; i < data.length; i++) {
                            if (data[i]['token'] == tokenMessage) {
                                indexLastMessage = data.indexOf(data[i]);
                                data = data.filter((messages, indexMessages) => indexMessages > indexLastMessage);
                            }
                        }

                        for (let i = data.length - 1; i >= 0; i--) {
                            message(data[i]);
                        }
                    }

                    $(".chat").find('.item').sort(function (a, b) {
                        return $(a).attr('data-index') - $(b).attr('data-index');
                    }).appendTo(".chat");

                    if (load) {
                        $("#" + tokenMessage)[0].scrollIntoView();
                    }

                    $("#load_container_svg").remove();
                }
            }
        }


        let scrollSize = $(this).scrollTop() + $(this).innerHeight();

        if (Math.ceil(scrollSize) >= this.scrollHeight) {
            // ? scroll bottom

            if (dt_start !== "") {

                const creation = $(".chat").find(".item").last()[0].attributes[1].nodeValue;
                const tokenMessage = $(".chat").find(".item").last()[0].attributes[2].nodeValue;

                if (checkScroll && endMessages) {
                    addLoad("bottom");
                    let data = await getMessages(id_chat, creation, true);

                    checkScroll = true;
                    if (data.length <= 3) endMessages = false;

                    if (data.length <= 1) {
                        load = false;
                    } else {
                        for (let i = 0; i < data.length; i++) {
                            if (data[i]['token'] == tokenMessage) {
                                indexLastMessage = data.indexOf(data[i]);
                                data = data.filter((messages, indexMessages) => indexMessages > indexLastMessage);
                            }
                        }

                        for (let i = 0; i < data.length; i++) {
                            message(data[i]);
                        }
                    }

                    if (load) {
                        $("#" + tokenMessage)[0].scrollIntoView();
                    }

                    $("#load_container_svg").remove();
                }
            }
        }
    });


    $("#clear-date-chat").on("click", async function () {
        load = true;

        document.getElementById("date-chat").innerHTML = "";
        document.getElementById("clear-date-chat").style.display = "none";
        document.getElementById("add-date-chat").style.display = "inline-block";

        document.getElementById("dt-start-chat").value = "";
        document.getElementById("dt-end-chat").value = "";

        document.querySelectorAll(".chat .item").forEach(elm => elm.remove());

        addLoad("center");
        const data = await getMessages(id_chat, 0, false);
        openChat(data);
    });


    $("#datatable-basic").on("click", ".exportTalkClass", function () {
        meuId = "";
        meuId = this.id;
        $("#modal-icon-export-talk").modal();
    });


    $("#modalFilter").one("click", () => modalFilter());
    $('#sendEmailExport').on('click', () => modalExport());

    $("#add-date-chat").on("click", () => modalSettings = true);
    $("#modal-content-chat").on("click", function () { $(".modal-settings-chat").remove(), modalSettings = true });


    $("body").on("click", ".documentMessage", function () {
        const link = document.createElement("a");
        link.href = this.attributes[1].nodeValue;
        link.click();
    });

    $("#modal-chat").on("click", ".UrlStoryMention", function () {
        if ($(this).attr("data-url") != "") {
            window.open($(this).attr("data-url"));
        }
    });

    $("#modal-chat").on("click", ".urlImageMessage", function () {
        if ($(this).attr("data-url") != "") {
            window.open($(this).attr("data-url"));
        }
    });

});

function modalFilter() {

    const msf_multiselect_container = document.querySelectorAll(".msf_multiselect_container");
    for (const elm of msf_multiselect_container) elm.remove();

    document.querySelectorAll('.msf_multiselect li').forEach((options) => {
        options.style.fontSize = '.875rem';
        options.style.display = 'flex';
        options.style.alignItems = 'center';
        options.style.color = '#8898aa';
    });

    const search = document.getElementById("search");
    const check_search = document.getElementById("check-search");
    const input_search = document.getElementById("input-search");

    const dt_end = document.getElementById("dt-end");
    const dt_start = document.getElementById("dt-start");

    const check_date = document.getElementById("check-date");
    const btn_search = document.getElementById("btn-search");

    const logger = document.querySelectorAll(".logger");
    const alert_filter_period = document.getElementById("alert-filter-period");

    if (check_search) check_search.checked = true;
    if (input_search) input_search.style.display = "block";

    const localDate = new Date();
    const dt_max = localDate.toISOString().split("T")[0];
    const millisecond_per_day = 1000 * 60 * 60 * 24;
    const difDate = new Date(localDate.getTime() - 90 * millisecond_per_day);
    const dt_min = difDate.toISOString().split("T")[0];

    // Toggle input_search ao clicar no checkbox de busca
    if (check_search && input_search) {
        check_search.addEventListener("click", () => {
            if (check_search.checked) {
                input_search.style.display = "block";
            } else {
                input_search.value = "";
                input_search.style.display = "none";
            }
        });
    }

    if (check_date && dt_start && dt_end && alert_filter_period) {
        check_date.addEventListener("click", () => {
            if (check_date.checked) {
                dt_start.value = "";
                dt_start.type = "text";
                dt_start.style.display = "block";
                dt_start.placeholder = GLOBAL_LANG.report_chatbot_service_quantitative_filter_period_placeholder_date_start;

                dt_end.type = "text";
                dt_end.value = "";
                dt_end.style.display = "block";
            } else {
                dt_start.style.display = "none";
                dt_end.style.display = "none";
                alert_filter_period.style.display = "none";
            }
        });

        check_date.addEventListener("change", () => {
            if (!check_date.checked) {
                dt_start.value = "";
                dt_start.type = "text";
                dt_start.placeholder = GLOBAL_LANG.report_chatbot_service_quantitative_filter_period_placeholder_date_start;

                dt_end.type = "text";
                dt_end.value = "";

                if (btn_search) btn_search.disabled = false;
            }
            if (check_date.checked && dt_end.value === '') {
                if (btn_search) btn_search.disabled = true;
            }
        });

        dt_start.addEventListener("focus", () => {
            dt_start.type = "date";
            if (btn_search) btn_search.disabled = true;
        });

        dt_start.addEventListener("change", () => {
            dt_end.disabled = false;
            dt_end.type = "date";
            if (btn_search) btn_search.disabled = false;

            let date = new Date();
            let day = String(date.getDate()).padStart(2, '0');
            let month = String(date.getMonth() + 1).padStart(2, '0');
            let year = date.getFullYear();
            let current = year + '-' + month + '-' + day;

            dt_end.value = current;
        });
    }

    if (btn_search && search && input_search) {
        btn_search.onclick = () => {
            search.value = input_search.value;
            find();
        };
    }

    for (const elm of logger) {
        elm.style.paddingLeft = "15px";
    }
}

function modalExport() {
    let columnIndex = $("#datatable-basic").DataTable().order()[0][0];
    let orderDir = $("#datatable-basic").DataTable().order()[0][1];

    let column = "options";
    switch (columnIndex) {
        case 0:
            column = "options";
            break;
        case 1:
            column = "total";
            break;
        case 2:
            column = "adhered";
            break;
        case 3:
            column = "adherence_rate";
            break;
        default:
            column = "options";
    }

    $.get(`/export/xlsx?` +
        `search=${$('#search').val() == "" ? $('#input-search').val() : $('#search').val()}` +
        `&column=${column}` +
        `&order=${orderDir}` +
        `&dt_start=${$('#dt-start').val()}` +
        `&dt_end=${$('#dt-end').val()}` +
        `&type=reportChatbotQuantitative`, function (response) {
            if (response != "Error") {
                Swal.fire({
                    title: GLOBAL_LANG.report_chatbot_alert_export_title,
                    text: GLOBAL_LANG.report_chatbot_alert_export_text,
                    type: 'success',
                    reverseButtons: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: GLOBAL_LANG.report_chatbot_alert_export_confirmButtonText
                });
            }
        });
}