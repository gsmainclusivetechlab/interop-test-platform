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
                        <li>
                            <p>
                                <strong>Configuration</strong>
                            </p>
                            <div
                                v-for="(component, i) in session.components
                                    .data"
                                :key="i"
                            >
                                <h3>{{ component.name }}</h3>
                                <div
                                    class="mb-3"
                                    v-for="(connection,
                                    i) in component.connections"
                                    :key="i"
                                >
                                    <label>
                                        {{ connection.name }}
                                    </label>
                                    <div class="input-group">
                                        <input
                                            :id="`testing-${component.id}-${connection.id}`"
                                            type="text"
                                            :value="
                                                route('testing.sut', [
                                                    session.uuid,
                                                    component.uuid,
                                                    connection.uuid,
                                                ])
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
                                <hr />
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
                    <div class="d-flex align-items-baseline border-bottom mb-4">
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
                            <div class="d-flex">
                                <inertia-link
                                    :href="
                                        route('sessions.test-cases.run', [
                                            session.id,
                                            testCase.id,
                                        ])
                                    "
                                    v-if="isAvailableRun"
                                    class="btn btn-primary"
                                    method="post"
                                >
                                    <icon name="bike"></icon>
                                    Run Test Case
                                </inertia-link>
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
};
</script>
