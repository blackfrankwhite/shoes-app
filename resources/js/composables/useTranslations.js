import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const resolveKey = (translations, key) =>
    key.split('.').reduce((value, segment) => {
        if (value && Object.prototype.hasOwnProperty.call(value, segment)) {
            return value[segment];
        }

        return undefined;
    }, translations);

export function useTranslations() {
    const page = usePage();

    const t = (key, replacements = {}) => {
        let value = resolveKey(page.props.i18n?.translations || {}, key) ?? key;

        Object.entries(replacements).forEach(([replacementKey, replacementValue]) => {
            value = String(value).replaceAll(`:${replacementKey}`, replacementValue);
        });

        return value;
    };

    return {
        t,
        locale: computed(() => page.props.i18n?.locale || 'ka'),
        locales: computed(() => page.props.i18n?.locales || {}),
    };
}
