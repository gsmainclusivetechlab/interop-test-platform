<template>
    <ul class="list-group">
        <li
            class="list-group-item"
            v-for="(environment, index) in environments"
        >
            <div class="input-group">
                <input
                    type="text"
                    placeholder="Key"
                    class="form-control"
                    v-model="environment.key"
                />
                <input
                    type="text"
                    placeholder="Value"
                    class="form-control"
                    v-model="environment.value"
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
            type: Object,
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
            this.$emit(
                'input',
                collect(value)
                    .filter((item) => item.key)
                    .mapWithKeys((item) => [item.key, item.value])
                    .all()
            );
        },
    },
    mounted() {
        this.syncEnvironment(this.value);
    },
    methods: {
        syncEnvironment(value) {
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
    },
};
</script>
