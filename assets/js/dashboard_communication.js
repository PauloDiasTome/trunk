
"use strict";

let fixedChannel = true;
let fixedbroadcast = false;
let totalContactAudience = document.getElementById("totalContact").value;

function filterDropdown(e) {

    const search = opt => {

        const text = e.value.toUpperCase();

        for (let i = 0; i < opt.length; i++) {

            const txtValue = opt[i].textContent || opt[i].innerText;

            if (txtValue.toUpperCase().indexOf(text) > -1) {
                opt[i].style.display = "block";
            } else {
                opt[i].style.display = "none";
            }
        }
    }

    if (e.id === "searchChannel") {

        if (fixedChannel) {

            document.getElementById("searchChannel").value = "";
            document.getElementById("selectedChannel").value = "";
            document.querySelector("#dropdown-channel .dropdown-content").style.display = "none";
            fixedChannel = false;
            return;
        }

        const dropdown = e.nextElementSibling;
        const option = dropdown.getElementsByTagName("a");

        search(option);
        document.querySelector("#dropdown-channel .dropdown-content").style.display = "block";
    }

    if (e.id === "searchBroadcast") {
        if (fixedbroadcast) {

            document.getElementById("searchBroadcast").value = "";
            document.getElementById("selectedBroadcast").value = "";
            document.querySelector("#dropdown-broadcast .dropdown-content").style.display = "none";
            fixedbroadcast = false;
            return;
        }

        const broadcast = document.querySelectorAll("#dropdown-broadcast a");
        const id_channel = document.getElementById("selectedChannel").value;

        if (id_channel === "") {
            search(broadcast);
        } else {
            search([...broadcast].filter(elm => elm.dataset.id_channel === id_channel));
        }

        document.querySelector("#dropdown-broadcast .dropdown-content").style.display = "block";
    }
}


function checkBroadcast(id_channel) {

    const broadcast = document.querySelectorAll("#dropdown-broadcast a");
    const list = [...broadcast];

    list.map((elm, idx) => {

        if (elm.dataset.id_channel === id_channel)
            elm.style.display = "block";
        else
            elm.style.display = "none";
    });
}


function openDropdown(e) {

    if (e.nextElementSibling.style.display === "block") {
        e.nextElementSibling.style.display = "none";
    } else {

        if (e.id === "searchChannel") {
            e.nextElementSibling.style.display = "block";
            return;
        }

        if (e.id === "searchBroadcast") {
            const id_channel = document.getElementById("selectedChannel").value;

            if (id_channel === "") {
                document.querySelectorAll("#dropdown-broadcast a").forEach(elm => elm.style.display = "block");
            } else {
                checkBroadcast(id_channel);
            }

            e.nextElementSibling.style.display = "block";
        }
    }
}


function closeDropdown(e) {
    e.nextElementSibling.style.display = "none";
}


function selectedChannel(e) {

    const searchChannel = document.getElementById("searchChannel");
    const selectedChannel = document.getElementById("selectedChannel");

    const searchBroadcast = document.getElementById("searchBroadcast");
    const selectedBroadcast = document.getElementById("selectedBroadcast");

    if (searchChannel !== null && searchChannel !== "") {

        searchChannel.value = e.text;
        selectedChannel.value = e.dataset.id_channel;
        fixedChannel = true;

        searchBroadcast.value = "";
        selectedBroadcast.value = "";

        checkBroadcast(e.dataset.id_channel);
    }
}


function selectedBroadcast(e) {

    const searchBroadcast = document.getElementById("searchBroadcast");
    const selectedBroadcast = document.getElementById("selectedBroadcast");

    if (searchBroadcast !== null) {

        searchBroadcast.value = e.text;
        selectedBroadcast.value = e.dataset.id_broadcast;
        fixedbroadcast = true;
    }
}


