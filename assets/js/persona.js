"use strict";

var myIdExportContacts;
let SelectAll = false;
let contactsData = [];
let searchContacts = null;
const Components = new ComponentsDom();
const Filters = JSON.parse(localStorage.getItem("filters"));


function checkFilter() {
    if (Filters) {

        if (!Filters.btn_back) return

        if (Filters.persona.search) {
            document.getElementById("search").value = Filters.persona.search;
        }

        Filters.btn_back = null;
        localStorage.setItem("filters", JSON.stringify(Filters));
    }
}

checkFilter(Filters);

function find() {
    $('#datatable-basic').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "persona/find",
            type: "POST",
            data: {
                text: $("#search").val(),
                csrf_talkall: Cookies.get("csrf_cookie_talkall")
            }
        },
        columns: [
            {
                mData: 'name'
            },
            {
                mData: 'creation'
            },
            {
                mData: 'channel'
            },
            {
                mData: 'participant_count'
            },
        ],
        columnDefs: [
            {
                targets: 0,
                render: function (data, type, full, meta) {
                    const persona_container = Components.div({ class: "kt-user-card-v2" });
                    const name = Components.div({ class: "kt-user-card-v2__details" });
                    const img = Components.div({ class: "kt-user-card-v2__pic" });

                    function checkIfSvg(url) {

                        let isSvg = false;
                        try {
                            const xhr = new XMLHttpRequest();
                            xhr.open('GET', url, false);
                            xhr.setRequestHeader('Accept', 'image/svg+xml');
                            xhr.send();

                            if (xhr.status === 200 && xhr.responseText.includes('<svg xmlns="http://www.w3.org/2000/svg"')) {
                                isSvg = true;
                            }
                        } catch (e) {
                            console.error('Erro ao verificar a URL:', e);
                        }
                        return isSvg;
                    }

                    const profileUrl = full.profile ? full.profile : "assets/img/group.svg";
                    const isSvg = checkIfSvg(profileUrl);

                    const imgSrc = isSvg
                        ? (full.key_id?.startsWith("IN") ? "assets/icons/panel/group_inactive.svg" : "assets/img/group.svg")
                        : profileUrl;

                    img.appendChild(Components.img({
                        src: imgSrc,
                        class: "avatar rounded-circle mr-3",
                        style: "object-fit: cover; width: 55px; height: 55px"
                    }));

                    name.appendChild(Components.span({ class: "kt-user-card-v2__name", text: full.name }));
                    persona_container.appendChild(img);
                    persona_container.appendChild(name);

                    if (full.key_id && full.key_id.startsWith("IN")) {
                        persona_container.appendChild(Components.i({
                            class: "far fa-question-circle fa-xs",
                            title: GLOBAL_LANG.persona_tooltip_update,
                            style: "margin-left: 5px; font-size: 13px; margin-top: 2px;"
                        }));
                    }

                    return persona_container.outerHTML;
                }
            },
            {
                targets: 1,
                render: function (data, type, full, meta) {

                    return full.creation;
                }
            },
            {
                targets: 3,
                render: function (data, type, full, meta) {
                    if (full.participant_count > 1) {
                        return full.participant_count + GLOBAL_LANG.persona_row_subscribers;
                    } else {
                        return full.participant_count + GLOBAL_LANG.persona_row_subscriber;
                    }
                }
            },
            {
                targets: 4,
                render: function (data, type, full, meta) {
                    const options_container = Components.div({ class: "dropleft" });
                    const options_icon = Components.button({ class: "btn btn-link mb-0 btn-dropleft", style: "color: #525f7f", customAttribute: ["data-toggle", "dropdown", "aria-haspopup", "true", "aria-expanded", "false"] });
                    const dropdown_menu = Components.div({ class: "dropdown-menu" });

                    const container_for_export_option = Components.a({ id: full.id_group_contact, class: "dropdown-item table-btn-export-contacts", style: "cursor: pointer" });
                    const export_icon = Components.div({ style: "width: 24px; display: inline-block" });

                    const container_for_edit_option = Components.a({ id: full.id_group_contact, class: "dropdown-item table-btn-edit", style: "cursor: pointer" });
                    const edit_icon = Components.div({ style: "width: 24px; display: inline-block" });

                    const container_for_delete_option = Components.a({ id: full.id_group_contact, class: "dropdown-item table-btn-delete", style: "cursor: pointer" });
                    const delete_icon = Components.div({ style: "width: 24px; display: inline-block" });

                    export_icon.appendChild(Components.i({ class: "fa fa-file-export" }));
                    edit_icon.appendChild(Components.i({ class: "far fa-edit" }));
                    delete_icon.appendChild(Components.i({ class: "far fa-trash-alt" }));

                    container_for_export_option.appendChild(export_icon);
                    container_for_export_option.appendChild(Components.span({ text: GLOBAL_LANG.persona_column_action_export }));

                    container_for_edit_option.appendChild(edit_icon);
                    container_for_edit_option.appendChild(Components.span({ text: GLOBAL_LANG.persona_column_action_edit }));

                    container_for_delete_option.appendChild(delete_icon);
                    container_for_delete_option.appendChild(Components.span({ text: GLOBAL_LANG.persona_column_action_delete }));

                    options_icon.appendChild(Components.i({ class: "fa fa-ellipsis-v" }));

                    dropdown_menu.appendChild(container_for_export_option);
                    dropdown_menu.appendChild(container_for_edit_option);
                    dropdown_menu.appendChild(container_for_delete_option);

                    options_container.appendChild(options_icon);
                    options_container.appendChild(dropdown_menu);

                    return options_container.outerHTML;
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
        searching: false,
        paginate: true,
        info: true,
        language: {
            url: `${document.location.origin}/assets/lang/${localStorage.getItem("language")}.json`
        },

        drawCallback: function (settings) {
            document.getElementById("modalExport").disabled = settings.json.recordsTotal == 0 ? true : false;

            const persona = {
                search: $("#search").val(),
            }

            let filter = localStorage.getItem("filters");

            filter = filter ? JSON.parse(filter) : {};
            filter.persona = persona;

            localStorage.setItem("filters", JSON.stringify(filter));
        }
    });
}


