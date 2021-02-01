<template>
    <div
        v-if="$page.props.app.locales.supported.length > 1"
        class="locale-changer"
    >
        <v-select
            v-model="$i18n.locale"
            class="form-control d-flex p-0"
            :options="$page.props.app.locales.supported"
            :clearable="false"
            :selectable="(option) => isSelectable(option, $i18n.locale)"
            @input="changeLang"
        />
    </div>
</template>

<script>
import mixinVSelect from '@/components/v-select/mixin';

export default {
    name: 'LocaleChanger',
    mixins: [mixinVSelect],
    methods: {
        changeLang() {
            this.$inertia.post(
                route('locale'),
                { locale: this.$i18n.locale },
                {
                    onFinish: () => {
                        document
                            .querySelector('html')
                            .setAttribute('lang', this.$i18n.locale);
                    },
                }
            );
        },
    },
    mounted() {
        this.$i18n.locale =
            this.$page.props.app.locales.selected ??
            this.$page.props.app.locales.default;
        this.$i18n.fallbackLocale = this.$page.props.app.locales.default;
    },
};
</script>
