function find() {
    $('#datatable-basic').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "blocklist/find",
            type: "POST",
            data: {
                "text": $("#search").val(),
                "csrf_talkall": Cookies.get("csrf_cookie_talkall")


            }
        },
        columns: [{
            mData: 'key_remote_id'
        },
        {
            mData: 'name_contact'
        },
        {
            mData: 'last_name'
        },
        ],
        columnDefs: [
            {
                targets: 3,
                render: function (data, type, full, meta) {

                    return `<div class="dropleft">
                                    <button class="btn btn-link mb-0 btn-dropleft" type="button" id="multiDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #525f7f">
                                        <i class="fa fa-ellipsis-v"></i>
                                    <div class="ripple-container"></div>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="multiDropdownMenu">
                                        <a id="` + full.id_block_list + `" href='#' class="dropdown-item table-btn-delete" style="cursor: pointer">
                                    <div style="width: 24px; display: inline-block">
                                        <i class="far fa-trash-alt"></i>
                                    </div>
                                     <span></i>${GLOBAL_LANG.blocklsit_dt_columndefs_targer3_delete}</span>
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
            if (settings.json.data.length == 0) {
                $("#modalExport").attr('disabled', 'disabled');
            } else {
                $("#modalExport").removeAttr('disabled');
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


    $("#datatable-basic").on("click", ".table-btn-delete", function () {
        swal({
            title: GLOBAL_LANG.blocklist_alert_delete_title,
            text: GLOBAL_LANG.blocklist_alert_delete_text,
            type: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            reverseButtons: true,
            confirmButtonClass: "btn btn-success",
            confirmButtonText: GLOBAL_LANG.blocklist_alert_delete_confirmButtonText,
            cancelButtonClass: "btn btn-danger",
            cancelButtonText: GLOBAL_LANG.blocklist_alert_delete_cancelButtonText,
        }).then(t => {
            if (t.value == true) {
                $.post("blocklist/delete/" + this.id, function (data) {
                    t.value && swal({
                        title: GLOBAL_LANG.blocklist_alert_delete_two_title,
                        text: GLOBAL_LANG.blocklist_alert_delete_two_text,
                        type: "success",
                        buttonsStyling: !1,
                        confirmButtonClass: "btn btn-success"
                    });
                    $("#datatable-basic").DataTable().ajax.reload(null, false);
                });
            }
        });
    });

    $("#sendEmailExport").on("click", () => modalExport());

});


function modalExport() {
    let column = $("#datatable-basic").DataTable().order()[0][0];
    let order = $("#datatable-basic").DataTable().order()[0][1];

    switch (column) {
        case 0:
            column = "key_remote_id";
            break;

        case 1:
            column = "full_name";
            break;
    }

    $.get(`/export/xlsx?
        search=${$('#search').val()}
        &column=${column}
        &order=${order}
        &type=blocklist`, function (response) {

        if (response != "Error") {
            Swal.fire({
                title: GLOBAL_LANG.blocklist_alert_export_title,
                text: GLOBAL_LANG.blocklist_alert_export_text,
                type: 'success',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: GLOBAL_LANG.blocklist_alert_export_confirmButtonText
            });
        }

    });

}