$(document).ready(async function () {

    $("#search").on("keyup", function (event) {
        if (event.which == 13)
            find();
    });

    find();
    await getContactsData();

    $("body").on("mouseover", ".transition-effect", () => {
        $("#add-profile").css({ "display": "block" });
        $(".picture-persona-title").css({ "display": "block" });
        $(".transition-effect img").css({ "opacity": "0.8" });
        $(".transition-effect img").css({ "filter": "brightness(50%)" });
    });

    $("body").on("mouseenter", ".transition-effect", () => {
        $("#add-profile").css({ "display": "block" });
        $(".picture-persona-title").css({ "display": "block" });
        $(".transition-effect img").css({ "opacity": "0.8" });
        $(".transition-effect img").css({ "opacity": "0.8" });
        $(".transition-effect img").css({ "filter": "brightness(40%)" });
    });

    $("body").on("mouseout", ".transition-effect", () => {
        $("#add-profile").css({ "display": "none" });
        $(".picture-persona-title").css({ "display": "none" });
        $(".transition-effect img").css({ "opacity": "1" });
        $(".transition-effect img").css({ "opacity": "1" });
        $(".transition-effect img").css({ "filter": "brightness(100%)" });
    });

    $("#datatable-basic").on("click", ".table-btn-export-contacts", function () {
        myIdExportContacts = "";
        myIdExportContacts = this.id;

        $("#modal-icon-export-contacts").modal();
        console.log('OPEN MODEL EXPORT CONTACTS: ', this);
    });

    $(".iconSendEmailExportContacts").on("click", () => modalIconExportContacts());

    $("#datatable-basic").on("click", ".table-btn-edit", function () {
        window.location.href = "persona/edit/" + this.id;
    });

    $("#datatable-basic").on("click", ".table-btn-delete", function () {
        swal({
            title: GLOBAL_LANG.persona_alert_delete_title,
            text: GLOBAL_LANG.persona_alert_delete_text,
            type: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            reverseButtons: true,
            confirmButtonClass: "btn btn-success",
            confirmButtonText: GLOBAL_LANG.persona_alert_delete_confirmButtonText,
            cancelButtonClass: "btn btn-danger",
            cancelButtonText: GLOBAL_LANG.persona_alert_delete_cancelButtonText,
        }).then(t => {
            if (t.value == true) {
                $.post(`persona/delete/${this.id}`, function (data) {
                    if (data) {
                        t.value && swal({
                            title: GLOBAL_LANG.persona_alert_delete_sucess,
                            text: "",
                            type: "success",
                            buttonsStyling: !1,
                            confirmButtonClass: "btn btn-primary",
                        });
                        $("#datatable-basic").DataTable().ajax.reload(null, false);
                    }
                })
            }
        });
    });
});


function optionSelectAll(checkbox_select_all) {
    const all_checkboxes = document.querySelectorAll("[type=checkbox]");

    if (checkbox_select_all.checked == true) {
        SelectAll = false;
        removeContactsTable();
        all_checkboxes.forEach(checkbox => checkbox.checked = false);
    } else {
        SelectAll = true;
        all_checkboxes.forEach(checkbox => checkbox.checked = true);
    }
}


function createOptionSelectAll() {
    document.getElementById("select_all")?.remove();

    const select_all = Components.div({ class: "select_all", id: "select_all" });
    select_all.addEventListener("click", () => optionSelectAll(checkbox));

    const checkbox_container = Components.div({ class: "custom-control custom-checkbox", style: "margin-left: 15px" });
    const checkbox = Components.checkbox({ class: "custom-control-input", id: "checkbox_select_all" });

    const label = Components.label({ class: "custom-control-label", text: GLOBAL_LANG.persona_participants_select_all });

    checkbox_container.appendChild(checkbox);
    checkbox_container.appendChild(label);

    select_all.appendChild(checkbox_container);

    if (SelectAll)
        checkbox.checked = true;

    return select_all;
}


function createGifLoading(type_load) {

    if (type_load == "list_center" || type_load == "list_bottom") {
        const loading_container = Components.div({ class: "modal-load" });
        const loading = Components.img({ src: document.location.origin + "/assets/img/loads/loading_2.gif" });
        loading_container.appendChild(loading);

        if (type_load == "list_center") {
            loading.style.height = "50px";
            document.querySelector(".main-modal")?.appendChild(loading_container);
        }

        if (type_load == "list_bottom") {
            loading.style.height = "40px";
            loading_container.style.padding = "1%";
            document.querySelector(".contact-list")?.appendChild(loading_container);
            $(".contact-list").scrollTop($(".contact-list").prop("scrollHeight"));
        }
    }

    if (type_load == "table_center" || type_load == "table_bottom") {
        const contacts_table = document.getElementById("contacts-table");
        const loading_container = Components.div({ class: "modal-load" });
        const loading = Components.img({ src: document.location.origin + "/assets/img/loads/loading_2.gif" });

        if (type_load == "table_center") {
            loading.style.height = "50px";
            loading_container.style.height = "100%";
        }

        if (type_load == "table_bottom")
            loading.style.height = "40px";

        loading_container.appendChild(loading);
        contacts_table.appendChild(loading_container);
        document.getElementById("contacts-table").style.display = "block";
    }
}


