const Filters = JSON.parse(localStorage.getItem("filters")) || null;


function checkFilter() {
    if (Filters) {

        if (!Filters.btn_back) return

        if (Filters.report_send.search) {
            document.getElementById("search").value = Filters.report_send.search;
        }

        if (Filters.report_send.input_search) {
            document.getElementById("input-search").value = Filters.report_send.input_search;
        }

        if (Filters.report_send.channel.length !== 0) {
            modalFilter();

            document.getElementById("check-select2").click();

            document.querySelectorAll("#mult-select2 .cust_").forEach((element, index) => {

                if (Filters.report_send.channel.includes(element.value))
                    document.querySelectorAll("#mult-select2 .cust_")[index].click();
            })
        }

        if (Filters.report_send.type.length !== 0) {
            modalFilter();

            document.getElementById("check-select").click();

            document.querySelectorAll("#mult-select .cust_").forEach((element, index) => {

                if (Filters.report_send.type.includes(element.value))
                    document.querySelectorAll("#mult-select .cust_")[index].click();
            })
        }

        if (Filters.report_send.dt_start) {
            modalFilter();

            document.getElementById("check-date").click();

            document.getElementById("dt-end").disabled = false;
            document.getElementById("dt-end").type = "date";
            document.getElementById("dt-start").type = "date";

            document.getElementById("dt-start").value = Filters.report_send.dt_start;
            document.getElementById("dt-end").value = Filters.report_send.dt_end;
        }

        Filters.btn_back = null;
        localStorage.setItem("filters", JSON.stringify(Filters));
    }
}

checkFilter();


