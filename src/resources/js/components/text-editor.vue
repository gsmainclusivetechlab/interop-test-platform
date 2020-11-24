<template>
    <div class="text-editor" :class="{ 'text-editor_focused': focused }">
        <editor-menu-bar :editor="editor" v-slot="{ commands, isActive }">
            <div>
                <button
                    v-if="menuItems.includes('ordered_list')"
                    type="button"
                    class="btn btn-sm"
                    :class="
                        !isActive.ordered_list()
                            ? 'btn-outline-primary'
                            : 'btn-primary'
                    "
                    @click="commands.ordered_list"
                    title="ordered list"
                >
                    ordered list
                </button>
                <button
                    v-if="menuItems.includes('bullet_list')"
                    type="button"
                    class="btn btn-sm"
                    :class="
                        !isActive.bullet_list()
                            ? 'btn-outline-primary'
                            : 'btn-primary'
                    "
                    @click="commands.bullet_list"
                    title="bullet list"
                >
                    bullet list
                </button>
                <button
                    v-if="menuItems.includes('todo_list')"
                    type="button"
                    class="btn btn-sm"
                    :class="
                        !isActive.todo_list()
                            ? 'btn-outline-primary'
                            : 'btn-primary'
                    "
                    @click="commands.todo_list"
                    title="todo list"
                >
                    todo list
                </button>
                <button
                    v-if="menuItems.includes('hard_break')"
                    type="button"
                    class="btn btn-sm btn-outline-primary"
                    @click="commands.hard_break"
                    title="hard break"
                >
                    <icon name="page-break" class="m-0"></icon>
                </button>
                <button
                    v-if="menuItems.includes('bold')"
                    type="button"
                    class="btn btn-sm"
                    :class="
                        !isActive.bold() ? 'btn-outline-primary' : 'btn-primary'
                    "
                    @click="commands.bold"
                    title="bold"
                >
                    <icon name="bold" class="m-0"></icon>
                </button>
                <button
                    v-if="menuItems.includes('italic')"
                    type="button"
                    class="btn btn-sm"
                    :class="
                        !isActive.italic()
                            ? 'btn-outline-primary'
                            : 'btn-primary'
                    "
                    @click="commands.italic"
                    title="italic"
                >
                    <icon name="italic" class="m-0"></icon>
                </button>
                <button
                    v-if="menuItems.includes('underline')"
                    type="button"
                    class="btn btn-sm"
                    :class="
                        !isActive.underline()
                            ? 'btn-outline-primary'
                            : 'btn-primary'
                    "
                    @click="commands.underline"
                    title="underline"
                >
                    <icon name="underline" class="m-0"></icon>
                </button>
                <button
                    v-if="menuItems.includes('strike')"
                    type="button"
                    class="btn btn-sm"
                    :class="
                        !isActive.strike()
                            ? 'btn-outline-primary'
                            : 'btn-primary'
                    "
                    @click="commands.strike"
                    title="strike"
                >
                    <icon name="strikethrough" class="m-0"></icon>
                </button>
                <button
                    v-if="menuItems.includes('code')"
                    type="button"
                    class="btn btn-sm"
                    :class="
                        !isActive.code() ? 'btn-outline-primary' : 'btn-primary'
                    "
                    @click="commands.code"
                    title="code"
                >
                    <icon name="code" class="m-0"></icon>
                </button>
            </div>
        </editor-menu-bar>
        <editor-content class="form-control mt-2" :editor="editor" />
    </div>
</template>
<script >
import { Editor, EditorContent, EditorMenuBar } from 'tiptap';
import {
    Blockquote,
    CodeBlock,
    HardBreak,
    Heading,
    HorizontalRule,
    OrderedList,
    BulletList,
    ListItem,
    TodoItem,
    TodoList,
    Bold,
    Code,
    Italic,
    Link,
    Strike,
    Underline,
    History,
} from 'tiptap-extensions';

export default {
    components: {
        EditorMenuBar,
        EditorContent,
    },
    props: {
        menuItems: {
            type: Array,
            requuired: true,
        },
        outputFormat: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            focused: false,
            editor: null,
            json: {},
            html: '',
        };
    },
    mounted() {
        this.editor = new Editor({
            extensions: [
                new Blockquote(),
                new BulletList(),
                new CodeBlock(),
                new HardBreak(),
                new Heading({ levels: [1, 2, 3] }),
                new HorizontalRule(),
                new ListItem(),
                new OrderedList(),
                new TodoItem(),
                new TodoList(),
                new Link(),
                new Bold(),
                new Code(),
                new Italic(),
                new Strike(),
                new Underline(),
                new History(),
            ],
            content: '',
            onUpdate: ({ getJSON, getHTML }) => {
                if (this.outputFormat.includes('html')) {
                    this.html = getHTML();
                }
                if (this.outputFormat.includes('json')) {
                    this.json = getJSON();
                }
            },
            onFocus: () => {
                this.focused = true;
            },
            onBlur: () => {
                this.focused = false;

                if (this.outputFormat.includes('html')) {
                    this.$emit('output-html', this.html);
                }

                if (this.outputFormat.includes('json')) {
                    this.$emit('output-json', this.json);
                }
            },
        });
    },
    beforeDestroy() {
        this.editor.destroy();
    },
};
</script>
