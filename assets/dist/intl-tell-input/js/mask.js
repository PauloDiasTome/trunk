"use strict";

function setDdiInputKeyup() {
    if (this.value.length <= 1 && this.value !== "+") {
        this.value = `+${this.value}`;
    } else if (this.value.length > 1) {
        // Verificar caracteres a partir do segundo
        const charsToCheck = this.value.slice(1);
        if (charsToCheck.match(/\+/)) {
            this.value = this.value[0] + charsToCheck.replace(/\+/g, "");
        }
        if (this.value[0] !== "+") {
            this.value = "+" + this.value.slice(1);
        }
    }

    clearInputContact();
}


function clearInputContact() {
    document.getElementById("contact_number").value = "";
}


function removeDdiCharacters() {
    document.getElementById("input-ddi-number").value = document.getElementById("input-ddi-number").value.replace(/[^+\d]/g, "");
};


function maskInputFocus() {
    const flag = document.querySelector(".iti__flag").className.split(" ")[1];

    switch (flag) {

        case "iti__as": // +1
        case "iti__ai": // +1
        case "iti__ag": // +1
        case "iti__bs": // +1
        case "iti__bb": // +1
        case "iti__bm": // +1
        case "iti__vg": // +1
        case "iti__ca": // +1
        case "iti__ky": // +1
        case "iti__dm": // +1
        case "iti__do": // +1
        case "iti__gd": // +1
        case "iti__gu": // +1
        case "iti__jm": // +1
        case "iti__ms": // +1
        case "iti__mp": // +1
        case "iti__pr": // +1
        case "iti__kn": // +1
        case "iti__lc": // +1
        case "iti__vc": // +1
        case "iti__sx": // +1
        case "iti__tt": // +1
        case "iti__tc": // +1
        case "iti__vi": // +1
        case "iti__us": // +1
            $(".input-number").mask("(999) 999-9999");
            break;

        case "iti__ru": // +7
            $(".input-number").mask("9 (999) 999-99-99");
            break;

        case "iti__kz": // +7
            $(".input-number").mask("9 (999) 999 9999");
            break;

        case "iti__za": // +27
        case "iti__pl": // +48
        case "iti__pe": // +51
        case "iti__gq": // +240
        case "iti__ao": // +244
        case "iti__gw": // +245
        case "iti__si": // +386
        case "iti__ba": // +387
        case "iti__pt": // +351
        case "iti__lu": // +352
        case "iti__bg": // +359
        case "iti__me": // +382
        case "iti__xk": // +383
        case "iti__mk": // +389
        case "iti__cz": // +420
        case "iti__li": // +423
        case "iti__uy": // +598
        case "iti__kh": // +855
            $(".input-number").mask("999 999 999");
            break;

        case "iti__gr":  // 30
        case "iti__it":  // 39
        case "iti__va":  // 39
        case "iti__mx":  // 52
        case "iti__nz":  // 64
        case "iti__th":  // 66
        case "iti__af":  // 93
        case "iti__lk":  // 94
        case "iti__lr": // 231
        case "iti__gh": // 233
        case "iti__sd": // 249
        case "iti__et": // 251
        case "iti__zw": // 263
        case "iti__na": // 264
        case "iti__ie": // 353
        case "iti__al": // 355
        case "iti__ua": // 380
        case "iti__hr": // 385
        case "iti__ec": // 593
        case "iti__mq": // 596
        case "iti__sa": // 966
        case "iti__ae": // 971
            $(".input-number").mask("999 999 9999");
            break;

        case "iti__nl": // 31
            $(".input-number").mask("99 99999999");
            break;

        case "iti__be": // 32           
        case "iti__dz": // 213
        case "iti__re": // 262
        case "iti__yt": // 262
        case "iti__mw": // 265
        case "iti__bl": // 590
        case "iti__gp": // 590
        case "iti__mf": // 590
        case "iti__gf": // 594
            $(".input-number").mask("9999 99 99 99");
            break;

        case "iti__fr":  // 33
        case "iti__mc": // 377
            $(".input-number").mask("99 99 99 99 99");
            break;

        case "iti__es": // 34
        case "iti__gn": // 224
        case "iti__ge": // 995
            $(".input-number").mask("999 99 99 99");
            break;

        case "iti__hu": // 36
            $(".input-number").mask("99 99 999 9999");
            break;

        case "iti__ro": // 40
        case "iti__au": // 61
        case "iti__cx": // 61
        case "iti__cc": // 61
        case "iti__ss": // 211
        case "iti__cd": // 243
        case "iti__rw": // 250
        case "iti__tz": // 255
        case "iti__sk": // 421
        case "iti__tw": // 886
        case "iti__sy": // 963
        case "iti__ye": // 967
        case "iti__ps": // 970
        case "iti__kg": // 996
            $(".input-number").mask("9999 999 999");
            break;

        case "iti__ch": // 41 
        case "iti__vn": // 84 
        case "iti__az": // 994
            $(".input-number").mask("999 999 99 99");
            break;

        case "iti__at": // 43
        case "iti__gg": // 44
        case "iti__im": // 44
        case "iti__je": // 44
        case "iti__gb": // 44
        case "iti__ke": // 254
        case "iti__ug": // 256
            $(".input-number").mask("9999 999999");
            break;

        case "iti__dk": // 45
        case "iti__mr": // 222
        case "iti__ml": // 223
        case "iti__bf": // 226
        case "iti__ne": // 227
        case "iti__tg": // 228
        case "iti__bj": // 229
        case "iti__td": // 235
        case "iti__cf": // 236
        case "iti__ga": // 241
        case "iti__dj": // 253
        case "iti__bi": // 257
        case "iti__sm": // 378
        case "iti__pf": // 689
        case "iti__bt": // 975
            $(".input-number").mask("99 99 99 99");
            break;

        case "iti__se": // 46
            $(".input-number").mask("999-999 99 99");
            break;

        case "iti__no": // 47
        case "iti__sj": // 47
            $(".input-number").mask("999 99 999");
            break;

        case "iti__de": // 49
            $(".input-number").mask("99999 9999999");
            break;

        case "iti__cu": // 53
            $(".input-number").mask("99 9999999");
            break;

        case "iti__ar": // 54
            $(".input-number").mask("9 9999 99 9999");
            break;

        case "iti__br": // 55
            $(".input-number").mask("(99) 99999-999?9");
            break;

        case "iti__cl": // 56
            $(".input-number").mask("(9) 9999 9999");
            break;

        case "iti__co": // 57
        case "iti__zm": // 260
        case "iti__ax": // 358
        case "iti__fi": // 358
        case "iti__rs": // 381
            $(".input-number").mask("999 9999999");
            break;

        case "iti__ve": // 58
            $(".input-number").mask("9999-9999999");
            break;

        case "iti__my": // 60
            $(".input-number").mask("999-999 9999");
            break;

        case "iti__id": // 62
            $(".input-number").mask("9999-999-999");
            break;

        case "iti__eg": // 20
        case "iti__ph": // 63
        case "iti__ir": // 98
        case "iti__ng": // 234
        case "iti__kp": // 850
        case "iti__iq": // 964
            $(".input-number").mask("9999 999 9999");
            break;

        case "iti__sg": // 65
        case "iti__mu": // 230
        case "iti__ls": // 266
        case "iti__sz": // 268
        case "iti__mt": // 356
        case "iti__gt": // 502
        case "iti__sv": // 503
        case "iti__ni": // 505
        case "iti__cr": // 506
        case "iti__tl": // 670
        case "iti__pg": // 675
        case "iti__hk": // 852
        case "iti__mo": // 853
        case "iti__om": // 968
        case "iti__bh": // 973
        case "iti__qa": // 974
        case "iti__mn": // 976
            $(".input-number").mask("9999 9999");
            break;

        case "iti__jp": // 81
        case "iti__kr": // 82
            $(".input-number").mask("999-9999-9999");
            break;

        case "iti__cn": // 86
            $(".input-number").mask("999 9999 9999");
            break;

        case "iti__tr": // 90
            $(".input-number").mask("9999 999 99 99");
            break;

        case "iti__in": // 91
            $(".input-number").mask("999999 99999");
            break;

        case "iti__pk": // 92
            $(".input-number").mask("9999 9999999");
            break;

        case "iti__mm": // 95
        case "iti__cg": // 242
        case "iti__mz": // 258
            $(".input-number").mask("99 999 9999");
            break;

        case "iti__ma": // 212
        case "iti__eh": // 212
            $(".input-number").mask("9999-999999");
            break;

        case "iti__ly": // 218
        case "iti__np": // 977
            $(".input-number").mask("999-9999999");
            break;

        case "iti__gm": // 220
        case "iti__st": // 239
        case "iti__aw": // 297
        case "iti__io": // 246
        case "iti__is": // 354
        case "iti__ee": // 372
        case "iti__gy": // 592
        case "iti__bn": // 673
        case "iti__nr": // 674
        case "iti__to": // 676
        case "iti__vu": // 678
        case "iti__fj": // 679
        case "iti__pw": // 680
        case "iti__nu": // 683
        case "iti__fm": // 691
            $(".input-number").mask("999 9999");
            break;

        case "iti__sn": // 221
            $(".input-number").mask("99 999 99 99");
            break;

        case "iti__ci": // 225
            $(".input-number").mask("99 99 99 9999");
            break;

        case "iti__sl": // 232
        case "iti__py": // 595
            $(".input-number").mask("(999) 999999");
            break;

        case "iti__cm": // 237
            $(".input-number").mask("9 99 99 99 99");
            break;

        case "iti__cv": // 238
        case "iti__km": // 269
        case "iti__pm": // 508
            $(".input-number").mask("999 99 99");
            break;

        case "iti__ac": // 247
        case "iti__sh": // 290
        case "iti__fk": // 500
            $(".input-number").mask("99999");
            break;

        case "iti__sc": // 248
            $(".input-number").mask("9 999 999");
            break;

        case "iti__so": // 252
            $(".input-number").mask("9 9999999");
            break;

        case "iti__mg": // 261
            $(".input-number").mask("999 99 999 99");
            break;

        case "iti__tn": // 216
        case "iti__bw": // 267
        case "iti__er": // 291
        case "iti__lv": // 371
        case "iti__lb": // 961
            $(".input-number").mask("99 999 999");
            break;

        case "iti__fo": // 298
            $(".input-number").mask("999999");
            break;

        case "iti__gl": // 299
        case "iti__wf": // 681
            $(".input-number").mask("99 99 99");
            break;

        case "iti__gi": // 350
        case "iti__bo": // 591
        case "iti__ki": // 686
            $(".input-number").mask("99999999");
            break;

        case "iti__cy": // 357
            $(".input-number").mask("99 999999");
            break;

        case "iti__lt": // 370
            $(".input-number").mask("(9-999) 99999");
            break;

        case "iti__md": // 373
            $(".input-number").mask("9999 99 999");
            break;

        case "iti__am": // 374
            $(".input-number").mask("999 999999");
            break;

        case "iti__by": // 375
            $(".input-number").mask("9 999 999-99-99");
            break;

        case "iti__ad": // 376
            $(".input-number").mask("999 999");
            break;

        case "iti__bz": // 501
        case "iti__sr": // 597
        case "iti__mh": // 692
        case "iti__mv": // 960
            $(".input-number").mask("999-9999");
            break;

        case "iti__hn": // 504
        case "iti__pa": // 507
            $(".input-number").mask("9999-9999");
            break;

        case "iti__ht": // 509
            $(".input-number").mask("99 99 9999");
            break;

        case "iti__bq": // 599
        case "iti__cw": // 599
            $(".input-number").mask("9 999 9999");
            break;

        case "iti__nf": // 672
            $(".input-number").mask("9 99999");
            break;

        case "iti__sb": // 677
        case "iti__ws": // 685
            $(".input-number").mask("99 99999");
            break;

        case "iti__ck": // 682
            $(".input-number").mask("99 999");
            break;

        case "iti__nc": // 687
            $(".input-number").mask("99.99.99");
            break;

        case "iti__tv": // 688
            $(".input-number").mask("99 9999");
            break;

        case "iti__tk": // 690
            $(".input-number").mask("9999");
            break;

        case "iti__la": // 856
            $(".input-number").mask("999 99 999 999");
            break;

        case "iti__bd": // 880
            $(".input-number").mask("99999-999999");
            break;

        case "iti__jo": // 962
            $(".input-number").mask("99 9999 9999");
            break;

        case "iti__kw": // 965
            $(".input-number").mask("999 99999");
            break;

        case "iti__il": // 972
            $(".input-number").mask("999-999-9999");
            break;

        case "iti__tj": // 992
            $(".input-number").mask("999 99 9999");
            break;

        case "iti__tm": // 993
            $(".input-number").mask("9 99 999999");
            break;

        case "iti__uz": // 998
            $(".input-number").mask("9 99 999 99 99");
            break;

        default:
            $(".input-number").mask("(99) 99999-999?9");
            break;
    }
}


