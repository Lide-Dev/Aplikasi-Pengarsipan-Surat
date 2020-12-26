import { lang } from "@@/Constants/lang";
import { DateTime } from "luxon";

const extension = {
    provide: ["image", "archive", "document"],
    image: ["jpg", "jpeg", "png", "gif", "tiff", "bmp"],
    archive: ["zip", "rar", "tar", "7z"],
    document: ["pdf", "doc", "docx", "rtf", "odt", "tex", "txt", "wpd"]
};

const typeInput = ["text", "select", "tags"]
const inputRules = {
    text: {
        max: (rules = 0, value) => { return value.length == 0 || value.length <= rules },
        min: (rules = 0, value) => { return value.length == 0 || value.length >= rules },
        required: (rules = true, value) => { return (typeof value != "undefined" && value.length > 0) },
    },
    select: {
        max: (rules = 0, value) => { return value == 0 || value <= rules },
        min: (rules = 0, value) => { return value == 0 || value >= rules },
        required: (rules = true, value) => { return (typeof value != "undefined" && value > 0) },
    },
    tags: {
        max: (rules = 0, value = []) => { return value.length == 0 || value.length <= rules },
        min: (rules = 0, value = []) => { return value.length == 0 || value.length >= rules },
        maxPerValue: (rules = 0, value = []) => {
            let isSomeMax = value.some((a) => {
                return typeof a == "string" ? a.length > rules : a > rules;
            });
            return !isSomeMax;
        },
        minPerValue: (rules = 0, value = []) => {
            let isSomeMin = value.some((a) => {
                return typeof a == "string" ? a.length > rules : a < rules;
            });
            return !isSomeMin;
        },
        required: (rules = true, value) => { return (typeof value != "undefined" && value.length > 0) },
    }
}

export const form = {
    inputValidate(input, value, errorBags) {
        for (const [key, rule] of Object.entries(input.rules)) {
            if (!inputRules[input.type][key](rule, value[input.name])) {
                errorBags[input.name] = lang('id').validation[input.type][key](rule, input.label);
                return false;
            }
        }
        return true;
    },
    validateForm: (key = [{ name: "", label: "", type: "text", rules: {} }], value) => {
        let validate = [];
        let errorBags = {};
        if (!Array.isArray(key)) {
            console.error(`Validate form value must be Array, instead got type ${typeof key}.`);
            return { valid: false, error: errorBags };
        }
        if (key.length == 0) {
            console.error(`Array must fulfilled by value that need for validation, instead got empty array.`);
            return { valid: false, error: errorBags };
        }
        let isTypeNotProvide = key.some((a) => {
            return !typeInput.includes(a.type)
        })
        if (isTypeNotProvide) {
            console.error(`Some type input at form value is not provided.`, key);
            return { valid: false, error: errorBags };
        }
        validate = key.map((input) => {
            return form.inputValidate(input, value, errorBags)
        })
        let isNotValidate = validate.some((value) => {
            return !value;
        })
        return { valid: !isNotValidate, error: errorBags };
    },
    // convertNameForm: (convertArray = { inputName: "input" }) => {
    // }
}

export const validation = {
    isNotEmpty: function (str) {
        var pattern = /\S+/;
        return pattern.test(str);  // returns a boolean
    },
    isNumber: function (str) {
        var pattern = /^\d+$/;
        return pattern.test(str);  // returns a boolean
    },
    isSame: function (str1, str2) {
        return str1 === str2;
    },
    isValidExtension: function (filename = "", type = extension.provide) {
        var a = filename.split(".");
        if (a.length === 1 || (a[0] === "" && a.length === 2)) {
            return "";
        }
        let ext = a.pop();
        if (typeof type != 'undefined' && type.length > 0) {
            let fil = type.filter((value) => {
                return extension.provide.includes(value, 0);
            });
            if (fil.length > 0) {
                let valid = fil.filter((value) => {
                    return extension[value].includes(ext, 0)
                })
                return valid.length > 0
            }
        }
        return false;
    }
}

export const random = {
    string: function (len, charSet) {
        charSet = charSet || 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var randomString = '';
        for (var i = 0; i < len; i++) {
            var randomPoz = Math.floor(Math.random() * charSet.length);
            randomString += charSet.substring(randomPoz, randomPoz + 1);
        }
        return randomString;
    }
}

export const convert = {
    /**
 * Format bytes as human-readable text.
 *
 * @param bytes Number of bytes.
 * @param si True to use metric (SI) units, aka powers of 1000. False to use
 *           binary (IEC), aka powers of 1024.
 * @param dp Number of decimal places to display.
 *
 * @return Formatted string.
 */
    humanFileSize: (bytes, si = false, dp = 1) => {
        const thresh = si ? 1000 : 1024;

        if (Math.abs(bytes) < thresh) {
            return bytes + ' B';
        }

        const units = si
            ? ['kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB']
            : ['KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];
        let u = -1;
        const r = 10 ** dp;

        do {
            bytes /= thresh;
            ++u;
        } while (Math.round(Math.abs(bytes) * r) / r >= thresh && u < units.length - 1);


        return bytes.toFixed(dp) + ' ' + units[u];
    },
    byteBinarySize: (value = 0, convert = "kb", si = false) => {
        if (value == 0) {
            return 0;
        }
        const thresh = si ? 1000 : 1024;
        const units = { kb: 1, mb: 2, gb: 3 }
        let bytes = value;
        if (!(convert in units)) { console.error("Units that want to convert is not provided at this function!"); return 0 }
        for (let i = 0; i < units[convert]; i++) {
            bytes *= thresh
        }
        return bytes;
    }

}

// export const capitalize = (s) => {
//     if (typeof s !== 'string') return ''
//     return s.charAt(0).toUpperCase() + s.slice(1)
// }

