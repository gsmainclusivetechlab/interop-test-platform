<template>
    <layout>
        <div class="flex-fill d-flex flex-column justify-content-center">
            <div class="page-header">
                <h1 class="page-title text-center">
                    <b>Update test case</b>
                </h1>
            </div>
            <div class="container">
                <div class="col-8 m-auto">
                    <form class="card" @submit.prevent="submit">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">
                                    Name
                                </label>
                                <input
                                    name="name"
                                    type="text"
                                    class="form-control"
                                    v-model="form.name"
                                    :class="{
                                        'is-invalid': $page.errors.name,
                                    }"
                                />
                                <span
                                    v-if="$page.errors.name"
                                    class="invalid-feedback"
                                >
                                    <strong>
                                        {{ $page.errors.name }}
                                    </strong>
                                </span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    Behavior
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :value="
                                        collect(
                                            $page.enums.test_case_behaviors
                                        ).get(testCase.behavior)
                                    "
                                    readonly
                                />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    Use Case
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :value="testCase.useCase.data.name"
                                    readonly
                                />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    Groups
                                </label>
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
                                    <template
                                        slot="option"
                                        slot-scope="{ option }"
                                    >
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
                                            collect(
                                                $page.errors.groups_id
                                            ).implode(' ')
                                        }}
                                    </strong>
                                </span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    Description
                                </label>
                                <textarea
                                    name="description"
                                    class="form-control"
                                    rows="5"
                                    v-model="form.description"
                                    :class="{
                                        'is-invalid': $page.errors.description,
                                    }"
                                ></textarea>
                                <span
                                    v-if="$page.errors.description"
                                    class="invalid-feedback"
                                >
                                    <strong>
                                        {{ $page.errors.description }}
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
                name: this.testCase.name,
                description: this.testCase.description,
                groups_id: null,
            },
        };
    },
    watch: {
        groups: {
            immediate: true,
            handler: function (value) {
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
                .get(
                    route(
                        'admin.test-cases.group-candidates',
                        this.testCase.id
                    ),
                    {
                        params: { q: query },
                    }
                )
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
