function find() {
    $('#datatable-basic').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "contact/find",
            type: "POST",
            data: {
                "text": $("#search").val(),
                "labels": $('#labels').val(),
                "csrf_talkall": Cookies.get("csrf_cookie_talkall")
            }
        },
        columns: [
            {
                mData: 'name'
            },
            {
                mData: 'count_contact'
            },
            {
                mData: 'count_month'
            },
            {
                mData: 'count_day'
            },
            {
                mData: 'p_month'
            },
            {
                mData: 'p_day'
            },
            {
                mData: 'a_day_ago'
            },
            {
                mData: 'two_day_ago'
            },
            {
                mData: 'three_day_ago'
            },
            {
                mData: 'four_day_ago'
            },
            {
                mData: 'five_day_ago'
            },
            {
                mData: 'six_day_ago'
            },
            {
                mData: 'seven_day_ago'
            },
        ],
        columnDefs: [
            {
                targets: 0,

            },
            {
                orderable: false,
                targets: [4, 5, 6, 7, 8, 9, 10, 11, 12]
            }
        ],

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

    $.get(`/export/xlsx?type=reportContact`, function (response) {

        if (response != "Error") {
            Swal.fire({
                title: GLOBAL_LANG.report_contact_alert_export_title,
                text: GLOBAL_LANG.report_contact_alert_export_text,
                type: 'success',
                reverseButtons: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: GLOBAL_LANG.report_contact_alert_export_confirmButtonText,
            });
        }
    });
}