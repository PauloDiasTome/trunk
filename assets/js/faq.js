function find() {
    $('#datatable-basic').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "faq/find",
            type: "POST",
            data: {
                text: $("#search").val(),
                csrf_talkall: Cookies.get("csrf_cookie_talkall")
            }
        },
        columns: [
            {
                mData: 'title'
            },
            {
                mData: 'last_name'
            },
        ],
        columnDefs: [
            {
                targets: 1,
            },
            {
                targets: 2,
                render: function (data, type, full, meta) {
                    return `<div class="dropleft">
                                    <button class="btn btn-link mb-0 btn-dropleft" type="button" id="multiDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #525f7f">
                                        <i class="fa fa-ellipsis-v"></i>
                                    <div class="ripple-container"></div>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="multiDropdownMenu">
                                            <a id="` + full.id_faq + `" href='#' class="dropdown-item table-btn-view" style="cursor: pointer">
                                                <div style="width: 24px; display: inline-block"> 
                                                    <i class="fas fa-eye" style="margin-left: -2px"></i>
                                                </div>
                                                <span>${GLOBAL_LANG.setting_faq_dt_columndefs_target2_title_view}</span>
                                            </a> 
                                            <a id="` + full.id_faq + `" href='#' class="dropdown-item table-btn-edit" style="cursor: pointer">
                                                <div style="width: 24px; display: inline-block"> 
                                                    <i class="far fa-edit"></i>
                                                </div>
                                                <span>${GLOBAL_LANG.setting_faq_dt_columndefs_target2_title_edit}</span>
                                            </a>
                                            <a id="` + full.id_faq + `" href='#' class="dropdown-item table-btn-delete" style="cursor: pointer">
                                                <div style="width: 24px; display: inline-block"> 
                                                    <i class="far fa-trash-alt"></i>
                                                </div>
                                                <span>${GLOBAL_LANG.setting_faq_dt_columndefs_target2_title_delete}</span>
                                            </a>
                                    </div>
                                </div>`
                }
            },
            {
                orderable: true,
                targets: [0, 1]
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
            find();
        }
    });


    if ($("#search").val() !== undefined) {
        find();
    }


    $("#datatable-basic").on("click", ".table-btn-view", function () {
        window.location.href = "faq/read/" + this.id;
    });


    $("#datatable-basic").on("click", ".table-btn-edit", function () {
        window.location.href = "faq/edit/" + this.id;
    });


    $("#input-title").on("click change keyup keydown paste cut", function () {

        document.getElementById("input-title").maxLength = 100;

    });


    $("#datatable-basic").on("click", ".table-btn-delete", function () {
        swal({
            title: GLOBAL_LANG.setting_faq_alert_delete_title,
            text: GLOBAL_LANG.setting_faq_alert_delete_text,
            type: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            reverseButtons: true,
            confirmButtonClass: "btn btn-success",
            confirmButtonText: GLOBAL_LANG.setting_faq_alert_delete_confirmButtonText,
            cancelButtonClass: "btn btn-danger",
            cancelButtonText: GLOBAL_LANG.setting_faq_alert_delete_cancelButtonText,
        }).then(t => {
            if (t.value == true) {
                $.post("faq/delete/" + this.id, function (data) {
                    t.value && swal({
                        title: GLOBAL_LANG.setting_faq_alert_delete_two_title,
                        text: GLOBAL_LANG.setting_faq_alert_delete_two_text,
                        type: "success",
                        buttonsStyling: !1,
                        confirmButtonClass: "btn btn-success"
                    });
                    $("#datatable-basic").DataTable().ajax.reload(null, false);
                });
            }
        });
    });


    $(".ql-editor").on("keyup", function () {
        $('#input-content').val($(".ql-editor").html());
    });


    $('#sendEmailExport').on('click', () => modalExport());
    $("form").submit(event => event.preventDefault());
    if (document.querySelector(".btn-success")) {
        document.querySelector(".btn-success").addEventListener("click", submit);
    }

});


function submit() {
    document.querySelector(".btn-success").disabled = true;
    $("form").unbind('submit').submit();
}


function modalExport() {
    let column = $("#datatable-basic").DataTable().order()[0][0];
    let order = $("#datatable-basic").DataTable().order()[0][1];

    switch (column) {
        case 0:
            column = "title";
            break;
        case 1:
            column = "last_name";
            break;
        default:
            break;
    }

    $.get(`/export/xlsx?
        search=${$('#search').val()}
        &column=${column}
        &order=${order}
        &type=faq`, function (response) {

        if (response != "Error") {
            Swal.fire({
                title: GLOBAL_LANG.setting_faq_alert_export_title,
                text: GLOBAL_LANG.setting_faq_alert_export_text,
                type: 'success',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: GLOBAL_LANG.setting_faq_alert_export_confirmButtonText,
            });
        }
    });
}
