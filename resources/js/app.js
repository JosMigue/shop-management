require('./bootstrap');
import "@fortawesome/fontawesome-free/css/all.min.css";

// Import modules...
import { createApp, h } from 'vue';
import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

const el = document.getElementById('app');
const mxnCurrencyHelper = function (number = 0) { //Transforma una cantidad en formato de moneta en pesos MXN
    return new Intl.NumberFormat('es-MX',{ style: 'currency', currency: 'MXN' }).format(parseFloat(number).toFixed(2));
}
const VueApp = createApp({
    render: () =>
        h(InertiaApp, {
            initialPage: JSON.parse(el.dataset.page),
            resolveComponent: (name) => require(`./Pages/${name}`).default,
        }),
})
    .mixin({ methods: { 
            route,
            currencyHelper: mxnCurrencyHelper,
        }
    })
    .use(InertiaPlugin);

VueApp.use(Toast);
VueApp.mount(el);
InertiaProgress.init({ color: '#1DD893' });