const filter = {
    graph: {
        interaction(res) {

            [...document.querySelector(".interaction-graph .left").children].map((elm, idx) => {

                if (elm.children[1].children[2].className == "number") {
                    elm.children[1].children[2].innerHTML = res.interaction[idx].percent;
                }
                elm.children[2].children[0].innerHTML = res.interaction[idx].total;
            });

            [...document.querySelector(".interaction-graph .right").children].map((elm, idx) => {

                const interaction_percentage = res.interaction[idx].percent.split("%")[0];

                if (interaction_percentage != 0) {

                    let width = 1;
                    const id = setInterval(() => {
                        if (width >= interaction_percentage) {
                            clearInterval(id);
                        } else {
                            width++;
                            elm.children[0].style.width = width + '%';
                        }
                    }, 20);

                } else {
                    elm.children[0].style.width = '0%';
                }
            });
        },

        audience(res) {
            [...document.querySelectorAll(".audience-graph .container-title .info")].map((elm, idx) => {
                switch (idx) {
                    case 0:
                        elm.children[0].innerHTML = "";
                        elm.children[1].innerHTML = "";
                        break;
                    case 1:
                        elm.children[0].innerHTML = res.audience.active_percent + "%";
                        elm.children[1].innerHTML = res.audience.active;
                        break;
                    case 2:
                        elm.children[0].innerHTML = res.audience.inactive_percent + "%";
                        elm.children[1].innerHTML = res.audience.inactive;
                        break;
                    default:
                        break;
                }
            });

            [...document.querySelectorAll(".audience-graph .wartnings div")].map((elm, idx) => {
                switch (idx) {
                    case 0:
                        elm.style.display = "none";
                        break;
                    case 1:
                        elm.style.display = "none";
                        break;
                    case 2:
                        elm.style.display = res.audience.alert.inactive_one;
                        break;
                    case 3:
                        elm.style.display = res.audience.alert.inactive_two;
                        break;
                    case 4:
                        elm.style.display = res.audience.alert.inactive_three;
                        break;
                    case 5:
                        elm.style.display = res.audience.alert.inactive_four;
                        break;
                    case 6:
                        elm.style.display = res.audience.alert.total_one;
                        break;
                    case 7:
                        elm.style.display = res.audience.alert.total_two;
                    default:
                        break;
                }
            });

            interactionResize();
            totalContactAudience = res.audience.total;

            document.getElementById("audienceChart").remove();

            const canvas = document.createElement("canvas");
            canvas.id = "audienceChart";
            canvas.setAttribute("width", "180");
            canvas.setAttribute("height", "180");

            document.querySelector(".audience-graph .container-graphic").append(canvas);

            const ctx = document.getElementById("audienceChart").getContext("2d");
            const data = [
                {
                    value: parseInt(res.audience.active),
                    color: "#2263D3",
                    highlight: "lightskyblue",
                    label: "JavaScript"
                },
                {
                    value: parseInt(res.audience.inactive),
                    color: "#000000",
                    highlight: "darkorange",
                    label: "CSS"
                }];

            new Chart(ctx).Doughnut(data);
            document.getElementById("audienceChart").style.height = "180px";
            document.getElementById("audienceChart").style.width = "180px";

        }

    }, search: {
        dashboard() {
            const dashboard_title = document.getElementById("dashboard_title");
            const id_channel = document.getElementById("selectedChannel").value;
            const id_broadcast = document.getElementById("selectedBroadcast").value;
            const period = { dt_start: document.getElementById("dt-start").value, dt_end: document.getElementById("dt-end").value };

            if (searchChannel.value != '')
                dashboard_title.innerHTML = `${GLOBAL_LANG.dashboard_communication_title} - ${searchChannel.value}`;

            if (document.getElementById("verify-broadcast") === 2) id_broadcast = "";
            if (document.getElementById("verify-channel") === 2) id_channel = "";
            fetch(`${document.location.origin}/dashboard/communication/get?id_channel=${id_channel}&id_broadcast=${id_broadcast}&period=${JSON.stringify(period)}`)
                .then((res) => res.json().then((res) => {
                    filter.graph.interaction(res);
                    filter.graph.audience(res);

                    document.getElementById("chartPeriod").addEventListener("click", audiencePeriod);
                    document.getElementById("chartSevenDays").addEventListener("click", audienceSevenDays);
                    document.getElementById("chartTotal").addEventListener("click", audienceTotal);
                }));

            document.getElementById("btn-search").removeEventListener("click", filter.search.dashboard);
        },

        audience(id_channel, id_broadcast, period) {
            fetch(`${document.location.origin}/dashboard/communication/get?id_channel=${id_channel}&id_broadcast=${id_broadcast}&period=${JSON.stringify(period)}`)
                .then((res) => res.json().then((res) => {
                    filter.graph.interaction(res);
                    filter.graph.audience(res);

                    document.getElementById("chartPeriod").addEventListener("click", audiencePeriod);
                    document.getElementById("chartSevenDays").addEventListener("click", audienceSevenDays);
                    document.getElementById("chartTotal").addEventListener("click", audienceTotal);
                }));
        },

    }, campaigns: {
        scheduled() {

            const broadcastStatus = "1, 3, 6";
            scheduledCampaigns(broadcastStatus);
        },

        published() {

            const broadcastStatus = "2";
            publishedCampaigns(broadcastStatus);
        }
    }
}


