<template>
    <layout>
        <div class="flex-fill d-flex flex-column justify-content-center">
            <div class="page-header">
                <h1 class="page-title text-center">
                    <b>Create test case</b>
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
                            <div class="col-6 mb-3">
                                <label class="form-label">Slug</label>
                                <input
                                    name="slug"
                                    type="text"
                                    class="form-control"
                                    v-model="form.slug"
                                    :class="{
                                        'is-invalid': $page.errors.slug,
                                    }"
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
                                    v-model="useCase"
                                    label="name"
                                    placeholder="Select use case"
                                    :options="$page.useCases"
                                    :disableSearch="false"
                                    :createItem="false"
                                />
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
                                    :content="{ html: form.description }"
                                    @output-html="
                                        (content) =>
                                            (form.description = content)
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
                                    :content="{ html: form.precondition }"
                                    @output-html="
                                        (content) =>
                                            (form.precondition = content)
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
                                    v-model="components"
                                    multiple
                                    class="form-select"
                                    placeholder="Select components"
                                    :class="{
                                        'is-invalid':
                                            $page.errors.components_id,
                                    }"
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
                            Create
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
        title: 'Create test case',
    },
    components: {
        Layout,
    },
    data() {
        return {
            sending: false,
            useCase: null,
            components: null,
            form: {
                name: null,
                slug: null,
                behavior: null,
                use_case_id: null,
                components_id: null,
                description: null,
                precondition: null,
            },
        };
    },
    watch: {
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
    methods: {
        submit() {
            this.sending = true;
            this.$inertia
                .post(route('admin.test-cases.store'), this.form)
                .then(() => (this.sending = false));
        },
    },
};
</script>
