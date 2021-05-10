<template>
    <div>
        <div v-for="(environment, i) in value" class="list-group-item" :key="i">
            <div
                class="input-group"
                :class="{
                    'is-invalid': errors[i],
                }"
            >
                <input
                    :value="environment.key"
                    type="text"
                    placeholder="Key"
                    class="form-control px-2"
                    :class="{
                        'is-invalid': errors[i],
                    }"
                    @input.prevent="
                        (e) =>
                            setVar(
                                { ...environment, key: e.target.value.trim() },
                                i
                            )
                    "
                />
                <input
                    v-if="environment.type === 'text'"
                    :value="environment.value"
                    @input.prevent="
                        (e) =>
                            setVar({ ...environment, value: e.target.value }, i)
                    "
                    type="text"
                    placeholder="Value"
                    class="form-control px-2"
                    :class="{
                        'is-invalid': errors[i],
                    }"
                />
                <template v-else-if="environment.type === 'file'">
                    <input
                        v-if="environment.file_name"
                        :value="environment.file_name"
                        type="text"
                        placeholder="Value"
                        readonly
                        class="form-control"
                    />
                    <b-form-file
                        v-else
                        :value="environment.value"
                        @input="
                            (file) => setVar({ ...environment, value: file }, i)
                        "
                        placeholder="Choose file..."
                        class="form-control border-0"
                    />
                </template>
                <button
                    type="button"
                    class="btn btn-icon"
                    :class="{
                        'btn-secondary': !errors[i],
                        'btn-outline-primary': errors[i],
                    }"
                    @click="deleteVar(i)"
                >
                    <icon name="trash" />
                </button>
            </div>
            <span v-if="errors[i]" class="invalid-feedback">
                <strong>
                    {{ errors[i] }}
                </strong>
            </span>
        </div>
        <div class="btn-group w-100 mt-2">
            <button
                type="button"
                class="btn btn-secondary"
                @click="addVar('text')"
            >
                <icon name="plus" />
                <span>Add String</span>
            </button>
            <button
                type="button"
                class="btn btn-secondary"
                @click="addVar('file')"
            >
                <icon name="plus" />
                <span>Add File</span>
            </button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        value: {
            type: Array,
            defaultValue: [],
        },
    },
    methods: {
        setVar(variable, index) {
            this.$emit(
                'input',
                this.value.map((v, i) => (i === index ? variable : v))
            );
        },
        addVar(type) {
            this.$emit(
                'input',
                this.value.concat([{ type, key: '', value: null, error: null }])
            );
        },
        deleteVar(index) {
            this.$emit(
                'input',
                this.value.filter((v, i) => i !== index)
            );
        },
    },
    computed: {
        errors: function () {
            return Object.fromEntries(
                this.value
                    .map((v, index) => {
                        if (!v.key && v.value) {
                            return [index, 'Empty key'];
                        }

                        // NB: technically the BE will allow a file env and a text env to share a key, but we forbid anyway
                        if (
                            this.value.some(
                                (el) => el !== v && el.key && el.key === v.key
                            )
                        ) {
                            return [index, 'Duplicate key'];
                        }
                        return null;
                    })
                    .filter((err) => !!err)
            );
        },
    },
};
</script>
