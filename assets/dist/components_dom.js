class ComponentsDom {
    separate(attr) {
        const data = [];
        for (let i = 0; i < attr.length; i++) {
            data.push([attr[i], attr[i + 1]]);
            i++;
        }

        return data;
    }

    span(...attr) {
        if (attr.length == 0) return document.createElement("span");

        const el = document.createElement("span");
        if (attr[0].class != undefined) el.className = attr[0].class;
        if (attr[0].id != undefined) el.id = attr[0].id;
        if (attr[0].text != undefined) el.innerHTML = attr[0].text;
        if (attr[0].hidden != undefined) el.hidden = attr[0].hidden;
        if (attr[0].style != undefined) el.style = attr[0].style;
        if (attr[0].customAttribute != undefined) {
            const res = this.separate(attr[0].customAttribute);
            for (let i = 0; i < res.length; i++) {
                el.setAttribute(`${res[i][0]}`, res[i][1]);
            }
        }

        return el;
    }

    label(...attr) {
        if (attr.length == 0) return document.createElement("label");

        const el = document.createElement("label");
        if (attr[0].class != undefined) el.className = attr[0].class;
        if (attr[0].id != undefined) el.id = attr[0].id;
        if (attr[0].text != undefined) el.innerHTML = attr[0].text;
        if (attr[0].disabled != undefined) el.disabled = attr[0].disabled;
        if (attr[0].hidden != undefined) el.hidden = attr[0].hidden;
        if (attr[0].for != undefined) el.setAttribute("for", attr[0].for);
        if (attr[0].style != undefined) el.style = attr[0].style;
        if (attr[0].customAttribute != undefined) {
            const res = this.separate(attr[0].customAttribute);
            for (let i = 0; i < res.length; i++) {
                el.setAttribute(`${res[i][0]}`, res[i][1]);
            }
        }

        return el;
    }

    div(...attr) {
        if (attr.length == 0) return document.createElement("div");

        const el = document.createElement("div");
        if (attr[0].class != undefined) el.className = attr[0].class;
        if (attr[0].id != undefined) el.id = attr[0].id;
        if (attr[0].text != undefined) el.innerHTML = attr[0].text;
        if (attr[0].disabled != undefined) el.disabled = attr[0].disabled;
        if (attr[0].hidden != undefined) el.hidden = attr[0].hidden;
        if (attr[0].style != undefined) el.style = attr[0].style;
        if (attr[0].customAttribute != undefined) {
            const res = this.separate(attr[0].customAttribute);
            for (let i = 0; i < res.length; i++) {
                el.setAttribute(`${res[i][0]}`, res[i][1]);
            }
        }

        return el;
    }

    input(...attr) {
        if (attr.length == 0) return document.createElement("input");

        const el = document.createElement("input");
        if (attr[0].class != undefined) el.className = attr[0].class;
        if (attr[0].id != undefined) el.id = attr[0].id;
        if (attr[0].name != undefined) el.name = attr[0].name;
        if (attr[0].type != undefined) el.type = attr[0].type;
        if (attr[0].value != undefined) el.value = attr[0].value;
        if (attr[0].disabled != undefined) el.disabled = attr[0].disabled;
        if (attr[0].hidden != undefined) el.hidden = attr[0].hidden;
        if (attr[0].style != undefined) el.style = attr[0].style;
        if (attr[0].customAttribute != undefined) {
            const res = this.separate(attr[0].customAttribute);
            for (let i = 0; i < res.length; i++) {
                el.setAttribute(`${res[i][0]}`, res[i][1]);
            }
        }

        return el;
    }

    checkbox(...attr) {
        if (attr.length == 0) {
            const el = document.createElement("input");
            el.type = "checkbox";

            return el;
        }

        const el = document.createElement("input");
        el.type = "checkbox";
        if (attr[0].class != undefined) el.className = attr[0].class;
        if (attr[0].id != undefined) el.id = attr[0].id;
        if (attr[0].text != undefined) el.innerHTML = attr[0].text;
        if (attr[0].disabled != undefined) el.disabled = attr[0].disabled;
        if (attr[0].hidden != undefined) el.hidden = attr[0].hidden;
        if (attr[0].value != undefined) el.disabled = attr[0].value;
        if (attr[0].style != undefined) el.style = attr[0].style;
        if (attr[0].customAttribute != undefined) {
            const res = this.separate(attr[0].customAttribute);
            for (let i = 0; i < res.length; i++) {
                el.setAttribute(`${res[i][0]}`, res[i][1]);
            }
        }

        return el;
    }

    date(...attr) {
        if (attr.length == 0) {
            const el = document.createElement("input");
            el.type = "date";

            return el;
        }

        const el = document.createElement("input");
        el.type = "date";
        if (attr[0].class != undefined) el.className = attr[0].class;
        if (attr[0].id != undefined) el.id = attr[0].id;
        if (attr[0].text != undefined) el.innerHTML = attr[0].text;
        if (attr[0].disabled != undefined) el.disabled = attr[0].disabled;
        if (attr[0].hidden != undefined) el.hidden = attr[0].hidden;
        if (attr[0].value != undefined) el.value = attr[0].value;
        if (attr[0].style != undefined) el.style = attr[0].style;
        if (attr[0].customAttribute != undefined) {
            const res = this.separate(attr[0].customAttribute);
            for (let i = 0; i < res.length; i++) {
                el.setAttribute(`${res[i][0]}`, res[i][1]);
            }
        }

        return el;
    }

    time(...attr) {
        if (attr.length == 0) {
            const el = document.createElement("input");
            el.type = "time";

            return el;
        }

        const el = document.createElement("input");
        el.type = "time";
        if (attr[0].class != undefined) el.className = attr[0].class;
        if (attr[0].id != undefined) el.id = attr[0].id;
        if (attr[0].text != undefined) el.innerHTML = attr[0].text;
        if (attr[0].disabled != undefined) el.disabled = attr[0].disabled;
        if (attr[0].hidden != undefined) el.hidden = attr[0].hidden;
        if (attr[0].value != undefined) el.value = attr[0].value;
        if (attr[0].style != undefined) el.style = attr[0].style;
        if (attr[0].customAttribute != undefined) {
            const res = this.separate(attr[0].customAttribute);
            for (let i = 0; i < res.length; i++) {
                el.setAttribute(`${res[i][0]}`, res[i][1]);
            }
        }

        return el;
    }

    img(...attr) {
        if (attr.length == 0) return document.createElement("img");

        const el = document.createElement("img");
        if (attr[0].class != undefined) el.className = attr[0].class;
        if (attr[0].id != undefined) el.id = attr[0].id;
        if (attr[0].src != undefined) el.src = attr[0].src;
        if (attr[0].style != undefined) el.style = attr[0].style;
        if (attr[0].customAttribute != undefined) {
            const res = this.separate(attr[0].customAttribute);
            for (let i = 0; i < res.length; i++) {
                el.setAttribute(`${res[i][0]}`, res[i][1]);
            }
        }

        return el;
    }

    video(...attr) {
        if (attr.length == 0) return document.createElement("video");

        const el = document.createElement("video");
        if (attr[0].class != undefined) el.className = attr[0].class;
        if (attr[0].id != undefined) el.id = attr[0].id;
        if (attr[0].src != undefined) el.src = attr[0].src;
        if (attr[0].style != undefined) el.style = attr[0].style;
        if (attr[0].customAttribute != undefined) {
            const res = this.separate(attr[0].customAttribute);
            for (let i = 0; i < res.length; i++) {
                el.setAttribute(`${res[i][0]}`, res[i][1]);
            }
        }

        return el;
    }

    audio(...attr) {
        if (attr.length == 0) return document.createElement("audio");

        const el = document.createElement("audio");
        if (attr[0].class != undefined) el.className = attr[0].class;
        if (attr[0].id != undefined) el.id = attr[0].id;
        if (attr[0].type != undefined) el.type = attr[0].type;
        if (attr[0].src != undefined) el.src = attr[0].src;
        if (attr[0].style != undefined) el.style = attr[0].style;
        if (attr[0].customAttribute != undefined) {
            const res = this.separate(attr[0].customAttribute);
            for (let i = 0; i < res.length; i++) {
                el.setAttribute(`${res[i][0]}`, res[i][1]);
            }
        }

        return el;
    }

    source(...attr) {
        if (attr.length == 0) return document.createElement("source");

        const el = document.createElement("source");
        if (attr[0].class != undefined) el.className = attr[0].class;
        if (attr[0].id != undefined) el.id = attr[0].id;
        if (attr[0].type != undefined) el.type = attr[0].type;
        if (attr[0].src != undefined) el.src = attr[0].src;
        if (attr[0].style != undefined) el.style = attr[0].style;
        if (attr[0].customAttribute != undefined) {
            const res = this.separate(attr[0].customAttribute);
            for (let i = 0; i < res.length; i++) {
                el.setAttribute(`${res[i][0]}`, res[i][1]);
            }
        }

        return el;
    }

    iframe(...attr) {
        if (attr.length == 0) return document.createElement("iframe");

        const el = document.createElement("iframe");
        if (attr[0].class != undefined) el.className = attr[0].class;
        if (attr[0].id != undefined) el.id = attr[0].id;
        if (attr[0].src != undefined) el.src = attr[0].src;
        if (attr[0].style != undefined) el.style = attr[0].style;
        if (attr[0].customAttribute != undefined) {
            const res = this.separate(attr[0].customAttribute);
            for (let i = 0; i < res.length; i++) {
                el.setAttribute(`${res[i][0]}`, res[i][1]);
            }
        }

        return el;
    }

    canvas(...attr) {
        if (attr.length == 0) return document.createElement("canvas");

        const el = document.createElement("canvas");
        if (attr[0].class != undefined) el.className = attr[0].class;
        if (attr[0].id != undefined) el.id = attr[0].id;
        if (attr[0].style != undefined) el.style = attr[0].style;
        if (attr[0].customAttribute != undefined) {
            const res = this.separate(attr[0].customAttribute);
            for (let i = 0; i < res.length; i++) {
                el.setAttribute(`${res[i][0]}`, res[i][1]);
            }
        }

        return el;
    }

    textarea(...attr) {
        if (attr.length == 0) return document.createElement("textarea");

        const el = document.createElement("textarea");
        if (attr[0].class != undefined) el.className = attr[0].class;
        if (attr[0].id != undefined) el.id = attr[0].id;
        if (attr[0].text != undefined) el.innerHTML = attr[0].text;
        if (attr[0].maxLength != undefined) el.maxLength = attr[0].maxLength;
        if (attr[0].minLength != undefined) el.minLength = attr[0].minLength;
        if (attr[0].disabled != undefined) el.disabled = attr[0].disabled;
        if (attr[0].hidden != undefined) el.hidden = attr[0].hidden;
        if (attr[0].rows != undefined) el.rows = attr[0].rows;
        if (attr[0].placeholder != undefined) el.placeholder = attr[0].placeholder;
        if (attr[0].style != undefined) el.style = attr[0].style;
        if (attr[0].customAttribute != undefined) {
            const res = this.separate(attr[0].customAttribute);
            for (let i = 0; i < res.length; i++) {
                el.setAttribute(`${res[i][0]}`, res[i][1]);
            }
        }

        return el;
    }

    button(...attr) {
        if (attr.length == 0) return document.createElement("button");

        const el = document.createElement("button");
        if (attr[0].class != undefined) el.className = attr[0].class;
        if (attr[0].id != undefined) el.id = attr[0].id;
        if (attr[0].text != undefined) el.innerHTML = attr[0].text;
        if (attr[0].disabled != undefined) el.disabled = attr[0].disabled;
        if (attr[0].hidden != undefined) el.hidden = attr[0].hidden;
        if (attr[0].style != undefined) el.style = attr[0].style;
        if (attr[0].customAttribute != undefined) {
            const res = this.separate(attr[0].customAttribute);
            for (let i = 0; i < res.length; i++) {
                el.setAttribute(`${res[i][0]}`, res[i][1]);
            }
        }

        return el;
    }

    hr(...attr) {
        if (attr.length == 0) return document.createElement("hr");

        const el = document.createElement("hr");
        if (attr[0].class != undefined) el.className = attr[0].class;
        if (attr[0].id != undefined) el.id = attr[0].id;
        if (attr[0].text != undefined) el.innerHTML = attr[0].text;
        if (attr[0].disabled != undefined) el.disabled = attr[0].disabled;
        if (attr[0].hidden != undefined) el.hidden = attr[0].hidden;
        if (attr[0].style != undefined) el.style = attr[0].style;
        if (attr[0].customAttribute != undefined) {
            const res = this.separate(attr[0].customAttribute);
            for (let i = 0; i < res.length; i++) {
                el.setAttribute(`${res[i][0]}`, res[i][1]);
            }
        }

        return el;
    }

    a(...attr) {
        if (attr.length == 0) return document.createElement("a");

        const el = document.createElement("a");
        if (attr[0].class != undefined) el.className = attr[0].class;
        if (attr[0].id != undefined) el.id = attr[0].id;
        if (attr[0].text != undefined) el.innerHTML = attr[0].text;
        if (attr[0].disabled != undefined) el.disabled = attr[0].disabled;
        if (attr[0].hidden != undefined) el.hidden = attr[0].hidden;
        if (attr[0].style != undefined) el.style = attr[0].style;
        if (attr[0].href != undefined) el.href = attr[0].href;
        if (attr[0].customAttribute != undefined) {
            const res = this.separate(attr[0].customAttribute);
            for (let i = 0; i < res.length; i++) {
                el.setAttribute(`${res[i][0]}`, res[i][1]);
            }
        }

        return el;
    }
    
    p(...attr) {
        if (attr.length == 0) return document.createElement("p");

        const el = document.createElement("p");
        if (attr[0].class != undefined) el.className = attr[0].class;
        if (attr[0].id != undefined) el.id = attr[0].id;
        if (attr[0].text != undefined) el.innerHTML = attr[0].text;
        if (attr[0].disabled != undefined) el.disabled = attr[0].disabled;
        if (attr[0].hidden != undefined) el.hidden = attr[0].hidden;
        if (attr[0].style != undefined) el.style = attr[0].style;
        if (attr[0].href != undefined) el.href = attr[0].href;
        if (attr[0].customAttribute != undefined) {
            const res = this.separate(attr[0].customAttribute);
            for (let i = 0; i < res.length; i++) {
                el.setAttribute(`${res[i][0]}`, res[i][1]);
            }
        }

        return el;
    }

    i(...attr) {
        if (attr.length == 0) return document.createElement("i");

        const el = document.createElement("i");
        if (attr[0].class != undefined) el.className = attr[0].class;
        if (attr[0].id != undefined) el.id = attr[0].id;
        if (attr[0].text != undefined) el.innerHTML = attr[0].text;
        if (attr[0].disabled != undefined) el.disabled = attr[0].disabled;
        if (attr[0].hidden != undefined) el.hidden = attr[0].hidden;
        if (attr[0].style != undefined) el.style = attr[0].style;
        if (attr[0].href != undefined) el.href = attr[0].href;
        if (attr[0].value != undefined) el.value = attr[0].value;
        if (attr[0].title != undefined) el.setAttribute("title", attr[0].title);
        if (attr[0].customAttribute != undefined) {
            const res = this.separate(attr[0].customAttribute);
            for (let i = 0; i < res.length; i++) {
                el.setAttribute(`${res[i][0]}`, res[i][1]);
            }
        }

        return el;
    }

    option(...attr) {
        if (attr.length == 0) return document.createElement("option");

        const el = document.createElement("option");
        if (attr[0].class != undefined) el.className = attr[0].class;
        if (attr[0].id != undefined) el.id = attr[0].id;
        if (attr[0].text != undefined) el.innerHTML = attr[0].text;
        if (attr[0].disabled != undefined) el.disabled = attr[0].disabled;
        if (attr[0].hidden != undefined) el.hidden = attr[0].hidden;
        if (attr[0].style != undefined) el.style = attr[0].style;
        if (attr[0].href != undefined) el.href = attr[0].href;
        if (attr[0].value != undefined) el.value = attr[0].value;
        if (attr[0].customAttribute != undefined) {
            const res = this.separate(attr[0].customAttribute);
            for (let i = 0; i < res.length; i++) {
                el.setAttribute(`${res[i][0]}`, res[i][1]);
            }
        }

        return el;
    }

    ul(...attr) {
        if (attr.length == 0) return document.createElement("ul");

        const el = document.createElement("ul");
        if (attr[0].class != undefined) el.className = attr[0].class;
        if (attr[0].id != undefined) el.id = attr[0].id;
        if (attr[0].text != undefined) el.innerHTML = attr[0].text;
        if (attr[0].disabled != undefined) el.disabled = attr[0].disabled;
        if (attr[0].hidden != undefined) el.hidden = attr[0].hidden;
        if (attr[0].style != undefined) el.style = attr[0].style;
        if (attr[0].href != undefined) el.href = attr[0].href;
        if (attr[0].value != undefined) el.value = attr[0].value;
        if (attr[0].customAttribute != undefined) {
            const res = this.separate(attr[0].customAttribute);
            for (let i = 0; i < res.length; i++) {
                el.setAttribute(`${res[i][0]}`, res[i][1]);
            }
        }

        return el;
    }

    li(...attr) {
        if (attr.length == 0) return document.createElement("li");

        const el = document.createElement("li");
        if (attr[0].class != undefined) el.className = attr[0].class;
        if (attr[0].id != undefined) el.id = attr[0].id;
        if (attr[0].text != undefined) el.innerHTML = attr[0].text;
        if (attr[0].disabled != undefined) el.disabled = attr[0].disabled;
        if (attr[0].hidden != undefined) el.hidden = attr[0].hidden;
        if (attr[0].style != undefined) el.style = attr[0].style;
        if (attr[0].href != undefined) el.href = attr[0].href;
        if (attr[0].value != undefined) el.value = attr[0].value;
        if (attr[0].customAttribute != undefined) {
            const res = this.separate(attr[0].customAttribute);
            for (let i = 0; i < res.length; i++) {
                el.setAttribute(`${res[i][0]}`, res[i][1]);
            }
        }

        return el;
    }
}