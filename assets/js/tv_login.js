"use strict";

document.querySelector("input").focus();

const TvConnection = {
    connect: function (connection_code) {
        return new Promise(async (reject) => {
            try {
                const formData = new FormData();
                formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));
                formData.append("connection_code", connection_code);

                const response = await fetch(document.location.origin + "/tv/connect", {
                    method: "POST",
                    body: formData
                });

                if (response.ok) {
                    const data = await response.json();

                    if (data.success) {
                        localStorage.setItem("tv_token", data.success.tv_token)
                        window.location.href = window.location.origin + "/tv/" + data.success.tv_token.replace("tv-", "");
                    } else {

                        if (data.error.code === "TA-006") {
                            FormManager.alert();
                        } else {
                            console.log("Error", data.error.code)
                        }
                    }
                }

            } catch (error) {
                console.log("error: ", error);
                reject(error);
            }

        });
    }
}


const FormManager = {
    autoTabFields: function () {
        document.querySelectorAll(".box-input input").forEach(function (input, index, array) {
            input.addEventListener("input", function () {
                this.value = this.value.replace(/[^\d]/g, "").trim();

                if (this.value.length == this.maxLength) {
                    const nextInput = this.nextElementSibling;
                    if (nextInput && nextInput.tagName === "INPUT") {
                        nextInput.focus();
                    }

                    if (index === array.length - 1) {
                        FormManager.validateFields(array);
                    }
                }
            });
        });
    },

    validateFields: function (inputs) {
        const allFilled = Array.from(inputs).every(input => input.value.length === input.maxLength);

        if (allFilled) {
            let connection_code = "";
            inputs.forEach(function (input) {
                input.readOnly = true;
                connection_code += input.value;
            });
            TvConnection.connect(connection_code);
        }
    },

    alert: function () {
        const div_alert = document.querySelector(".alert-field-validation");
        div_alert.innerHTML = GLOBAL_LANG.tv_login_alert_invalid_code;
        div_alert.style.display = "block";

        [...document.querySelectorAll("input")].forEach(function (input, index) {

            input.style.color = "#BE185D";
            input.style.border = "1px solid #BE185D";
            input.style.backgroundColor = "rgba(255, 0, 0, 0.1)";

            setTimeout(function () {
                input.value = "";
                input.style.color = "";
                input.style.border = "";
                input.style.backgroundColor = "";
                input.readOnly = false;
                document.querySelector(".alert-field-validation").style.display = "none";

                if (index === 0) {
                    input.focus();
                }
            }, 3000);
        });
    },
};


FormManager.autoTabFields();