function contactsAlreadySelected(data, contact_type) {

    if (contact_type == "list") {

        for (let i = 0; i < data.length; i++) {
            const contact_already_selected = contactsData.find((contact) => contact.id_contact == data[i].id);
            const checkbox = data[i].children[0].children[0];

            if (contact_already_selected)
                checkbox.checked = true;
        }
    }

    if (contact_type == "table") {

        for (let i = 0; i < contactsData.length; i++) {
            const index_contact_to_remove = data.findIndex(contact => contact.id_contact == contactsData[i].id_contact);

            if (index_contact_to_remove !== -1)
                data.splice(index_contact_to_remove, 1);
        }

        return data;
    }
}


function removeContact(element) {
    if (this) {

        this.parentNode.remove();
        contactsData = contactsData.filter(contact => contact.id_contact !== this.parentNode.getAttribute("data-id"));

    } else if (element.id != "") {

        document.querySelectorAll("#contacts-table-body tr").forEach((item) => {
            const matching = item.getAttribute("data-id") == element.id;
            if (matching) item.remove();
        });

        contactsData = contactsData.filter(contact => contact.id_contact !== element.id);

    } else {

        const id_contact = element.getAttribute("data-id");
        contactsData = contactsData.filter(contact => contact.id_contact !== id_contact);
        element.remove();

    }

    document.getElementById("count").innerHTML = contactsData.length;

    if (document.getElementById("count").innerHTML == "0")
        removeContactsTable();
}


function createCountContacts() {
    document.getElementById("count-contacts")?.remove();

    const container = Components.div({ class: "col-6", id: "count-contacts" });
    const title = Components.span({ class: "size-14 float-right", text: GLOBAL_LANG.persona_number_of_contacts });
    const count_contacts = Components.span({ class: "size-14 float-right", text: contactsData.length, style: "margin-left: 4px", id: "count" });

    container.appendChild(count_contacts);
    container.appendChild(title);

    document.getElementById("table-contacts-footer").appendChild(container);
}


function createContactsTable(data) {
    return new Promise((resolve, reject) => {

        let tbody = "";
        let contacts = "";

        if (Array.isArray(data)) {
            data = contactsAlreadySelected(data, "table");

            contactsData = contactsData.concat(data);
            contacts = contactsData.slice(0, 50);

            document.getElementById("contacts-table-body")?.remove();
            document.getElementById("contacts-table").scrollTop = 0;
        } else {
            //? Quando o scroll dentro da tabela é acionado, pega os próximos 50 contatos a serem renderizados.
            contacts = contactsData.slice(data.length, data.length + 50);
        }

        document.getElementsByClassName("modal-load")[0]?.remove();
        const contact_table = document.getElementById("persona-contact-table");

        if (document.getElementById("contacts-table-body")) {
            tbody = document.getElementById("contacts-table-body");
        } else {
            tbody = document.createElement("tbody");
            tbody.id = "contacts-table-body";
        }

        for (let i = 0; i < contacts.length; i++) {
            const tr = document.createElement("tr");
            tr.setAttribute("data-id", contacts[i].id_contact);

            const name = document.createElement("td");
            name.innerText = Util.doTruncarStr(contacts[i].name, 30) ?? "";

            const key_remote_id = document.createElement("td");
            key_remote_id.innerText = contacts[i].key_remote_id.split("-")[0];

            const email = document.createElement("td");
            email.innerText = contacts[i].email ? Util.doTruncarStr(contacts[i].email, 30) : "";

            const remove_contact = document.createElement("td");
            remove_contact.appendChild(Components.i({ class: "fas fa-times remove-contact" }));
            remove_contact.addEventListener("click", removeContact);

            tr.appendChild(name);
            tr.appendChild(key_remote_id);
            tr.appendChild(email);
            tr.appendChild(remove_contact);

            tbody.appendChild(tr);
        }

        contact_table.appendChild(tbody);
        createCountContacts();
        document.getElementById("contacts-table").style.display = "block";
        document.getElementById("btn-success").removeAttribute("disabled");
        document.getElementById("contacts-table").addEventListener("scroll", checkScroll);

        resolve();
    });
}


async function getContactsFromBase(limit = false) {
    const contacts = document.getElementsByClassName("contact-list")[0]?.children;

    const formData = new FormData();
    formData.append("limit", limit);
    formData.append("search", document.getElementById("search-contact").value);
    formData.append("offset", contacts != null ? Array.from(contacts).slice(1).length : 0);
    formData.append("id_channel", document.getElementById("select-channel").value);
    formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

    const response = await fetch(`${document.location.origin}/persona/list/contact`, {
        method: "POST",
        body: formData
    });

    return await response.json();
}


async function getContactsData() {
    const id_persona = window.location.href.split("edit/")[1];

    if (id_persona != undefined) {
        createGifLoading("table_center");
        document.getElementById("add-contacts")?.removeEventListener("click", handleClickAddContacts);
        document.getElementById("import-contacts")?.removeEventListener("click", handleClickImportContacts);

        const formData = new FormData();
        formData.append("id_persona", id_persona);
        formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

        const response = await fetch(`${document.location.origin}/persona/list/participants`, {
            method: "POST",
            body: formData
        });

        createContactsTable(await response.json())
            .then(() => {
                document.getElementById("add-contacts")?.addEventListener("click", handleClickAddContacts);
                document.getElementById("import-contacts")?.addEventListener("click", handleClickImportContacts);
            })
    }
}


