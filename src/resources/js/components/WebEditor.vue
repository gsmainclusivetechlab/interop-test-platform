<template>
    <div>
        <div class="form-group">
            <textarea
                :name="this.$props.editorSubjectName"
                ref="editorSubject"
                class="form-control"
                hidden
            ></textarea>
            <div
                class="web-editor form-control pr-0"
                :class="this.$props.editorClass"
                ref="editor"
            >
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<script>
import ace from 'ace-builds/src-min-noconflict/ace';
import { debounce } from '../helpers';
import 'ace-builds/src-min-noconflict/mode-yaml';

const DEBOUNCED_TIME = 250;
const ANNOTATION_TYPE = 'error';
const DEFAULT_MODE = 'ace/mode/yaml';

const options = {
    mode: DEFAULT_MODE,
};

export default {
    props: {
        options: {
            type: Object,
        },
        editorClass: {
            type: String,
        },
        editorSubjectName: {
            type: String,
            default: 'validation_rules',
        },
    },
    data() {
        return {
            editor: null,
            editorSubject: null,
            value: null,
        };
    },
    async mounted() {
        this.editorSubject = this.$refs.editorSubject;

        this.editor = ace.edit(this.$refs.editor, {
            ...options,
            ...this.$props.options,
        });

        this.onChange();

        const isSyntaxValid = await this.validateSyntax();

        if (!isSyntaxValid) {
            return;
        }

        this.value = this.getValue();
        this.clearAnnotations().setEditorSubjectValue();
    },
    methods: {
        getValue() {
            return this.editor.getSession().getValue();
        },
        getMode() {
            return this.editor.getSession().getMode().$id;
        },
        setEditorSubjectValue(value = this.value) {
            this.editorSubject.value = value;

            return this;
        },
        onChange() {
            const onEditorChange = async () => {
                const isSyntaxValid = await this.validateSyntax();

                if (!isSyntaxValid) {
                    return;
                }

                this.value = this.getValue();
                this.clearAnnotations().setEditorSubjectValue();
            };

            const debouncedOnChange = debounce(onEditorChange, DEBOUNCED_TIME);

            this.editor.getSession().on('change', debouncedOnChange);

            return this;
        },
        validateSyntax(value = this.getValue()) {
            const mode = this.getMode();

            return new Promise((resolve, reject) => {
                try {
                    if (mode !== DEFAULT_MODE) {
                        return;
                    }

                    import(/* webpackChunkName: "yaml" */ 'js-yaml')
                        .then(({ default: yaml }) => {
                            yaml.safeLoad(value);
                        })
                        .then(() => resolve(true))
                        .catch((error) => {
                            this.onSyntaxError(error);
                        });
                } catch (error) {
                    throw error;
                }
            });
        },
        onSyntaxError({
            mark: { column, line },
            message,
            type = ANNOTATION_TYPE,
        }) {
            this.editor.getSession().setAnnotations([
                {
                    column,
                    row: line,
                    text: message,
                    type,
                },
            ]);

            return this;
        },
        clearAnnotations() {
            this.editor.getSession().clearAnnotations();

            return this;
        },
    },
};
</script>