function modalFilter() {

    const check_broadcast = document.getElementById("check-broadcast");
    const dropdown_broadcast = document.getElementById("dropdown-broadcast");
    const verify_broadcast = document.getElementById("verify-broadcast");

    const check_channel = document.getElementById("check-channel");
    const dropdown_channel = document.getElementById("dropdown-channel");
    const verify_channel = document.getElementById("verify-channel");

    const check_period = document.getElementById("check-period");
    const dt_end = document.getElementById("dt-end");
    const dt_start = document.getElementById("dt-start");

    const btn_search = document.getElementById("btn-search");

    dt_start.style.display = "block";
    dt_end.style.display = "block";
    check_period.checked = "true";
    dt_end.disabled = false;

    check_broadcast.addEventListener("click", () => {
        if (check_broadcast.checked) {
            verify_broadcast.value = "1";
            dropdown_broadcast.style.display = "block";
        } else {
            verify_broadcast.value = "2";
            dropdown_broadcast.value = "";
            dropdown_broadcast.style.display = "none";
        }
    });

    check_channel.addEventListener("click", () => {
        if (check_channel.checked) {
            verify_channel.value = "1";
            dropdown_channel.style.display = "block";
        } else {
            verify_channel.value = "2";
            dropdown_channel.value = "";
            dropdown_channel.style.display = "none";
        }
    });

    check_period.addEventListener("click", () => {
        check_period.checked = "true";
    });

    if (check_period.checked) {
        if (dt_start.value == "" && dt_end.value == "") {
            setLastNDays(29);
        }
    }

    // dt_start.addEventListener("change", () => {

    // if (validateNinetyDays(dt_start)) {
    //     dt_end.disabled = false;
    // } else
    // }
    // dt_end.disabled = true;
    //  setDateEnd();
    // });

    // dt_end.addEventListener("change", () => {

    // if (validateNinetyDays(dt_end)) {
    // validateThirtyDays();
    // }
    // });

    // dt_start.addEventListener("blur", () => {

    //     if (!isEmptyDateField()) {
    //         if (validateNinetyDays(dt_start)) {
    //         validateThirtyDays();
    //         }
    //     }
    // });

    // dt_end.addEventListener("blur", () => {

    //     if (!isEmptyDateField()) {
    //         if (validateNinetyDays(dt_end)) {
    //         validateThirtyDays();
    //         }
    //     }
    // });

    // const setDateEnd = () => {

    //     const parts = dt_start.value.split("-");
    //     const start_date = new Date(parts[0], parts[1] - 1, parts[2]);
    //     const end_date = new Date(start_date);
    //     end_date.setDate(start_date.getDate() + 29);

    //     const current_date = new Date();
    //     const regex_date_pattern = /^\d{4}-\d{2}-\d{2}$/;

    //     if (end_date != "Invalid Date") {

    //         const formatted_end_date = end_date.getFullYear() + '-' + String(end_date.getMonth() + 1).padStart(2, '0') + '-' + String(end_date.getDate()).padStart(2, '0');

    //         if (regex_date_pattern.test(formatted_end_date)) {
    //             if (Date.parse(formatted_end_date) > Date.parse(current_date)) {
    //                 dt_end.valueAsDate = current_date;
    //             } else {
    //                 dt_end.value = formatted_end_date;
    //             }
    //         }
    //     }
    // }

    btn_search.addEventListener("click", search);
}


function isEmptyDateField() {

    if (document.getElementById("dt-end").value == "" || document.getElementById("dt-start").value == "") {
        document.getElementById("alert-filter-period").innerHTML = GLOBAL_LANG.dashboard_communication_filter_period_notify_empty_date;
        document.getElementById("alert-filter-period").style.display = "block";
        document.getElementById("btn-search").disabled = true;
        return true;
    } else {
        return false;
    }
}


