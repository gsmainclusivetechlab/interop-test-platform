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
                <!--                <input-->
                <!--                    type="text"-->
                <!--                    placeholder="Value"-->
                <!--                    class="form-control"-->
                <!--                    @input="updateEnvironmentValue(index, $event.target.value)"-->
                <!--                    v-model="environment.value"-->
                <!--                />-->
                <b-form-file
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
                this.environments.push({ key: key, value: value[key] });
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
