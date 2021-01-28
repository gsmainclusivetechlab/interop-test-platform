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
                    type="text"
                    placeholder="Value"
                    class="form-control"
                    @input="updateEnvironmentValue(index, $event.target.value)"
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
            this.environments = [];
            for (let key in value) {
                this.environments.push({ key: key, value: value[key] });
            }
        },
    },
};
</script>
