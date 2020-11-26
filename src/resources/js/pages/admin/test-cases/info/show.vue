<template>
    <layout>
        <!-- TODO - put out in common layout test case navigation -->
        <div class="d-flex align-items-baseline border-bottom mb-4">
            <ul class="nav nav-tabs mx-0 border-0">
                <li class="nav-item">
                    <inertia-link
                        :href="`route('groups.show', group.id)`"
                        class="nav-link rounded-0"
                        v-bind:class="{
                            active: `route().current('info.show')`,
                        }"
                    >
                        Info
                    </inertia-link>
                </li>
                <li class="nav-item">
                    <inertia-link
                        :href="`route('groups.show', group.id)`"
                        class="nav-link rounded-0"
                        v-bind:class="{
                            active: `route().current('test-steps.index')`,
                        }"
                    >
                        Test steps
                    </inertia-link>
                </li>
                <li class="nav-item">
                    <inertia-link
                        :href="`route('groups.show', group.id)`"
                        class="nav-link rounded-0"
                        v-bind:class="{
                            active: `route().current('groups.show')`,
                        }"
                    >
                        Groups
                    </inertia-link>
                </li>
                <li class="nav-item">
                    <inertia-link
                        :href="`route('groups.show', group.id)`"
                        class="nav-link rounded-0"
                        v-bind:class="{
                            active: `route().current('versions.index')`,
                        }"
                    >
                        Versions
                    </inertia-link>
                </li>
            </ul>
        </div>
        <!-- end TODO -->
        <div class="flex-fill d-flex flex-column justify-content-center">
            <div class="page-header">
                <h1 class="page-title text-center">
                    <b>Test case</b>
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
                                    v-model="slug"
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
                                :href="
                                    route(
                                        'admin.test-cases.info.edit',
                                        testCase.id
                                    )
                                "
                                class="btn btn-primary"
                            >
                                Edit
                            </inertia-link>
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
};
</script>
