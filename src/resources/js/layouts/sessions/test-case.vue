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
                            <template
                                v-if="
                                    $page.props.auth.user.groups &&
                                    $page.props.auth.user.groups.length > 0 &&
                                    $page.props.auth.user.groups.some(
                                        (el) =>
                                            el.default_session_id === session.id
                                    )
                                "
                            >
                                <button
                                    type="button"
                                    class="btn btn-link card-title dropdown-toggle px-0"
                                    v-b-toggle="`by-groups`"
                                >
                                    By groups
                                </button>
                                <b-collapse id="by-groups">
                                    <template
                                        v-for="(group, i) in $page.props.auth
                                            .user.groups"
                                    >
                                        <div
                                            v-if="
                                                group.default_session_id ===
                                                session.id
                                            "
                                            :key="`group-${i}`"
                                        >
                                            <h3 class="text-secondary">
                                                {{ group.name }}
                                            </h3>
                                            <template
                                                v-for="(component, j) in session
                                                    .components.data"
                                            >
                                                <div
                                                    v-if="
                                                        Array.from(
                                                            new Set(
                                                                testSteps.data.map(
                                                                    (el) =>
                                                                        el
                                                                            .source
                                                                            .id
                                                                )
                                                            )
                                                        ).includes(component.id)
                                                    "
                                                    :key="`component-${j}`"
                                                >
                                                    <h3>
                                                        {{ component.name }}
                                                    </h3>
                                                    <div
                                                        class="mb-3"
                                                        v-for="(
                                                            connection, k
                                                        ) in component.connections"
                                                        :key="`connection-${k}`"
                                                    >
                                                        <div
                                                            v-if="
                                                                Array.from(
                                                                    new Set(
                                                                        testSteps.data.map(
                                                                            (
                                                                                el
                                                                            ) =>
                                                                                el
                                                                                    .target
                                                                                    .id
                                                                        )
                                                                    )
                                                                ).includes(
                                                                    connection.id
                                                                )
                                                            "
                                                        >
                                                            <label>
                                                                {{
                                                                    connection.name
                                                                }}
                                                            </label>
                                                            <div
                                                                class="input-group"
                                                            >
                                                                <input
                                                                    :id="`testing-${group.id}-${component.id}-${connection.id}`"
                                                                    type="text"
                                                                    :value="
                                                                        component.use_encryption
                                                                            ? route(
                                                                                  'testing-group.sut',
                                                                                  [
                                                                                      group.id,
                                                                                      component.uuid,
                                                                                      connection.uuid,
                                                                                  ]
                                                                              )
                                                                            : route(
                                                                                  'testing-insecure-group.sut',
                                                                                  [
                                                                                      group.id,
                                                                                      component.uuid,
                                                                                      connection.uuid,
                                                                                  ]
                                                                              )
                                                                    "
                                                                    class="form-control"
                                                                    readonly
                                                                />
                                                                <clipboard-copy-btn
                                                                    :target="`#testing-${group.id}-${component.id}-${connection.id}`"
                                                                    title="Copy"
                                                                ></clipboard-copy-btn>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                            <hr />
                                        </div>
                                    </template>
                                </b-collapse>
                            </template>
                            <button
                                type="button"
                                class="btn btn-link card-title dropdown-toggle px-0"
                                v-b-toggle="`by-session`"
                            >
                                By session
                            </button>
                            <b-collapse id="by-session">
                                <template
                                    v-for="(component, i) in session.components
                                        .data"
                                >
                                    <div
                                        v-if="
                                            Array.from(
                                                new Set(
                                                    testSteps.data.map(
                                                        (el) => el.source.id
                                                    )
                                                )
                                            ).includes(component.id)
                                        "
                                        :key="`component-${i}`"
                                    >
                                        <h3>{{ component.name }}</h3>
                                        <template
                                            v-for="(
                                                connection, j
                                            ) in component.connections"
                                        >
                                            <div
                                                v-if="
                                                    Array.from(
                                                        new Set(
                                                            testSteps.data.map(
                                                                (el) =>
                                                                    el.target.id
                                                            )
                                                        )
                                                    ).includes(connection.id)
                                                "
                                                class="mb-3"
                                                :key="`connection-${j}`"
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
                                        </template>
                                        <hr />
                                    </div>
                                </template>
                            </b-collapse>
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
                this.session.components.data?.filter(
                    (component) => component.use_encryption
                )?.length > 0,
        };
    },
};
</script>
