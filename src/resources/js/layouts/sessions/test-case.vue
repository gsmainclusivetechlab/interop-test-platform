<template>
    <app-layout
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
                                session.components.data &&
                                session.components.data.length > 0 &&
                                session.components.data.some((compEl) =>
                                    Array.from(
                                        new Set(
                                            testSteps.data.map(
                                                (stepEl) => stepEl.source.id
                                            )
                                        )
                                    ).includes(compEl.id)
                                )
                            "
                            class="mb-3"
                        >
                            <h3>
                                <b>Configuration</b>
                            </h3>
                            <div v-if="hasEncrypted" class="mb-3">
                                <h4>Encrypted connection</h4>

                                <a
                                    :href="
                                        route('sessions.certificates.download')
                                    "
                                    class="btn btn-sm btn-block btn-outline-primary"
                                >
                                    Download certificates
                                </a>
                            </div>
                            <template
                                v-if="testCaseUrls && testCaseUrls.length"
                            >
                                <h3>
                                    <b>SUTs URLs</b>
                                </h3>
                                <template v-if="sutUrls.isGroup">
                                    <div
                                        v-for="group in testCaseUrls"
                                        :key="group.id"
                                    >
                                        <h3>
                                            {{
                                                `${
                                                    $page.props.auth.user.groups.find(
                                                        (el) =>
                                                            el.id === group.id
                                                    ).name
                                                }`
                                            }}
                                        </h3>
                                        <div
                                            v-for="(
                                                component, i
                                            ) in group.items"
                                            class="mb-3"
                                            :key="component.title"
                                        >
                                            <h4>
                                                {{ component.title }}
                                            </h4>
                                            <div
                                                v-for="(
                                                    connection, j
                                                ) in component.items"
                                                class="mb-3"
                                                :key="connection.label"
                                            >
                                                <label>
                                                    <i>{{
                                                        connection.label
                                                    }}</i>
                                                </label>
                                                <div class="input-group">
                                                    <input
                                                        :id="`#testing-${group.id}-${i}-${j}`"
                                                        type="text"
                                                        :value="connection.url"
                                                        class="form-control"
                                                        readonly
                                                    />
                                                    <clipboard-copy-btn
                                                        :target="`#testing-${i}-${j}`"
                                                        title="Copy"
                                                    ></clipboard-copy-btn>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <template v-else>
                                    <div
                                        v-for="(component, i) in testCaseUrls"
                                        class="mb-3"
                                        :key="component.title"
                                    >
                                        <h4>
                                            {{ component.title }}
                                        </h4>
                                        <div
                                            v-for="(
                                                connection, j
                                            ) in component.items"
                                            class="mb-3"
                                            :key="connection.label"
                                        >
                                            <label>
                                                <i>{{ connection.label }}</i>
                                            </label>
                                            <div class="input-group">
                                                <input
                                                    :id="`#testing-${i}-${j}`"
                                                    type="text"
                                                    :value="connection.url"
                                                    class="form-control"
                                                    readonly
                                                />
                                                <clipboard-copy-btn
                                                    :target="`#testing-${i}-${j}`"
                                                    title="Copy"
                                                ></clipboard-copy-btn>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </template>
                        </li>
                        <li v-if="testCase.description" class="mb-3">
                            <h3>
                                <strong>Description</strong>
                            </h3>
                            <div v-html="testCase.description"></div>
                        </li>
                        <li v-if="testCase.precondition" class="mb-3">
                            <h3>
                                <strong>Precondition</strong>
                            </h3>
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
                                    :class="{
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
                                    :class="{
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
                                    :class="{
                                        active: route().current(
                                            'sessions.test-cases.test-steps.flow'
                                        ),
                                    }"
                                >
                                    Test Flow
                                </inertia-link>
                            </li>
                        </ul>
                        <div class="ml-auto">
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
    </app-layout>
</template>

<script>
import AppLayout from '@/layouts/sessions/app';

export default {
    components: {
        AppLayout,
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
        isAvailableRun: {
            type: Boolean,
            required: false,
        },
        testRunAttempts: {
            type: Number | String,
            required: false,
        },
        sutUrls: {
            type: Object,
            required: false,
        },
    },
    data() {
        return {
            testCaseUrls: [],
            hasEncrypted:
                this.session.components.data?.filter(
                    (component) => component.use_encryption
                )?.length > 0,
        };
    },

    mounted() {
        if (this.sutUrls.isGroup) {
            this.testCaseUrls = this.sutUrls.items.map((group) => ({
                items: group.items
                    .filter((component) =>
                        this.testSteps.data.some(
                            (step) => step.source.name === component.title
                        )
                    )
                    .map((component) => ({
                        items: component.items.filter((connection) =>
                            this.testSteps.data.some((step) =>
                                connection.label.match(`-> ${step.target.name}`)
                            )
                        ),
                        title: component.title,
                    })),
                id: group.id,
            }));
        } else {
            this.testCaseUrls = this.sutUrls.items
                .filter((component) =>
                    this.testSteps.data.some(
                        (step) => step.source.name === component.title
                    )
                )
                .map((component) => ({
                    items: component.items.filter((connection) =>
                        this.testSteps.data.some((step) =>
                            connection.label.match(`-> ${step.target.name}`)
                        )
                    ),
                    title: component.title,
                }));
        }
    },
};
</script>
