<template>
    <div class="json-editor-block row mt-2">
        <div class="col-6">
            <json-editor
                class="json-editor"
                :data-input="jsonData"
                @data-output="(data) => (jsonData = data)"
            >
                <template #icon-add>
                    <icon name="plus" v-b-tooltip.hover title="Add" />
                </template>
                <template #icon-delete>
                    <icon name="trash" v-b-tooltip.hover title="Delete" />
                </template>
                <template #icon-drag>
                    <icon name="arrows-sort" v-b-tooltip.hover title="Move" />
                </template>
                <template #icon-collapse>
                    <icon
                        name="chevron-up"
                        v-b-tooltip.hover
                        title="Collapse"
                    />
                </template>
            </json-editor>
        </div>
        <div class="col-6">
            <json-tree
                :data="jsonData"
                :deep="2"
                :show-line="false"
                class="p-2"
            ></json-tree>
        </div>
    </div>
</template>

<script>
import JsonEditor from '@kassaila/vue-json-editor';

export default {
    name: 'JsonEditorBlock',
    components: {
        JsonEditor,
    },
    props: {
        inputJson: {
            type: Object | Array,
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
            deep: true,
            handler() {
                this.$emit('output-json', this.jsonData);
            },
        },
    },
};
</script>