function getValidPeriod() {
    const localDate = new Date();
    const dt_max = localDate.toISOString().split("T")[0];
    const millisecond_per_day = 1000 * 60 * 60 * 24;
    const difDate = new Date(localDate.getTime() - 90 * millisecond_per_day)

    const dt_min = difDate.toISOString().split("T")[0];

    return {
        dt_max: dt_max,
        dt_min: dt_min,
        millisecond_per_day: millisecond_per_day
    };
}

/*
function validateNinetyDays(date) {

    const period = getValidPeriod();

    const alert_filter_period = document.getElementById("alert-filter-period");

    if (Date.parse(date.value) < Date.parse(period.dt_min)) {
        alert_filter_period.innerHTML = GLOBAL_LANG.dashboard_communication_filter_period_notify_ninety_days;
        alert_filter_period.style.display = "block";
        document.getElementById("btn-search").disabled = true;
        return false;

    } else if (Date.parse(date.value) > Date.parse(period.dt_max)) {

        alert_filter_period.innerHTML = GLOBAL_LANG.dashboard_communication_filter_period_notify_greater_than_today;
        alert_filter_period.style.display = "block";
        document.getElementById("btn-search").disabled = true;
        return false;
    } else {
        alert_filter_period.style.display = "none";
        document.getElementById("btn-search").disabled = false;
        return true;
    }
}
*/

// function validateThirtyDays() {

//     const period = getValidPeriod();
//     const dt_end = document.getElementById("dt-end");
//     const dt_start = document.getElementById("dt-start");

//     if (Date.parse(dt_end.value) > Date.parse(dt_start.value)) {

//         const time_difference = Date.parse(dt_end.value) - Date.parse(dt_start.value);
//         const days_difference = Math.floor(time_difference / period.millisecond_per_day);

//         if (days_difference > 29) {
//             document.getElementById("alert-filter-period").innerHTML = GLOBAL_LANG.dashboard_communication_filter_period_notify_exceeds_thirty_days;
//             document.getElementById("alert-filter-period").style.display = "block";
//             document.getElementById("btn-search").disabled = true;
//             return false;
//         } else {
//             document.getElementById("btn-search").disabled = false;
//             return true;
//         }
//     }
//     document.getElementById("btn-search").disabled = false;
//     return true;
// }


function search() {

    if (!isEmptyDateField()) {
        // if (validateNinetyDays(document.getElementById("dt-start")) && validateNinetyDays(document.getElementById("dt-end"))) {
        //     if (validateThirtyDays()) {

        filter.search.dashboard();
        filter.campaigns.scheduled();
        filter.campaigns.published();

        showButtonDate();

        document.getElementById("btn-search").removeEventListener("click", search);
    }
    // }
    // }
}


function audiencePeriod() {

    const period = { dt_start: document.getElementById("dt-start").value, dt_end: document.getElementById("dt-end").value };
    const id_channel = document.getElementById("selectedChannel").value;
    const id_broadcast = document.getElementById("selectedBroadcast").value;
    const chart_period = document.getElementById("chartPeriod");

    updateButtonSelection(chart_period.querySelector("a"));

    filter.search.audience(id_channel, id_broadcast, period);

    chart_period.removeEventListener("click", audiencePeriod);
}


function audienceSevenDays() {

    setLastNDays(6);

    const period = { dt_start: document.getElementById("dt-start").value, dt_end: document.getElementById("dt-end").value };
    const id_channel = document.getElementById("selectedChannel").value;
    const id_broadcast = document.getElementById("selectedBroadcast").value;
    const chart_seven_days = document.getElementById("chartSevenDays");

    document.getElementById("chartPeriod").style.display = "none";

    filter.search.audience(id_channel, id_broadcast, period);

    updateButtonSelection(chart_seven_days.querySelector("a"));

    chart_seven_days.removeEventListener("click", audienceSevenDays);
}


function audienceTotal() {

    const period = "";
    const id_channel = document.getElementById("selectedChannel").value;
    const id_broadcast = document.getElementById("selectedBroadcast").value;
    const chart_total = document.getElementById("chartTotal");

    document.getElementById("chartPeriod").style.display = "none";

    filter.search.audience(id_channel, id_broadcast, period);

    updateButtonSelection(chart_total.querySelector("a"));

    chart_total.removeEventListener("click", audienceTotal);
}