async function handleContactSelection(checkbox) {
    document.getElementById("btn-success").disabled = "true";

    if (SelectAll) {
        document.getElementById("add-contacts")?.removeEventListener("click", handleClickAddContacts);
        document.getElementById("import-contacts")?.removeEventListener("click", handleClickImportContacts)

        if (document.getElementById("contacts-table-body") == null)
            createGifLoading("table_center");

        const data = await getContactsFromBase();

        createContactsTable(data)
            .then(() => {
                document.getElementById("add-contacts")?.addEventListener("click", handleClickAddContacts);
                document.getElementById("import-contacts")?.addEventListener("click", handleClickImportContacts);
            })
    } else {
        const data = new Array;

        if (checkbox.length > 0) {

            for (let i = 0; i < checkbox.length; i++) {
                const contact = checkbox[i].parentNode.parentNode;

                data.push({
                    id_contact: contact.id,
                    key_remote_id: contact.getElementsByClassName("contact")[0].children[1].innerHTML,
                    email: contact.getElementsByClassName("contact")[0].getAttribute("data-email"),
                    name: contact.getElementsByClassName("contact")[0].children[0].innerHTML
                });
            }

            createContactsTable(data);
        } else {
            document.getElementById("btn-success").removeAttribute("disabled");
        }
    }
}


function uncheckSelectAll() {
    const count_unchecked_contact = document.querySelectorAll(".contact-list [type=checkbox]:not(:checked)").length;
    SelectAll = false;

    if (count_unchecked_contact > 0)
        document.querySelector("#select_all [type=checkbox]").checked = false;
}


function removeContactsTable() {
    contactsData = [];

    document.getElementById("contacts-table").style.display = "none";
    document.getElementById("contacts-table-body")?.remove();
    document.getElementById("count-contacts")?.remove();

    uncheckSelectAll();
}


function selectContact(element) {
    const checkbox = element.querySelector("[type=checkbox]");
    checkbox.checked = !checkbox.checked;

    if (checkbox.checked == true) {
        handleContactSelection([checkbox]);
    } else {
        if (document.getElementById("contacts-table-body"))
            removeContact(element);
    }

    uncheckSelectAll();
}


function createDataNotFoundWarning(element) {
    const body_alert = Components.div({ id: "body-warning" });
    const alert_text = Components.span({ text: GLOBAL_LANG.persona_participants_no_contacts });
    const alert_img = Components.img({ src: location.origin + "/assets/icons/panel/not_found_contact.svg" });

    body_alert.appendChild(alert_img);
    body_alert.appendChild(alert_text);
    element.appendChild(body_alert);
}


function handleScroll(element) {
    if (element.id == "contacts-table") {
        const contacts = document.getElementById("contacts-table-body").children;

        if (contacts.length < contactsData.length) {
            createGifLoading("table_bottom");
            $(".modal-load")[0].scrollIntoView();
            return "table_bottom";
        }

        if (contacts.length == contactsData.length)
            return false;
    }

    if (element.className == "contact-list") {
        createGifLoading("list_bottom");
        return "list_bottom";
    }
}


async function scrollActivated(element) {
    const type_scroll = handleScroll(element);

    if (type_scroll == "list_bottom") {
        const data = await getContactsFromBase(50);
        createContactsList(data);

        if (data.length == 0) {
            $(".contact-list").scrollTop($(".contact-list").prop("scrollHeight"));
        } else {
            const id_first_contact = data.shift().id_contact;
            $("#" + id_first_contact)[0].scrollIntoView();
        }
    }

    if (type_scroll == "table_bottom") {
        const contacts = document.getElementById("contacts-table-body").children;
        createContactsTable(contacts);
    }
}


function checkScroll() {
    const scroll_size = this.scrollTop + this.clientHeight;

    if (Math.ceil(scroll_size) >= this.scrollHeight - 1) {
        this.removeEventListener("scroll", checkScroll);
        scrollActivated(this);
    }
}


function createContactsList(data) {
    const contacts_modal = document.getElementsByClassName("main-modal")[0];
    let contact_list_body = "";

    document.getElementById("body-warning")?.remove();
    document.getElementsByClassName("modal-load")[0]?.remove();

    if (document.getElementsByClassName("contact-list")[0])
        contact_list_body = document.getElementsByClassName("contact-list")[0];
    else
        contact_list_body = Components.div({ class: "contact-list" });

    if (data.length == 0 && contact_list_body.children.length == 0)
        return createDataNotFoundWarning(contacts_modal);

    for (let i = 0; i < data.length; i++) {
        const item = Components.div({ class: "item", id: data[i].id_contact });
        item.addEventListener("click", () => selectContact(item));

        const checkbox_container = Components.div({ class: "custom-control custom-checkbox mb-3", style: "margin-left: 15px; margin-top: 0.8rem" });
        const checkbox = Components.checkbox({ class: "custom-control-input" });
        checkbox.checked = SelectAll;

        const label = Components.label({ class: "custom-control-label" });

        const img_container = Components.div({ style: "padding: 9px 15px 10px 5px" });
        const img = Components.img({ src: data[i].profile, class: "avatar rounded-circle" });

        const contact_container = Components.div({ class: "contact" });
        const contact_name = Components.span({ class: "name", text: Util.doTruncarStr(data[i].name, 33) });
        const key_remote_id = Components.span({ class: "number", text: Util.doTruncarStr(data[i].key_remote_id, 33) });

        if (data[i].email) contact_container.setAttribute("data-email", data[i].email);

        checkbox_container.appendChild(checkbox);
        checkbox_container.appendChild(label);

        img_container.appendChild(img);

        contact_container.appendChild(contact_name);
        contact_container.appendChild(key_remote_id);

        item.appendChild(checkbox_container);
        item.appendChild(img_container);
        item.appendChild(contact_container);

        contact_list_body.appendChild(item);
    }
    contacts_modal.appendChild(contact_list_body);

    if (contact_list_body.children.length != 0)
        contact_list_body.prepend(createOptionSelectAll());

    if (document.getElementById("contacts-table-body") != null)
        contactsAlreadySelected(contact_list_body.children, "list");

    if (data.length != 0)
        contact_list_body.addEventListener("scroll", checkScroll);
}


