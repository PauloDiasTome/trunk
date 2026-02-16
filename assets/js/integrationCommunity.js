"use strict";

let Communitys = [];
let CommunitySelected = [];
let cacheCommunitys = [];

// ------ MODELO ------ //
// let Communitys = {
//     uuid: "",
//     file: "",
//     name: "",
//     description: "",
//     creator: {
//         new: true,
//         name: "",
//         number: ""
//     },
//     numberTrigger: {
//         new: true,
//         name: "",
//         number: "",
//         description: "",
//         file: ""
//     }
// }

// ---------- MODELO CACHE --------- //
// let cacheCommunitys = {
//      uuid: ""
//      creator: {
//         name: "",
//         number: ""
//     },
//     trigger: {
//         name: "",
//         number: "",
//         id_channel: ""
//     }
//}

const Whats = {
    form: {
        handleFiles() {
            const file = document.getElementById("input-file");

            file.click();
            file.addEventListener("change", Whats.form.uploadFiles);
        },
        handleFilesTrigger() {
            const file = document.getElementById("fileTrigger");

            file.click();
            file.addEventListener("change", Whats.form.uploadFilesTrigger);
        },
        async uploadFiles() {
            await Whats.validation.fileExtension(this.files[0]);

            let formData = new FormData();
            formData.append("filetoupload", this.files[0]);
            formData.append("ta_id", Math.floor(Math.random() * 100000));

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
                if (Whats.card.isSelected())
                    Whats.cache.update({ file: JSON.parse(response).url });

                document.getElementById("file").value = JSON.parse(response).url;
                Whats.preview.photo("data:image/jpeg;base64," + JSON.parse(response).thumbnail);
                document.getElementById("preview_form").setAttribute("src", "data:image/jpeg;base64," + JSON.parse(response).thumbnail);

                document.querySelector('input[type="file"]').value = '';
            });
        },
        async uploadFilesTrigger() {
            await Whats.validation.fileTriggerExtension(this.files[0]);

            let formData = new FormData();
            formData.append("filetoupload", this.files[0]);
            formData.append("ta_id", Math.floor(Math.random() * 100000));

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
                if (Whats.card.isSelected()) {
                    Whats.cache.update({ triggerFile: JSON.parse(response).url });
                }

                document.getElementById("preview_form_trigger").setAttribute("src", "data:image/jpeg;base64," + JSON.parse(response).thumbnail);

                document.querySelector('input[type="file"]').value = '';
            });
        },
        mouseOverProrile() {
            const previewContainer = document.querySelector('._preview');
            const profileImage = previewContainer ? previewContainer.querySelector('img') : null;
            const profileOverlay = previewContainer ? previewContainer.querySelector('.profile-overlay') : null;

            profileImage.style.opacity = '0.4';
            profileOverlay.style.opacity = '1';
            profileOverlay.style.pointerEvents = 'auto';
        },
        mouseOutProrile() {
            const previewContainer = document.querySelector('._preview');
            const profileImage = previewContainer ? previewContainer.querySelector('img') : null;
            const profileOverlay = previewContainer ? previewContainer.querySelector('.profile-overlay') : null;

            profileImage.style.opacity = '1';
            profileOverlay.style.opacity = '0';
            profileOverlay.style.pointerEvents = 'none';
        },
        mouseOverProrileTrigger() {
            const previewContainer = document.querySelector('._preview_trigger');
            const profileImage = previewContainer ? previewContainer.querySelector('img') : null;
            const profileOverlay = previewContainer ? previewContainer.querySelector('.profile-overlay-trigger') : null;

            profileImage.style.opacity = '0.4';
            profileOverlay.style.opacity = '1';
            profileOverlay.style.pointerEvents = 'auto';
        },
        mouseOutProrileTrigger() {
            const previewContainer = document.querySelector('._preview_trigger');
            const profileImage = previewContainer ? previewContainer.querySelector('img') : null;
            const profileOverlay = previewContainer ? previewContainer.querySelector('.profile-overlay-trigger') : null;

            profileImage.style.opacity = '1';
            profileOverlay.style.opacity = '0';
            profileOverlay.style.pointerEvents = 'none';
        },
        recover() {
            document.getElementById("file").value =
                CommunitySelected[0].file === "" ? window.location.origin + '/assets/img/avatar.svg' : CommunitySelected[0].file;

            document.getElementById("preview_form").src =
                CommunitySelected[0].file === "" ? window.location.origin + '/assets/img/avatar.svg' : CommunitySelected[0].file;

            document.getElementById("input-number-creator").value =
                (!CommunitySelected[0].creator.number || CommunitySelected[0].creator.number === "00 00 00000-0000")
                    ? "" : CommunitySelected[0].creator.number;

            document.getElementById("input-name-creator-modal").value =
                CommunitySelected[0].creator.name || "";

            document.getElementById("input-number-creator-modal").value =
                (!CommunitySelected[0].creator.number || CommunitySelected[0].creator.number === "00 00 00000-0000")
                    ? "" : CommunitySelected[0].creator.number;

            document.getElementById("input-number-trigger").value =
                (!CommunitySelected[0].trigger.number || CommunitySelected[0].trigger.number === "00 00 00000-0000")
                    ? "" : CommunitySelected[0].trigger.number || "";

            document.getElementById("input-name").value = CommunitySelected[0].name || "";
            document.getElementById("textarea-content").value = CommunitySelected[0].description || "";

            document.getElementById("input-name-trigger").value = CommunitySelected[0].trigger.name || "";

            document.getElementById("input-number-trigger-modal").value =
                (!CommunitySelected[0].trigger.number || CommunitySelected[0].trigger.number === "00 00 00000-0000")
                    ? "" : CommunitySelected[0].trigger.number;

            document.querySelector("input[type='hidden'][name='fileTrigger']").value =
                CommunitySelected[0].trigger.file && CommunitySelected[0].trigger.file !== ""
                    ? CommunitySelected[0].trigger.file
                    : window.location.origin + '/assets/img/avatar.svg';

            document.getElementById("preview_form_trigger").src =
                CommunitySelected[0].trigger.file && CommunitySelected[0].trigger.file !== ""
                    ? CommunitySelected[0].trigger.file
                    : window.location.origin + '/assets/img/avatar.svg';

            document.getElementById("textarea-content-trigger").value = CommunitySelected[0].trigger.description || "";
        },

        clear() {
            document.getElementById("input-name").value = null;
            document.getElementById("textarea-content").value = null;
            document.getElementById("input-number-creator").value = null;
            document.getElementById("input-number-trigger").value = null;
            document.getElementById("preview_form").src = window.location.origin + "/assets/img/avatar.svg";
            document.getElementById("file").value = null;

            const titleForm = document.getElementById("title_form");
            titleForm.textContent = `${GLOBAL_LANG.setting_intergration_broadcast_business_form_channel} ${Whats.card.next()}`;
        },

        disabledbtDelete() {
            const btnDelete = document.getElementById("btnDeleteChannel");
            btnDelete.classList.add("disabled");
            btnDelete.classList.remove("efect");
        },

        disabledbtnDuplicate() {
            const btnDuplicate = document.getElementById("btnDuplicateChannel");
            btnDuplicate.classList.add("disabled");
            btnDuplicate.classList.remove("efect");
        },

        enabledbtnDelete() {
            const channelFlexLeft = document.getElementById("channel-flex-left");
            const channelFlexRight = document.getElementById("channel-flex-right");

            const leftChildrenCount = channelFlexLeft.children.length;
            const rightChildrenCount = channelFlexRight.children.length;
            const totalCount = leftChildrenCount + rightChildrenCount;

            if (totalCount > 1) {
                const btnDelete = document.getElementById("btnDeleteChannel");
                btnDelete.classList.remove("disabled");
                btnDelete.classList.add("efect");
            }
        },

        enabledbtnDuplicate() {
            const btnDuplicate = document.getElementById("btnDuplicateChannel");
            btnDuplicate.classList.remove("disabled");
            btnDuplicate.classList.add("efect");
        },

        keyup: {
            name() {
                Whats.card.borderGray();
                Whats.preview.name(this);
                if (Whats.card.isSelected())
                    Whats.cache.update({ name: this.value.trim() });
            },
            description() {
                Whats.preview.description(this);
                if (Whats.card.isSelected())
                    Whats.cache.update({ description: this.value.trim() });
            }
        },
        change: {
            trigger() {
                if (Whats.card.isSelected())
                    Whats.cache.update({ trigger: this.value.trim() });
                Whats.card.preview.trigger();
            },
            creator() {

            }
        },
        clearAlert() {
            document.querySelectorAll(".alert-field-validation").forEach(elm => {
                elm.style.display = "none";
            });
        },
    },

    btn: {
        validation() {
            Whats.validation.name();
            Whats.validation.file();
            Whats.validation.phone();
        },
        async bookEvaluation() {
            this.disabled = true;
            Whats.btn.addLoad("arrow_right_advance", "load_advance");
            const validName = Whats.validation.name();
            const validFile = Whats.validation.file();
            const validPhone = await Whats.validation.phone();
            const cacheNumberDuplicate = Whats.validation.cacheNumberDuplicate();
            const cacheNameInvalid = Whats.validation.cacheNameInvalid();
            const cacheFileInvalid = Whats.validation.cacheFileInvalid();

            const cacheInvalids = [cacheNameInvalid, cacheNumberDuplicate, cacheFileInvalid];


            const borderInvalid = cacheInvalids.find(cacheInvalid => cacheInvalid !== false);

            if (borderInvalid) {
                Whats.card.borderRed(borderInvalid);
                Whats.btn.removeLoad("arrow_right_advance", "load_advance");
                this.disabled = false;
                return;
            }

            if (!validName || !validFile || !validPhone) {
                Whats.btn.removeLoad("arrow_right_advance", "load_advance");
                this.disabled = false;
                return;
            }

            document.getElementsByClassName("fist-phase")[0].style.display = "none";
            document.getElementsByClassName("second-phase")[0].style.display = "block";

            document.querySelectorAll(".step-progress-item")[0].classList.remove("current-item");
            document.querySelectorAll(".step-progress-item")[1].classList.add("current-item");
        },

        async bookMeeting() {
            this.disabled = true;
            Whats.btn.addLoad("arrow_right_book", "load_book");
            Whats.card.progress();

            setTimeout(async () => {
                const csrfTokenName = "csrf_token_talkall";
                const csrfTokenValue = Cookies.get("csrf_cookie_talkall");

                const dataToEncode = {
                    communitys: JSON.stringify(Communitys),
                    [csrfTokenName]: csrfTokenValue
                };

                const urlEncodedBody = new URLSearchParams(dataToEncode).toString();

                const result = await fetch(`${document.location.origin}/integration/save/whatsapp/community`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
                    },
                    body: urlEncodedBody,
                });

                const response = await result.json();

                if (response.status === true) {
                    this.disabled = false;
                    window.open("https://calendly.com/talkall-ativacao/reuniao-de-ativacao-canal-whatsapp", "_blank");
                    window.location.href = document.location.origin + "/integration";
                }

                $("[name='csrf_token_talkall']").val(Cookies.get("csrf_cookie_talkall"));
            }, 500);
        },
        addLoad(arrow, load) {
            document.getElementById(arrow).style.display = "none";
            document.getElementById(load).style.display = "inline";
        },
        removeLoad(arrow, load) {
            document.getElementById(arrow).style.display = "inline";
            document.getElementById(load).style.display = "none";
        },

        callBack() {
            document.getElementsByClassName("fist-phase")[0].style.display = "block";
            document.getElementsByClassName("second-phase")[0].style.display = "none";

            document.querySelectorAll(".step-progress-item").forEach(item => item.classList.remove("current-item"));
            document.querySelectorAll(".step-progress-item")[0].classList.add("current-item");

            Whats.btn.removeLoad("arrow_right_advance", "load_advance");
            document.getElementById("btnBookEvaluation").disabled = false;
        },

        async new(clone) {
            this.disabled = true;

            const validName = Whats.validation.name();
            const validFile = Whats.validation.file();
            const validTrigger = await Whats.validation.phone();
            const validCreator = await Whats.validation.phone();

            if (validName && validTrigger && validCreator && validFile) {

                if (clone === true) {
                    Whats.card.clone();
                    return;
                }

                Whats.card.deselected();
                Whats.form.clear();
                Whats.preview.clear();

                Whats.card.first();
                Whats.form.enabledbtnDelete();
            }

            this.disabled = false;
        },
        delete() {
            if (this.classList.contains("disabled"))
                return;

            Whats.cache.delete(CommunitySelected[0].uuid);
            Whats.cache.deselected();

            Whats.card.delete();

            Whats.form.clear();
            Whats.preview.clear();
            Whats.card.selected(document.querySelector("#channel-flex-left").children[0]);

            if (Whats.card.count() === 1)
                Whats.form.disabledbtDelete();
        },
        duplicate() {
            if (this.classList.contains("disabled"))
                return;

            Whats.btn.new(true);
        },
        async newTrigger() {
            const modal = document.getElementById("modalAddNumberTrigger");
            if (!modal) return;

            const mainInputTrigger = document.getElementById("input-number-trigger");

            const validName = Whats.validation.nameTrigger();
            const validNumberTrigger = await Whats.validation.phoneTriggerModal();

            if (validName && validNumberTrigger) {
                const number = document.getElementById("input-number-trigger-modal").value.trim().replace(/\D/g, "");

                const alreadyLimit = await Whats.validation.NumberDuplicateTriggerCache(number);

                if (alreadyLimit) {
                    document.getElementById("alert__input-number-trigger-modal").textContent =
                        GLOBAL_LANG.setting_integration_broadcast_business_validation_trigger_limit;
                    document.getElementById("alert__input-number-trigger-modal").style.display = "block";
                    return;
                }

                Whats.cache.update({
                    triggerName: document.getElementById("input-name-trigger").value.trim(),
                    triggerNumber: number,
                    triggerDescription: document.getElementById("textarea-content-trigger").value.trim(),
                });

                if (mainInputTrigger) {
                    mainInputTrigger.value = document.getElementById("input-number-trigger-modal").value.trim();
                }

                Whats.card.preview.trigger();
                Whats.modal.close("modalAddNumberTrigger");
            }
        },
        selectTrigger(selectedNumber) {

            const modal = document.getElementById("modalAddNumberTrigger");
            if (!modal) return;
            const mainInputTrigger = document.getElementById("input-number-trigger");

            if (selectedNumber.id_channel == null || selectedNumber.id_channel == undefined) {
                Whats.cache.update({
                    triggerName: selectedNumber.name || "",
                    triggerNumber: selectedNumber.trigger,
                    new: true,
                    id_channel: selectedNumber.id_channel
                });
            } else {
                Whats.cache.update({
                    triggerName: selectedNumber.name || "",
                    triggerNumber: selectedNumber.trigger,
                    new: false,
                    id_channel: selectedNumber.id_channel
                });
            }


            if (mainInputTrigger) {
                mainInputTrigger.value = selectedNumber.trigger;
            }

            Whats.card.preview.trigger();
            Whats.modal.close("modalAddNumberTrigger");
        },
        async newCreator(step1) {
            const modal = document.getElementById("modalAddNumberCreator");
            if (!modal) return;

            const mainInputCreator = document.getElementById("input-number-creator");

            const inputName = step1.querySelector('input[type="text"]');
            const inputTel = step1.querySelector('input[type="tel"]');

            const name = inputName.value.trim();
            const number = inputTel.value.trim();

            const validName = Whats.validation.nameCreator();
            const validPhone = await Whats.validation.phoneCreatorModal();

            if (validName && validPhone) {
                const alreadyLimit = await Whats.validation.NumberDuplicateCreatorCache(number);

                if (alreadyLimit) {
                    document.getElementById("alert__input-number-creator-modal").textContent =
                        GLOBAL_LANG.setting_integration_broadcast_business_validation_creator_limit;
                    document.getElementById("alert__input-number-creator-modal").style.display = "block";
                    return;
                }

                Whats.cache.update({
                    creatorName: name,
                    creatorNumber: number
                });

                if (mainInputCreator) {
                    mainInputCreator.value = number;
                }

                Whats.modal.close("modalAddNumberCreator");
            }
        },
        selectCreator(selectedNumber) {
            const modal = document.getElementById("modalAddNumberCreator");
            if (!modal) return;
            const mainInputCreator = document.getElementById("input-number-creator");

            Whats.cache.update({
                creatorName: selectedNumber.name,
                creatorNumber: selectedNumber.number,
            });

            if (mainInputCreator) {
                mainInputCreator.value = selectedNumber.number;
            }

            Whats.card.preview.creator();
            Whats.modal.close("modalAddNumberCreator");
        },

    },

    card: {
        add(data) {
            const card = document.createElement("div");
            card.id = data.uuid;
            card.className = "_channel _selected";
            card.addEventListener("click", Whats.card.click);

            const title = document.createElement("span");
            title.classList.add("title");
            title.textContent = `${GLOBAL_LANG.setting_intergration_broadcast_business_form_community} ${Whats.card.next()}`;

            const triggerChannel = document.createElement("span");
            triggerChannel.classList.add("trigger");
            triggerChannel.textContent = data.trigger.number == "" ? "00 00 00000-0000" : data.trigger.number;

            const nameChannel = document.createElement("span");
            nameChannel.classList.add("name");
            nameChannel.textContent = data.name == "" ? GLOBAL_LANG.setting_intergration_broadcast_business_form_name_placeholder : data.name;

            const arrow = document.createElement("i");
            arrow.classList.add("fas");
            arrow.classList.add("fa-arrow-right");

            card.appendChild(title);
            card.appendChild(triggerChannel);
            card.appendChild(nameChannel);
            card.appendChild(arrow);

            const element = Whats.card.getSmaller();
            element.appendChild(card);
        },

        delete() {
            const channelFlexLeft = document.getElementById("channel-flex-left");
            const channelFlexRight = document.getElementById("channel-flex-right");

            while (channelFlexLeft.firstChild) {
                channelFlexLeft.removeChild(channelFlexLeft.firstChild);
            }

            while (channelFlexRight.firstChild) {
                channelFlexRight.removeChild(channelFlexRight.firstChild);
            }

            this.organize();
        },
        organize() {
            for (let i = 0; i < Communitys.length; i++) {
                this.add(Communitys[i]);
            }
        },
        getSmaller() {
            const channelFlexLeft = document.getElementById("channel-flex-left");
            const channelFlexRight = document.getElementById("channel-flex-right");

            const leftChildrenCount = channelFlexLeft.children.length;
            const rightChildrenCount = channelFlexRight.children.length;

            if (leftChildrenCount <= rightChildrenCount) {
                return channelFlexLeft;
            } else {
                return channelFlexRight;
            }
        },
        click() {
            Whats.card.selected(this);
            Whats.form.enabledbtnDelete();
            Whats.form.enabledbtnDuplicate();
        },
        selected(element) {
            Whats.form.clearAlert();
            this.deselected();

            element.classList.add("_selected");
            Whats.cache.selected(Whats.cache.search(parseInt(element.id)));

            Whats.form.recover();
            Whats.preview.recover();

            const titleCard = document.querySelector('._selected').children[0];
            const titleForm = document.getElementById("title_form");

            titleForm.textContent = titleCard.textContent;
        },
        deselected() {
            const channelFlexLeft = document.getElementById("channel-flex-left");
            const channelFlexRight = document.getElementById("channel-flex-right");

            const childrenLeft = channelFlexLeft.children;
            for (let i = 0; i < childrenLeft.length; i++) {
                childrenLeft[i].classList.remove("_selected");
            }

            const childrenRight = channelFlexRight.children;
            for (let i = 0; i < childrenRight.length; i++) {
                childrenRight[i].classList.remove("_selected");
            }
        },
        reset() {
            Whats.form.clear();
            Whats.preview.clear();
            Whats.card.deselected();
            Whats.cache.deselected();
            document.getElementById("title_form").textContent = `${GLOBAL_LANG.setting_intergration_broadcast_business_form_channel} ${Whats.card.next()}`;
        },
        next() {
            const channelFlexLeft = document.getElementById("channel-flex-left");
            const channelFlexRight = document.getElementById("channel-flex-right");

            return channelFlexLeft.children.length + channelFlexRight.children.length + 1;
        },
        isSelected() {
            return CommunitySelected.length > 0;
        },
        first() {
            document.querySelector(".first__")?.remove();

            const data = {
                uuid: Math.floor(Math.random() * 100000),
                file: "",
                name: "",
                description: "",
                creator: {
                    new: true,
                    name: "",
                    number: ""
                },
                trigger: {
                    new: true,
                    name: "",
                    number: "",
                    description: "",
                    file: ""
                }
            }

            Whats.card.add(data);
            Whats.cache.add(data);

            const element = document.querySelector('._selected');
            Whats.card.selected(element);
        },
        async clone() {
            document.querySelector(".first__")?.remove();

            const creatorNumber = CommunitySelected[0].creator.number;
            const triggerNumber = CommunitySelected[0].trigger.number;

            const creatorLimit = await Whats.validation.NumberDuplicateCreatorCache(creatorNumber);
            const triggerLimit = await Whats.validation.NumberDuplicateTriggerCache(triggerNumber);

            if (creatorLimit) {
                swal({
                    title: GLOBAL_LANG.setting_intergration_broadcast_business_community_alert_creator_limit_title,
                    text: GLOBAL_LANG.setting_intergration_broadcast_business_community_alert_creator_limit_text,
                    type: "warning",
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: GLOBAL_LANG.setting_intergration_broadcast_business_community_alert_creator_limit_button,
                    cancelButtonClass: "btn btn-secondary",
                });
                document.getElementById("alert__input-number-creator").textContent =
                    GLOBAL_LANG.setting_integration_broadcast_business_validation_creator_limit;
                document.getElementById("alert__input-number-creator").style.display = "block";
                return;
            }

            if (triggerLimit) {
                swal({
                    title: GLOBAL_LANG.setting_intergration_broadcast_business_community_alert_trigger_limit_title,
                    text: GLOBAL_LANG.setting_intergration_broadcast_business_community_alert_trigger_limit_text,
                    type: "warning",
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: GLOBAL_LANG.setting_intergration_broadcast_business_community_alert_trigger_limit_button,
                    cancelButtonClass: "btn btn-secondary",
                });
                document.getElementById("alert__input-number-trigger").textContent =
                    GLOBAL_LANG.setting_integration_broadcast_business_validation_trigger_limit;
                document.getElementById("alert__input-number-trigger").style.display = "block";
                return;
            }

            const data = {
                uuid: Math.floor(Math.random() * 100000),
                file: CommunitySelected[0].file,
                name: CommunitySelected[0].name,
                description: CommunitySelected[0].description,
                creator: {
                    new: CommunitySelected[0].creator.new,
                    name: CommunitySelected[0].creator.name,
                    number: creatorNumber
                },
                trigger: {
                    new: CommunitySelected[0].trigger.new,
                    name: CommunitySelected[0].trigger.name,
                    number: triggerNumber,
                    description: CommunitySelected[0].trigger.description,
                    file: CommunitySelected[0].trigger.file,
                    id_channel: CommunitySelected[0].trigger.id_channel
                }
            };

            Whats.card.deselected();
            Whats.card.add(data);
            Whats.cache.add(data);

            const element = document.querySelector('._channel');
            Whats.card.selected(element);
            Whats.form.enabledbtnDelete();
        },
        borderRed(id) {
            document.getElementById(id).style.border = "1px solid #ff0000";
        },
        borderGray() {
            document.querySelectorAll("._channel").forEach((element) => {
                element.style.border = "1px solid #dee2e6";
            });
        },
        count() {
            const channelFlexLeft = document.getElementById("channel-flex-left");
            const channelFlexRight = document.getElementById("channel-flex-right");

            return channelFlexLeft.children.length + channelFlexRight.children.length;
        },
        progress() {
            document.querySelectorAll(".step-progress-list > *").forEach(c => {
                c.classList.remove("current-item");
            });
        },

        preview: {
            name() {
                if (Whats.card.isSelected()) {
                    const card = document.getElementById(CommunitySelected[0].uuid);
                    const inputName = document.getElementById("input-name");
                    const name = inputName.value.trim() || GLOBAL_LANG.setting_intergration_broadcast_business_form_name_placeholder;
                    if (card) {
                        card.children[2].textContent = name;
                    }
                }
            },
            trigger() {
                if (Whats.card.isSelected()) {
                    const card = document.getElementById(CommunitySelected[0].uuid);
                    const inputTrigger = document.getElementById('input-number-trigger');
                    const trigger = inputTrigger.value.trim().replace(/\D/g, "") || "00000000000";
                    if (card) {
                        card.children[1].textContent = trigger;
                    }
                }
            },
            creator() {
                if (Whats.card.isSelected()) {
                    const card = document.getElementById(CommunitySelected[0].uuid);
                    const inputCreator = document.getElementById('input-number-creator');
                    const creator = inputCreator.value.trim().replace(/\D/g, "") || "00000000000";
                    if (card) {
                        card.children[1].textContent = creator;
                    }
                }
            }
        }
    },

    cache: {
        add(data) {
            Communitys.push({
                uuid: data.uuid,
                file: data.file,
                name: data.name,
                description: data.description,
                creator: {
                    new: data.creator.new,
                    name: data.creator.name,
                    number: data.creator.number
                },
                trigger: {
                    new: data.trigger.new,
                    name: data.trigger.name,
                    number: data.trigger.number,
                    description: data.trigger.description,
                    file: data.trigger.file,
                    id_channel: data.trigger.id_channel
                }
            });

            cacheCommunitys.push({
                uuid: data.uuid,
                name: data.name,
                creator: {
                    name: data.creator.name,
                    number: data.creator.number
                },
                trigger: {
                    name: data.trigger.name,
                    number: data.trigger.number,
                    id_channel: data.trigger.id_channel
                }
            });

        },
        update(data) {
            const index = Communitys.findIndex(
                community => community.uuid == CommunitySelected[0].uuid
            );

            const indexCache = cacheCommunitys.findIndex(
                cacheCommunitys => cacheCommunitys.uuid == CommunitySelected[0].uuid
            );

            if (index > -1) {
                const name = data.hasOwnProperty('name') ? data.name : CommunitySelected[0].name;
                const file = data.hasOwnProperty('file') ? data.file : CommunitySelected[0].file;
                const description = data.hasOwnProperty('description') ? data.description : CommunitySelected[0].description;

                const creatorName = data.hasOwnProperty('creatorName') ? data.creatorName : CommunitySelected[0].creator.name;
                const creatorNumber = data.hasOwnProperty('creatorNumber') ? data.creatorNumber : CommunitySelected[0].creator.number;

                const triggerName = data.hasOwnProperty('triggerName') ? data.triggerName : CommunitySelected[0].trigger.name;
                const triggerNumber = data.hasOwnProperty('triggerNumber') ? data.triggerNumber : CommunitySelected[0].trigger.number;
                const triggerDescription = data.hasOwnProperty('triggerDescription') ? data.triggerDescription : CommunitySelected[0].trigger.description;
                const triggerFile = data.hasOwnProperty('triggerFile') ? data.triggerFile : CommunitySelected[0].trigger.file;
                const newTrigger = data.hasOwnProperty('new') ? data.new : CommunitySelected[0].trigger.new;
                const idChannel = data.hasOwnProperty('id_channel') ? data.id_channel : CommunitySelected[0].trigger.id_channel;

                Communitys[index] = {
                    uuid: CommunitySelected[0].uuid,
                    name,
                    file,
                    description,
                    creator: {
                        new: true,
                        name: creatorName,
                        number: creatorNumber,
                    },
                    trigger: {
                        new: newTrigger,
                        name: triggerName,
                        number: triggerNumber,
                        description: triggerDescription,
                        file: triggerFile,
                        id_channel: idChannel
                    }
                };

                CommunitySelected[0].uuid = CommunitySelected[0].uuid;
                CommunitySelected[0].name = name;
                CommunitySelected[0].file = file;
                CommunitySelected[0].description = description;
                CommunitySelected[0].creator.name = creatorName;
                CommunitySelected[0].creator.number = creatorNumber;
                CommunitySelected[0].trigger.name = triggerName;
                CommunitySelected[0].trigger.number = triggerNumber;
                CommunitySelected[0].trigger.file = triggerFile;
                CommunitySelected[0].trigger.description = triggerDescription;
                CommunitySelected[0].trigger.new = newTrigger;
                CommunitySelected[0].trigger.id_channel = idChannel;

                cacheCommunitys[indexCache] = {
                    uuid: CommunitySelected[0].uuid,
                    name: name,
                    creator: {
                        name: creatorName,
                        number: creatorNumber,
                    },
                    trigger: {
                        name: triggerName,
                        number: triggerNumber,
                        id_channel: idChannel
                    }
                }

            }
        },

        delete(id) {
            const index = Communitys.findIndex(community => community.uuid === id);
            if (index > -1) {
                Communitys.splice(index, 1);
            }

            const cacheIndex = cacheCommunitys.findIndex(item => item.uuid === id);
            if (cacheIndex > -1) {
                cacheCommunitys.splice(cacheIndex, 1);
            }

        },

        search(uuid) {
            return Communitys.find(community => community.uuid == uuid);
        },

        selected(data) {
            this.deselected();
            CommunitySelected.push({
                uuid: data.uuid,
                file: data.file,
                name: data.name,
                description: data.description,
                creator: {
                    new: data.creator.new,
                    name: data.creator.name,
                    number: data.creator.number
                },
                trigger: {
                    new: data.trigger.new,
                    name: data.trigger.name,
                    number: data.trigger.number,
                    description: data.trigger.description,
                    file: data.trigger.file,
                    id_channel: data.trigger.id_channel
                }
            });
        },
        deselected() {
            CommunitySelected = [];
        },
        CommunityChannelsCache() {
            return new Promise((resolve, reject) => {
                fetch(`${document.location.origin}/integration/whatsapp/community/channels`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(item => {
                            cacheCommunitys.push({
                                uuid: item.uuid,
                                name: item.name,
                                creator: {
                                    name: item.creator.name,
                                    number: item.creator.number
                                },
                                trigger: {
                                    name: item.trigger.name,
                                    number: item.trigger.number,
                                    id_channel: item.trigger.id_channel
                                }
                            });
                        });
                        resolve(cacheCommunitys);
                    })
                    .catch(error => {
                        console.error("Erro ao carregar comunidades:", error);
                        reject(false);
                    });
            });
        },
    },

    validation: {
        name() {
            const name = document.getElementById("input-name").value.trim();
            const alertMessage = document.getElementById("alert__input-name");

            if (name === "" || name.length < 3) {
                alertMessage.textContent = name === "" ? GLOBAL_LANG.setting_intergration_broadcast_business_community_validation_name_required : GLOBAL_LANG.setting_intergration_broadcast_business_community_validation_name_min_length;
                alertMessage.style.display = "block";
                return false;
            } else {
                alertMessage.style.display = "none";
                return true;
            }
        },
        NumberDuplicate() {
            const creatorNumber = document.getElementById("input-number-creator").value.trim();
            const triggerNumber = document.getElementById("input-number-trigger").value.trim();

            // Regra: o número disparador não pode ser criador
            if (creatorNumber && triggerNumber && creatorNumber === triggerNumber) {
                document.getElementById("alert__input-number-trigger").textContent =
                    GLOBAL_LANG.setting_integration_broadcast_business_validation_trigger_cannot_be_creator;
                document.getElementById("alert__input-number-trigger").style.display = "block";
                return true;
            }

            return false;
        },
        cacheNameInvalid() {
            const invalid = Communitys.find(community => community.name.length < 3);

            if (invalid) {
                return invalid.uuid;
            }

            return false;
        },
        cacheFileInvalid() {
            const invalid = Communitys.find(community => community.file == "");

            if (invalid) {
                return invalid.uuid;
            }

            return false;
        },
        cacheNumberDuplicate() {
            const creatorCount = {};
            const triggerCount = {};

            for (const community of Communitys) {
                const creator = community.phoneCreator;
                const trigger = community.phoneTrigger;

                if (creator) {
                    creatorCount[creator] = (creatorCount[creator] || 0) + 1;
                    if (creatorCount[creator] >= 10) {
                        document.getElementById("alert__input-number-creator").textContent =
                            GLOBAL_LANG.setting_integration_broadcast_business_validation_creator_limit;
                        document.getElementById("alert__input-number-creator").style.display = "block";
                        return community.uuid;
                    }
                }

                if (trigger) {
                    triggerCount[trigger] = (triggerCount[trigger] || 0) + 1;
                    if (triggerCount[trigger] >= 10) {
                        document.getElementById("alert__input-number-trigger").textContent =
                            GLOBAL_LANG.setting_integration_broadcast_business_validation_trigger_limit;
                        document.getElementById("alert__input-number-trigger").style.display = "block";
                        return community.uuid;
                    }
                }
            }

            return false;
        },
        file() {
            const file = document.getElementById("file").value.trim();
            const alertMessage = document.getElementById("alert__file");
            const currentHost = window.location.origin;

            if (file === "" || file === `${currentHost}/assets/img/avatar.svg`) {
                alertMessage.textContent = GLOBAL_LANG.setting_intergration_broadcast_business_community_validation_file;
                alertMessage.style.display = "block";
                return false;
            } else {
                alertMessage.style.display = "none";
                return true;
            }
        },
        fileExtension(file) {
            return new Promise((resolve, reject) => {
                const fileMb = file.size / 1024 / 1024;
                const alertMessage = document.getElementById("alert__file");
                alertMessage.style.display = "none";

                if (fileMb > 1) {
                    alertMessage.textContent = GLOBAL_LANG.setting_intergration_broadcast_business_community_validation_file_size;
                    alertMessage.style.display = "block";
                    return reject(false);
                }

                const validTypes = ["image/jpeg", "image/gif"];
                if (!validTypes.includes(file.type)) {
                    alertMessage.textContent = GLOBAL_LANG.setting_intergration_broadcast_business_community_validation_file_type;
                    alertMessage.style.display = "block";
                    return reject(false);
                }

                const reader = new FileReader();
                reader.onload = evt => {
                    const img = new Image();

                    img.onload = () => {
                        const { height, width } = img;
                        const maxSize = 1080;

                        if (height > maxSize || width > maxSize) {
                            alertMessage.textContent = `${GLOBAL_LANG.setting_intergration_broadcast_business_community_validation_file_maxSize} ${maxSize}x${maxSize}.`;
                            alertMessage.style.display = "block";
                            return reject(false);
                        }
                        resolve(true);
                    };

                    img.onerror = () => {
                        alertMessage.textContent = GLOBAL_LANG.setting_intergration_broadcast_business_community_validation_file_upload;
                        alertMessage.style.display = "block";
                        reject(false);
                    };

                    img.src = evt.target.result;
                };

                reader.onerror = () => {
                    alertMessage.textContent = GLOBAL_LANG.setting_intergration_broadcast_business_community_validation_file_read;
                    alertMessage.style.display = "block";
                    reject(false);
                };
                reader.readAsDataURL(file);
            });
        },
        fileTrigger() {
            const file = document.getElementById("fileTrigger").value.trim();
            const alertMessage = document.getElementById("alert__file-trigger");
            const currentHost = window.location.origin;

            if (file === "" || file === `${currentHost}/assets/img/avatar.svg`) {
                alertMessage.textContent = GLOBAL_LANG.setting_intergration_broadcast_business_community_validation_file;
                alertMessage.style.display = "block";

                return false;
            } else {
                alertMessage.style.display = "none";
                return true;
            }
        },
        fileTriggerExtension(file) {
            return new Promise((resolve, reject) => {
                const fileMb = file.size / 1024 / 1024;
                const alertMessage = document.getElementById("alert__file-trigger");
                alertMessage.style.display = "none";

                if (fileMb > 1) {
                    alertMessage.textContent = GLOBAL_LANG.setting_intergration_broadcast_business_community_validation_file_size;
                    alertMessage.style.display = "block";
                    return reject(false);
                }

                const validTypes = ["image/jpeg", "image/gif"];
                if (!validTypes.includes(file.type)) {
                    alertMessage.textContent = GLOBAL_LANG.setting_intergration_broadcast_business_community_validation_file_type;
                    alertMessage.style.display = "block";
                    return reject(false);
                }

                const reader = new FileReader();
                reader.onload = evt => {
                    const img = new Image();

                    img.onload = () => {
                        const { height, width } = img;
                        const maxSize = 1080;

                        if (height > maxSize || width > maxSize) {
                            alertMessage.textContent = `${GLOBAL_LANG.setting_intergration_broadcast_business_community_validation_file_maxSize} ${maxSize}x${maxSize}.`;
                            alertMessage.style.display = "block";
                            return reject(false);
                        }
                        resolve(true);
                    };

                    img.onerror = () => {
                        alertMessage.textContent = GLOBAL_LANG.setting_intergration_broadcast_business_community_validation_file_upload;
                        alertMessage.style.display = "block";
                        reject(false);
                    };

                    img.src = evt.target.result;
                };

                reader.onerror = () => {
                    alertMessage.textContent = GLOBAL_LANG.setting_intergration_broadcast_business_community_validation_file_read;
                    alertMessage.style.display = "block";
                    reject(false);
                };
                reader.readAsDataURL(file);
            });
        },
        phoneCreator() {
            return new Promise((resolve) => {
                const creatorNumber = document.getElementById("input-number-creator").value.trim();
                const alertMessageCreator = document.getElementById("alert__input-number-creator");

                if (creatorNumber === "") {
                    alertMessageCreator.textContent = GLOBAL_LANG.setting_intergration_broadcast_business_community_validation_phone_required;
                    alertMessageCreator.style.display = "block";
                    return resolve(false);
                }
                if (creatorNumber.length < 11) {
                    alertMessageCreator.textContent = GLOBAL_LANG.setting_intergration_broadcast_business_community_validation_phone_length;
                    alertMessageCreator.style.display = "block";
                    return resolve(false);
                }

                alertMessageCreator.style.display = "none";
                resolve(true);
            });
        },
        phoneCreatorModal() {
            return new Promise((resolve) => {
                const creatorNumber = document.getElementById("input-number-creator-modal").value.trim();
                const alertMessageCreator = document.getElementById("alert__input-number-creator-modal");

                if (creatorNumber === "") {
                    alertMessageCreator.textContent = GLOBAL_LANG.setting_intergration_broadcast_business_community_validation_phone_required;
                    alertMessageCreator.style.display = "block";
                    return resolve(false);
                }
                if (!/^\d+$/.test(creatorNumber)) {
                    alertMessageCreator.textContent = GLOBAL_LANG.setting_intergration_broadcast_business_community_validation_phone_number;
                    alertMessageCreator.style.display = "block";
                    return resolve(false);
                }
                if (creatorNumber.length < 11) {
                    alertMessageCreator.textContent = GLOBAL_LANG.setting_intergration_broadcast_business_community_validation_phone_length;
                    alertMessageCreator.style.display = "block";
                    return resolve(false);
                }

                alertMessageCreator.style.display = "none";
                resolve(true);
            });
        },
        phoneTrigger() {
            return new Promise((resolve) => {
                const triggerNumber = document.getElementById("input-number-trigger").value.trim();
                const alertMessageTrigger = document.getElementById("alert__input-number-trigger");

                if (triggerNumber === "") {
                    alertMessageTrigger.textContent = GLOBAL_LANG.setting_intergration_broadcast_business_community_validation_phone_required;
                    alertMessageTrigger.style.display = "block";
                    return resolve(false);
                }
                if (triggerNumber.length < 11) {
                    alertMessageTrigger.textContent = GLOBAL_LANG.setting_intergration_broadcast_business_community_validation_phone_length;
                    alertMessageTrigger.style.display = "block";
                    return resolve(false);
                }

                alertMessageTrigger.style.display = "none";
                resolve(true);
            });
        },
        phoneTriggerModal() {
            return new Promise((resolve) => {
                const inputNumber = document.getElementById("input-number-trigger-modal");
                const alertMessage = document.getElementById("alert__input-number-trigger-modal");
                const value = inputNumber.value.trim(); // <-- pega o valor correto
                const exists = cacheCommunitys.some(channel => channel.trigger.number === value);

                // verifica se o campo está vazio
                if (value === "") {
                    alertMessage.textContent = GLOBAL_LANG.setting_intergration_broadcast_business_community_validation_phone_required;
                    alertMessage.style.display = "block";
                    return resolve(false);
                }

                // verifica se contém apenas números
                if (!/^\d+$/.test(value)) {
                    alertMessage.textContent = GLOBAL_LANG.setting_intergration_broadcast_business_community_validation_phone_number;
                    alertMessage.style.display = "block";
                    return resolve(false);
                }

                // verifica o tamanho mínimo
                if (value.length < 11) {
                    alertMessage.textContent = GLOBAL_LANG.setting_intergration_broadcast_business_community_validation_phone_length;
                    alertMessage.style.display = "block";
                    return resolve(false);
                }

                // verifica se o número já existe no cache
                if (exists) {
                    alertMessage.textContent = GLOBAL_LANG.setting_intergration_broadcast_business_community_validation_number_exist;
                    alertMessage.style.display = "block";
                    return resolve(false);
                }

                // tudo certo
                alertMessage.style.display = "none";
                resolve(true);
            });

        },
        phone() {
            return Promise.all([this.phoneCreator(), this.phoneTrigger()])
                .then(([validCreator, validTrigger]) => {
                    if (!validCreator || !validTrigger) return false;

                    // Validação de duplicidade (outras regras globais)
                    if (Whats.validation.NumberDuplicate()) {
                        document.getElementById("alert__input-number-creator").textContent =
                            GLOBAL_LANG.setting_intergration_broadcast_business_community_validation_phone_duplicate;
                        document.getElementById("alert__input-number-creator").style.display = "block";

                        document.getElementById("alert__input-number-trigger").textContent =
                            GLOBAL_LANG.setting_intergration_broadcast_business_community_validation_phone_duplicate;
                        document.getElementById("alert__input-number-trigger").style.display = "block";

                        return false;
                    }

                    return true;
                })
                .catch(() => false);
        },
        description() {
            const description = document.getElementById("textarea-content").value.trim();
            const alertMessage = document.getElementById("alert__textarea-content");

            if (description === "") {
                alertMessage.textContent = GLOBAL_LANG.setting_intergration_broadcast_business_community_validation_description_required;
                alertMessage.style.display = "block";
                return false;
            } else {
                alertMessage.style.display = "none";
                return true;
            }
        },
        repeatedPhone(phone) {
            const Communitys = Communitys.filter(channel => channel.phone == phone);
            return Communitys.length > 1;
        },
        nameCreator() {
            const name = document.getElementById("input-name-creator-modal").value.trim();
            const alertMessage = document.getElementById("alert__input-name-creator-modal");

            if (name === "" || name.length < 3) {
                alertMessage.textContent = name === "" ? GLOBAL_LANG.setting_intergration_broadcast_business_community_validation_name_required : GLOBAL_LANG.setting_intergration_broadcast_business_community_validation_name_min_length;
                alertMessage.style.display = "block";
                return false;
            } else {
                alertMessage.style.display = "none";
                return true;
            }
        },
        nameTrigger() {
            const name = document.getElementById("input-name-trigger").value.trim();
            const alertMessage = document.getElementById("alert__input-name-trigger");

            if (name === "" || name.length < 3) {
                alertMessage.textContent = name === "" ? GLOBAL_LANG.setting_intergration_broadcast_business_community_validation_name_required : GLOBAL_LANG.setting_intergration_broadcast_business_community_validation_name_min_length;
                alertMessage.style.display = "block";
                return false;
            } else {
                alertMessage.style.display = "none";
                return true;
            }
        },
        NumberDuplicateTriggerCache(number) {
            return new Promise((resolve) => {
                try {
                    const normalize = (num) => {
                        let n = (num || "").replace(/\D/g, "");
                        if (n.startsWith("55")) n = n.substring(2);
                        if (n.length === 11 && n[2] === "9") n = n.substring(0, 2) + n.substring(3);
                        return n;
                    };

                    const cleanNumber = normalize(number);

                    if (!cacheCommunitys || !Array.isArray(cacheCommunitys) || !cacheCommunitys.length) {
                        console.warn("⚠️ cacheCommunitys ainda não carregado");
                        resolve(false);
                        return;
                    }

                    const communityCount = cacheCommunitys.filter(item => item.trigger && normalize(item.trigger.number) === cleanNumber).length;

                    resolve(communityCount >= 10);
                } catch (err) {
                    console.error("Erro ao validar trigger pelo cache:", err);
                    resolve(false);
                }
            });
        },
        NumberDuplicateCreatorCache(number) {
            return new Promise((resolve) => {
                try {
                    const normalize = (num) => {
                        let n = (num || "").replace(/\D/g, "");
                        if (n.startsWith("55")) n = n.substring(2);
                        if (n.length === 11 && n[2] === "9") n = n.substring(0, 2) + n.substring(3);
                        return n;
                    };

                    const cleanNumber = normalize(number);

                    if (!cacheCommunitys || !Array.isArray(cacheCommunitys) || !cacheCommunitys.length) {
                        console.warn("⚠️ cacheCommunitys ainda não carregado");
                        resolve(false);
                        return;
                    }

                    const communityCount = cacheCommunitys.filter(item => item.creator && normalize(item.creator.number) === cleanNumber).length;

                    resolve(communityCount >= 10);
                } catch (err) {
                    console.error("Erro ao validar creator pelo cache:", err);
                    resolve(false);
                }
            });
        }
    },

    preview: {
        photo(url) {
            const previewPhoto = document.getElementById("preview_photo");
            previewPhoto.src = url;
        },
        name(elm) {
            const previewName = document.getElementById("preview_name");
            previewName.textContent = elm.value.trim() || GLOBAL_LANG.setting_intergration_broadcast_business_community_preview_name;
            Whats.card.preview.name();
        },
        creator(elm) {
            const previewCreator = document.getElementById("preview_creator");
            previewCreator.textContent = elm.value.trim() || GLOBAL_LANG.setting_intergration_broadcast_business_preview_creator;
        },
        trigger(elm) {
            const previewTrigger = document.getElementById("preview_trigger");
            previewTrigger.textContent = elm.value.trim() || GLOBAL_LANG.setting_intergration_broadcast_business_preview_trigger;
            Whats.card.preview.trigger();
        },
        description(elm) {
            if (!Whats.card.isSelected()) return;
            const previewDescription = document.getElementById("preview_description");
            const newValue = elm.value.trim() || GLOBAL_LANG.setting_intergration_broadcast_business_community_form_desc_placeholder;
            previewDescription.value = newValue;
            Whats.herper.autoResizeTextarea(previewDescription);
        },
        recover() {
            document.getElementById("preview_photo").src = CommunitySelected[0].file == "" ? window.location.origin + '/assets/img/avatar.svg' : CommunitySelected[0].file;

            if (CommunitySelected[0].name !== "")
                document.getElementById("preview_name").textContent = CommunitySelected[0].name;
            else
                document.getElementById("preview_name").textContent = GLOBAL_LANG.setting_intergration_broadcast_business_form_name_placeholder;

            document.getElementById("preview_description").value = CommunitySelected[0].description;
        },
        clear() {
            document.getElementById("preview_photo").src = window.location.origin + "/assets/img/avatar.svg";
            document.getElementById("preview_name").value = GLOBAL_LANG.setting_intergration_broadcast_business_form_name_placeholder;
            document.getElementById("textarea-content").value = GLOBAL_LANG.setting_intergration_broadcast_business_community_form_desc_placeholder;
            document.getElementById("input-number-creator").value = GLOBAL_LANG.setting_intergration_broadcast_business_community_form_number_creator_placeholder;
            document.getElementById("input-number-trigger").value = GLOBAL_LANG.setting_intergration_broadcast_business_community_form_number_trigger_placeholder;
            document.getElementById("preview_form").src = window.location.origin + "/assets/img/avatar.svg";
            document.getElementById("file").value = null;

            const titleForm = document.getElementById("title_form");
            titleForm.textContent = `${GLOBAL_LANG.setting_intergration_broadcast_business_form_channel} ${Whats.card.next()}`;
        },
    },

    herper: {
        autoResizeTextarea(element) {
            element.style.height = 'auto';
            element.style.height = element.scrollHeight + 'px';
        },
        hourIPhone() {
            const clockElement = document.getElementById('hourIPhone');

            function updateClockDisplay() {
                const now = new Date();
                let hours = now.getHours();
                let minutes = now.getMinutes();

                hours = hours < 10 ? '0' + hours : hours;
                minutes = minutes < 10 ? '0' + minutes : minutes;

                const formattedTime = `${hours}:${minutes}`;
                clockElement.textContent = formattedTime;
            }

            updateClockDisplay();
            setInterval(updateClockDisplay, 1000);
        },
        numericOnly() {
            this.value = this.value.replace(/\D/g, '');
        }
    },
    modal: {
        open(modalId) {
            const modal = document.getElementById(modalId);
            if (!modal) return;
            modal.style.display = "block";
            modal.classList.add("show");
        },
        close(modalId) {
            const modal = document.getElementById(modalId);
            if (!modal) return;

            modal.classList.remove("show");
            modal.style.display = "none";

            // Reset dos steps
            if (Whats.CreatorSteps && typeof Whats.CreatorSteps.reset === "function") {
                Whats.CreatorSteps.reset(modalId);
            }
            if (Whats.TriggerSteps && typeof Whats.TriggerSteps.reset === "function") {
                Whats.TriggerSteps.reset(modalId);
            }
        },
        init() {
            document.querySelectorAll("[data-modal-open]").forEach(btn => {
                const modalId = btn.dataset.modalOpen;
                btn.addEventListener("click", () => Whats.modal.open(modalId));
            });
            document.querySelectorAll("[data-modal-close]").forEach(btn => {
                const modalId = btn.dataset.modalClose;
                btn.addEventListener("click", () => Whats.modal.close(modalId));
            });
            document.querySelectorAll(".modal").forEach(modal => {
                modal.addEventListener("click", e => {
                    if (e.target === modal) Whats.modal.close(modal.id);
                });
            });
        }
    },

    CreatorSteps: {
        init() {
            const modal = document.getElementById("modalAddNumberCreator");
            if (!modal) return;

            const step1 = modal.querySelector(".step-1");
            const step2 = modal.querySelector(".step-2");

            const btnAdd = modal.querySelector("#btnAdd");
            const btnReuse = modal.querySelector("#btnReuse");
            const btnBackToStep1 = modal.querySelector("#btnBackToStep1");
            const btnNextToStep3 = modal.querySelector("#btnNextToStep3");
            const btnBackToStep2 = modal.querySelector("#btnBackToStep2");
            const btnSelect = modal.querySelector("#btnSelect");

            this.showStep(1);

            let selectedNumber = null;

            const goToStep1 = () => this.showStep(1);

            const goToStep2 = async () => {
                this.showStep(2);

                const listGroup = step2.querySelector(".list-group");
                listGroup.innerHTML = `<div class="p-2 text-muted">Carregando...</div>`;

                try {
                    const data = cacheCommunitys;

                    if (!data.length) {
                        listGroup.innerHTML = `<div class="p-2 text-muted">Nenhum número disponível</div>`;
                        return;
                    }

                    const grouped = {};
                    data.forEach(item => {
                        const num = item.creator.number;

                        if (!num) return;

                        if (!grouped[num]) {
                            grouped[num] = {
                                name: item.creator.name,
                                number: num,
                                total_communities: 0
                            };
                        }
                        grouped[num].total_communities++;
                    });

                    listGroup.innerHTML = "";

                    const values = Object.values(grouped);

                    if (!values.length) {
                        listGroup.innerHTML = `<div class="p-2 text-muted">Nenhum número disponível</div>`;
                        return;
                    }

                    values.forEach(item => {
                        const comunidadesCount = item.total_communities;
                        const formattedNumber = maskPhone(item.number);
                        const isDisabled = comunidadesCount >= 10;

                        const label = document.createElement("label");
                        label.className = "list-group-item list-group-item-action d-flex align-items-center";

                        label.innerHTML = `
                        <input type="radio" name="numero" value="${item.number}" class="d-none" ${isDisabled ? "disabled" : ""}>
                        <div class="d-flex align-items-center w-100" style="opacity: ${isDisabled ? 0.6 : 1}; pointer-events: ${isDisabled ? 'none' : 'auto'};">
                            <div class="me-4 text-muted" style="font-size: 1.5rem; min-width: 50px;">
                                <img src="/assets/icons/panel/phone.svg" alt="Descrição da imagem">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <strong>${item.name || "Sem nome"}</strong><br>
                                <small class="text-muted">
                                    ${formattedNumber} - ${comunidadesCount} ${GLOBAL_LANG.setting_intergration_broadcast_business_community_modal_creator_step3_subtitle_p2}${comunidadesCount > 1 ? "s" : ""}
                                </small>
                            </div>
                            <span class="checkmark text-primary">✔</span>
                        </div>
                        `;

                        if (!isDisabled) {
                            label.querySelector("input").addEventListener("change", () => {
                                selectedNumber = {
                                    number: item.number,
                                    name: item.name
                                };
                                btnNextToStep3.classList.remove("d-none");
                            });
                        }

                        listGroup.appendChild(label);
                    });
                } catch (err) {
                    console.error("Erro ao carregar números:", err);
                    listGroup.innerHTML = `<div class="p-2 text-danger">Erro ao carregar números</div>`;
                }

            };

            function loadStep3() {
                if (!selectedNumber) return;

                const step3 = document.querySelector(".step.step-3");
                const listGroup = step3.querySelector(".list-group");
                const cardSelected = step3.querySelector(".selected");
                const pText = step3.querySelector("p");

                listGroup.innerHTML = `<div class="p-2 text-muted">Carregando...</div>`;

                // filtra direto do cache
                const data = cacheCommunitys.filter(item => item.creator.number === selectedNumber.number);

                const formattedNumber = maskPhone(selectedNumber.number);
                cardSelected.innerHTML = `
                <div class="d-flex align-items-center gap-3">
                    <div class="me-4 text-muted" style="font-size: 1.5rem; min-width: 50px;">
                        <img src="/assets/icons/panel/phone.svg" alt="Telefone">
                    </div>
                    <div>
                        <div class="fw-bold">${selectedNumber.name || "Sem nome"}</div>
                        <small class="text-muted">${formattedNumber}</small>
                    </div>
                </div>`;

                pText.innerText = `${GLOBAL_LANG.setting_intergration_broadcast_business_community_modal_creator_step3_subtitle_p1} ${data.length} ${GLOBAL_LANG.setting_intergration_broadcast_business_community_modal_creator_step3_subtitle_p2}${data.length > 1 ? "s" : ""}:`;

                listGroup.innerHTML = "";

                data.forEach(item => {
                    const div = document.createElement("div");
                    div.className = "list-group-item d-flex align-items-center gap-3";
                    div.innerHTML = `
                    <div class="me-4 text-muted" style="font-size: 1.5rem; min-width: 50px;">
                        <img src="/assets/icons/panel/phone.svg" alt="Telefone">
                    </div>
                    <div>
                        <div class="fw-bold">${item.trigger.name || "Sem nome"}</div>
                        <small class="text-muted">${item.creator.name || ""}</small>
                    </div>`;
                    listGroup.appendChild(div);
                });

            }

            const goToStep3 = () => {
                if (!selectedNumber) {
                    alert("Selecione um número primeiro!");
                    return;
                }
                loadStep3();
                this.showStep(3);
            };

            btnReuse.addEventListener("click", goToStep2);
            btnBackToStep1.addEventListener("click", goToStep1);
            btnNextToStep3.addEventListener("click", goToStep3);
            btnBackToStep2.addEventListener("click", goToStep2);

            btnSelect.addEventListener("click", () => {
                if (!selectedNumber) {
                    alert("Selecione um número primeiro!");
                    return;
                }

                Whats.btn.selectCreator(selectedNumber);
            });

            btnAdd.addEventListener("click", () => Whats.btn.newCreator(step1));

        },

        showStep(stepNumber) {
            const modal = document.getElementById("modalAddNumberCreator");
            if (!modal) return;

            modal.querySelectorAll(".step").forEach(step => step.classList.add("d-none"));
            modal.querySelectorAll(".modal-footer .btn").forEach(btn => btn.classList.add("d-none"));

            if (stepNumber === 1) {
                modal.querySelector(".step-1").classList.remove("d-none");
                modal.querySelector("#btnAdd").classList.remove("d-none");
                modal.querySelector("#btnReuse").classList.remove("d-none");
            } else if (stepNumber === 2) {
                modal.querySelector(".step-2").classList.remove("d-none");
                modal.querySelector("#btnBackToStep1").classList.remove("d-none");
            } else if (stepNumber === 3) {
                modal.querySelector(".step-3").classList.remove("d-none");
                modal.querySelector("#btnBackToStep2").classList.remove("d-none");
                modal.querySelector("#btnSelect").classList.remove("d-none");
            }
        },

        reset(modal) {
            this.showStep(1);
        }
    },

    TriggerSteps: {
        init() {
            const modal = document.getElementById("modalAddNumberTrigger");
            if (!modal) return;

            const step1 = modal.querySelector(".step-1");
            const step2 = modal.querySelector(".step-2");
            const step3 = modal.querySelector(".step-3");
            const inputNumberTrigger = modal.querySelector("#input-number-trigger-modal");

            const btnAdd = modal.querySelector("#btnAddTrigger");
            const btnReuse = modal.querySelector("#btnReuseTrigger");
            const btnBackStep1 = modal.querySelector("#btnBackToStepTrigger1");
            const btnNextStep3 = modal.querySelector("#btnNextToStepTrigger3");
            const btnBackStep2 = modal.querySelector("#btnBackToStepTrigger2");
            const btnSelect = modal.querySelector("#btnSelect");

            let selectedNumber = null;

            this.showStep(1);
            modal.addEventListener("shown.bs.modal", Whats.btn.newTrigger);

            const goToStep1 = () => this.showStep(1);
            const goToStep2 = async () => {
                this.showStep(2);

                const listGroup = step2.querySelector(".list-group");
                listGroup.innerHTML = `<div class="p-2 text-muted">Carregando...</div>`;

                try {
                    const data = cacheCommunitys;

                    if (!data.length) {
                        listGroup.innerHTML = `<div class="p-2 text-muted">Nenhum número disponível</div>`;
                        return;
                    }

                    const grouped = {};
                    data.forEach(item => {
                        const num = item.trigger.number;

                        if (!num || !num.toString().trim()) return;

                        if (!grouped[num]) {
                            grouped[num] = {
                                id_channel: item.trigger.id_channel || null,
                                name: item.trigger.name,
                                number: num,
                                community_count: 0
                            };
                        }
                        grouped[num].community_count++;
                    });

                    listGroup.innerHTML = "";

                    const values = Object.values(grouped);

                    if (!values.length) {
                        listGroup.innerHTML = `<div class="p-2 text-muted">Nenhum número disponível</div>`;
                        return;
                    }

                    values.forEach(item => {
                        const disabledAttr = item.community_count >= 10 ? "disabled" : "";
                        const formattedNumber = maskPhone(item.number);

                        const label = document.createElement("label");
                        label.className = "list-group-item list-group-item-action d-flex align-items-center";

                        label.innerHTML = `
                            <input type="radio" name="number-trigger" value="${item.id_channel || item.number}" class="d-none" ${disabledAttr}>
                            <div class="d-flex align-items-center w-100" style="opacity: ${disabledAttr ? 0.6 : 1}; pointer-events: ${disabledAttr ? 'none' : 'auto'};">
                                <div class="me-4 text-muted" style="font-size: 1.5rem; min-width: 50px;">
                                    <img src="/assets/icons/panel/phone.svg" alt="Phone">
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <strong>${item.name || "Sem nome"}</strong><br>
                                    <small class="text-muted">
                                        ${formattedNumber} - ${item.community_count} ${GLOBAL_LANG.setting_intergration_broadcast_business_community_modal_creator_step3_subtitle_p2}${item.community_count > 1 ? "s" : ""}
                                    </small>
                                </div>
                            </div>
                        `;

                        if (!disabledAttr) {
                            label.querySelector("input").addEventListener("change", () => {
                                selectedNumber = {
                                    id_channel: item.id_channel || null,
                                    trigger: item.number,
                                    name: item.name
                                };
                                btnNextStep3.classList.remove("d-none");
                            });
                        }

                        listGroup.appendChild(label);
                    });
                } catch (err) {
                    console.error("Erro ao buscar números (trigger):", err);
                    step2.querySelector(".list-group").innerHTML = `<div class="p-2 text-danger">Erro ao carregar números</div>`;
                }

            };

            const goToStep3 = async () => {
                if (!selectedNumber) {
                    alert("Selecione um número primeiro!");
                    return;
                }

                this.showStep(3);

                const step3List = step3.querySelector(".list-group");
                const cardSelected = step3.querySelector(".selected div");
                const pText = step3.querySelector("p");

                // Aplica máscara no número do card selecionado
                cardSelected.innerHTML = `
                <div class="d-flex align-items-center gap-3">
                    <div class="me-4 text-muted" style="font-size: 1.5rem; min-width: 50px;">
                        <img src="/assets/icons/panel/phone.svg" alt="Telefone">
                    </div>
                    <div>
                        <div class="fw-bold">${selectedNumber.name}</div>
                        <small class="text-muted">${maskPhone(selectedNumber.trigger)}</small>
                    </div>
                </div>`;

                step3List.innerHTML = `<div class="p-2 text-muted">Carregando...</div>`;

                try {
                    // filtra direto do cache os triggers que correspondem ao selecionado
                    const data = cacheCommunitys.filter(item => item.trigger.number === selectedNumber.trigger);

                    // texto acima da lista
                    pText.innerText = `${GLOBAL_LANG.setting_intergration_broadcast_business_community_modal_trigger_step3_subtitle_p1} ${data.length} ${GLOBAL_LANG.setting_intergration_broadcast_business_community_modal_trigger_step3_subtitle_p2}${data.length > 1 ? "s" : ""}:`;

                    step3List.innerHTML = "";

                    data.forEach(item => {
                        const div = document.createElement("div");
                        div.className = "list-group-item d-flex align-items-center gap-3";
                        div.innerHTML = `
                            <div class="me-4 text-muted" style="font-size: 1.5rem; min-width: 50px;">
                                <img src="/assets/icons/panel/phone.svg" alt="Telefone">
                            </div>
                            <div>
                                <div class="fw-bold">${item.name || "Sem nome"}</div>
                                <small class="text-muted">${item.creator.name} - ${maskPhone(item.creator.number)}</small>
                            </div>`;

                        step3List.appendChild(div);
                    });

                } catch (err) {
                    console.error("Erro ao carregar triggers:", err);
                    step3List.innerHTML = `<div class="p-2 text-danger">Erro ao carregar números</div>`;
                }
            };

            // Eventos dos botões
            btnReuse.addEventListener("click", goToStep2);
            btnBackStep1.addEventListener("click", goToStep1);
            btnNextStep3.addEventListener("click", goToStep3);
            btnBackStep2.addEventListener("click", goToStep2);

            btnSelect.addEventListener("click", () => {
                if (!selectedNumber) {
                    console.warn("Nenhum número selecionado.");
                    return;
                }

                Whats.btn.selectTrigger(selectedNumber);
            });

            btnAdd.addEventListener("click", Whats.btn.newTrigger);

        },

        showStep(stepNumber) {
            const modal = document.getElementById("modalAddNumberTrigger");
            if (!modal) return;

            modal.querySelectorAll(".step").forEach(step => step.classList.add("d-none"));

            // Step 1
            modal.querySelector(".step-1").classList.toggle("d-none", stepNumber !== 1);
            modal.querySelector("#btnAddTrigger").classList.toggle("d-none", stepNumber !== 1);
            modal.querySelector("#btnReuseTrigger").classList.toggle("d-none", stepNumber !== 1);

            // Step 2
            modal.querySelector(".step-2").classList.toggle("d-none", stepNumber !== 2);
            modal.querySelector("#btnBackToStepTrigger1").classList.toggle("d-none", stepNumber !== 2);
            modal.querySelector("#btnNextToStepTrigger3").classList.add("d-none");

            // Step 3
            modal.querySelector(".step-3").classList.toggle("d-none", stepNumber !== 3);
            modal.querySelector("#btnBackToStepTrigger2").classList.toggle("d-none", stepNumber !== 3);
            modal.querySelector("#btnSelect").classList.toggle("d-none", stepNumber !== 3);
        },

        reset(modal) {
            this.showStep(1);
        }
    }

}

