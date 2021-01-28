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
                    class="form-control border-0"
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
                this.environments.push({
                    key: value[key].name,
                    value: value[key].id,
                    file_name: value[key].file_name,
                });
            }
        },
    },
};
</script>
