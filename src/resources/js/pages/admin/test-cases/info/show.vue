<template>
    <layout>
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">
                    <b>Info test case</b>
                </h1>
            </div>
            <!-- TODO - put out in common layout test case navigation -->
            <div class="d-flex align-items-baseline border-bottom mb-4">
                <ul class="nav nav-tabs mx-0 border-0">
                    <li class="nav-item">
                        <inertia-link
                            :href="
                                route('admin.test-cases.info.show', testCase.id)
                            "
                            class="nav-link rounded-0"
                            v-bind:class="{
                                active: route().current(
                                    'admin.test-cases.info.show'
                                ),
                            }"
                        >
                            Info
                        </inertia-link>
                    </li>
                    <li class="nav-item">
                        <inertia-link
                            :href="
                                route(
                                    'admin.test-cases.test-steps.index',
                                    testCase.id
                                )
                            "
                            class="nav-link rounded-0"
                            v-bind:class="{
                                active: route().current(
                                    'admin.test-cases.test-steps.index'
                                ),
                            }"
                        >
                            Test steps
                        </inertia-link>
                    </li>
                    <li class="nav-item">
                        <inertia-link
                            :href="
                                route(
                                    'admin.test-cases.groups.edit',
                                    testCase.id
                                )
                            "
                            class="nav-link rounded-0"
                            v-bind:class="{
                                active: route().current(
                                    'admin.test-cases.groups.edit'
                                ),
                            }"
                        >
                            Groups
                        </inertia-link>
                    </li>
                    <li class="nav-item">
                        <inertia-link
                            :href="
                                route(
                                    'admin.test-cases.versions.index',
                                    testCase.id
                                )
                            "
                            class="nav-link rounded-0"
                            v-bind:class="{
                                active: route().current(
                                    'admin.test-cases.versions.index'
                                ),
                            }"
                        >
                            Versions
                        </inertia-link>
                    </li>
                </ul>
            </div>
            <!-- end TODO -->
            <div class="flex-fill d-flex flex-column justify-content-center">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label">Name</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="name"
                                    readonly
                                />
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Slug</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="slug"
                                    readonly
                                />
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Behavior</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="behavior"
                                    readonly
                                />
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Use Case</label>
                                <input
                                    class="form-control"
                                    type="text"
                                    v-model="useCase"
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
                    </div>
                    <div class="card-footer text-right">
                        <inertia-link
                            :href="
                                route('admin.test-cases.info.edit', testCase.id)
                            "
                            class="btn btn-primary"
                        >
                            Edit
                        </inertia-link>
                    </div>
                </div>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/main';

export default {
    metaInfo: {
        title: 'Test case',
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
            name: this.testCase.name,
            slug: this.testCase.slug,
            behavior: collect(this.$page.enums.test_case_behaviors).get(
                this.testCase.behavior
            ),
            useCase: this.testCase.useCase.data.name,
            description: this.testCase.description,
            precondition: this.testCase.precondition,
            components: this.testCase.components.data,
        };
    },
};
</script>
