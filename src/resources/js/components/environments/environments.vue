<template>
    <ul class="list-group">
        <li
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
                    v-model="environment.value"
                    type="text"
                    placeholder="Value"
                    class="form-control px-2"
                    :class="{
                        'is-invalid': environment.error,
                    }"
                />
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
        </li>
        <li class="list-group-item">
            <button
                type="button"
                class="btn btn-block btn-secondary"
                @click="addEnvironment"
            >
                <icon name="plus" />
                <span>Add New</span>
            </button>
        </li>
    </ul>
</template>

<script>
import mixin from '@/components/environments/mixin';
export default {
    props: {
        value: {
            type: Object | Array,
            required: false,
        },
    },
    mixins: [mixin],
    methods: {
        syncEnvironments(value) {
            this.environments.splice(0, this.environments.length, ...value);

            // for (let key in value) {
            //     this.environments.push({
            //         key: key,
            //         value: value[key],
            //     });
            // }
        },
    },
};
</script>
