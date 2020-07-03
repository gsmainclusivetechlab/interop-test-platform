<template>
    <layout
        :session="session"
        :useCases="useCases"
        :testCase="testCase"
        :breadcrumbs="breadcrumbs"
    >
        <div class="row">
            <div class="col-md-12">
                <div class="row border-bottom pb-4 align-items-center">
                    <div class="col-6 d-flex flex-column">
                        <div class="page-pretitle font-weight-normal">
                            <breadcrumb
                                class="breadcrumb-bullets"
                                :items="breadcrumbs"
                            ></breadcrumb>
                        </div>
                        <h1 class="page-title mw-100 mr-2 text-break">
                            <b>{{ session.name }}</b>
                        </h1>
                    </div>
                    <div class="ml-auto col-2 d-flex justify-content-end">
                        <div class="w-100">
                            <div>
                                Execution:
                                <icon
                                    name="briefcase"
                                    v-b-tooltip.hover
                                    title="Use Case"
                                />
                                <small>
                                    {{
                                        session.testCases
                                            ? collect(session.testCases.data).map(function (value) {
                                                return value.useCase.id;
                                            }).unique().count()
                                            : 0
                                    }}
                                </small>
                                <icon
                                    name="file-text"
                                    v-b-tooltip.hover
                                    title="Test Case"
                                />
                                <small>
                                    {{
                                        session.testCases
                                            ? session.testCases.data.length
                                            : 0
                                    }}
                                </small>
                            </div>
                            <session-progress
                                :testCases="session.testCases.data"
                            />
                        </div>
                    </div>
                </div>
                <div class="row align-items-start">
                    <div class="col-3 mt-3 pr-0">
                        <div class="card">
                            <div class="card-header">
                                <h2
                                    class="card-title d-flex align-items-center"
                                >
                                    <inertia-link
                                        :href="
                                            route('sessions.show', session.id)
                                        "
                                        class="d-inline-flex mr-1 text-decoration-none"
                                    >
                                        <icon name="corner-down-left"></icon>
                                    </inertia-link>
                                    <b>{{ `${testCase.name}` }}</b>
                                </h2>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li>
                                        <p>
                                            <strong>Configuration</strong>
                                        </p>
                                        <div
                                            v-for="component in session
                                                .components.data"
                                        >
                                            <div
                                                class="mb-3"
                                                v-for="connection in component.connections"
                                            >
                                                <label>
                                                    {{ connection.name }}
                                                </label>
                                                <div class="input-group">
                                                    <input
                                                        :id="`testing-${connection.id}`"
                                                        type="text"
                                                        :value="
                                                            route(
                                                                'testing.sut',
                                                                [
                                                                    session.uuid,
                                                                    component.uuid,
                                                                    connection.uuid,
                                                                ]
                                                            )
                                                        "
                                                        class="form-control"
                                                        readonly
                                                    />
                                                    <clipboard-copy-btn
                                                        :target="`#testing-${connection.id}`"
                                                        title="Copy"
                                                    ></clipboard-copy-btn>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li v-if="testCase.description">
                                        <p>
                                            <strong>Description</strong>
                                        </p>
                                        <div
                                            v-html="testCase.description"
                                        ></div>
                                    </li>
                                    <li v-if="testCase.precondition">
                                        <p>
                                            <strong>Precondition</strong>
                                        </p>
                                        <div
                                            v-html="testCase.precondition"
                                        ></div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-9 mt-3">
                        <div class="row">
                            <div class="col">
                                <div
                                    class="d-flex align-items-baseline border-bottom mb-4"
                                >
                                    <ul class="nav nav-tabs mx-0 border-0">
                                        <li class="nav-item">
                                            <inertia-link
                                                :href="
                                                    route(
                                                        'sessions.test-cases.show',
                                                        [
                                                            session.id,
                                                            testCase.id,
                                                        ]
                                                    )
                                                "
                                                class="nav-link rounded-0"
                                                v-bind:class="{
                                                    active:
                                                        route().current(
                                                            'sessions.test-cases.show'
                                                        ) ||
                                                        route().current(
                                                            'sessions.test-cases.test-runs.*'
                                                        ),
                                                }"
                                            >
                                                Test Runs
                                            </inertia-link>
                                        </li>
                                        <li class="nav-item">
                                            <inertia-link
                                                :href="
                                                    route(
                                                        'sessions.test-cases.test-steps.index',
                                                        [
                                                            session.id,
                                                            testCase.id,
                                                        ]
                                                    )
                                                "
                                                class="nav-link rounded-0"
                                                v-bind:class="{
                                                    active: route().current(
                                                        'sessions.test-cases.test-steps.index'
                                                    ),
                                                }"
                                            >
                                                Test Steps
                                            </inertia-link>
                                        </li>
                                        <li class="nav-item">
                                            <inertia-link
                                                :href="
                                                    route(
                                                        'sessions.test-cases.test-steps.flow',
                                                        [
                                                            session.id,
                                                            testCase.id,
                                                        ]
                                                    )
                                                "
                                                class="nav-link rounded-0"
                                                v-bind:class="{
                                                    active: route().current(
                                                        'sessions.test-cases.test-steps.flow'
                                                    ),
                                                }"
                                            >
                                                Test Flow
                                            </inertia-link>
                                        </li>
                                    </ul>
                                    <div
                                        class="ml-auto"
                                        v-if="
                                            !collect(session.components.data)
                                                .where(
                                                    'id',
                                                    testStepFirstSource.id
                                                )
                                                .count()
                                        "
                                    >
                                        <div class="d-flex">
                                            <inertia-link
                                                :href="
                                                    route(
                                                        'sessions.test-cases.run',
                                                        [
                                                            session.id,
                                                            testCase.id,
                                                        ]
                                                    )
                                                "
                                                class="btn btn-primary"
                                                method="post"
                                            >
                                                <icon name="bike"></icon>
                                                Run Test Case
                                            </inertia-link>
                                        </div>
                                    </div>
                                </div>
                                <slot />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/main';
import SessionProgress from '@/components/sessions/progress';

export default {
    components: {
        Layout,
        SessionProgress,
    },
    metaInfo() {
        return {
            title: this.session.name,
        };
    },
    props: {
        session: {
            type: Object,
            required: true,
        },
        useCases: {
            type: Object,
            required: true,
        },
        testCase: {
            type: Object,
            required: true,
        },
        testStepFirstSource: {
            type: Object,
            required: true,
        },
        breadcrumbs: {
            type: Array,
            required: false,
            default: function () {
                return [
                    { name: 'Sessions', url: route('sessions.index') },
                    {
                        name: this.session.name,
                        url: route('sessions.show', this.session.id),
                    },
                    { name: this.testCase.name },
                ];
            },
        },
    },
};
</script>
