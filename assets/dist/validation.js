
function formValidation(data) {
    if (document.getElementById("alert__" + data.field.id)) document.getElementById("alert__" + data.field.id).style.display = "none";

    if (data.required) {
        if (data.field.value.trim() === "") {
            document.getElementById("alert__" + data.field.id).style.display = "block";
            document.getElementById("alert__" + data.field.id).innerHTML = GLOBAL_LANG.form_validation_required.replace('{field}', data.text);
            return;
        }
    }

    if (data.min) {
        if (data.field.value.trim() !== "" && data.field.value.trim().length < data.min) {
            document.getElementById("alert__" + data.field.id).style.display = "block";
            document.getElementById("alert__" + data.field.id).innerHTML = GLOBAL_LANG.form_validation_min_length.replace('{field}', data.text).replace('{param}', data.min);
            return;
        }
    }

    if (data.max) {
        if (data.field.value.trim().length > data.max) {
            document.getElementById("alert__" + data.field.id).style.display = "block";
            document.getElementById("alert__" + data.field.id).innerHTML = GLOBAL_LANG.form_validation_max_length.replace('{field}', data.text).replace('{param}', data.max);
            return;
        }
    }

    if (data.email) {

        const email_regex = /\S+@\S+\.\S+/;
        if (!email_regex.test(data.field.value.trim())) {
            document.getElementById("alert__" + data.field.id).style.display = "block";
            document.getElementById("alert__" + data.field.id).innerHTML = GLOBAL_LANG.form_validation_valid_email.replace('{field}', data.text);
            return;
        }
    }

    if (data.email_in_use) {
        document.getElementById("alert__" + data.field.id).style.display = "block";
        document.getElementById("alert__" + data.field.id).innerHTML = GLOBAL_LANG.form_validation_email_in_use;
        return;
    }

    if (data.value_between) {
        if (data.field.value.trim() < data.value_between[0] || data.field.value.trim() > data.value_between[1]) {
            document.getElementById("alert__" + data.field.id).style.display = "block";
            document.getElementById("alert__" + data.field.id).innerHTML = GLOBAL_LANG.form_validation_value_between.replace('{field}', data.text).replace('{param1}', data.value_between[0]).replace('{param2}', data.value_between[1]);
            return
        }
    }

    if (data.alpha_numeric_spaces) {

        const regex = /^[A-Z0-9 ]+$/i;
        if (!regex.test(data.field.value.trim())) {
            document.getElementById("alert__" + data.field.id).style.display = "block";
            document.getElementById("alert__" + data.field.id).innerHTML = GLOBAL_LANG.form_validation_alpha_numeric_spaces.replace('{field}', data.text);
            return;
        }
    }

    if (data.numeric_length) {

        const numbers = data.field.value.trim().match(/[0-9:]/g).length;
        if (numbers < data.numeric_length) {
            document.getElementById("alert__" + data.field.id).style.display = "block";
            document.getElementById("alert__" + data.field.id).innerHTML = GLOBAL_LANG.form_validation_exact_length.replace('{field}', data.text).replace('{param}', data.numeric_length);
            return;
        }
    }

    if (data.is_date_time_less_than_current) {

        const current_date_time = new Date();
        const checked_date_time = new Date(data.field.value);

        if (checked_date_time <= current_date_time) {
            document.getElementById("alert__" + data.field.id).style.display = "block";
            document.getElementById("alert__" + data.field.id).innerHTML = GLOBAL_LANG.form_validation_date_smaller_than_current;
            return;
        }
    }

    if (data.comparing_two_date_time) {

        const date_time = new Date(data.field.value);
        const date_time2 = new Date(data.field2.value);

        if (date_time <= date_time2) {
            document.getElementById("alert__" + data.field.id).style.display = "block";
            document.getElementById("alert__" + data.field.id).innerHTML = GLOBAL_LANG.form_validation_comparing_datetime.replace('{param}', data.text);
            return;
        }
    }


    return true;
    
}
