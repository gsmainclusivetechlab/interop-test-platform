<template>
    <div class="input-group input-group-flat">
        <input
            type="text"
            :value="value"
            class="form-control"
            @input="$emit('input', $event.target.value)"
            ref="input"
            :placeholder="$t('placeholder')"
        />
        <span class="input-group-text">
            <a
                href="#"
                @click.prevent="clear"
                class="link-secondary"
                v-if="value"
            >
                <icon name="x" />
            </a>
            <icon v-else name="search" />
        </span>
    </div>
</template>

<script>
export default {
    props: {
        value: {
            type: String,
            required: false,
        },
    },
    methods: {
        clear() {
            this.$refs.input.value = null;
            this.$refs.input.focus();
            this.$emit('input', this.$refs.input.value);
        },
    },
};
</script>
<i18n src="@locales/components/input-search.json"></i18n>
