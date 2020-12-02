require('./bootstrap');

import { createApp, h } from 'vue'
import { App, plugin } from '@inertiajs/inertia-vue3'
import Toast, { useToast } from "vue-toastification";
import "vue-toastification/dist/index.css";
import { ID_ID } from './Constants/lang';

const el = document.getElementById('app')
const optionsToast = {
    position: "top-right",
    timeout: 3500,
    closeOnClick: true,
    pauseOnFocusLoss: false,
    pauseOnHover: false,
    draggable: true,
    draggablePercent: 0.6,
    showCloseButtonOnHover: false,
    closeButton: "button",
};

/**
 * This is for Chrome Android Browser support. The navbar if hide, it will make kinda bad viewport. So we need configure at Javascript.
 * Found at url:
 * https://stackoverflow.com/questions/39384154/calculating-viewport-height-on-chrome-android-with-css
 */
(function () {
    function size() {
        // you can change here what you prefer
        if (/android|webos|iphone|ipad|ipod|blackberry|nokia|opera mini|opera mobi|skyfire|maemo|windows phone|palm|iemobile|symbian|symbianos|fennec/i.test(navigator.userAgent.toLowerCase())) {
            var minheight = Math.min(document.documentElement.clientHeight, window.screen.height, window.innerHeight);
            //now apply height ... if needed...add html & body ... i need and i use it
            document.getElementById('app').height = minheight
            // $('html body #app').css('height', theminheight);
        }
    }
    window.addEventListener('resize orientationchange', function () {
        size();
    }, false);
    size();
}());


createApp({
    render: () => h(App, {
        initialPage: JSON.parse(el.dataset.page),
        resolveComponent: name => require(`./Pages/${name}`).default,
        transformProps: props => {
            globalToastManagement(props)
            return { ...props }
        }
    })
}).use(plugin).use(Toast, optionsToast).mount(el)

/**
 * Control a toast by flash session at server-side.
 * @param {*} props
 */
const globalToastManagement = (props) => {
    if (props.authToast) {
        let status = props.authToast;
        if (status.logout) {
            useToast().info(ID_ID.authentication.logoutSuccess, optionsToast);
        }
        if (status.sessionExpired) {
            useToast().error(ID_ID.authentication.sessionExpired, optionsToast);
        }
    }
    if (props.loginToast) {
        let status = props.loginToast;
        if (status.loginSuccess) useToast().success(ID_ID.authentication.loginSuccess, optionsToast); else
            useToast().error(ID_ID.authentication.loginFailed, optionsToast);
    }
}