function updateButtonSelection(selectedButton) {

    document.querySelectorAll(".audience-graph .nav-item a").forEach(button => {
        button.classList.remove("selected");
    });
    selectedButton.classList.add("selected");

}


function interactionResize() {

    const audienceGraph = document.querySelector(".audience-graph .card").clientHeight;
    const interactionGraph = document.querySelector(".interaction-graph .card");

    interactionGraph.style.height = audienceGraph + "px";
}


function interactionGraph() {

    const showSubtitle = () => {

        let count = 0;

        const wartnings = document.querySelectorAll(".audience-graph .wartnings div");
        const subTitle = document.querySelector(".interaction-graph .info-footer");

        for (const elm of wartnings) {
            if (elm.style.display === "flex") count++;
        }

        if (count > 3) {
            subTitle.style.display = "block";
        }
    }

    showSubtitle();
    interactionResize();
    setTimeout(() => interactionResize(), 1200);
}


function geltElmSubtitle() {

    const info = [];

    [...document.querySelectorAll(".audience-graph .container-title .info")]
        .map((elm, idx) => {
            info.push(parseInt(elm.children[1].innerHTML));
        });

    return { fans: info[0], active: info[1], inactive: info[2] }
}


function audienceGraph() {

    const ctx = document.getElementById("audienceChart").getContext("2d");
    const info = geltElmSubtitle();

    const data = [
        {
            value: info.active,
            color: "#2263D3",
            highlight: "lightskyblue",
            label: "JavaScript"
        },
        // {
        //     value: 50,
        //     color: "#FF499A",
        //     highlight: "yellowgreen",
        //     label: "HTML"
        // },
        {
            value: info.inactive,
            color: "#000000",
            highlight: "darkorange",
            label: "CSS"
        }];

    new Chart(ctx).Doughnut(data);
}


function scheduledCampaigns() {
    $('#datatable-scheduled-campaigns').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "communication/scheduled/campaigns",
            type: "POST",
            data: {
                status: "1,3,6",
                period: { dt_start: document.getElementById("dt-start").value, dt_end: document.getElementById("dt-end").value },
                broadcast: $("#verify-broadcast").val() == 1 ? $("#selectedBroadcast").val() : "",
                csrf_talkall: Cookies.get("csrf_cookie_talkall"),
            }
        },
        columns: [
            {
                mData: 'schedule'
            },
            {
                mData: 'title'
            },
        ],
        columnDefs: [
            {
                targets: 2,
                render: function (data, type, full, meta) {

                    let url = "#";

                    if (full.is_wa_broadcast == 1)
                        url = `publication/whatsapp/broadcast/view/${full.token}`;

                    else if (full.is_waba_broadcast == 1)
                        url = `publication/whatsapp/broadcast/waba/view/${full.token}`;

                    else if (full.is_wa_community == 1)
                        url = `publication/whatsapp/broadcast/community/view/${full.token}`;

                    else if (full.is_wa_status == 1)
                        url = `publication/whatsapp/status/view/${full.token}`;

                    return "<a href='" + document.location.origin + "/" + url + "' id='" + full.id_broadcast_schedule + "' class='table-action table-action-edit' data-toggle='tooltip'" +
                        "target='_blank' data-original-title=''> <i class='fas fa-eye'></i></a>";
                }
            },
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
            if ($("#selectedBroadcast").val() != "" && $("#verify-broadcast").val() == 1) {
                $("#datatable-scheduled-campaigns").css({ "height": "auto" });
                $("#datatable-published-campaigns").css({ "height": "auto" });
            } else {
                $("#datatable-scheduled-campaigns").css({ "height": "192.5px" });
                $("#datatable-published-campaigns").css({ "height": "192.5px" });
            }
        }
    });
}