async function showModalContacts() {
    document.getElementById("body-warning")?.remove();
    document.getElementById("search-contact").value = "";
    document.getElementsByClassName("contact-list")[0]?.remove();
    document.getElementsByClassName("modal-load")[0]?.remove();

    $("#modal-add-contacts").modal("show");
    createGifLoading("list_center");

    const data = await getContactsFromBase(50);
    createContactsList(data);
}


function validateFields(e) {
    const persona_name = document.getElementById("input-name").value;
    const id_channel = document.getElementById("select-channel").value;
    const contacts_table = document.getElementById("contacts-table-body");

    const alert_input_name = document.getElementById("alert-input-name");
    const alert_select_channel = document.getElementById("alert-select-channel");

    alert_input_name.innerText = "";
    alert_select_channel.innerText = "";

    if (e.target.id == "btn-success") {
        if (contacts_table == null && persona_name != "" && id_channel != "")
            return callAlerts("contact_table");

        if (persona_name == "") {
            alert_input_name.innerText = GLOBAL_LANG.persona_alert_name;
        } else if (persona_name.length < 3) {
            alert_input_name.innerText = GLOBAL_LANG.persona_alert_name_min_length;
        }

        if (id_channel == "")
            alert_select_channel.innerText = GLOBAL_LANG.persona_alert_channel;
    }

    if (e.target.id == "add-contacts") {
        if (id_channel == "")
            alert_select_channel.innerText = GLOBAL_LANG.persona_alert_channel_btn_add_contacts;
    }

    if (e.target.id == "import-contacts") {
        if (id_channel == "")
            alert_select_channel.innerText = GLOBAL_LANG.persona_alert_channel_btn_import_contacts;
    }

    if (alert_input_name.innerText != "" || alert_select_channel.innerText != "")
        return false

    return true
}


function searchContact(element) {
    const search = element.target.value.trim();

    clearTimeout(searchContacts);

    searchContacts = setTimeout(async () => {

        if (search.length === 0 || search.length >= 3) {
            document.getElementsByClassName("contact-list")[0]?.remove();
            document.getElementsByClassName("modal-load")[0]?.remove();
            document.getElementById("body-warning")?.remove();

            createGifLoading("list_center");

            const data = await getContactsFromBase(50);
            document.getElementsByClassName("contact-list")[0]?.remove();
            createContactsList(data);
        }
    }, 400);
}


function verifyContactsData(contacts) {
    const unique_key_remote_id = new Set();

    if (contacts.length !== 0) {
        contacts.forEach(item => {
            const formatted_number = item.key_remote_id.replace(/[^\d]/g, ""); // Remove tudo que não for número
            const is_valid_email = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(item.email);

            if (!is_valid_email)
                item.email = null;

            // Validação do número de telefone (mínimo 10, máximo 13 dígitos)
            if (formatted_number.length >= 10 && formatted_number.length <= 13) {
                if (!unique_key_remote_id.has(formatted_number)) {
                    unique_key_remote_id.add(formatted_number);
                    item.key_remote_id = formatted_number;
                } else {
                    item.key_remote_id = null; // Remove duplicatas
                }
            } else {
                item.key_remote_id = null; // Números inválidos
            }
        });
    }

    return contacts.filter(item => item.key_remote_id !== null);
}


function clearProgressModal(type) {
    if (type == "import")
        document.getElementById("title-progress").innerText = GLOBAL_LANG.persona_progress_title_import;

    if (type == "save")
        document.getElementById("title-progress").innerText = GLOBAL_LANG.persona_progress_title_save;

    document.getElementById("progress-bar").style.width = 0;
    document.getElementById("progress-main").style.display = "none";
    document.getElementById("load-main").style.display = "block";
    document.getElementById("description-progress").innerText = GLOBAL_LANG.persona_progress_default;

    $("#modal-preview-contacts").modal("hide");
    $("#modal-progress").modal("show");
}


function progressModal(saved_contacts, valid_contacts, data = 0) {
    const percentage = Math.round((saved_contacts.length / valid_contacts.length) * 100);
    let text = "";

    if (data != 0) {
        text = GLOBAL_LANG.persona_progress_import
            .replace("{1}", saved_contacts.length)
            .replace("{2}", data.length);
    } else {
        text = GLOBAL_LANG.persona_progress_save;
    }

    document.getElementById("description-progress").innerText = text;
    document.getElementById("load-main").style.display = "none";
    document.getElementById("progress-main").style.display = "flex";
    document.getElementById("progress-bar").style.width = `${percentage}%`;
    document.getElementById("progress-bar").innerText = `${percentage}%`;
}


function blinkAlertText() {
    document.getElementsByClassName("alert-import-text")[0].classList.add("blink-text");
    document.getElementsByClassName("alert-import-text")[1].classList.add("blink-text");
    document.getElementById("icon-preview").classList.add("blink-icon");
    document.getElementById("icon-import").classList.add("blink-icon");

    setTimeout(() => {
        document.getElementsByClassName("alert-import-text")[0].classList.remove("blink-text");
        document.getElementsByClassName("alert-import-text")[1].classList.remove("blink-text");
        document.getElementById("icon-preview").classList.remove("blink-icon");
        document.getElementById("icon-import").classList.remove("blink-icon");
    }, 2000);
}


