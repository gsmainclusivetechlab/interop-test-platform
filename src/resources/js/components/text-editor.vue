<template>
    <div class="text-editor">
        <editor-menu-bar :editor="editor" v-slot="{ commands, isActive }">
            <div>
                <button
                    v-if="menuItems.includes('block_quote')"
                    type="button"
                    class="btn btn-sm"
                    :class="
                        !isActive.block_quote()
                            ? 'btn-outline-primary'
                            : 'btn-primary'
                    "
                    @click="commands.block_quote"
                >
                    block quote
                </button>
                <button
                    v-if="menuItems.includes('block_quote')"
                    type="button"
                    class="btn btn-sm"
                    :class="
                        !isActive.code_block()
                            ? 'btn-outline-primary'
                            : 'btn-primary'
                    "
                    @click="commands.code_block"
                >
                    code block
                </button>
                <button
                    v-if="menuItems.includes('hard_break')"
                    type="button"
                    class="btn btn-sm"
                    :class="
                        !isActive.hard_break()
                            ? 'btn-outline-primary'
                            : 'btn-primary'
                    "
                    @click="commands.hard_break"
                >
                    hard break
                </button>
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
                >
                    todo list
                </button>
                <button
                    v-if="menuItems.includes('bold')"
                    type="button"
                    class="btn btn-sm"
                    :class="
                        !isActive.bold() ? 'btn-outline-primary' : 'btn-primary'
                    "
                    @click="commands.bold"
                >
                    bold
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
                >
                    italic
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
                >
                    underline
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
                >
                    strike
                </button>
                <button
                    v-if="menuItems.includes('code')"
                    type="button"
                    class="btn btn-sm"
                    :class="
                        !isActive.code() ? 'btn-outline-primary' : 'btn-primary'
                    "
                    @click="commands.code"
                >
                    code
                </button>
            </div>
        </editor-menu-bar>
        <editor-content class="form-control mt-1" :editor="editor" />
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
        },
        outputFormat: {
            type: Array,
        },
    },
    data() {
        return {
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
        });
    },
    beforeDestroy() {
        this.editor.destroy();
    },
};
</script>
