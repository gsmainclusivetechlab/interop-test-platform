<template>
    <div class="locale-changer">
        <selectize
            v-model="$i18n.locale"
            class="form-select"
            :options="$page.props.app.locales"
            :createItem="false"
            @input="$inertia.post(route('locale'), { locale: $i18n.locale })"
        />
    </div>
</template>

<script>
export default {
    name: 'LocaleChanger',
    mounted() {
        this.$i18n.locale = this.$page.props.app.locale;
    },
};
</script>