function find() {
    $('#datatable-basic').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "send/find",
            "type": "POST",
            data: {
                text: $("#search").val(),
                channel: $('#verify-select2').val() == "2" ? "" : $('#multiselect2').val(),
                campaign_type: $('#verify-select').val() == "2" ? "" : $('#multiselect').val(),
                dt_start: $('#dt-start').val(),
                dt_end: $('#dt-end').val(),
                csrf_talkall: Cookies.get("csrf_cookie_talkall"),
            },
        },
        "columns": [
            {
                mData: 'thumb_image'
            },
            {
                mData: 'title'
            },
            {
                mData: 'campaign_type'
            },
            {
                mData: 'message_send'
            },
            {
                mData: 'message_receipt'
            },
            {
                mData: 'message_read'
            },
            {
                mData: 'message_reactions'
            },
            {
                mData: 'message_valid_key'
            },
        ],
        "columnDefs": [
            {
                targets: 0,
                className: "thumb",
                orderable: false,
                contentPadding: "0px",
                render: function (data, type, full) {

                    if (full.media_type == 3) {
                        return "<div class='box-inner-datatable'><img src='" + full.media_url + "'></div>";

                    } else if (full.media_type == 1 || full.media_type == 27) {
                        return "<div class='box-inner-datatable'><img src='" + document.location.origin + "/assets/img/panel/big-texto.png' alt='image' width='44px' style='padding: 10px;'></div>";

                    } else if (full.thumb_image == null && full.media_url != null && full.media_type == 4) {
                        let thumb = document.location.origin + "/assets/img/panel/pdf_icon.png";
                        return "<div class='box-inner-datatable'><img src='" + thumb + "' style='padding: 2px;'></div>";

                    } else if (full.thumb_image == null && full.media_url != null && full.media_type == 5) {
                        let thumb = document.location.origin + "/assets/img/panel/video_player.png";
                        return "<div class='box-inner-datatable'><img src='" + thumb + "' style='padding: 10px;'></div>";

                    } else {
                        return "<div class='box-inner-datatable'><img src='" + 'data:image/jpeg;base64,' + full.thumb_image + "' alt='' width='' height=''></div>";
                    }
                }
            },
            {
                contentPadding: "0px",
                targets: 1,
                render: function (data, type, full) {
                    return `<a id="${full.token}" class="table-action-view" style="cursor: pointer">
                                <b>${full.title}</b>
                            </a>
                            <br><span>${full.schedule}</span>`;
                }
            },
            {
                targets: 2,
                render: function (data, type, full) {
                    let info = "";

                    if (full.is_wa_status == 1)
                        info = `<b>${GLOBAL_LANG.report_send_campaign_type_status}</b><br>`;

                    if (full.is_wa_broadcast == 1)
                        info = `<b>${GLOBAL_LANG.report_send_campaign_type_broadcast}</b><br>`;

                    if (full.is_waba_broadcast == 1)
                        info = `<b>${GLOBAL_LANG.report_send_campaign_type_api}</b><br>`;

                    if (full.is_wa_community == 1)
                        info = `<b>${GLOBAL_LANG.report_send_campaign_type_community}</b><br>`;

                    if (full.is_wa_channel == 1)
                        info = `<b>${GLOBAL_LANG.report_send_campaign_type_channel}</b><br>`;

                    return info += `<span>${full.name_channel}</span>`
                }
            },
            {
                targets: 3,
                render: function (data, type, full) {
                    if (full.is_wa_channel == 2) {
                        return '<b>' + full.message_send + '</b><br>' + full.porcent_send;
                    } else {
                        return full.porcent_send;
                    }
                }
            },
            {
                targets: 4,
                render: function (data, type, full) {
                    if (full.is_wa_channel == 2) {
                        return '<b>' + full.message_receipt + '</b><br>' + full.porcent_receipt;
                    } else {
                        return full.porcent_receipt;
                    }
                }
            },
            {
                targets: 5,
                render: function (data, type, full) {
                    return '<b>' + full.message_read + '</b><br>' + full.porcent_read;
                }
            },
            {
                targets: 6,
                render: function (data, type, full) {
                    return '<b>' + full.message_reactions + '</b><br>' + full.percent_reactions;
                },
                visible: false,
            },
            {
                targets: 7,
                render: function (data, type, full) {
                    return '<b>' + full.message_valid_key + '</b><br>' + full.percent_valid_key;
                },
                visible: false,
            },
            {
                targets: 8,
                render: function (data, type, full, meta) {

                    var res = ""

                    if (full.type == 2) {
                        res += `<div class="dropleft">
                                        <button class="btn btn-link mb-0 btn-dropleft" type="button" id="multiDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #525f7f">
                                            <i class="fa fa-ellipsis-v"></i>
                                            <div class="ripple-container"></div>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="multiDropdownMenu">                 
                                            <a id="${full.token}" class="dropdown-item table-action-view action" style="cursor: pointer"> 
                                                <div style="width: 24px; display: inline-block"> 
                                                    <i class='fa fa-eye'></i> 
                                                </div>
                                                <span>${GLOBAL_LANG.report_send_column_action_view}</span>
                                            </a>
                                        </div>
                                    </div>`
                    }

                    return res;
                }
            },
            {
                orderable: true,
                targets: [0, 1, 2, 3, 4, 5]
            }
        ],

        order: [[0, 'desc']],
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
            $(".thead-light").find("th")[0].attributes.class.value = "";
            if (settings.json.recordsTotal == 0) {
                $("#modalExport").attr('disabled', true);
            } else {
                $("#modalExport").attr('disabled', false);
            }

            const report_send = {
                search: $("#search").val(),
                input_search: $("#input-search").val(),
                channel: $("#multiselect2").val(),
                type: $("#multiselect").val(),
                dt_start: $("#dt-start").val(),
                dt_end: $("#dt-end").val(),
            }

            let filter = localStorage.getItem("filters");

            filter = filter ? JSON.parse(filter) : {};
            filter.report_send = report_send;

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
    find();

    $('#datatable-basic tbody').on('mouseover', 'td', function (e) {

        let exist_class, thumb, src, preview;

        exist_class = this.attributes.class;
        preview = this;

        if (exist_class != undefined) {

            thumb = this.attributes.class.nodeValue;

            if (thumb.trim() == "thumb sorting_1") {

                src = this.firstChild.firstChild.attributes.src.nodeValue;

                let box = document.createElement("div");
                box.className = "preview-thumb";

                let img = document.createElement("img");
                img.src = src;

                $(".preview-thumb").remove();

                box.appendChild(img);
                preview.prepend(box);


                box.animate([
                    { opacity: '0' },
                    { opacity: '1' }
                ], {
                    duration: 500,
                });
            }
        }

    });


    $('#datatable-basic tbody').on('mouseout', 'td', function (e) {

        let exist_class, thumb, src, preview;

        exist_class = this.attributes.class;
        preview = this;

        if (exist_class != undefined) {

            thumb = this.attributes.class.nodeValue;

            if (thumb.trim() == "thumb sorting_1") {

                $(".preview-thumb").remove();
            }
        }

    });


    $('#tex_area').each(function () {

        if ($("#thumb_image").length !== 0) {
            if (this.scrollHeight <= 175) {
                this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
            } else {
                this.setAttribute('style', 'height: 175px;');
            }
        } else {
            if (this.scrollHeight <= 450) {
                this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
            } else if ($('#videoPreview').attr('width') >= 190) {
                this.setAttribute('style', 'height: 120px;');
            } else {
                this.setAttribute('style', 'height: 450px;');
            }
        }

    }).on('input', function () {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    $('.tex-area-template').each(function () {

        if ($("#thumb_image").length !== 0) {
            if (this.scrollHeight <= 175) {
                this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;');
            } else {
                this.setAttribute('style', 'height: 175px;');
            }
        } else {
            if (this.scrollHeight < 50) {
                if (this.id == 'tex_area_view_templateHeader') {
                    this.setAttribute('style', 'width: 274px; max-height: 50px; margin-left: -5px; font-weight: bolder; height:' + (this.scrollHeight) + 'px;');
                } else {
                    this.setAttribute('style', 'max-width: 274px; max-height: 50px; color: grey; height:' + (this.scrollHeight) + 'px;');
                }

            } else {
                this.setAttribute('style', 'max-width: 274px; max-height: 220px; color: black; height:' + (this.value.trim().length + 100) + 'px;');
            }
        }

    }).on('input', function () {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });


    $('#sendEmailExport').on('click', () => modalExport());
    $("#modalFilter").one("click", () => modalFilter());

    $("#datatable-basic").on("click", ".table-action-view", function () {
        viewBroadcast(this);
    });

    $('#timeline-content-partial').on("mouseenter", () => {
        $("#tooltip-content-partial").tooltip('show')
    })
    $('#timeline-content-partial').on("mouseleave", () => {
        $("#tooltip-content-partial").tooltip('hide')
    })
    $('#timeline-content-exceed-period').on("mouseenter", () => {
        $("#tooltip-content-exceed-period").tooltip('show')
    })
    $('#timeline-content-exceed-period').on("mouseleave", () => {
        $("#tooltip-content-exceed-period").tooltip('hide')
    })

    const view = document.getElementById("view");

    if (view != null) {

        if (document.getElementsByClassName("box-inner-pdf")[0] != undefined) {
            const box_inner_pdf = document.getElementsByClassName("box-inner-pdf")[0];
            const media_type = box_inner_pdf.getAttribute("data-media-type");
            const media_url = box_inner_pdf.getAttribute("data-media-url");

            if (media_type === "4") {

                const pdfjsLib = window['pdfjs-dist/build/pdf'];
                pdfjsLib.GlobalWorkerOptions.workerSrc = document.location.origin + "/assets/lib/build_pdf/pdf_build_pdf.worker.js";

                const loadingTask = pdfjsLib.getDocument(media_url);
                loadingTask.promise.then(function (pdf) {

                    const pageNumber = 1;
                    pdf.getPage(pageNumber).then(function (page) {

                        let desiredWidth = 240;
                        let viewport = page.getViewport({ scale: 1, });
                        let scale = desiredWidth / viewport.width;
                        let scaledViewport = page.getViewport({ scale: scale, });

                        let canvas = document.createElement("canvas");
                        canvas.id = "pdf-canva";

                        let context = canvas.getContext('2d');
                        canvas.height = 180;
                        canvas.width = 274;

                        const renderContext = {
                            canvasContext: context,
                            viewport: scaledViewport
                        };

                        const renderTask = page.render(renderContext);
                        renderTask.promise.then(function () {
                            document.querySelector("#thumb_image").remove();

                            let pdf_link = document.createElement("a");
                            pdf_link.href = media_url;
                            pdf_link.setAttribute("target", "_blank");
                            pdf_link.appendChild(canvas);

                            box_inner_pdf.appendChild(pdf_link);
                        });
                    });
                }, function (reason) {
                    console.error(reason);
                });
            }
        }

        let id_template = document.querySelector("#id_template").value;
        let parametrosJson = JSON.parse(document.querySelector("#json_parameters").value);

        $.ajax({
            type: "GET",
            url: "/publication/whatsapp/broadcast/waba/listtemplate/" + id_template,
            success: function (data) {

                let parametrosView = data[0]["text_body"].includes('{{');
                let headerTypeView = data[0]["header_type"];
                let headerView = data[0]["header"];
                let footerView = data[0]["text_footer"];
                qtdParametrosView = data[0]["text_body"].split('{{').length - 1;

                if (headerTypeView != "1") {
                    var parametrosHeader = [];
                    JSON.parse(parametrosJson).forEach(c => { c["type"] == "header" ? parametrosHeader.push(c["parameters"]) : null });

                    var link = '';
                    if (parametrosHeader.length > 0) {

                        if (parametrosHeader[0][0]["type"] == "video") {
                            link = parametrosHeader[0][0]["video"]["link"];
                        } else {
                            link = parametrosHeader[0][0]["document"]["link"];
                        }

                    }

                    document.querySelector("#imgHeaderText").setAttribute("src", link);
                } else {
                    document.querySelector("#tex_area_view_templateHeader").value = headerView;
                }

                if (parametrosView == true) {
                    var textoOriginal = document.querySelector("#tex_area_view_template").value;
                    var parametrosBody = [];
                    var textoParametro = textoOriginal;
                    JSON.parse(parametrosJson).forEach(c => { c["type"] == "body" ? parametrosBody.push(c["parameters"]) : null });

                    for (var i = 0; i < qtdParametrosView; i++) {

                        if (parametrosBody[0][i]["type"] == "date") {
                            textoParametro = textoParametro.replace(`{{${(i + 1)}}}`, parametrosBody[0][i]["date"]);
                        } else if (parametrosBody[0][i]["type"] == "currency") {
                            textoParametro = textoParametro.replace(`{{${(i + 1)}}}`, parametrosBody[0][i]["currency"][0]["fallback_value"]);
                        } else {
                            textoParametro = textoParametro.replace(`{{${(i + 1)}}}`, parametrosBody[0][i]["text"]);
                        }
                    }

                    document.querySelector("#tex_area_view_template").innerHTML = textoParametro
                }

                if (footerView != null) {
                    document.querySelector("#tex_area_view_templateFooter").value = footerView;
                }
            }
        });
    }

});

