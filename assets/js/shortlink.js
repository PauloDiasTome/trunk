const Filters = JSON.parse(localStorage.getItem("filters")) || null;


function checkFilter() {
    if (Filters) {

        if (!Filters.btn_back) return

        if (Filters.shortlink.search) {
            document.getElementById("search").value = Filters.shortlink.search;
        }

        Filters.btn_back = null;
        localStorage.setItem("filters", JSON.stringify(Filters));
    }
}

checkFilter();


function find() {
    $('#datatable-basic').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "shortlink/find",
            type: "POST",
            data: {
                "text": $("#search").val(),
                // "opa": order[0],
                "csrf_talkall": Cookies.get("csrf_cookie_talkall")
            }
        },
        columns: [
            {
                mData: 'name'
            },
            {
                mData: 'link'
            },
        ],
        columnDefs: [
            {
                orderable: true,
                targets: 1,
                render: function (data, type, full, meta) {
                    return "<a href='" + full.link + "' class='table-action' data-toggle='tooltip' target='_blank'>" + full.link + "</a>";
                }
            },
            {
                orderable: false,
                targets: 2,
                render: function (data, type, full, meta) {
                    var a = $('#view').val();

                    if (a == "false") {

                        return `<div class="dropleft">
                                        <button class="btn btn-link mb-0 btn-dropleft" type="button" id="multiDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #525f7f">
                                            <i class="fa fa-ellipsis-v"></i>
                                        <div class="ripple-container"></div>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="multiDropdownMenu">
                                            <a href="dashborad/shotlink/` + full.id_short_link + `" class="dropdown-item btn-outline-primary" style="cursor: pointer">
                                                <i class="far fa-chart-bar"></i>${GLOBAL_LANG.shortlink_datatable_column}
                                            </a>
                                        </div>
                                    </div>`
                    }
                    else {

                        return `<div class="dropleft">
                                        <button class="btn btn-link mb-0 btn-dropleft" type="button" id="multiDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #525f7f">
                                            <i class="fa fa-ellipsis-v"></i>
                                        <div class="ripple-container"></div>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="multiDropdownMenu">
                                            <a href="shortlink/qrcode/` + full.id_short_link + `" class="dropdown-item table-btn-qrcode" style="cursor: pointer">
                                                <div style="width: 24px; display: inline-block">   
                                                    <i class="fas fa-qrcode"></i>
                                                </div>
                                                <span>${GLOBAL_LANG.shortlink_dt_columndefs_target2_title_qrcode}</span>
                                            </a>
                                            <a id="` + full.id_short_link + `" href='#' class="dropdown-item table-btn-edit" style="cursor: pointer">
                                                <div style="width: 24px; display: inline-block">     
                                                    <i class="far fa-edit"></i>
                                                </div>
                                                <span>${GLOBAL_LANG.shortlink_dt_columndefs_target2_title_edit}</span>
                                            </a>
                                            <a id="` + full.id_short_link + `" href='#' class="dropdown-item table-btn-delete" style="cursor: pointer">
                                                <div style="width: 24px; display: inline-block"> 
                                                    <i class="far fa-trash-alt"></i>
                                                </div>
                                                <span>${GLOBAL_LANG.shortlink_dt_columndefs_target2_title_delete}</span>
                                            </a>
                                        </div>
                                    </div>`
                    }

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

            const shortlink = {
                search: $("#search").val(),
            }

            let filter = localStorage.getItem("filters");

            filter = filter ? JSON.parse(filter) : {};
            filter.shortlink = shortlink;

            localStorage.setItem("filters", JSON.stringify(filter));
        }
    });
}


document.querySelectorAll(".btn.btn-primary, .btn.btn-danger").forEach(function (button) {
    button.addEventListener("click", function () {
        if (Filters) {
            Filters.btn_back = true;
            localStorage.setItem("filters", JSON.stringify(Filters));
        }
    });
});


$(document).ready(function () {

    $("#search").on("keyup", function (event) {
        if (event.which == 13) {
            find()
        }
    });
    find();


    $("#datatable-basic").on("click", ".table-btn-edit", function () {
        window.location.href = "shortlink/edit/" + this.id;
    });



    $("#datatable-basic").on("click", ".table-btn-delete", function () {
        swal({
            title: GLOBAL_LANG.shortlink_alert_delete_title,
            text: GLOBAL_LANG.shortlink_alert_delete_text,
            type: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            reverseButtons: true,
            confirmButtonClass: "btn btn-success",
            confirmButtonText: GLOBAL_LANG.shortlink_alert_delete_confirmButtonText,
            cancelButtonClass: "btn btn-danger",
            cancelButtonText: GLOBAL_LANG.shortlink_alert_delete_cancelButtonText,
        }).then(t => {
            if (t.value == true) {
                $.post("shortlink/delete/" + this.id, function (data) {
                    t.value && swal({
                        title: GLOBAL_LANG.shortlink_alert_delete_two_title,
                        text: GLOBAL_LANG.shortlink_alert_delete_two_text,
                        type: "success",
                        buttonsStyling: !1,
                        confirmButtonClass: "btn btn-success"
                    });
                    $("#datatable-basic").DataTable().ajax.reload(null, false);
                });
            }
        });
    });


    $('#sendEmailExport').on('click', function () {
        let column = $("#datatable-basic").DataTable().order()[0][0];
        let order = $("#datatable-basic").DataTable().order()[0][1];

        switch (column) {
            case 0:
                column = "Name";
                break;

            default:
                column = "Link";
                break;
        }

        $.get(`/export/xlsx?
            search=${$('#search').val()}
            &column=${column}
            &order=${order}
            &type=shortlink`, function (response) {

            if (response != "Error") {
                Swal.fire({
                    title: GLOBAL_LANG.shortlink_alert_export_title,
                    text: GLOBAL_LANG.shortlink_alert_export_text,
                    type: 'success',
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: GLOBAL_LANG.shortlink_alert_export_confirmButtonText
                });
            }
        });
    });

    $("form").submit(event => event.preventDefault());
    if (document.querySelector(".btn-success")) {
        document.querySelector(".btn-success").addEventListener("click", submit);
    }

});


function submit() {


    const checkFild = validateFilds();

    if (checkFild) {
        document.querySelector(".btn-success").disabled = true;
        $("form").unbind('submit').submit();
    }
}


function validateFilds() {

    let input_name = document.getElementById('input-name');
    let input_phone = document.getElementById('input-phone');

    let name = false;
    let phone = false;

    if (input_name.value.length > 0) {
        document.getElementById('name-field').style.display = 'none';
        name = true;
    } else {
        document.getElementById('name-field').style.display = 'block';
    }

    if (input_phone.value.length > 0) {
        document.getElementById('phone-field').style.display = 'none';
        phone = true;
    } else {
        document.getElementById('phone-field').style.display = 'block';
    }

    if (name && phone) {
        return true;
    } else {
        return false;
    }
}