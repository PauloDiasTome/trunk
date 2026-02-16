const Filters = JSON.parse(localStorage.getItem("filters")) || null;


function checkFilter() {
    if (Filters) {

        if (!Filters.btn_back) return

        if (Filters.user_call.search) {
            document.getElementById("search").value = Filters.user_call.search;
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
            url: "usercall/find",
            type: "POST",
            data: {
                text: $("#search").val(),
                channel: $('#multiselect2').val() == "" ? "" : $('#multiselect2').val(),
                situation: $('#select-situation').val(),
                dt_start: $('#dt-start').val(),
                dt_end: $('#dt-end').val(),
                csrf_talkall: Cookies.get("csrf_cookie_talkall"),
            }
        },
        columns: [
            {
                mData: 'name'
            },
            {
                mData: 'limit'
            },
        ],
        columnDefs: [
            {
                "targets": 2,
                "render": function (data, type, full, meta) {

                    return `<div class="dropleft">
                                    <button class="btn btn-link mb-0 btn-dropleft" type="button" id="multiDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #525f7f">
                                            <i class="fa fa-ellipsis-v"></i>
                                       <div class="ripple-container"></div>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="multiDropdownMenu">
                                        <a id="` + full.id_user_call + `" href='#' class="dropdown-item table-btn-edit" style="cursor: pointer">
                                            <div style="width: 24px; display: inline-block"> 
                                                <i class="far fa-edit"></i>
                                            </div>
                                            <span>${GLOBAL_LANG.usercall_dt_columndefs_target2_title_edit}</span>
                                        </a>
                                        <a id="` + full.id_user_call + `" href='#' class="dropdown-item table-btn-delete" style="cursor: pointer">
                                            <div style="width: 24px; display: inline-block"> 
                                                <i class="far fa-trash-alt"></i>
                                            </div>
                                            <span>${GLOBAL_LANG.usercall_dt_columndefs_target2_title_delete}</span>
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

            const user_call = {
                search: $("#search").val(),
            }

            let filter = localStorage.getItem("filters");

            filter = filter ? JSON.parse(filter) : {};
            filter.user_call = user_call;

            localStorage.setItem("filters", JSON.stringify(filter));
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


    $("#datatable-basic").on("click", ".table-btn-edit", function () {
        window.location.href = "usercall/edit/" + this.id;
    });


    $("#datatable-basic").on("click", ".table-btn-delete", function () {
        swal({
            title: GLOBAL_LANG.usercall_alert_delete_title,
            text: GLOBAL_LANG.usercall_alert_delete_text,
            type: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            reverseButtons: true,
            confirmButtonClass: "btn btn-success",
            confirmButtonText: GLOBAL_LANG.usercall_alert_delete_confirmButtonText,
            cancelButtonClass: "btn btn-danger",
            cancelButtonText: GLOBAL_LANG.usercall_alert_delete_cancelButtonText,
        }).then(t => {
            if (t.value == true) {
                $.post("usercall/delete/" + this.id, function (data) {
                    if (data.length < 1) {
                        t.value && swal({
                            title: GLOBAL_LANG.usercall_alert_delete_two_title,
                            text: GLOBAL_LANG.usercall_alert_delete_two_text,
                            type: "success",
                            buttonsStyling: !1,
                            confirmButtonClass: "btn btn-success"
                        });
                        $("#datatable-basic").DataTable().ajax.reload(null, false);
                    } else {

                        let name = "";
                        let total = "";
                        let i = 0;

                        for (i; i < data.length; i++) {
                            if (i < 2) {
                                name += data[i].name + ', '
                            }
                        }

                        name = name.substring(0, name.length - 2);

                        if (data.length > 2) {
                            total = data.length - 2;
                            total = "+" + total
                        }

                        t.value && swal({
                            title: GLOBAL_LANG.usercall_alert_delete_three_title,
                            html: `${GLOBAL_LANG.usercall_alert_delete_three_text_first_part}<span>  ${name} </span><span>${total == "" ? "" : GLOBAL_LANG.usercall_alert_delete_three_text_second_part} ${total}  </span>${GLOBAL_LANG.usercall_alert_delete_three_text_third_part}`,
                            type: 'warning',
                            buttonsStyling: !1,
                            confirmButtonClass: "btn btn-primary"
                        });

                    }
                });
            }
        });
    });


    $('#sendEmailExport').on('click', () => modalExport());
    $("form").submit(event => event.preventDefault());
    if (document.querySelector(".btn-success")) {
        document.querySelector(".btn-success").addEventListener("click", submit);
    }

});


function submit() {

    const input_name = formValidation({ field: document.getElementById("input-name"), text: GLOBAL_LANG.usercall_nome, required: true, min: 3, max: 30 });
    const input_limit = formValidation({ field: document.getElementById("input-limit"), text: GLOBAL_LANG.usercall_simultaneous_service_limit, required: true, min: 1, max: 3, value_between: [1, 100] });

    if (input_name && input_limit) {
        document.querySelector(".btn-success").disabled = true;
        $("form").unbind('submit').submit();
    }
}


document.querySelectorAll(".btn.btn-primary, .btn.btn-danger").forEach(function (button) {
    button.addEventListener("click", function () {
        if (Filters) {
            Filters.btn_back = true;
            localStorage.setItem("filters", JSON.stringify(Filters));
        }
    });
});


function modalExport() {
    let column = $("#datatable-basic").DataTable().order()[0][0];
    let order = $("#datatable-basic").DataTable().order()[0][1];

    switch (column) {
        case 0:
            column = "name";
            break;
    }

    $.get(`/export/xlsx?
        search=${$('#search').val()}
        &column=${column}
        &order=${order}
        &type=userCall`, function (response) {

        if (response != "Error") {
            Swal.fire({
                title: GLOBAL_LANG.usercall_alert_export_title,
                text: GLOBAL_LANG.usercall_alert_export_text,
                type: 'success',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: GLOBAL_LANG.usercall_alert_export_confirmButtonText
            });
        }
    });
}


function validNumber(evt) {
    if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
        evt.preventDefault();
    }
}

if (document.querySelector("#input-limit") != null) {
    document.querySelector("#input-limit").addEventListener("keypress", validNumber);
}