async function importContacts(data) {
    const select_channel = document.getElementById("select-channel");
    const contacts = verifyContactsData(data);
    let reponse_data = [];

    if (contacts.length == 0)
        return callAlerts("contact_number");

    clearProgressModal("import");

    for (let i = 0; i < contacts.length; i += 500) {

        const formData = new FormData();
        formData.append("id_channel", select_channel.value);
        formData.append("contacts", JSON.stringify(contacts.slice(i, i + 500)));
        formData.append("channel", select_channel.options[select_channel.selectedIndex].getAttribute("data-id"));
        formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

        const response = await fetch(`${document.location.origin}/persona/import/contact`, {
            method: "POST",
            body: formData
        });

        reponse_data = reponse_data.concat(await response.json());
        reponse_data = removeDuplicatesByKeyRemoteId(reponse_data);
        progressModal(reponse_data, contacts, data);

        $("[name='csrf_token_talkall']").val(Cookies.get("csrf_cookie_talkall"));
    }

    if (parseFloat(document.getElementsByClassName("progress-bar")[0].style.width) >= 100) {
        setTimeout(async () => {
    
            await createContactsTable(reponse_data);
            $("#modal-progress").modal("hide");
    
        }, 1000);
    }

    if(reponse_data.length < 100){
        await createContactsTable(reponse_data);
        $("#modal-progress").modal("hide");
    }
}

function removeDuplicatesByKeyRemoteId(data) {
    const uniqueKeys = new Set();
    const result = [];

    data.forEach(item => {
        const [fullPhone, conversationId] = item.key_remote_id.split('-');

        const countryCode = fullPhone.slice(0, 2);  // 55
        const areaCode = fullPhone.slice(2, 4);     // 43
        const phoneNumber = fullPhone.slice(4);     // remaining number

        let normalizedNumber = phoneNumber;
        if (phoneNumber.length === 9) {
            // Remove the 9th digit if present
            normalizedNumber = phoneNumber.slice(1);
        }

        const normalizedKey = `${countryCode}${areaCode}${normalizedNumber}-${conversationId}`;

        if (!uniqueKeys.has(normalizedKey)) {
            uniqueKeys.add(normalizedKey);
            result.push(item);
        }
    });

    return result;
}



function prepareContactsForImport() {
    const selects = document.querySelectorAll("[name=select-data-type]");
    const data = getImportContacts();
    const contacts = [];
    const data_index = {};

    if (data.contacts.length > 10000)
        data.contacts = data.contacts.slice(0, 10000);

    selects.forEach((select, idx) => {
        if (select.value == "name")
            data_index.name = idx;

        if (select.value == "phone")
            data_index.phone = idx;

        if (select.value == "email")
            data_index.email = idx;
    });

    if (data_index.phone == undefined)
        return callAlerts("phone_column");

    data.contacts.forEach(contact => {
        contacts.push({
            key_remote_id: contact[data_index.phone] ?? "",
            email: contact[data_index.email] ?? "",
            name: contact[data_index.name] ?? "",
        });
    });

    importContacts(contacts);
}


function removeDuplicateSelectValues(selects, current_index, value) {
    selects.forEach((select, last_index) => {
        if (current_index != last_index && value == select.value)
            select.value = "default";
    });
}


function createImportColumns(data) {
    const columns = document.createElement("tr");
    columns.id = "select-columns";

    for (let i = 0; i < data.column_count; i++) {
        const th = document.createElement("th");
        th.setAttribute("index", i);

        const select = document.createElement("select");
        select.name = "select-data-type";
        select.className = "form-control form-control-sm";

        const option_default = Components.option({ id: "default", value: "default", text: GLOBAL_LANG.persona_import_select_data_type });
        const name = Components.option({ id: "name", value: "name", text: GLOBAL_LANG.persona_import_option_name });
        const phone = Components.option({ id: "phone", value: "phone", text: GLOBAL_LANG.persona_import_option_phone });
        const email = Components.option({ id: "email", value: "email", text: GLOBAL_LANG.persona_import_option_email });

        select.appendChild(option_default);
        select.appendChild(name);
        select.appendChild(phone);
        select.appendChild(email);

        th.appendChild(select);
        columns.appendChild(th);
    }
    document.getElementById("table-for-preview").appendChild(columns);

    $("#modal-import-contacts").modal("hide");
    $("#modal-preview-contacts").modal("show");
}


function markRepeatedContactData() {
    const data = Array.from(document.querySelectorAll("#table-for-preview tbody tr td"));
    const values = data.map(item => item.innerHTML);

    const occurrence_count = {};
    const repeated_values_indices = [];

    values.forEach((value, index) => {
        occurrence_count[value] = (occurrence_count[value] || 0) + 1;
        if (occurrence_count[value] > 1)
            repeated_values_indices.push(index);
    });

    data.forEach((item, index) => {
        if (repeated_values_indices.includes(index))
            item.style.color = "red";
    });
}


function createImportContactsList(data) {
    const tbody = document.createElement("tbody");

    data.contacts.forEach(row => {
        const tr = document.createElement("tr");

        row.forEach(item => {
            const td = document.createElement("td");
            td.innerHTML = Util.doTruncarStr(item, 30);
            tr.appendChild(td);
        });
        tbody.appendChild(tr);
    });
    document.getElementById("table-for-preview").appendChild(tbody);
    markRepeatedContactData();
}


