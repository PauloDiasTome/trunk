"use strict";

let Channels = [];
let ChannelSelected = [];

const Whats = {
    form: {
        handleFiles() {
            const file = document.getElementById("input-file");

            file.click();
            file.addEventListener("change", Whats.form.uploadFiles);
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
        recover() {
            document.getElementById("file").value = ChannelSelected[0].file == "" ? window.location.origin + '/assets/img/avatar.svg' : ChannelSelected[0].file;
            document.getElementById("preview_form").src = ChannelSelected[0].file == "" ? window.location.origin + '/assets/img/avatar.svg' : ChannelSelected[0].file;

            if (document.getElementById("input-phone").value = ChannelSelected[0].phone == "00 00 00000-0000") {
                document.getElementById("input-phone").value = "";
            } else {
                document.getElementById("input-phone").value = ChannelSelected[0].phone;
            }

            document.getElementById("input-name").value = ChannelSelected[0].name;
            document.getElementById("textarea-content").value = ChannelSelected[0].description;
            document.getElementById("input-address").value = ChannelSelected[0].address;
            document.getElementById("input-email").value = ChannelSelected[0].email;
            document.getElementById("input-site").value = ChannelSelected[0].site;
        },
        clear() {
            document.getElementById("input-name").value = null;
            document.getElementById("input-phone").value = null;
            document.getElementById("preview_form").src = window.location.origin + "/assets/img/avatar.svg";
            document.getElementById("textarea-content").value = null;
            document.getElementById("file").value = null;
            document.getElementById("input-address").value = null;
            document.getElementById("input-email").value = null;
            document.getElementById("input-site").value = null;

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
            number() {
                Whats.card.borderGray();
                Whats.preview.number(this);
                if (Whats.card.isSelected())
                    Whats.cache.update({ phone: this.value.trim() });
            },
            description() {
                Whats.preview.description(this);
                if (Whats.card.isSelected())
                    Whats.cache.update({ description: this.value.trim() });
            },
            address() {
                Whats.preview.address(this);
                if (Whats.card.isSelected())
                    Whats.cache.update({ address: this.value.trim() });
            },
            email() {
                Whats.preview.email(this);
                if (Whats.card.isSelected())
                    Whats.cache.update({ email: this.value.trim() });
            },
            site() {
                Whats.preview.site(this);
                if (Whats.card.isSelected())
                    Whats.cache.update({ site: this.value.trim() });
            }
        },
        clearAlert() {
            document.querySelectorAll(".alert-field-validation").forEach(elm => {
                elm.style.display = "none";
            });
        }
    },

    btn: {
        validation() {
            Whats.validation.name();
            Whats.validation.file();
            Whats.validation.phone();
            Whats.validation.description();
        },
        async bookEvaluation() {
            this.disabled = true;
            Whats.btn.addLoad("arrow_right_advance", "load_advance");

            const validName = Whats.validation.name();
            const validFile = Whats.validation.file();
            const validPhone = await Whats.validation.phone();
            const validDescription = Whats.validation.description();
            const cacheNumberDuplicate = Whats.validation.cacheNumberDuplicate();
            const cacheEmpaty = Whats.validation.cacheEmpaty();
            const cacheNumberInvalid = Whats.validation.cacheNumberInvalid();
            const cacheNameInvalid = Whats.validation.cacheNameInvalid();

            const cacheInvalids = [cacheEmpaty, cacheNumberInvalid, cacheNameInvalid];
            const borderInvalid = cacheInvalids.find(cacheInvalid => cacheInvalid !== false);

            if (borderInvalid) {
                Whats.card.borderRed(borderInvalid);
                Whats.btn.removeLoad("arrow_right_advance", "load_advance");
                this.disabled = false;
                return
            }

            if (!validName || !validFile || !validPhone || !validDescription || cacheNumberDuplicate) {
                Whats.btn.removeLoad("arrow_right_advance", "load_advance");
                this.disabled = false;
                return;
            }

            for (let i = 0; i < Channels.length; i++) {
                const isNotDuplicate = await Whats.validation.NumberDuplicateDataBase(Channels[i].phone);

                if (isNotDuplicate == true) {
                    Whats.card.borderRed(Channels[i].uuid);
                    Whats.btn.removeLoad("arrow_right_advance", "load_advance");
                    this.disabled = false;
                    return;
                }
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
                    channels: JSON.stringify(Channels),
                    [csrfTokenName]: csrfTokenValue
                };

                const urlEncodedBody = new URLSearchParams(dataToEncode).toString();

                const result = await fetch(`${document.location.origin}/integration/save/whatsapp/broadcast`, {
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
            const validPhone = await Whats.validation.phone();
            const validDescription = Whats.validation.description();

            if (validName && validPhone && validDescription && validFile) {

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

            Whats.cache.delete(ChannelSelected[0].uuid);
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
        }
    },

    card: {
        add(uuid, name, phone) {
            const card = document.createElement("div");
            card.id = uuid;
            card.className = "_channel _selected";
            card.addEventListener("click", Whats.card.click);

            const title = document.createElement("span");
            title.classList.add("title");
            title.textContent = `${GLOBAL_LANG.setting_intergration_broadcast_business_form_channel} ${Whats.card.next()}`;

            const phoneChannel = document.createElement("span");
            phoneChannel.classList.add("phone");
            if (phone == "") phoneChannel.textContent = "00 00 00000-0000"; else phoneChannel.textContent = phone;

            const nameChannel = document.createElement("span");
            nameChannel.classList.add("name");
            if (name == "") nameChannel.textContent = GLOBAL_LANG.setting_intergration_broadcast_business_form_name_placeholder; else nameChannel.textContent = name;

            const arrow = document.createElement("i");
            arrow.classList.add("fas");
            arrow.classList.add("fa-arrow-right");

            card.appendChild(title);
            card.appendChild(phoneChannel);
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
            for (let i = 0; i < Channels.length; i++) {
                const channel = Channels[i];
                this.add(channel.uuid, channel.name, channel.phone);
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
            return ChannelSelected.length > 0;
        },
        first() {
            document.querySelector(".first__")?.remove();

            const uuid = Math.floor(Math.random() * 100000);
            const name = "";
            const phone = "";
            const file = "";
            const description = "";
            const address = "";
            const email = "";
            const site = "";

            Whats.card.add(uuid, name, phone);

            Whats.cache.add({
                uuid,
                name,
                phone,
                file,
                description,
                address,
                email,
                site
            });

            const element = document.querySelector('._selected');
            Whats.card.selected(element);
        },
        clone() {
            document.querySelector(".first__")?.remove();

            const uuid = Math.floor(Math.random() * 100000);
            const name = ChannelSelected[0].name;
            const phone = ChannelSelected[0].phone;
            const file = ChannelSelected[0].file;
            const description = ChannelSelected[0].description;
            const address = ChannelSelected[0].address;
            const email = ChannelSelected[0].email;
            const site = ChannelSelected[0].site;

            Whats.card.deselected();
            Whats.card.add(uuid, name, phone);

            Whats.cache.add({
                uuid,
                name,
                phone,
                file,
                description,
                address,
                email,
                site
            });

            const element = document.querySelector('._selected');
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
                    const card = document.getElementById(ChannelSelected[0].uuid);
                    const inputName = document.getElementById("input-name");
                    const name = inputName.value.trim() || GLOBAL_LANG.setting_intergration_broadcast_business_form_name_placeholder;
                    if (card) {
                        card.children[2].textContent = name;
                    }
                }
            },
            phone() {
                if (Whats.card.isSelected()) {
                    const card = document.getElementById(ChannelSelected[0].uuid);
                    const inputPhone = document.getElementById('input-phone');
                    const phone = inputPhone.value.trim() || "00 00 00000-0000";
                    if (card) {
                        card.children[1].textContent = phone;
                    }
                }
            }
        }
    },

    cache: {
        add(data) {
            Channels.push({
                uuid: data.uuid,
                name: data.name,
                phone: data.phone,
                file: data.file,
                description: data.description,
                address: data.address,
                email: data.email,
                site: data.site
            });
        },
        update(data) {
            const index = Channels.findIndex(channel => channel.uuid == ChannelSelected[0].uuid);
            if (index > -1) {
                const name = data.hasOwnProperty('name') ? data.name : ChannelSelected[0].name;
                const phone = data.hasOwnProperty('phone') ? data.phone : ChannelSelected[0].phone;
                const file = data.hasOwnProperty('file') ? data.file : ChannelSelected[0].file;
                const description = data.hasOwnProperty('description') ? data.description : ChannelSelected[0].description;
                const address = data.hasOwnProperty('address') ? data.address : ChannelSelected[0].address;
                const email = data.hasOwnProperty('email') ? data.email : ChannelSelected[0].email;
                const site = data.hasOwnProperty('site') ? data.site : ChannelSelected[0].site;

                Channels[index] = {
                    uuid: ChannelSelected[0].uuid,
                    name,
                    phone,
                    file,
                    description,
                    address,
                    email,
                    site
                };

                ChannelSelected[0].uuid = ChannelSelected[0].uuid;
                ChannelSelected[0].name = name;
                ChannelSelected[0].phone = phone;
                ChannelSelected[0].file = file;
                ChannelSelected[0].description = description;
                ChannelSelected[0].address = address;
                ChannelSelected[0].email = email;
                ChannelSelected[0].site = site;
            }
        },
        delete(id) {
            const index = Channels.findIndex(channel => channel.uuid === id);
            if (index > -1) {
                Channels.splice(index, 1);
            }
        },
        search(uuid) {
            return Channels.find(channel => channel.uuid == uuid);
        },
        selected(data) {
            this.deselected();
            ChannelSelected.push({
                uuid: data.uuid,
                name: data.name,
                phone: data.phone,
                file: data.file,
                description: data.description,
                address: data.address,
                email: data.email,
                site: data.site
            });
        },
        deselected() {
            ChannelSelected = [];
        }
    },

    validation: {
        name() {
            const name = document.getElementById("input-name").value.trim();
            const alertMessage = document.getElementById("alert__input-name");

            if (name === "" || name.length < 3) {
                alertMessage.textContent = name === "" ? GLOBAL_LANG.setting_intergration_broadcast_business_validation_name_required : GLOBAL_LANG.setting_intergration_broadcast_business_validation_name_min_length;
                alertMessage.style.display = "block";
                return false;
            } else {
                alertMessage.style.display = "none";
                return true;
            }
        },
        NumberDuplicate() {
            const number = document.getElementById("input-phone").value.trim();
            let isDuplicate = false;
            const normalizedNumber = number.startsWith("55") ? number.slice(4) : number;

            document.querySelectorAll("._channel:not(._selected)").forEach(item => {
                const itemNumber = item.children[1].innerText.trim();
                const normalizedItemNumber = itemNumber.startsWith("55") ? itemNumber.slice(4) : itemNumber;

                if (normalizedNumber === normalizedItemNumber ||
                    normalizedNumber.slice(1) === normalizedItemNumber ||
                    normalizedNumber === normalizedItemNumber.slice(1)) {
                    isDuplicate = true;
                }
            });

            return isDuplicate;
        },
        NumberDuplicateDataBase(number) {
            return new Promise((resolve, reject) => {
                fetch(`${document.location.origin}/integration/whatsapp/broadcast/phone/duplicate/${number}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                        resolve(data.status);
                    })
                    .catch(error => {
                        reject(false);
                    });
            });
        },
        cacheNumberInvalid() {
            return Channels.find(channel => channel.phone.length < 11)?.uuid || false;
        },
        cacheNameInvalid() {
            return Channels.find(channel => channel.name.length < 3)?.uuid || false;
        },
        cacheNumberDuplicate() {
            return Channels.find((channel, index) => {
                const firstTwoDigits = channel.phone.slice(0, 2);
                const fourFirstDigits = channel.phone.slice(0, 4);
                const rest = channel.phone.slice(firstTwoDigits === "55" ? 4 : 2);

                return Channels.slice(index + 1).some(item => {
                    const itemFirstTwoDigits = item.phone.slice(0, 2);
                    const itemFourFirstDigits = item.phone.slice(0, 4);
                    const itemRest = item.phone.slice(itemFirstTwoDigits === "55" ? 4 : 2);

                    return (itemFirstTwoDigits === firstTwoDigits && itemFourFirstDigits === fourFirstDigits && itemRest === rest) ||
                        (itemFirstTwoDigits === firstTwoDigits && itemFourFirstDigits !== fourFirstDigits && itemRest === rest.slice(1));
                }) ? channel.uuid : false;
            });
        },
        cacheEmpaty() {
            return Channels.find(channel => ["name", "phone", "description", "file"].some(field => channel[field] === "" || channel[field] === null))?.uuid || false;
        },
        file() {
            const file = document.getElementById("file").value.trim();
            const alertMessage = document.getElementById("alert__file");
            const currentHost = window.location.origin;

            if (file === "" || file === `${currentHost}/assets/img/avatar.svg`) {
                alertMessage.textContent = GLOBAL_LANG.setting_intergration_broadcast_business_validation_file;
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
                    alertMessage.textContent = GLOBAL_LANG.setting_intergration_broadcast_business_validation_file_size;
                    alertMessage.style.display = "block";
                    return reject(false);
                }

                const validTypes = ["image/jpeg", "image/gif"];
                if (!validTypes.includes(file.type)) {
                    alertMessage.textContent = GLOBAL_LANG.setting_intergration_broadcast_business_validation_file_type;
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
                            alertMessage.textContent = `${GLOBAL_LANG.setting_intergration_broadcast_business_validation_file_maxSize} ${maxSize}x${maxSize}.`;
                            alertMessage.style.display = "block";
                            return reject(false);
                        }
                        resolve(true);
                    };

                    img.onerror = () => {
                        alertMessage.textContent = GLOBAL_LANG.setting_intergration_broadcast_business_validation_file_upload;
                        alertMessage.style.display = "block";
                        reject(false);
                    };

                    img.src = evt.target.result;
                };

                reader.onerror = () => {
                    alertMessage.textContent = GLOBAL_LANG.setting_intergration_broadcast_business_validation_file_read;
                    alertMessage.style.display = "block";
                    reject(false);
                };
                reader.readAsDataURL(file);
            });
        },
        phone() {
            return new Promise((resolve, reject) => {
                const number = document.getElementById("input-phone").value.trim();
                const alertMessage = document.getElementById("alert__input-phone");

                if (number === "") {
                    alertMessage.textContent = GLOBAL_LANG.setting_intergration_broadcast_business_validation_phone_required;
                    alertMessage.style.display = "block";
                    resolve(false);
                    return;
                }

                if (number.length < 11) {
                    alertMessage.textContent = GLOBAL_LANG.setting_intergration_broadcast_business_validation_phone_length;
                    alertMessage.style.display = "block";
                    resolve(false);
                    return;
                }

                Whats.validation.NumberDuplicateDataBase(document.getElementById("input-phone").value.trim())
                    .then(isNotDuplicate => {
                        if (Whats.validation.NumberDuplicate() || isNotDuplicate) {
                            alertMessage.textContent = GLOBAL_LANG.setting_intergration_broadcast_business_validation_phone_duplicate;
                            alertMessage.style.display = "block";
                            resolve(false);
                            return;
                        }

                        alertMessage.style.display = "none";
                        resolve(true);
                        return;

                    }).catch(() => {
                        alertMessage.textContent = GLOBAL_LANG.setting_intergration_broadcast_business_validation_phone_duplicate;
                        alertMessage.style.display = "block";
                        reject(false);
                    });
            });
        },
        description() {
            const description = document.getElementById("textarea-content").value.trim();
            const alertMessage = document.getElementById("alert__textarea-content");

            if (description === "") {
                alertMessage.textContent = GLOBAL_LANG.setting_intergration_broadcast_business_validation_description_required;
                alertMessage.style.display = "block";
                return false;
            } else {
                alertMessage.style.display = "none";
                return true;
            }
        },
        repeatedPhone(phone) {
            const channels = Channels.filter(channel => channel.phone == phone);
            return channels.length > 1;
        }
    },

    preview: {
        photo(url) {
            const previewPhoto = document.getElementById("preview_photo");
            previewPhoto.src = url;
        },
        name(elm) {
            const previewName = document.getElementById("preview_name");
            previewName.textContent = elm.value.trim() || GLOBAL_LANG.setting_intergration_broadcast_business_preview_name;
            Whats.card.preview.name();
        },
        number(elm) {
            const previewNumber = document.getElementById("preview_phone");
            previewNumber.textContent = elm.value.trim() || GLOBAL_LANG.setting_intergration_broadcast_business_preview_phone;
            Whats.card.preview.phone();
        },
        description(elm) {
            const previewDescription = document.getElementById("preview_description");
            const newValue = elm.value.trim() || GLOBAL_LANG.setting_intergration_broadcast_business_preview_desciption;
            previewDescription.value = newValue;

            Whats.herper.autoResizeTextarea(previewDescription);
        },
        address(elm) {
            const previewAddress = document.getElementById("preview_address");
            previewAddress.value = elm.value.trim() || GLOBAL_LANG.setting_intergration_broadcast_business_preview_address;
        },
        email(elm) {
            const previewEmail = document.getElementById("preview_email");
            const trimmedValue = elm.value.trim();

            previewEmail.value = trimmedValue;
            previewEmail.placeholder = trimmedValue === "" ? GLOBAL_LANG.setting_intergration_broadcast_business_preview_email : "";
        },
        site(elm) {
            const previewSite = document.getElementById("preview_site");
            const trimmedValue = elm.value.trim();

            previewSite.value = trimmedValue;
            previewSite.placeholder = trimmedValue === "" ? GLOBAL_LANG.setting_intergration_broadcast_business_preview_site : "";
        },
        recover() {
            document.getElementById("preview_photo").src = ChannelSelected[0].file == "" ? window.location.origin + '/assets/img/avatar.svg' : ChannelSelected[0].file;

            if (ChannelSelected[0].phone !== "")
                document.getElementById("preview_phone").textContent = ChannelSelected[0].phone;

            if (ChannelSelected[0].name !== "")
                document.getElementById("preview_name").textContent = ChannelSelected[0].name;
            else
                document.getElementById("preview_name").textContent = GLOBAL_LANG.setting_intergration_broadcast_business_form_name_placeholder;

            document.getElementById("preview_description").value = ChannelSelected[0].description;
            document.getElementById("preview_address").value = ChannelSelected[0].address;
            document.getElementById("preview_email").value = ChannelSelected[0].email;
            document.getElementById("preview_site").value = ChannelSelected[0].site;
        },
        clear() {
            document.getElementById("preview_photo").src = window.location.origin + "/assets/img/avatar.svg";
            document.getElementById("preview_name").textContent = GLOBAL_LANG.setting_intergration_broadcast_business_preview_name;
            document.getElementById("preview_phone").textContent = GLOBAL_LANG.setting_intergration_broadcast_business_preview_phone;
            document.getElementById("preview_description").value = GLOBAL_LANG.setting_intergration_broadcast_business_preview_desciption
            document.getElementById("preview_address").value = GLOBAL_LANG.setting_intergration_broadcast_business_preview_address;
            document.getElementById("preview_email").value = "";
            document.getElementById("preview_email").placeholder = GLOBAL_LANG.setting_intergration_broadcast_business_preview_email;
            document.getElementById("preview_site").value = "";
            document.getElementById("preview_site").placeholder = GLOBAL_LANG.setting_intergration_broadcast_business_preview_site;
        }
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
    }
}

document.addEventListener('DOMContentLoaded', function () {
    Whats.herper.hourIPhone();
    Whats.card.first();

    document.getElementById("input-name").addEventListener("keyup", Whats.form.keyup.name);
    document.getElementById("input-phone").addEventListener("keyup", Whats.form.keyup.number);
    document.getElementById("input-phone").addEventListener("input", Whats.herper.numericOnly);
    document.getElementById("input-phone").addEventListener("change", Whats.validation.phone);
    document.getElementById("textarea-content").addEventListener("keyup", Whats.form.keyup.description);
    document.getElementById("input-address").addEventListener("keyup", Whats.form.keyup.address);
    document.getElementById("input-email").addEventListener("keyup", Whats.form.keyup.email);
    document.getElementById("input-site").addEventListener("keyup", Whats.form.keyup.site);

    document.getElementById("btnBookEvaluation").addEventListener("click", Whats.btn.bookEvaluation);
    document.getElementById("btnBookMeeting").addEventListener("click", Whats.btn.bookMeeting);
    document.getElementById("btnCallBack").addEventListener("click", Whats.btn.callBack);

    document.querySelector('._preview').addEventListener("mouseover", Whats.form.mouseOverProrile);
    document.querySelector('._preview').addEventListener("mouseout", Whats.form.mouseOutProrile);
    document.querySelector('.profile-overlay').addEventListener("click", Whats.form.handleFiles);

    document.querySelector('#btnNewChannel').addEventListener("click", Whats.btn.new);
    document.querySelector('#btnDeleteChannel').addEventListener("click", Whats.btn.delete);
    document.querySelector('#btnDuplicateChannel').addEventListener("click", Whats.btn.duplicate);
});
