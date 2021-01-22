<template>
    <layout
        :session="session"
        :breadcrumbs="[
            { name: 'Sessions', url: route('sessions.index') },
            {
                name: session.name,
                url: route('sessions.show', session.id),
            },
            { name: testCase.name },
        ]"
    >
        <div class="col-3 mt-3 pr-0">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title d-flex align-items-center">
                        <inertia-link
                            :href="route('sessions.show', session.id)"
                            class="d-inline-flex mr-1 text-decoration-none"
                        >
                            <icon name="corner-down-left"></icon>
                        </inertia-link>
                        <b>{{ `${testCase.name}` }}</b>
                    </h2>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li
                            v-if="
                                session.components.data.length &&
                                inArray(
                                    session.components.data,
                                    collect(testSteps.data)
                                        .map((value) => value.source.id)
                                        .unique()
                                        .toArray()
                                )
                            "
                        >
                            <p>
                                <strong>Configuration</strong>
                            </p>
                            <div v-if="hasEncrypted" class="mb-3">
                                <h3>Encrypted connection</h3>

                                <a
                                    :href="
                                        route('sessions.certificates.download')
                                    "
                                    class="btn btn-outline-primary"
                                >
                                    Download certificates
                                </a>
                            </div>
                            <div
                                v-for="(component, i) in session.components
                                    .data"
                                :key="i"
                            >
                                <div
                                    v-if="
                                        inArray(
                                            [component],
                                            collect(testSteps.data)
                                                .map((value) => value.source.id)
                                                .unique()
                                                .toArray()
                                        )
                                    "
                                >
                                    <h3>{{ component.name }}</h3>
                                    <div
                                        class="mb-3"
                                        v-for="(
                                            connection, i
                                        ) in component.connections"
                                        :key="i"
                                    >
                                        <div
                                            v-if="
                                                inArray(
                                                    [connection],
                                                    collect(testSteps.data)
                                                        .map(
                                                            (value) =>
                                                                value.target.id
                                                        )
                                                        .unique()
                                                        .toArray()
                                                )
                                            "
                                        >
                                            <label>
                                                {{ connection.name }}
                                            </label>
                                            <div class="input-group">
                                                <input
                                                    :id="`testing-${component.id}-${connection.id}`"
                                                    type="text"
                                                    :value="
                                                        component.use_encryption
                                                            ? route(
                                                                  'testing.sut',
                                                                  [
                                                                      session.uuid,
                                                                      component.uuid,
                                                                      connection.uuid,
                                                                  ]
                                                              )
                                                            : route(
                                                                  'testing-insecure.sut',
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
                                                    :target="`#testing-${component.id}-${connection.id}`"
                                                    title="Copy"
                                                ></clipboard-copy-btn>
                                            </div>
                                        </div>
                                    </div>
                                    <hr />
                                </div>
                            </div>
                        </li>
                        <li v-if="testCase.description">
                            <p>
                                <strong>Description</strong>
                            </p>
                            <div v-html="testCase.description"></div>
                        </li>
                        <li v-if="testCase.precondition">
                            <p>
                                <strong>Precondition</strong>
                            </p>
                            <div v-html="testCase.precondition"></div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-9 mt-3">
            <div class="row">
                <div class="col">
                    <div class="d-flex align-items-center border-bottom mb-4">
                        <ul class="nav nav-tabs mx-0 border-0">
                            <li class="nav-item">
                                <inertia-link
                                    :href="
                                        route('sessions.test-cases.show', [
                                            session.id,
                                            testCase.id,
                                        ])
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
                                            [session.id, testCase.id]
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
                                            [session.id, testCase.id]
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
                                    .where('id', testStepFirstSource.id)
                                    .count()
                            "
                        >
                            <confirm-link
                                v-if="isAvailableRun"
                                :href="
                                    route('sessions.test-cases.run', [
                                        session.id,
                                        testCase.id,
                                    ])
                                "
                                :confirm-title="'Run test case'"
                                :confirm-text="`Start a new test case run?`"
                                method="post"
                                class="btn btn-primary"
                            >
                                <icon name="bike"></icon>
                                Run Test Case
                            </confirm-link>
                            <button
                                class="btn btn-secondary"
                                v-else
                                v-b-tooltip.hover.left
                                :title="
                                    session.status !== 'in_execution'
                                        ? 'Session not available to update'
                                        : `You have reached the limit of ${testRunAttempts} allowed test runs per test case.`
                                "
                            >
                                <icon name="bike"></icon>
                                Run Test Case
                            </button>
                        </div>
                    </div>
                    <slot />
                </div>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/sessions/app';

export default {
    components: {
        Layout,
    },
    props: {
        session: {
            type: Object,
            required: true,
        },
        testCase: {
            type: Object,
            required: true,
        },
        testSteps: {
            type: Object,
            required: true,
        },
        testStepFirstSource: {
            type: Object,
            required: true,
        },
        isAvailableRun: {
            type: Boolean,
            required: false,
        },
        testRunAttempts: {
            type: Number | String,
            required: false,
        },
    },
    data() {
        return {
            hasEncrypted:
                collect(this.session.components.data)
                    .filter((component) => component.use_encryption)
                    .count() > 0,
        };
    },
    methods: {
        inArray(components, array) {
            let result = false;
            components.forEach(function (component) {
                if (array.includes(component.id)) {
                    result = true;
                }
            });
            return result;
        },
    },
};
</script>
