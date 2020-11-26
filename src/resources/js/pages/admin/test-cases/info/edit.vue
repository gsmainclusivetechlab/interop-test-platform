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
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label">Name</label>
                                <input
                                    name="name"
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': $page.errors.name,
                                    }"
                                    v-model="name"
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
                            <div class="col-6 mb-3">
                                <label class="form-label">Slug</label>
                                <input
                                    name="slug"
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': $page.errors.slug,
                                    }"
                                    v-model="slug"
                                />
                                <span
                                    v-if="$page.errors.slug"
                                    class="invalid-feedback"
                                >
                                    <strong>
                                        {{ $page.errors.slug }}
                                    </strong>
                                </span>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Behavior</label>
                                <selectize
                                    class="form-select"
                                    :class="{
                                        'is-invalid': $page.errors.behavior,
                                    }"
                                    v-model="behavior"
                                    placeholder="Select behavior"
                                    :options="
                                        collect(
                                            $page.enums.test_case_behaviors
                                        ).toArray()
                                    "
                                    :disableSearch="false"
                                    :createItem="false"
                                />
                                <span
                                    v-if="$page.errors.behavior"
                                    class="invalid-feedback"
                                >
                                    <strong>
                                        {{ $page.errors.behavior }}
                                    </strong>
                                </span>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Use Case</label>
                                <selectize
                                    class="form-select"
                                    :class="{
                                        'is-invalid': $page.errors.use_case_id,
                                    }"
                                    v-model="useCase"
                                    label="name"
                                    placeholder="Select use case"
                                    :options="$page.useCases"
                                    :disableSearch="false"
                                    :createItem="false"
                                />
                                <span
                                    v-if="$page.errors.use_case_id"
                                    class="invalid-feedback"
                                >
                                    <strong>
                                        {{ $page.errors.use_case_id }}
                                    </strong>
                                </span>
                            </div>
                            <div class="col-6 mb-3">
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
                                    :content="{ html: description }"
                                    @output-html="
                                        (content) => (description = content)
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
                            <div class="col-6 mb-3">
                                <label class="form-label">Precondition</label>
                                <text-editor
                                    :class="{
                                        'is-invalid': $page.errors.precondition,
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
                                    :content="{ html: precondition }"
                                    @output-html="
                                        (content) => (precondition = content)
                                    "
                                />
                                <span
                                    v-if="$page.errors.precondition"
                                    class="invalid-feedback"
                                >
                                    <strong>
                                        {{ $page.errors.precondition }}
                                    </strong>
                                </span>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Components</label>
                                <selectize
                                    class="form-select"
                                    :class="{
                                        'is-invalid':
                                            $page.errors.components_id,
                                    }"
                                    v-model="components"
                                    multiple
                                    placeholder="Select components"
                                    label="name"
                                    :options="$page.components"
                                    :createItem="false"
                                    :disableSearch="true"
                                />
                                <span
                                    v-if="$page.errors.components_id"
                                    class="invalid-feedback"
                                >
                                    <strong>
                                        {{
                                            collect(
                                                $page.errors.components_id
                                            ).implode(' ')
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
            name: this.testCase.name,
            slug: this.testCase.slug,
            behavior: collect(this.$page.enums.test_case_behaviors).get(
                this.testCase.behavior
            ),
            useCase: this.$page.useCases.filter(
                (el) => el.name === this.testCase.useCase.data.name
            )[0],
            description: this.testCase.description,
            precondition: this.testCase.precondition,
            components: this.testCase.components.data,
        };
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
    },
    computed: {
        form() {
            const form = {
                name: this.name,
                slug: this.slug,
                behavior: collect(this.$page.enums.test_case_behaviors)
                    .flip()
                    .all()[this.behavior],
                use_case_id: this.useCase?.id,
                components_id: this.components?.map((el) => el.id),
                description: this.description,
                precondition: this.precondition,
            };

            return form;
        },
    },
};
</script>
