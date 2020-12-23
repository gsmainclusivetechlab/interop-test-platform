<template>
    <div class="locale-changer">
        <selectize
            v-model="$i18n.locale"
            class="form-select"
            :options="langs"
            :createItem="false"
        />
    </div>
</template>

<script>
export default {
    name: 'LocaleChanger',
    data() {
        return { langs: ['en-GB', 'en-US', 'ru-RU'] };
    },
};
</script>
