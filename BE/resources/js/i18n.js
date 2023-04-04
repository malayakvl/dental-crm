import Vue from "vue";
import VueI18n from "vue-i18n";
import ru from "./locales/ru.json";
const defaultImpl = VueI18n.prototype.getChoiceIndex;
VueI18n.prototype.getChoiceIndex = function (choice, choicesLength) {
    // this === VueI18n instance, so the locale property also exists here
    if (this.locale !== "ru") {
        // proceed to the default implementation
        return defaultImpl.apply(this, arguments);
    }

    if (choice === 0) {
        return 0;
    }

    const teen = choice > 10 && choice < 20;
    const endsWithOne = choice % 10 === 1;

    if (!teen && endsWithOne) {
        return 1;
    }

    if (!teen && choice % 10 >= 2 && choice % 10 <= 4) {
        return 2;
    }

    return choicesLength < 4 ? 2 : 3;
};

const dateTimeFormats = {
    en: {
        short: {
            year: "numeric",
            month: "short",
            day: "numeric",
        },
    },
    ru: {
        short: {
            year: "numeric",
            month: "short",
            day: "numeric",
        },
    },
};

const numberFormats = {
    en: {
        currency: {
            style: "currency",
            currency: "USD",
        },
    },
    ru: {
        currency: {
            style: "currency",
            currency: "RUB",
        },
    },
};

Vue.use(VueI18n);
export const i18n = new VueI18n({
    locale: process.env.MIX_PUSHER_APP_I18N_LOCALE || "en",
    fallbackLocale: process.env.MIX_PUSHER_APP_I18N_FALLBACK_LOCALE || "en",
    messages: { ru },
    dateTimeFormats,
    numberFormats,
});
