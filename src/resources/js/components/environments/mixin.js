export default {
    data() {
        return {
            environments: [],
        };
    },
    watch: {
        environments: {
            deep: true,
            handler(value) {
                this.emitEnvironments(value);
            },
        },
    },
    mounted() {
        this.syncEnvironments(this.value);
    },
    methods: {
        emitEnvironments(values) {
            const emitValue = Object.fromEntries(
                values
                    .filter((el) => el.error === null)
                    .map((el) => [el.key, el.value])
            );

            this.$emit('input', emitValue);
        },
        addEnvironment() {
            this.environments.push({ key: null, value: null, error: null });
        },
        deleteEnvironment(index) {
            this.environments.splice(index, 1);
        },
        setKey(val, environment, index) {
            if (environment.error) environment.error = null;

            if (this.checkKey(val, index)) {
                environment.key = val;
            } else {
                environment.error = 'Duplicated key';
            }
        },
        checkKey(key, index) {
            return !this.environments.some(
                (el, i) => el.key === key && index !== i
            );
        },
    },
};