function getImportContacts() {
    const contacts_string = document.getElementById("list-contacts").value;
    const contacts_data = contacts_string.split("\n").map((line) => { return line.split("\t") });
    const contacts = contacts_data.filter(array => array != "");
    const column_count = Math.max(...contacts.map(item => item.length));

    const data = {
        contacts: contacts,
        column_count: column_count
    }

    return data;
}


function initializeContactsImport(data) {
    createImportColumns(data);
    createImportContactsList(data);

    const selects = document.querySelectorAll("[name=select-data-type]");

    selects.forEach((select, index) => {
        select.addEventListener("change", (element) => {
            removeDuplicateSelectValues(selects, index, element.target.value);
        })
    });
}


function verifyLimitContactsForImport() {
    const data = getImportContacts();

    if (data.contacts.length > 10000) {
        data.contacts = data.contacts.slice(0, 10000);

        swal({
            title: GLOBAL_LANG.persona_alert_import_title,
            text: GLOBAL_LANG.persona_alert_import_contact_limit_exceeded,
            type: "warning",
            confirmButtonColor: '#3085d6',
            cancelButtonClass: "btn btn-secondary",
            onClose: () => {
                initializeContactsImport(data);
            }
        })
    } else {
        initializeContactsImport(data)
    }
}


function showModalImportContacts() {
    document.getElementById("list-contacts").value = "";
    document.getElementById("select-columns")?.remove();
    document.querySelector("#table-for-preview tbody")?.remove();
    $("#modal-import-contacts").modal("show");

    document.getElementById("btn-import-contacts").addEventListener("click", verifyLimitContactsForImport);
    blinkAlertText();
}


async function deleteParticipants(id_persona) {
    const formData = new FormData();
    formData.append("id_persona", id_persona);
    formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

    const response = await fetch(`${document.location.origin}/persona/delete/participants`, {
        method: "POST",
        body: formData
    });

    await response.json();

    $("[name='csrf_token_talkall']").val(Cookies.get("csrf_cookie_talkall"));
}


async function savePersona() {
    clearProgressModal("save");

    let saved_contacts = [];
    let id_persona_add = "";
    let id_persona_edit = window.location.href.split("edit/")[1];
    const type = id_persona_edit == undefined ? "add" : "edit";

    if (type === "edit") await deleteParticipants(id_persona_edit);

    const contacts_id = contactsData.map(item => item.id_contact);

    for (let i = 0; i < contacts_id.length; i += 500) {
        const contact_slice = contacts_id.slice(i, i + 500);

        const formData = new FormData();
        formData.append("type", type);
        formData.append("name", document.getElementById("input-name").value);
        formData.append("id_persona", type === "add" ? id_persona_add : id_persona_edit);
        formData.append("id_channel", document.getElementById("select-channel").value);
        formData.append("contacts_id", JSON.stringify(contact_slice));
        formData.append("image", document.getElementById("input-file-hidden").value);
        formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

        const response = await fetch(`${document.location.origin}/persona/save`, {
            method: "POST",
            body: formData
        });

        const data = await response.json();

        if (data.success?.status == true) {
            saved_contacts = saved_contacts.concat(contact_slice);
            id_persona_add = data.success.id_persona;
            progressModal(saved_contacts, contacts_id);
        };

        $("[name='csrf_token_talkall']").val(Cookies.get("csrf_cookie_talkall"));
    }
}


async function submit() {
    document.getElementById("btn-success").disabled = "true";
    await savePersona();
    setTimeout(() => window.location.href = "/persona", 2000);
}


function uploadImage(file) {
    const formData = new FormData();
    const id_channel = document.getElementById("select-channel").value;

    formData.append("filetoupload", file);
    formData.append("ta_id", id_channel);
    formData.append("media_mime_type", file.type);

    const settings = {
        "url": "https://files.talkall.com.br:3000",
        "method": "POST",
        "timeout": 0,
        "crossDomain": true,
        "processData": false,
        "mimeType": "multipart/form-data",
        "contentType": false,
        "data": formData
    };

    $.ajax(settings).done(function (response) {
        document.querySelector(".transition-effect img").setAttribute("src", "data:image/jpeg;base64," + JSON.parse(response).thumbnail);
        document.getElementById("input-file-hidden").value = JSON.parse(response).url;
    });
}


function callAlerts(alert) {
    switch (alert) {
        case "max_size":
            swal({
                title: GLOBAL_LANG.persona_alert_dropzone_maxSize_title,
                text: GLOBAL_LANG.persona_alert_dropzone_maxSize_text,
                type: "warning",
                confirmButtonColor: '#3085d6',
                confirmButtonText: GLOBAL_LANG.persona_alert_dropzone_maxSize_confirmButtonText,
                cancelButtonClass: "btn btn-secondary"
            });
            break;
        case "files":
            swal({
                title: GLOBAL_LANG.persona_alert_dropzone_archives_title,
                text: GLOBAL_LANG.persona_alert_dropzone_archives_text,
                type: "warning",
                confirmButtonColor: '#3085d6',
                confirmButtonText: GLOBAL_LANG.persona_alert_dropzone_archives_confirmButtonText,
                cancelButtonClass: "btn btn-secondary"
            });
            break;
        case "contact_number":
            swal({
                title: GLOBAL_LANG.persona_alert_import_title,
                text: GLOBAL_LANG.persona_alert_import_contact_number,
                type: "warning",
                buttonsStyling: !1,
                confirmButtonClass: "btn btn-success",
                onClose: blinkAlertText
            });
            break;
        case "phone_column":
            swal({
                title: GLOBAL_LANG.persona_alert_import_title,
                text: GLOBAL_LANG.persona_alert_import_phone_select,
                type: "warning",
                buttonsStyling: !1,
                confirmButtonClass: "btn btn-success",
            });
            break;
        case "contact_table":
            swal({
                title: GLOBAL_LANG.persona_alert_empty_title,
                text: GLOBAL_LANG.persona_alert_empty_text,
                type: "warning",
                confirmButtonColor: '#3085d6',
                confirmButtonText: GLOBAL_LANG.persona_alert_empty_text_confirmButtonText,
                cancelButtonClass: "btn btn-secondary"
            });
            break;
        default:
            break;
    }
}


