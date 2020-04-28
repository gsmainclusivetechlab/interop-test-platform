<template>
    <div class="json-tree-wrapper">
        <button
            v-if="showCopyBtn"
            ref="clipboard"
            v-b-tooltip.hover.topright.viewport
            title="Copy"
            type="button"
            class="btn btn-secondary px-2 json-tree-copy"
        >
            <icon name="copy" class="m-0" />
        </button>

        <vue-json-pretty v-bind="$props"></vue-json-pretty>
    </div>
</template>

<script>
import vueJsonPretty from 'vue-json-pretty';

export default {
    props: {
        showCopyBtn: {
            type: Boolean,
            default: true
        },
        data: {
            type: Object,
            required: true
        },
        deep: {
            type: Number
        },
        showLength: {
            type: Boolean
        },
        showLine: {
            type: Boolean
        },
        showDoubleQuotes: {
            type: Boolean
        },
        highlightMouseoverNode: {
            type: Boolean
        },
        vModel: {
            type: [String, Array]
        },
        path: {
            type: String
        },
        pathChecked: {
            type: Array
        },
        pathSelectable: {
            type: Function
        },
        selectableType: {
            type: String
        },
        showSelectController: {
            type: Boolean
        },
        selectOnClickNode: {
            type: Boolean
        },
        highlightSelectedNode: {
            type: Boolean
        },
        customValueFormatter: {
            type: Function
        }
    },
    components: {
        vueJsonPretty
    },
    mounted() {
        if (!this.$props.showCopyBtn) {
            return;
        }

        const self = this;

        import(/* webpackChunkName: "clipboard" */ 'clipboard').then(
            ({ default: Clipboard }) => {
                new Clipboard(this.$refs.clipboard, {
                    container: self.$el,
                    text: () => JSON.stringify(self.data)
                });
            }
        );
    }
};
</script>
