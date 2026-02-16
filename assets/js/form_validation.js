function max_length(data, max) {

    if (data === undefined) return false;
    if (max === undefined) return false;

    const text = data.trim();

    if (text.length >= max) {
        return false;
    }

    return true;
}


function min_length(data, min) {

    if (data === undefined) return false;
    if (min === undefined) return false;

    const text = data.trim();

    if (text.length < min && text !== "") {
        return false;
    }

    return true;
}


function emptyText(data) {

    const type = typeof (data);

    if (data === undefined) return false;
    if (type != 'string') return false;

    if (data.trim() === "") {
        return false;
    }

    return true;
}


function checkNegativeNumber(num) {

    if (num === undefined) return false;

    if (num.trim() < 0) {
        return true;
    } else {
        return false;
    }
}


function max_lengthNumber(num, limit) {

    if (num === undefined) return false;
    if (limit === undefined) return false;

    if (num.trim() <= limit) {
        return false;
    }

    return true;
}


function maskPhone(e, f) {
    setTimeout(function () {
        const num = checkIsPhone(e.value);
        if (num != e.value) {
            e.value = num;
        }

    }, 1);
}


function checkIsPhone(v) {
    const r = v.replace(/\D/g, "");
    let num = r.replace(/^0/, "");

    if (num.length > 10)
        num = num.replace(/^(\d\d)(\d{5})(\d{4}).*/, "($1) $2-$3");
    else if (num.length > 5)
        num = num.replace(/^(\d\d)(\d{4})(\d{0,4}).*/, "($1) $2-$3");
    else if (num.length > 2)
        num = num.replace(/^(\d\d)(\d{0,5})/, "($1) $2");
    else
        num = num.replace(/^(\d*)/, "($1");
    return num;
}