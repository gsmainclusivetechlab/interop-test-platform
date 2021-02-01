export default {
    data() {
        return {
            environments: [],
        };
    },
    watch: {
        environments(value) {
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
