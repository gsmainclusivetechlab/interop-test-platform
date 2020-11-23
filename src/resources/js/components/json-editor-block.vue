<template>
    <div>
        <div class="editor-w clearfix">
            <div>
                <div class="editor">
                    <json-editor
                        :options="{
                            confirmText: 'confirm',
                            cancelText: 'cancel',
                        }"
                        :objData="jsonData"
                        v-model="jsonData"
                    ></json-editor>
                </div>
            </div>
            <div>
                <json-tree
                    :data="jsonData"
                    :deep="1"
                    :show-line="false"
                    class="p-2"
                ></json-tree>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            jsonData: {
                name: 'may',
                age: null,
                address: [
                    'Panyu Shiqiao on Canton',
                    'Tianhe',
                    {
                        city: 'forida meta 11',
                    },
                ],
                ohters: {
                    id: 1246,
                    joinTime: '2017-08-20. 10:20',
                    description: 'another',
                },
            },
        };
    },
};
</script>