function verifyUploadImage(element) {
    const max_file_size = 10 * 1024 * 1024;
    const accepted_file_type = ["image/png", "image/jpg", "image/jpeg"];
    const file = element.target.files[0];

    if (file.size > max_file_size) {
        callAlerts("max_size");
    } else if (!accepted_file_type.includes(file.type)) {
        callAlerts("files");
    } else {
        uploadImage(file);
    }
}


function cancelPersona() {
    const count_contacts = document.querySelector("#persona-contact-table tbody")?.children.length;

    if (count_contacts > 0) {
        swal({
            title: GLOBAL_LANG.persona_alert_title,
            text: GLOBAL_LANG.persona_alert_text,
            type: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            reverseButtons: true,
            confirmButtonClass: "btn btn-success",
            confirmButtonText: GLOBAL_LANG.persona_alert_btn_confirm,
            cancelButtonClass: "btn btn-danger",
            cancelButtonText: GLOBAL_LANG.persona_alert_btn_cancel,
        }).then(t => {
            if (t.value == true)
                location.href = "/persona";
        });
    } else location.href = "/persona";
}


function alertChangeChannel(element) {
    document.getElementById("alert-select-channel").innerText = "";
    const count_contacts = document.querySelector("#persona-contact-table tbody")?.children.length;

    if (count_contacts > 0) {
        swal({
            title: GLOBAL_LANG.persona_alert_title,
            text: GLOBAL_LANG.persona_alert_text,
            type: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            reverseButtons: true,
            confirmButtonClass: "btn btn-success",
            confirmButtonText: GLOBAL_LANG.persona_alert_btn_confirm,
            cancelButtonClass: "btn btn-danger",
            cancelButtonText: GLOBAL_LANG.persona_alert_btn_cancel,
        }).then(t => {
            if (t.value == true) {
                removeContactsTable();
                element.target.setAttribute("data-last-channel", element.target.value);
            } else
                element.target.value = element.target.getAttribute("data-last-channel");
        })
    } else
        element.target.setAttribute("data-last-channel", element.target.value);
}


function exportPersona() {
    const order = $("#datatable-basic").DataTable().order()[0][1];

    $.get(`/export/xlsx?
        search=${$("#search").val()}
        &email=${$("#emailExport").val()}
        &column=name
        &order=${order}
        &type=personas`, function (response) {
        if (response != "Error") {
            Swal.fire({
                title: GLOBAL_LANG.persona_alert_export_title,
                text: GLOBAL_LANG.persona_alert_export_text,
                type: "success",
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: GLOBAL_LANG.persona_alert_export_confirmButtonText,
            });
        }
    });
}

function modalIconExportContacts(e) {

    $.get(`/export/xlsx?
    &id_group_contact=${myIdExportContacts}
    &type=export_personas_contacts`, function (response) {

        if (response == "Error") {
            Swal.fire({
                title: GLOBAL_LANG.persona_export_contacts_no_permit_title,
                text: GLOBAL_LANG.persona_export_contacts_no_permit_content,
                type: 'error',
                buttonsStyling: !1,
                confirmButtonClass: "btn btn-danger",
                confirmButtonText: GLOBAL_LANG.without
            });
        }

        if (response != "Error") {
            Swal.fire({
                title: GLOBAL_LANG.persona_alert_export_title,
                text: GLOBAL_LANG.persona_alert_export_text,
                type: 'success',
                reverseButtons: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: GLOBAL_LANG.persona_alert_export_confirmButtonText
            });
            $('.swal2-container').css('z-index', 10000);
        }
    });
}

function handleClickAddContacts(e) {
    if (validateFields(e) == true) {
        showModalContacts();
    }
}

function handleClickImportContacts(e) {
    if (validateFields(e) == true) {
        showModalImportContacts();
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

document.getElementById("btn-add-contacts")?.addEventListener("click", () => {
    const selected_contacts = document.querySelectorAll(".contact-list [type=checkbox]:checked");
    handleContactSelection(selected_contacts);
});

document.getElementById("search-contact")?.addEventListener("keyup", searchContact);
document.getElementById("cancel-persona")?.addEventListener("click", cancelPersona);
document.getElementById("input-file")?.addEventListener("change", verifyUploadImage);
document.getElementById("sendEmailExport")?.addEventListener("click", exportPersona);
document.getElementById("clean-persona")?.addEventListener("click", removeContactsTable);
document.getElementById("select-channel")?.addEventListener("change", alertChangeChannel);
document.getElementById("btn-import-confirm")?.addEventListener("click", prepareContactsForImport);
document.getElementById("add-contacts")?.addEventListener("click", handleClickAddContacts);
document.getElementById("import-contacts")?.addEventListener("click", handleClickImportContacts);
document.getElementById("btn-success")?.addEventListener("click", (e) => { if (validateFields(e) == true) submit() });
document.getElementsByClassName("picture-persona")[0]?.addEventListener("click", () => document.getElementById("input-file").click());