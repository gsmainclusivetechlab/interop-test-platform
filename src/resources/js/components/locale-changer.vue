<template>
    <div v-if="$page.props.app.locales.length > 0" class="locale-changer">
        <v-select
            v-model="$i18n.locale"
            class="form-control d-flex p-0"
            :options="$page.props.app.locales"
            :clearable="false"
            :searchable="false"
            @input="changeLang"
        />
    </div>
</template>

<script>
export default {
    name: 'LocaleChanger',
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
        this.$i18n.locale = this.$page.props.app.locale;
    },
};
</script>
