const document = [
    "application/msword",
    "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
    "application/x-abiword",
    "application/vnd.oasis.opendocument.presentation",
    "application/vnd.oasis.opendocument.spreadsheet",
    "application/vnd.oasis.opendocument.text",
    "application/vnd.ms-powerpoint",
    "application/vnd.openxmlformats-officedocument.presentationml.presentation",
    "application/rtf",
    "text/plain",
    "application/vnd.ms-excel",
    "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
];
const archive = [
    "application/zip",
    "application/x-7z-compressed",
    "application/x-tar",
    "application/vnd.rar",
    "application/x-bzip",
    "application/x-bzip2",
]
// const image = [
//     "image/bmp",
//     "image/gif",
//     "image/jpeg",
//     "image/png",
//     "image/svg+xml",
//     "image/tiff",
//     "image/webp"
// ]

export function checkTypeMIME(type = "") {
    if (typeof type == "undefined" || type.length === 0) {
        return "unknown"
    }
    let arrMime = type.split("/", 2);
    if (arrMime[0] == "image") {
        return "image"
    }
    if (arrMime[0] == "application") {
        if (arrMime[1] == "pdf") {
            return "pdf"
        }
        if (document.includes(type, 0)) {
            return "document"
        }
        if (archive.includes(type, 0)) {
            return "archive"
        }
    }
    return "unknown"
}

export function classIconFiles(type) {
    let a = checkTypeMIME(type);
    let css = "ri-3x ";

    if (a === "unknown") {
        return css + "ri-file-unknow-fill";
    }
    if (a === "image") {
        return css + "ri-image-fill";
    }
    if (a === "document") {
        return css + "ri-file-word-2-fill";
    }
    if (a === "pdf") {
        return css + "ri-file-pdf-fill";
    }
}

export function filterFileOnly(files) {
    return files.map((value) => {
        return value.file;
    })
}
