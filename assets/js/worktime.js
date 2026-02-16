const Filters = JSON.parse(localStorage.getItem("filters")) || null;


$(document).ready(function () {
    var maskBehavior = function (val) {
        val = val.split(":");
        return (parseInt(val[0]) > 19) ? "HZ:M0" : "H0:M0";
    }

    spOptions = {
        onKeyPress: function (val, e, field, options) {
            field.mask(maskBehavior.apply({}, arguments), options);
        },
        translation: {
            'H': { pattern: /[0-2]/, optional: false },
            'Z': { pattern: /[0-3]/, optional: false },
            'M': { pattern: /[0-5]/, optional: false }
        }
    };

    $('.time').mask(maskBehavior, spOptions);


    $("#search").on("keyup", function (event) {
        if (event.which == 13) {
            find();
        }
    });
    find();


    $("#datatable-basic").on("click", ".table-btn-delete", function () {
        swal({
            title: GLOBAL_LANG.setting_worktime_alert_delete_title,
            text: GLOBAL_LANG.setting_worktime_alert_delete_text,
            type: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            reverseButtons: true,
            confirmButtonClass: "btn btn-success",
            confirmButtonText: GLOBAL_LANG.setting_worktime_alert_delete_confirmButtonText,
            cancelButtonClass: "btn btn-danger",
            cancelButtonText: GLOBAL_LANG.setting_worktime_alert_delete_cancelButtonText,
        }).then(t => {
            if (t.value == true) {
                $.post("worktime/delete/" + this.id, function (data) {
                    console.log(data)

                    if (data == null) {
                        t.value && swal({
                            title: GLOBAL_LANG.setting_worktime_alert_delete_two_title,
                            text: GLOBAL_LANG.setting_worktime_alert_delete_two_text,
                            type: "success",
                            buttonsStyling: !1,
                            confirmButtonClass: "btn btn-success"
                        });
                        $("#datatable-basic").DataTable().ajax.reload(null, false);
                    } else {

                        let name = "", total = "";

                        for (let i = 0; i < data.length; i++) {
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
                            title: GLOBAL_LANG.setting_worktime_delete_three_title,
                            html: `${GLOBAL_LANG.setting_worktime_delete_three_text_first_part} ${name} ${total == "" ? "" : GLOBAL_LANG.setting_worktime_delete_three_text_second_part} ${total} ${GLOBAL_LANG.setting_worktime_delete_three_text_third_part}`,
                            type: 'warning',
                            buttonsStyling: !1,
                            confirmButtonClass: "btn btn-success"
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

    let input_worktime = true;
    const input_enabled = verifyValidInput();
    const input_name = formValidation({ field: document.getElementById("input-name"), text: GLOBAL_LANG.setting_worktime_subtitle_label, required: true, min: 3, max: 100 });


    if (input_enabled.length > 0) {
        input_enabled.forEach(function (elemento, indice) {
            const id_elm = elemento.attr('id');

            if (id_elm.includes("start")) {
                input_worktime = formValidation({ field: document.getElementById(id_elm), text: GLOBAL_LANG.setting_worktime_start_placeholder, required: true });
            } else {
                input_worktime = formValidation({ field: document.getElementById(id_elm), text: GLOBAL_LANG.setting_worktime_end_placeholder, required: true });
            }
        });
    }

    if (input_name && input_worktime) {
        document.querySelector(".btn-success").disabled = true;
        $("form").unbind('submit').submit();
    }
}


function verifyValidInput() {
    $('.alert-field-validation').hide();
    let invalid_elements = [];

    $("#worktime input").each(function () {
        var element = $(this);
        if ($(this).hasClass("time") && !element.attr("disabled")) {
            if (element.val().length != 5) {
                element.addClass('is-invalid');
                invalid_elements.push(element);
            }
            else {
                element.removeClass('is-invalid');
            }
        }

    });

    return invalid_elements;
}


function checkFilter() {
    if (Filters) {

        if (!Filters.btn_back) return

        if (Filters.worktime.search) {
            document.getElementById("search").value = Filters.worktime.search;
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
            url: "worktime",
            type: "POST",
            data: {
                text: $("#search").val(),
                csrf_talkall: Cookies.get("csrf_cookie_talkall")
            }
        },
        columns: [
            {
                mData: 'name'
            }
        ],
        columnDefs: [
            {
                targets: 1,
                render: function (data, type, full, meta) {

                    return `<div class="dropleft">
                                    <button class="btn btn-link mb-0 btn-dropleft" type="button" id="multiDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #525f7f">
                                        <i class="fa fa-ellipsis-v"></i>
                                    <div class="ripple-container"></div>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="multiDropdownMenu">
                                        <a href="worktime/edit/` + full.id_work_time + `" class="dropdown-item table-btn-edit" style="cursor: pointer">
                                            <div style="width: 24px; display: inline-block"> 
                                                <i class="far fa-edit"></i>
                                            </div>
                                            <span>${GLOBAL_LANG.setting_worktime_dt_columndefs_target1_title_edit}</span>
                                        </a>
                                        <a id="` + full.id_work_time + `" href='#' class="dropdown-item table-btn-delete" style="cursor: pointer">
                                            <div style="width: 24px; display: inline-block"> 
                                                <i class="far fa-trash-alt"></i>
                                            </div>
                                            <span>${GLOBAL_LANG.setting_worktime_dt_columndefs_target1_title_delete}</span>
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
            if (settings.json.recordsTotal == 0) {
                $("#modalExport").attr('disabled', true);
            } else {
                $("#modalExport").attr('disabled', false);
            }

            const worktime = {
                search: $("#search").val(),
            }

            let filter = localStorage.getItem("filters");

            filter = filter ? JSON.parse(filter) : {};
            filter.worktime = worktime;

            localStorage.setItem("filters", JSON.stringify(filter));
        }
    });
}

function inputGroup(id) {

    var start = "#" + id + "-start";
    var end = "#" + id + "-end";

    if ($(start).attr("disabled") == "disabled") {
        $(start).removeAttr("disabled");
    }
    else {
        $(start).attr("disabled", "disabled");
        $(start).removeClass('is-invalid');
    }

    if ($(end).attr("disabled") == "disabled") {
        $(end).removeAttr("disabled");
    }
    else {
        $(end).attr("disabled", "disabled");
        $(end).removeClass('is-invalid');
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

        default:
            break;
    }

    $.get(`/export/xlsx?
        search=${$('#search').val()}
        &column=${column}
        &order=${order}
        &type=workTime`, function (response) {

        if (response != "Error") {
            Swal.fire({
                title: GLOBAL_LANG.setting_worktime_alert_export_title,
                text: GLOBAL_LANG.setting_worktime_alert_export_text,
                type: 'success',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: GLOBAL_LANG.setting_worktime_alert_export_confirmButtonText,
            });
        }
    });
}