function publishedCampaigns() {
    $('#datatable-published-campaigns').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "communication/published/campaigns",
            type: "POST",
            data: {
                status: "2",
                period: { dt_start: document.getElementById("dt-start").value, dt_end: document.getElementById("dt-end").value },
                broadcast: $("#verify-broadcast").val() == 1 ? $("#selectedBroadcast").val() : "",
                csrf_talkall: Cookies.get("csrf_cookie_talkall"),
            }
        },
        columns: [
            {
                mData: 'schedule'
            },
            {
                mData: 'title'
            },
        ],
        columnDefs: [
            {
                targets: 2,
                render: function (data, type, full, meta) {

                    let url = "#";

                    if (full.is_wa_broadcast == 1)
                        url = `publication/whatsapp/broadcast/view/${full.token}`;

                    else if (full.is_waba_broadcast == 1)
                        url = `publication/whatsapp/broadcast/waba/view/${full.token}`;

                    else if (full.is_wa_community == 1)
                        url = `publication/whatsapp/broadcast/community/view/${full.token}`;

                    else if (full.is_wa_status == 1)
                        url = `publication/whatsapp/status/view/${full.token}`;

                    return "<a href='" + document.location.origin + "/" + url + "' id='" + full.id_broadcast_schedule + "' class='table-action table-action-edit' data-toggle='tooltip'" +
                        "target='_blank' data-original-title=''> <i class='fas fa-eye'></i></a>";
                }
            },
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
            if ($("#selectedBroadcast").val() != "" && $("#verify-broadcast").val() == 1) {
                $("#datatable-scheduled-campaigns").css({ "height": "auto" });
                $("#datatable-published-campaigns").css({ "height": "auto" });
            } else {
                $("#datatable-scheduled-campaigns").css({ "height": "192.5px" });
                $("#datatable-published-campaigns").css({ "height": "192.5px" });
            }
        }
    });
}


function showButtonDate() {

    const chart_period = document.getElementById("chartPeriod");
    chart_period.style.display = "flex";

    updateButtonSelection(chart_period.querySelector("a"));

    setDateOnButton();
}


function setLastNDays(days) {

    let last_n_days = new Date();
    last_n_days.setDate(last_n_days.getDate() - days);
    document.getElementById("dt-start").valueAsDate = last_n_days;

    let current_date = new Date();
    document.getElementById("dt-end").valueAsDate = current_date;
    document.getElementById("btn-search").disabled = false;
    document.getElementById("alert-filter-period").style.display = "none";
}


function setDateOnButton() {

    const dt_start = document.getElementById("dt-start").value;
    const dt_start_parts = dt_start.split("-");
    const dt_start_formatted = dt_start_parts[2] + "/" + dt_start_parts[1] + "/" + dt_start_parts[0];

    const dt_end = document.getElementById("dt-end").value;
    const dt_end_parts = dt_end.split("-");
    const dt_end_formatted = dt_end_parts[2] + "/" + dt_end_parts[1] + "/" + dt_end_parts[0];

    document.getElementById("button-date-filtered").innerHTML = dt_start_formatted + " - " + dt_end_formatted;
}


function setDateOnModal(data) {

    const dt_start = new Date(data.start_date);
    const dt_end = new Date(data.end_date);

    document.getElementById("dt-start").valueAsDate = dt_start;
    document.getElementById("dt-end").valueAsDate = dt_end;
    document.getElementById("btn-search").disabled = false;
    document.getElementById("alert-filter-period").style.display = "none";
}


function compareDate() {
    if (!document.querySelector(".button-hidden")) {

        const dt_start = new Date(document.getElementById("dt-start").value);
        const dt_end = new Date(document.getElementById("dt-end").value);

        const span_date = document.getElementById("button-date-filtered").innerText.trim().split(' - ');
        const start_date = new Date(span_date[0].split('/').reverse().join('-'));
        const end_date = new Date(span_date[1].split('/').reverse().join('-'));

        const date_different = !(
            dt_start.getTime() === start_date.getTime() &&
            dt_end.getTime() === end_date.getTime()
        );

        if (date_different) {
            setDateOnModal({ start_date, end_date });
        }

    }
}


$(document).ready(function () {

    audienceGraph();
    interactionGraph();

    scheduledCampaigns(1, 3, 6);
    publishedCampaigns(2);

    const modal = document.getElementById('modal-filter');

    document.getElementById("modalFilter").addEventListener("click", modalFilter);
    document.getElementById("chartTotal").addEventListener("click", audienceTotal);
    document.getElementById("chartSevenDays").addEventListener("click", audienceSevenDays);
    document.querySelector("#modal-filter .close").addEventListener("click", compareDate);
    document.querySelector("#modal-filter .btn.btn-secondary").addEventListener("click", compareDate);
    modal.addEventListener('click', function (event) { if (event.target === modal) { compareDate(); } });

    window.addEventListener('resize', () => window.location.reload(), true);
});
