function find() {
    $('#datatable-basic').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "broadcast/find",
            "type": "POST",
            data: {
                text: $("#search").val(),
                channel: $('#verify-select1').val() == "2" ? "" : $('#multiselect1').val(),
                status: $('#verify-select2').val() == "2" ? "" : $('#multiselect2').val(),
                csrf_talkall: Cookies.get("csrf_cookie_talkall"),
            },
        },
        "columns": [
            {
                mData: 'channel'
            },
            {
                mData: 'schedule'
            },
            {
                mData: 'send_timestamp'
            },
            {
                mData: 'status'
            }
        ],
        columnDefs: [
            {
                targets: 4,
                render: function (data, type, full, meta) {

                    return `<div class="dropleft">
                                    <button class="btn btn-link mb-0 btn-dropleft" type="button" id="multiDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #525f7f">
                                        <i class="fa fa-ellipsis-v"></i>
                                    <div class="ripple-container"></div>
                                    </button>
                                </div>`
                }
            },
            {
                orderable: true,
                targets: [0, 1, 2]
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
    });
}


$(document).ready(function () {

    $('#search').on('keyup', (e) => {
        if (e.which == 13) {
            document.getElementById("input-search").value = e.target.value;
            find();
        }
    });

    $("#modalFilter").one("click", () => modalFilter());

    find();
});

function modalFilter() {

    if (!$(".msf_multiselect_container").hasClass("msf_multiselect_container")) {

        const msf_multiselect_container = document.querySelectorAll(".msf_multiselect_container");

        for (elm of msf_multiselect_container) elm.remove();

        var select1 = new MSFmultiSelect(
            document.querySelector('#multiselect1'), {
            theme: 'theme2',
            selectAll: true,
            searchBox: true,
            width: 'auto',
            height: 47,
            onChange: function (checked, value, instance) {
                if (select1 == "") select2 = value;

                if (checked == false) {
                    $(".ignore.add").children().prop('checked', false);
                };
            },
        });

        var select2 = new MSFmultiSelect(
            document.querySelector('#multiselect2'), {
            theme: 'theme2',
            selectAll: true,
            searchBox: true,
            width: 'auto',
            height: 47,
            onChange: function (checked, value, instance) {
                if (select1 == "") select2 = value;

                if (checked == false) {
                    $(".ignore.add").children().prop('checked', false);
                };
            },
        });
    }

    const search = document.getElementById("search");

    const check_search = document.getElementById("check-search");
    const input_search = document.getElementById("input-search");

    const check_select1 = document.getElementById("check-select1");
    const mult_select1 = document.getElementById("mult-select1");
    const verify_select1 = document.getElementById("verify-select1");

    const check_select2 = document.getElementById("check-select2");
    const mult_select2 = document.getElementById("mult-select2");
    const verify_select2 = document.getElementById("verify-select2");

    const btn_search = document.getElementById("btn-search");

    check_search.checked = true;
    input_search.style.display = "block";

    check_search.addEventListener("click", () => {
        if (check_search.checked) {
            input_search.style.display = "block";
        }
        else {
            input_search.value = "";
            input_search.style.display = "none";
        }
    });

    check_select1.addEventListener("click", () => {
        if (check_select1.checked) {
            mult_select1.style.display = "block";
            verify_select1.value = "1";
        }
        else {
            mult_select1.style.display = "none";
            verify_select1.value = "2";
        }
    });

    check_select2.addEventListener("click", () => {
        if (check_select2.checked) {
            mult_select2.style.display = "block";
            verify_select2.value = "1";
        }
        else {
            mult_select2.style.display = "none";
            verify_select2.value = "2";
        }
    });

    btn_search.addEventListener("click", () => {
        search.value = input_search.value;
        find();
    });

    for (elm of logger) {
        elm.style.paddingLeft = "15px";
    }

}
