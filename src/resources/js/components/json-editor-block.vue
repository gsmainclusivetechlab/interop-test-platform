<template>
    <b-tabs content-class="json-editor-block mt-2">
        <b-tab title="Editor" title-link-class="rounded-0">
            <json-editor
                :options="{
                    confirmText: 'Confirm',
                    cancelText: 'Cancel',
                }"
                :objData="jsonData"
                v-model="jsonData"
            ></json-editor>
        </b-tab>
        <b-tab title="Preview" title-link-class="rounded-0">
            <json-tree
                :data="jsonData"
                :deep="2"
                :show-line="false"
                class="p-2"
            ></json-tree>
        </b-tab>
    </b-tabs>
</template>

<script>
export default {
    props: {
        inputJson: {
            type: Object,
            required: false,
        },
    },
    data() {
        return {
            jsonData: this.inputJson,
        };
    },
    watch: {
        jsonData: {
            // immediate: true,
            deep: true,
            handler() {
                this.$emit('output-json', this.jsonData);
            },
        },
    },
};
</script>
