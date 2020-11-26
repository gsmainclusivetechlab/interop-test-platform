<template>
    <layout>
        <div class="flex-fill d-flex flex-column justify-content-center">
            <div class="page-header">
                <h1 class="page-title text-center">
                    <b>Groups</b>
                </h1>
            </div>
            <div class="container">
                <form class="card" @submit.prevent="submit">
                    <div class="card-body">
                        <div>
                            <label class="form-label">Groups</label>
                            <selectize
                                v-model="groups"
                                multiple
                                class="form-select"
                                placeholder="Select groups..."
                                :class="{
                                    'is-invalid': $page.errors.groups_id,
                                }"
                                label="name"
                                :keys="['name']"
                                :options="groupsList"
                                :createItem="false"
                                :searchFn="searchGroups"
                            >
                                <template slot="option" slot-scope="{ option }">
                                    <div>{{ option.name }}</div>
                                    <div class="text-muted small">
                                        {{ option.domain }}
                                    </div>
                                </template>
                            </selectize>
                            <span
                                v-if="$page.errors.groups_id"
                                class="invalid-feedback"
                            >
                                <strong>
                                    {{
                                        collect($page.errors.groups_id).implode(
                                            ' '
                                        )
                                    }}
                                </strong>
                            </span>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <inertia-link
                            :href="route('admin.test-cases.index')"
                            class="btn btn-link"
                        >
                            Cancel
                        </inertia-link>
                        <button type="submit" class="btn btn-primary">
                            <span
                                v-if="sending"
                                class="spinner-border spinner-border-sm mr-2"
                            ></span>
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/main';

export default {
    metaInfo: {
        title: 'Update test case',
    },
    components: {
        Layout,
    },
    props: {
        testCase: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            sending: false,
            groups: this.testCase.groups ? this.testCase.groups.data : [],
            groupsList: [],
            form: {
                groups_id: null,
            },
        };
    },
    watch: {
        groups: {
            immediate: true,
            handler(value) {
                this.form.groups_id = value
                    ? collect(value)
                          .map((item) => item.id)
                          .all()
                    : [];
            },
        },
    },
    mounted() {
        this.loadGroupsList();
    },
    methods: {
        submit() {
            this.sending = true;
            this.$inertia
                .put(
                    route('admin.test-cases.update', this.testCase.id),
                    this.form
                )
                .then(() => (this.sending = false));
        },
        loadGroupsList(query = '') {
            axios
                .get(route('admin.test-cases.group-candidates'), {
                    params: { q: query },
                })
                .then((result) => {
                    this.groupsList = collect(result.data.data)
                        .whereNotIn('id', this.form.groups_id)
                        .all();
                });
        },
        searchGroups(query, callback) {
            this.loadGroupsList(query);
            callback();
        },
    },
};
</script>
