import '../css/app.css';
import './bootstrap';

import { createInertiaApp, router } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        const vueApp = createApp({ render: () => h(App, props) });
        let pageProps = props.initialPage.props;

        router.on('success', (event) => {
            pageProps = event.detail.page.props;
        });

        vueApp.config.globalProperties.$t = (key, replacements = {}) => {
            const resolveKey = (translations, translationKey) =>
                translationKey.split('.').reduce((value, segment) => {
                    if (value && Object.prototype.hasOwnProperty.call(value, segment)) {
                        return value[segment];
                    }

                    return undefined;
                }, translations);

            let value =
                resolveKey(
                    pageProps.i18n?.translations || {},
                    key,
                ) ?? key;

            Object.entries(replacements).forEach(([replacementKey, replacementValue]) => {
                value = String(value).replaceAll(`:${replacementKey}`, replacementValue);
            });

            return value;
        };

        return vueApp.use(plugin).use(ZiggyVue).mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
