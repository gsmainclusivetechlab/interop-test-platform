<template>
    <div>
        <textarea
            :name="this.$props.name"
            ref="editorSubject"
            hidden
        ></textarea>

        <div
            class="web-editor form-control pr-0"
            :class="this.$props.editorClass"
            ref="editor"
        >
            <slot name="content"></slot>
        </div>

        <slot name="validation"></slot>
    </div>
</template>

<script>
import ace from 'brace';
import { debounce } from '../helpers';

import 'brace/theme/chrome';
import 'brace/mode/yaml';
import 'brace/mode/json';

const DEBOUNCED_TIME = 250;
const ANNOTATION_TYPE = 'error';
const YAML_MODE = 'ace/mode/yaml';
const JSON_MODE = 'ace/mode/json';

const options = {
    mode: YAML_MODE,
    theme: 'ace/theme/chrome',
    fontSize: 14,
};

export default {
    props: {
        options: {
            type: Object,
        },
        editorClass: {
            type: String,
        },
        name: {
            type: String,
            required: true,
        },
    },
    data() {
        return {
            editor: null,
            editorSubject: null,
        };
    },
    async mounted() {
        this.editorSubject = this.$refs.editorSubject;
        this.editor = ace.edit(this.$refs.editor);

        this.editor.setOptions({
            ...options,
            ...this.$props.options,
        });

        this.onChange();

        await this.validateSyntax();

        this.setEditorSubjectValue();
    },
    methods: {
        getValue() {
            return this.editor.getSession().getValue();
        },
        getMode() {
            return this.editor.getSession().getMode().$id;
        },
        setEditorSubjectValue(value = this.getValue()) {
            this.editorSubject.value = value;
            return this;
        },
        onChange() {
            const onEditorChange = async () => {
                await this.validateSyntax();
                this.setEditorSubjectValue();
            };

            const debouncedOnChange = debounce(onEditorChange, DEBOUNCED_TIME);

            this.editor.getSession().on('change', debouncedOnChange);

            return this;
        },
        validateSyntax(value = this.getValue()) {
            const mode = this.getMode();

            if (mode === YAML_MODE) {
                return new Promise((resolve, reject) => {
                    try {
                        import(/* webpackChunkName: "yaml" */ 'js-yaml')
                            .then(({ default: yaml }) => {
                                yaml.safeLoad(value);
                            })
                            .then(() => {
                                this.clearAnnotations();

                                return resolve(true);
                            })
                            .catch((error) => {
                                this.onSyntaxError(error);
                            });
                    } catch (error) {
                        throw error;
                    }
                });
            }

            return this;
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

            this.setEditorSubjectValue();

            return this;
        },
        clearAnnotations() {
            this.editor.getSession().clearAnnotations();

            return this;
        },
    },
};
</script>
