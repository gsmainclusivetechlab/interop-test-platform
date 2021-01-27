<template>
    <ul class="list-group">
        <li
            class="list-group-item"
            v-bind:key="index"
            v-for="(environment, index) in environments"
        >
            <div class="input-group">
                <input
                    type="text"
                    placeholder="Key"
                    class="form-control"
                    @input="updateEnvironmentKey(index, $event.target.value)"
                    v-model="environment.key"
                />
                <input
                    v-if="environment.file_name"
                    type="text"
                    placeholder="Value"
                    class="form-control"
                    v-model="environment.file_name"
                    readonly
                />
                <b-form-file
                    v-else
                    class="form-control"
                    placeholder="Choose file..."
                    @input="updateEnvironmentValue(index, $event)"
                />
                <button
                    type="button"
                    class="btn btn-secondary btn-icon"
                    @click="deleteEnvironment(index)"
                >
                    <icon name="trash" />
                </button>
            </div>
        </li>
        <li class="list-group-item">
            <button
                type="button"
                class="btn btn-block btn-secondary"
                @click="addEnvironment"
            >
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
            type: Object | Array,
            required: false,
        },
    },
    data() {
        return {
            environments: [],
        };
    },
    watch: {
        environments: function (value) {
            this.emitEnvironments(value);
        },
    },
    mounted() {
        this.syncEnvironments(this.value);
    },
    methods: {
        emitEnvironments(values) {
            this.$emit(
                'input',
                collect(values)
                    .filter((item) => item.key)
                    .mapWithKeys((item) => [item.key, item.value])
                    .all()
            );
        },
        syncEnvironments(value) {
            this.environments = [];
            for (let key in value) {
                this.environments.push({
                    key: value[key].name,
                    value: value[key].id,
                    file_name: value[key].file_name,
                });
            }
        },
        addEnvironment() {
            this.environments.push([]);
        },
        deleteEnvironment(index) {
            this.environments.splice(index, 1);
        },
        updateEnvironmentKey(index, value) {
            this.environments[index].key = value;
            this.emitEnvironments(this.environments);
        },
        updateEnvironmentValue(index, value) {
            this.environments[index].value = value;
            this.emitEnvironments(this.environments);
        },
    },
};
</script>
