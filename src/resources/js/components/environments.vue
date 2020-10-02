<template>
    <ul class="list-group">
        <li class="list-group-item" v-for="(environment, index) in environments">
            <div class="input-group">
                <input
                    type="text"
                    placeholder="Name"
                    class="form-control"
                    v-model="environment.name"
                />
                <input
                    type="text"
                    placeholder="Value"
                    class="form-control"
                    v-model="environment.value"
                />
                <button type="button" class="btn btn-secondary btn-icon" @click="deleteEnvironment(index)">
                    <icon name="trash" />
                </button>
            </div>
        </li>
        <li class="list-group-item">
            <button type="button" class="btn btn-block btn-secondary" @click="addEnvironment">
                <icon name="plus" />
                Add New
            </button>
        </li>
    </ul>
</template>

<script>
export default {
    props: {
        value: {
            type: Array,
            required: false,
        },
    },
    data() {
        return {
            environments: this.value,
        };
    },
    watch: {
        value: {
            immediate: true,
            handler: function (value) {
                this.environments = value;
            },
        },
        environments: {
            immediate: true,
            handler: function (value) {
                this.$emit('input', value);
            },
        },
    },
    methods: {
        addEnvironment() {
            this.environments.push({name: '', value: ''});
        },
        deleteEnvironment(index) {
            this.environments.splice(index, 1);
        },
    },
};
</script>
