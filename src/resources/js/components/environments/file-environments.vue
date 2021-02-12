<template>
    <ul class="list-group">
        <li
            v-for="(environment, i) in environments"
            class="list-group-item"
            :key="i"
        >
            <div class="input-group">
                <input
                    v-model="environment.key"
                    type="text"
                    placeholder="Key"
                    class="form-control"
                    @input="updateEnvironmentKey(i, $event.target.value)"
                />
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
                    placeholder="Choose file..."
                    class="form-control border-0"
                    @input="updateEnvironmentValue(i, $event)"
                />
                <button
                    type="button"
                    class="btn btn-secondary btn-icon"
                    @click="deleteEnvironment(i)"
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
            console.log('file-sync: ', value);
            this.environments = [];
            for (let key in value) {
                this.environments.push({
                    key: value[key].name,
                    value: value[key]?.id ?? null,
                    file_name: value[key]?.file_name ?? null,
                });
            }
        },
    },
};
</script>
