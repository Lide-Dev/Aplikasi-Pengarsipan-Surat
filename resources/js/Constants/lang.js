/* eslint-disable no-unused-vars */
// import { size } from "lodash";

export const ID_ID = {
    toastMessage: { success: "Permintaan Berhasil!", error: "Terjadi Kesalahan pada Permintaan!", warning: "Peringatan!", info: "Informasi." },
    authentication: {
        loginSuccess: "Login Berhasil!",
        loginFailed: "Email atau Password anda salah!",
        sessionExpired: "Session login telah berakhir. Silahkan login kembali!",
        logoutSuccess: "Logout Berhasil!"
    },
    uploadAction: "Klik tombol dibawah untuk mengunggah file.",
    form: {
        invalid: "Beberapa data form tidak memenuhi syarat form!",
        documentInvalid: "Dokumen yang di upload tidak memenuhi syarat form!",
        tembusanInvalid: "Tembusan yang di kirim tidak memenuhi syarat form!",
    },
    errors: {
        contact: "Kontak ke herlandrotri@gmail.com",
        noNetwork: "Sepertinya terjadi masalah di bagian koneksi anda. Silahkan dicoba lagi!",
        server: "Terjadi kesalahan pada server!",
        maintenance: "Terjadi kesalahan pada server!",
    },
    validation: {
        noUploadFile: "Tidak ada file yang di unggah disini.",
        sizeTooLarge: "Ukuran file yang di unggah terlalu besar!",
        sizeTooSmall: "Ukuran file yang di unggah terlalu kecil!",
        unknownExtension: "Ekstensi file yang di unggah tidak dikenal!",
        maximalFiles: "File yang di unggah telah maksimal!",
        fileIsSame: (name) => `File dengan nama ${name} sama dengan file yang telah diunggah!`,
        text: {
            max: (rules = 0, name = "input bagian ini") => `Teks yang dimasukkan pada ${name} melebihi ${rules} karakter`,
            min: (rules = 0, name = "input bagian ini") => `Teks yang dimasukkan pada ${name} kurang dari ${rules} karakter`,
            required: (_rules = 0, name = "input bagian ini") => `Form ${name} dibutuhkan.`
        },
        tags: {
            max: (rules = 0, name = "input bagian ini") => `Pilihan pada input ${name} melebihi ${rules} pilihan`,
            min: (rules = 0, name = "input bagian ini") => `Pilihan pada input ${name} kurang dari ${rules} pilihan`,
            required: (_rules = 0, name = "input bagian ini") => `Form ${name} dibutuhkan.`
        },
        select: {
            max: (_rules = 0, name = "input bagian ini") => `Pilihan pada input ${name} tidak ada`,
            min: (_rules = 0, name = "input bagian ini") => `Pilihan pada input ${name} tidak ada`,
            required: (_rules = 0, name = "input bagian ini") => `Form ${name} dibutuhkan.`
        }
    }
}

export function lang(locale = "id") {
    let l = ID_ID;
    switch (locale) {
        case "id":
            l = ID_ID;
            break;
        default:
            console.error(`No locale ${locale} detected on lang object!`, locale)
            break;
    }
    return l;
}
