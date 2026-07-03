import "./bootstrap";
import { createApp, h } from "vue";
import { createInertiaApp, Link, Head } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { createPinia } from 'pinia';
import { createI18n } from 'vue-i18n';

import ar from './locales/ar.json';
import en from './locales/en.json';

const i18n = createI18n({
    legacy: false,
    locale: 'ar',
    fallbackLocale: 'en',
    messages: { ar, en }
});

const pinia = createPinia();

createInertiaApp({
    title: (title) => (title ? `${title} - وِصال` : "✦ وِصال ✦"),
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue"),
        ),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(i18n)
            .use(pinia)
            .component('Link', Link)
            .component('Head', Head)
            .mount(el);
    },
});
