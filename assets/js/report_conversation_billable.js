function find() {
    $('#datatable-basic').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "billable/find",
            type: "POST",
            data: {
                "text": $("#search").val(),
                "csrf_talkall": Cookies.get("csrf_cookie_talkall")
            }
        },
        columns: [
            {
                mData: 'creation'
            },
            {
                mData: 'key_remote_id'
            },
            {
                mData: 'origin_type'
            },
            {
                mData: 'billable'
            },
            {
                mData: 'current_price'
            },
        ],

        columnDefs: [
            {
                targets: 3,
                render: (data, type, full, meta) => {
                    if (full.billable == 1) {
                        return GLOBAL_LANG.report_conversation_billable_yes;
                    } else {
                        return GLOBAL_LANG.report_conversation_billable_no;
                    }
                }
            },
            {
                orderable: false,
                targets: [0, 1, 2, 3, 4]
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

    $.get(`/export/xlsx?type=reportConversationBillable`, function (response) {

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