require('./bootstrap');

import { createApp, h } from 'vue'
import { App, plugin } from '@inertiajs/inertia-vue3'
import Toast, { useToast } from "vue-toastification";
// import "vue-toastification/dist/index.css";
import { ID_ID } from './Constants/lang';
import { InertiaProgress } from '@inertiajs/progress'
import { Settings } from 'luxon';
// import Config from '@@/Constants/config';

const el = document.getElementById('app')
const optionsToast = {
    position: "top-right",
    timeout: 5000,
    closeOnClick: true,
    pauseOnFocusLoss: false,
    pauseOnHover: false,
    draggable: true,
    draggablePercent: 0.6,
    showCloseButtonOnHover: false,
    closeButton: "button",
};

Settings.defaultZoneName = "Asia/Jakarta";
Settings.defaultLocale = "id";
// DateTime.local().toLocaleString(DateTime.DATE_SHORT)
// console.log(DateTime.local()<DateTime.local().endOf('year'));

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

const app = createApp({
    render: () => h(App, {
        initialPage: JSON.parse(el.dataset.page),
        resolveComponent: name => require(`./Pages/${name}`).default,
        transformProps: props => {
            globalToastManagement(props)
            return { ...props }
        }
    })
})
/**
 * https://stackoverflow.com/questions/63869859/detect-click-outside-element-on-vue-3
 */
app.directive('closable', {
    beforeMount(el, binding) {
        el.clickOutsideEvent = function (event) {
            if (!(el === event.target || el.contains(event.target))) {
                binding.value(event, el);
            }
        };
        document.body.addEventListener('click', el.clickOutsideEvent);
    },
    unmounted(el) {
        document.body.removeEventListener('click', el.clickOutsideEvent);
    }
});
app.provide('lang', "id")
app.provide('optionsToast', optionsToast)
app.use(plugin)
app.use(Toast, optionsToast)
app.mount(el)

InertiaProgress.init({
    // The delay after which the progress bar will
    // appear during navigation, in milliseconds.
    delay: 250,

    // The color of the progress bar.
    color: '#29d',

    // Whether to include the default NProgress styles.
    includeCSS: true,

    // Whether the NProgress spinner will be shown.
    showSpinner: false,
})

const popToast = (state = "info" || "error" || "success" || "warning", message = "") => {
    useToast()[state](message, optionsToast);
}
/**
 * Control a toast by flash session at server-side.
 * @param {*} props
 */
const globalToastManagement = (props) => {
    let toast = props.toast
    if (toast === "login.expire") popToast("error", ID_ID.authentication.sessionExpired)
    if (toast === "login.failed") popToast("error", ID_ID.authentication.loginFailed)
    if (toast === "login.success") popToast("success", ID_ID.authentication.loginSuccess)
    if (toast === "logout") popToast("success", ID_ID.authentication.logoutSuccess)
    if (toast === "create.inbox.mail.error" || toast === "create.sent.mail.error") popToast("error", ID_ID.form.invalid)
    if (toast === "create.inbox.document.error" || toast === "create.sent.document.error") popToast("error", ID_ID.form.documentInvalid)
    if (toast === "create.inbox.tembusan.error" || toast === "create.sent.tembusan.error") popToast("error", ID_ID.form.documentInvalid)
    props.toast = "";
}
