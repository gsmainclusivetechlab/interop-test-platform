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
        emitEnvironments(value) {
            if (value.find((el) => el.error !== null)) return;

            // const emitValue = Object.fromEntries(
            //     value.map((el) => [el.key, el.value])
            // );

            this.$emit('input', value);
        },
        addEnvironment() {
            this.environments.push({ key: '', value: null, error: null });
        },
        deleteEnvironment(index) {
            this.environments.splice(index, 1);
        },
        setKey(val, environment, index) {
            if (environment.error) environment.error = null;
            environment.key = val;

            switch (this.checkKey(val, index)) {
                case 'duplicated':
                    environment.error = 'Duplicated key';
                    break;
                case 'empty':
                    environment.error = 'Empty key';
                    break;
                default:
                    break;
            }
        },
        checkKey(key, index) {
            if (
                this.environments.some((el, i) => el.key === key && index !== i)
            ) {
                return 'duplicated';
            }
            if (key === null || key === undefined || key === '') {
                return 'empty';
            }
        },
    },
};