function viewBroadcast(e) {
    window.location.href = "send/view/" + e.id;
}

function handleMultiSelect(element) {
    if (element.check_select.checked === true) {
        element.mult_select.style.display = "block";
        element.verify_select.value = "1";
    }

    if (element.check_select.checked === false) {
        element.mult_select.style.display = "none";
        element.verify_select.value = "2";
    }
}

function modalFilter() {

    if (!$(".msf_multiselect_container").hasClass("msf_multiselect_container")) {
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

                if (checked == false) {
                    $(".ignore.add").children().prop('checked', false);
                };
            },
        });

        let campaign_type_select = new MSFmultiSelect(
            document.querySelector('#multiselect'), {
            theme: 'theme2',
            selectAll: true,
            searchBox: true,
            width: 'auto',
            height: 47,
            onChange: function (checked, value, instance) {
                if (campaign_type_select == "") campaign_type_select = value;

                if (checked == false) {
                    $(".ignore.add").children().prop('checked', false);
                };
            },
        });
    }

    const search = document.getElementById("search");

    const check_search = document.getElementById("check-search");
    const input_search = document.getElementById("input-search");

    const check_select2 = document.getElementById("check-select2");
    const mult_select2 = document.getElementById("mult-select2");
    const verify_select2 = document.getElementById("verify-select2");

    const campaign_type_check_select = document.getElementById("check-select");
    const campaign_type_mult_select = document.getElementById("mult-select");
    const campaign_type_verify_select = document.getElementById("verify-select");

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

    check_select2.addEventListener("click", () => {
        const element = {
            check_select: check_select2,
            mult_select: mult_select2,
            verify_select: verify_select2
        }

        handleMultiSelect(element);
    });

    campaign_type_check_select.addEventListener("click", () => {
        const element = {
            check_select: campaign_type_check_select,
            mult_select: campaign_type_mult_select,
            verify_select: campaign_type_verify_select
        }

        handleMultiSelect(element);
    });

    check_date.addEventListener("click", () => {
        if (check_date.checked) {

            dt_start.value = "";
            dt_start.type = "text";
            dt_start.style.display = "block";
            dt_start.placeholder = GLOBAL_LANG.report_send_filter_period_placeholder_date_start;

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
        elm.style.padding = "13px 15px 5px 15px";
    }

}


document.querySelectorAll(".btn.btn-primary, .btn.btn-danger").forEach(function (button) {
    button.addEventListener("click", function () {
        if (Filters) {
            Filters.btn_back = true;
            localStorage.setItem("filters", JSON.stringify(Filters));
        }
    });
})


function modalExport() {
    let column = $("#datatable-basic").DataTable().order()[0][0];
    let order = $("#datatable-basic").DataTable().order()[0][1];

    switch (column) {
        case 1:
            column = "title";
            break;
        case 2:
            column = "campaign_type";
            break;
        case 3:
            column = "message_send";
            break;
        case 4:
            column = "message_receipt";
            break;
        case 5:
            column = "message_read";
            break;
        default:
            column = "schedule";
            break;
    }

    $.get(`/export/xlsx?
        search=${$('#search').val() == "" ? $('#input-search').val() : $('#search').val()}
        &channel=${$('#verify-select2').val() == "2" ? "" : $('#multiselect2').val()}
        &campaign_type=${$('#verify-select').val() == "2" ? "" : $('#multiselect').val()}
        &column=${column}
        &order=${order}
        &dt_start=${$('#dt-start').val()}
        &dt_end=${$('#dt-end').val()}
        &type=reportBroadcastSynthetic`, function (response) {

        Swal.fire({
            title: GLOBAL_LANG.report_send_alert_export_title,
            text: GLOBAL_LANG.report_send_alert_export_text,
            type: 'success',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: GLOBAL_LANG.report_send_alert_export_confirmButtonText
        });
    });
}