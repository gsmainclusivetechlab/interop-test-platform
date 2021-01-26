<template>
    <div class="json-tree-wrapper">
        <clipboard-copy-btn
            v-if="showCopyBtn"
            :data="JSON.stringify(data)"
            class="json-tree-copy px-2"
        ></clipboard-copy-btn>

        <vue-json-pretty v-bind="$props"></vue-json-pretty>
    </div>
</template>

<script>
import vueJsonPretty from 'vue-json-pretty';

export default {
    props: {
        showCopyBtn: {
            type: Boolean,
            default: true,
        },
        data: {
            required: true,
        },
        deep: {
            type: Number,
        },
        showLength: {
            type: Boolean,
        },
        showLine: {
            type: Boolean,
        },
        showDoubleQuotes: {
            type: Boolean,
        },
        highlightMouseoverNode: {
            type: Boolean,
        },
        vModel: {
            type: [String, Array],
        },
        path: {
            type: String,
        },
        pathChecked: {
            type: Array,
        },
        pathSelectable: {
            type: Function,
        },
        selectableType: {
            type: String,
        },
        showSelectController: {
            type: Boolean,
        },
        selectOnClickNode: {
            type: Boolean,
        },
        highlightSelectedNode: {
            type: Boolean,
        },
        customValueFormatter: {
            type: Function,
        },
    },
    components: {
        vueJsonPretty,
    },
};
</script>
