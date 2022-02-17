require('./bootstrap');

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import swal from 'sweetalert2';
window.Swal = swal;

import ClassicEditor from '@ckeditor/ckeditor5-build-classic/build/ckeditor';
var ready = (callback) => {
    if (document.readyState != "loading") callback();
    else document.addEventListener("DOMContentLoaded", callback);
}

ready(() => {
    ClassicEditor
        .create(document.querySelector('.ck'))
        .catch(error => {
            console.log(`error`, error)
        });
});

const {Dropzone} = require("dropzone");