function maskPhone(phone) {
    if (!phone) return "";

    let num = phone.replace(/\D/g, "");

    if (num.length > 11 && num.startsWith("55")) {
        num = num.substring(2);
    }

    if (num.length === 11) {
        return num.replace(/(\d{2})(\d{5})(\d{4})/, "($1) $2-$3");
    }

    if (num.length === 10) {
        return num.replace(/(\d{2})(\d{4})(\d{4})/, "($1) $2-$3");
    }

    return phone;
}

document.addEventListener('DOMContentLoaded', function () {
    Whats.herper.hourIPhone();
    Whats.card.first();

    Whats.modal.init();
    Whats.CreatorSteps.init();
    Whats.TriggerSteps.init();
    Whats.cache.CommunityChannelsCache();

    document.getElementById("input-number-trigger").addEventListener("change", Whats.form.change.trigger);
    document.getElementById("input-name").addEventListener("keyup", Whats.form.keyup.name);
    document.getElementById("textarea-content").addEventListener("keyup", Whats.form.keyup.description);

    document.getElementById("btnBookEvaluation").addEventListener("click", Whats.btn.bookEvaluation);
    document.getElementById("btnBookMeeting").addEventListener("click", Whats.btn.bookMeeting);
    document.getElementById("btnCallBack").addEventListener("click", Whats.btn.callBack);

    document.querySelector('._preview').addEventListener("mouseover", Whats.form.mouseOverProrile);
    document.querySelector('._preview').addEventListener("mouseout", Whats.form.mouseOutProrile);
    document.querySelector('.profile-overlay').addEventListener("click", Whats.form.handleFiles);

    document.querySelector('._preview_trigger').addEventListener("mouseover", Whats.form.mouseOverProrileTrigger);
    document.querySelector('._preview_trigger').addEventListener("mouseout", Whats.form.mouseOutProrileTrigger);
    document.querySelector('.profile-overlay-trigger').addEventListener("click", Whats.form.handleFilesTrigger);

    document.querySelector('#btnNewChannel').addEventListener("click", Whats.btn.new);
    document.querySelector('#btnDeleteChannel').addEventListener("click", Whats.btn.delete);
    document.querySelector('#btnDuplicateChannel').addEventListener("click", Whats.btn.duplicate);
});
