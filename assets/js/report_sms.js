var last_date = "";

function FormatShortDate(timestamp) {

    var date = new Date(timestamp * 1000);

    var today = new Date();

    var currentDate = today.getFullYear() + '/' + today.getMonth() + '/' + today.getDate();
    var dt = date.getFullYear() + '/' + date.getMonth() + '/' + date.getDate();

    if (currentDate == dt) {
        return GLOBAL_LANG.report_sms_function_fsd_today;
    } else {
        var diff = dateDiffInDays(date, today);
        if (diff < 3) {
            switch (diff) {
                case 1:
                    return GLOBAL_LANG.report_sms_function_fsd_yesterday;
                case 2:
                    return GLOBAL_LANG.report_sms_function_fsd_day_before_yesterday;
            }
        } else {
            if (diff < 7) {
                var semana = [GLOBAL_LANG.report_sms_function_fsd_sunday, GLOBAL_LANG.report_sms_function_fsd_monday, GLOBAL_LANG.report_sms_function_fsd_tuesday, GLOBAL_LANG.report_sms_function_fsd_wednesday, GLOBAL_LANG.report_sms_function_fsd_thursday, GLOBAL_LANG.report_sms_function_fsd_friday, GLOBAL_LANG.report_sms_function_fsd_saturday];
                return semana[date.getDay()];
            } else {
                return date.getDate() + '/' + date.getMonth() + '/' + date.getFullYear();
            }
        }
    }
}


function FormatShortTime(timestamp) {
    var date = new Date(timestamp * 1000);
    var hours = date.getHours();
    var minutes = "0" + date.getMinutes();
    return hours + ':' + minutes.substr(-2);
}


const _MS_PER_DAY = 1000 * 60 * 60 * 24;

// a and b are javascript Date objects
function dateDiffInDays(a, b) {
    // Discard the time and time-zone information.
    const utc1 = Date.UTC(a.getFullYear(), a.getMonth(), a.getDate());
    const utc2 = Date.UTC(b.getFullYear(), b.getMonth(), b.getDate());
    return Math.floor((utc2 - utc1) / _MS_PER_DAY);
}


function find() {
    $('#datatable-basic').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "sms/find",
            type: "POST",
            data: {
                "text": $("#search").val(),
                "dt-start": $('#dt-start').val(),
                "dt-end": $('#dt-end').val(),
                "csrf_talkall": Cookies.get("csrf_cookie_talkall")
            }
        },
        columns: [
            {
                mData: 'creation'
            },
            {
                mData: 'to'
            },
            {
                mData: 'text'
            },
            {
                mData: 'status'
            },
        ],
        columnDefs: [
            {
                orderable: false,
                targets: [0, 1, 3]
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
        }
    });
}

$(document).ready(function () {

    $("#search").on("keyup", function (event) {
        if (event.which == 13) {
            find()
        }
    });
    find();

    $('#sendEmailExport').on('click', () => modalExport());

});


function modalExport() {

    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (!re.test(String($('#emailExport').val()).toLowerCase())) {
        Swal.fire({
            title: GLOBAL_LANG.report_sms_alert_export_title,
            text: GLOBAL_LANG.report_sms_alert_export_text,
            type: 'warning',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: GLOBAL_LANG.report_sms_alert_export_confirmButtonText,
        });
        $('.swal2-container').css('z-index', 10000);
        return false;
    }

    $.get(`/csv/export?
        type=reportSms&dt_start=${$('#dt-start').val()}&dt_end=${$('#dt-end').val()}`, function (response) {
        Swal.fire({
            title: GLOBAL_LANG.report_sms_alert_export_two_title,
            text: GLOBAL_LANG.report_sms_alert_export_two_title,
            type: 'success',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: GLOBAL_LANG.report_sms_alert_export_two_confirmButtonText,
        });
    });
}