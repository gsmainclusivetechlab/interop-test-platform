<template>
    <div>
        <div
            v-for="(environment, i) in environments"
            class="list-group-item"
            :key="i"
        >
            <div
                class="input-group"
                :class="{
                    'is-invalid': environment.error,
                }"
            >
                <input
                    :value="environment.key"
                    type="text"
                    placeholder="Key"
                    class="form-control px-2"
                    :class="{
                        'is-invalid': environment.error,
                    }"
                    @input.prevent="
                        (e) => setKey(e.target.value, environment, i)
                    "
                />
                <input
                    v-if="environment.type === 'text'"
                    v-model="environment.value"
                    type="text"
                    placeholder="Value"
                    class="form-control px-2"
                    :class="{
                        'is-invalid': environment.error,
                    }"
                />
                <template v-else-if="environment.type === 'file'">
                    <input
                        v-if="environment.file_name"
                        v-model="environment.file_name"
                        type="text"
                        placeholder="Value"
                        readonly
                        class="form-control"
                    />
                    <b-form-file
                        v-else
                        v-model="environment.value"
                        placeholder="Choose file..."
                        class="form-control border-0"
                    />
                </template>
                <button
                    type="button"
                    class="btn btn-icon"
                    :class="{
                        'btn-secondary': !environment.error,
                        'btn-outline-primary': environment.error,
                    }"
                    @click="deleteEnvironment(i)"
                >
                    <icon name="trash" />
                </button>
            </div>
            <span v-if="environment.error" class="invalid-feedback">
                <strong>
                    {{ environment.error }}
                </strong>
            </span>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        value: {
            type: Array,
            required: false,
            default: [],
        },
        envsType: {
            type: String,
            required: false,
            default: 'text',
        },
    },
    data() {
        return {
            environments: this.value,
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
    methods: {
        syncEnvironments(value) {
            this.environments.splice(0, this.environments.length, ...value);
        },
        emitEnvironments(value) {
            if (value.find((el) => el.error !== null)) return;

            this.$emit('input', value);
        },
        addEnvironment(type) {
            this.environments.push({
                key: '',
                value: null,
                type: type,
                error: null,
            });
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
</script>
