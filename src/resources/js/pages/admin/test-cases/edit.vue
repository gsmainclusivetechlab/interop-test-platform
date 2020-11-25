<template>
    <layout>
        <div class="flex-fill d-flex flex-column justify-content-center">
            <div class="page-header">
                <h1 class="page-title text-center">
                    <b>Update test case</b>
                </h1>
            </div>
            <div class="container">
                <form class="card" @submit.prevent="submit">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label"> Name </label>
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
                            <label class="form-label">Behavior</label>
                            <selectize
                                class="form-select"
                                v-model="form.behavior"
                                placeholder="Select behavior"
                                :options="
                                    collect(
                                        $page.enums.test_case_behaviors
                                    ).toArray()
                                "
                                :disableSearch="false"
                                :createItem="false"
                            />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Use Case</label>
                            <selectize
                                class="form-select"
                                v-model="useCase"
                                label="name"
                                placeholder="Select use case"
                                :options="$page.useCases"
                                :disableSearch="false"
                                :createItem="false"
                            />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <text-editor
                                :class="{
                                    'is-invalid': $page.errors.description,
                                }"
                                :menu-items="[
                                    'bold',
                                    'italic',
                                    'strike',
                                    'underline',
                                    'ordered_list',
                                    'bullet_list',
                                    'code',
                                    'hard_break',
                                ]"
                                :output-format="['html']"
                                :content="{ html: form.description }"
                                @output-html="
                                    (content) => (form.description = content)
                                "
                            />
                            <span
                                v-if="$page.errors.description"
                                class="invalid-feedback"
                            >
                                <strong>
                                    {{ $page.errors.description }}
                                </strong>
                            </span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Precondition</label>
                            <text-editor
                                :class="{
                                    'is-invalid': $page.errors.description,
                                }"
                                :menu-items="[
                                    'bold',
                                    'italic',
                                    'strike',
                                    'underline',
                                    'ordered_list',
                                    'bullet_list',
                                    'code',
                                    'hard_break',
                                ]"
                                :output-format="['html']"
                                :content="{ html: form.precondition }"
                                @output-html="
                                    (content) => (form.precondition = content)
                                "
                            />
                            <span
                                v-if="$page.errors.description"
                                class="invalid-feedback"
                            >
                                <strong>
                                    {{ $page.errors.description }}
                                </strong>
                            </span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Components</label>
                            <selectize
                                v-model="components"
                                multiple
                                class="form-select"
                                placeholder="Select components"
                                :class="{
                                    'is-invalid': $page.errors.groups_id,
                                }"
                                label="name"
                                :options="$page.components"
                                :createItem="false"
                                :disableSearch="true"
                            />
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
                        <div class="mb-3">
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
            useCase: this.$page.useCases.filter(
                (el) => el.name === this.testCase.useCase.data.name
            )[0],
            components: null,
            behavior: this.testCase.behavior,
            form: {
                name: this.testCase.name,
                behavior: this.testCase.behavior,
                use_case_id: this.useCase?.id,
                components_id: null,
                description: this.testCase.description,
                precondition: this.testCase.precondition,
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
        useCase: {
            immediate: true,
            deep: true,
            handler() {
                this.form.use_case_id = this.useCase?.id;
            },
        },
        components: {
            immediate: true,
            deep: true,
            handler() {
                this.form.components_id = this.components?.map((el) => el.id);
            },
        },
    },
    mounted() {
        console.log();
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
