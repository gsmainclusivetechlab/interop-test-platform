<template>
    <layout
        :session="session"
        :testCase="testCase"
        :testSteps="testSteps"
        :isAvailableRun="isAvailableRun"
    >
        <div class="card">
            <div class="card-header">
                <h2 class="card-title d-flex align-items-center">
                    <inertia-link
                        :href="
                            route('sessions.test-cases.show', [
                                session.id,
                                testCase.id,
                            ])
                        "
                        class="d-inline-flex text-decoration-none mr-1"
                    >
                        <icon name="corner-down-left"></icon>
                    </inertia-link>
                    <b class="mr-2">{{ `Run ID: #${testRun.id} - ` }}</b>
                    <span
                        class="mr-2"
                        :class="{
                            'text-success': testRun.completed_at,
                        }"
                    >
                        {{ `${testRun.duration} ms` }}
                    </span>
                    <div v-if="!testRun.completed_at">
                        <span class="spinner-border spinner-border-sm"></span>
                        <span class="ml-1">Processing</span>
                    </div>
                    <template v-else>
                        <span
                            class="text-success d-flex align-items-center"
                            v-if="testRun.successful"
                        >
                            <icon
                                name="circle-check"
                                class="icon-md mr-1"
                            ></icon>
                            Pass
                        </span>
                        <span
                            class="text-danger d-flex align-items-center"
                            v-else
                        >
                            <icon
                                name="alert-circle"
                                class="icon-md mr-1"
                            ></icon>
                            Fail
                        </span>
                    </template>
                </h2>
                <div class="card-options">
                    <b v-if="testRun.passed || testRun.failures" class="mr-2"
                        >Steps:</b
                    >
                    <span
                        class="text-success mr-2 align-items-center d-flex"
                        v-if="testRun.passed"
                    >
                        <icon name="circle-check" class="icon-md mr-1"></icon>
                        {{ `${testRun.passed} Pass` }}
                    </span>
                    <span
                        class="text-danger align-items-center d-flex"
                        v-if="testRun.failures"
                    >
                        <icon name="alert-circle" class="icon-md mr-1"></icon>
                        {{ `${testRun.failures} Fail` }}
                    </span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="p-4">
                    <diagram>
                        graph LR;
                        <template v-for="component in components.data">
                            {{ component.id }}({{ component.name }})<template
                                v-if="
                                    collect(session.components.data).contains(
                                        'id',
                                        component.id
                                    )
                                "
                                >:::is-active</template
                            ><template v-else></template>;
                            <template
                                v-for="connection in component.connections"
                            >
                                {{ component.id }}
                                -->
                                <template
                                    v-if="
                                        testResult.testStep &&
                                        component.id ==
                                            testResult.testStep.data.source
                                                .id &&
                                        connection.id ==
                                            testResult.testStep.data.target.id
                                    "
                                >
                                    |{{
                                        `Step ${testResult.testStep.data.position}`
                                    }}| {{ connection.id }};
                                </template>
                                <template v-else>
                                    {{ connection.id }};
                                </template>
                            </template>
                        </template>
                    </diagram>
                </div>
                <div class="rounded-0 border-top">
                    <div class="row">
                        <div class="col-3 pr-0">
                            <ul class="list-group mb-0">
                                <template
                                    v-for="(testStep, i) in testCase.testSteps
                                        .data"
                                >
                                    <li
                                        v-if="testResultSteps.has(testStep.id)"
                                        :class="{
                                            'active': testResultSteps.has(
                                                testStep.id
                                            ),
                                            'list-group-item-action': !testResultSteps.has(
                                                testStep.id
                                            ),
                                        }"
                                        class="list-group-item p-0 rounded-0 border-0"
                                        :key="`step-${i}`"
                                    >
                                        <inertia-link
                                            :href="
                                                route(
                                                    'sessions.test-cases.test-runs.show',
                                                    [
                                                        session.id,
                                                        testCase.id,
                                                        testRun.id,
                                                        testStep.position,
                                                    ]
                                                )
                                            "
                                            class="d-flex justify-content-between align-items-center py-2 px-4 text-reset text-decoration-none"
                                        >
                                            <div class="mr-1 text-truncate">
                                                <b>
                                                    {{
                                                        `Step ${testStep.position}`
                                                    }}
                                                </b>
                                                <div
                                                    class="d-flex align-items-baseline text-truncate"
                                                    v-if="
                                                        testResultSteps.get(
                                                            testStep.id
                                                        ).request
                                                    "
                                                >
                                                    <span
                                                        class="font-weight-bold"
                                                        :class="{
                                                            'text-orange':
                                                                testResultSteps.get(
                                                                    testStep.id
                                                                ).request
                                                                    .method ===
                                                                'POST',
                                                            'text-blue':
                                                                testResultSteps.get(
                                                                    testStep.id
                                                                ).request
                                                                    .method ===
                                                                'PUT',
                                                            'text-red':
                                                                testResultSteps.get(
                                                                    testStep.id
                                                                ).request
                                                                    .method ===
                                                                'DELETE',
                                                            'text-mint':
                                                                testResultSteps.get(
                                                                    testStep.id
                                                                ).request
                                                                    .method ===
                                                                'GET',
                                                        }"
                                                    >
                                                        {{
                                                            testResultSteps.get(
                                                                testStep.id
                                                            ).request.method
                                                        }}
                                                    </span>
                                                    <span
                                                        class="d-inline-block ml-1 text-truncate"
                                                        :title="`${
                                                            testResultSteps.get(
                                                                testStep.id
                                                            ).request.method
                                                        } ${
                                                            testResultSteps.get(
                                                                testStep.id
                                                            ).request.path
                                                        }`"
                                                    >
                                                        {{
                                                            testResultSteps.get(
                                                                testStep.id
                                                            ).request.path
                                                        }}
                                                    </span>
                                                </div>
                                            </div>
                                            <span
                                                class="flex-shrink-0 badge mr-0"
                                                :class="{
                                                    'bg-success': testResultSteps.get(
                                                        testStep.id
                                                    ).successful,
                                                    'bg-danger': !testResultSteps.get(
                                                        testStep.id
                                                    ).successful,
                                                }"
                                            >
                                            </span>
                                        </inertia-link>
                                    </li>
                                    <li
                                        v-else
                                        class="list-group-item px-4 rounded-0 border-0 disabled"
                                        :key="`step-${i}`"
                                    >
                                        <div class="text-truncate">
                                            <b>
                                                {{
                                                    `Step ${testStep.position}`
                                                }}
                                            </b>
                                            <div
                                                class="text-truncate"
                                                :title="`${testStep.method} ${testStep.path}`"
                                            >
                                                {{ testStep.method }}
                                                {{ testStep.path }}
                                            </div>
                                        </div>
                                    </li>
                                </template>
                            </ul>
                        </div>
                        <div class="col-9 pl-0 pb-4 border-left">
                            <div
                                class="lead py-3 px-4"
                                v-if="testResult.testStep"
                            >
                                <div class="d-flex justigy-content-between">
                                    <b class="text-nowrap">
                                        {{
                                            `Step ${testResult.testStep.data.position}`
                                        }}
                                    </b>
                                    <div class="d-inline-block ml-auto">
                                        <small
                                            class="d-inline-block ml-2"
                                            v-if="testResult.response"
                                        >
                                            Status:
                                            <span class="text-success">
                                                {{
                                                    `HTTP ${testResult.response.status}`
                                                }}
                                            </span>
                                        </small>
                                        <small
                                            class="d-inline-block ml-2"
                                            v-if="testResult.duration"
                                        >
                                            Duration:
                                            <span class="text-success">
                                                {{
                                                    `${testResult.duration} ms`
                                                }}
                                            </span>
                                        </small>
                                    </div>
                                </div>
                                <div
                                    class="text-truncate"
                                    v-if="testResult.request"
                                >
                                    <u class="mr-2"
                                        >{{ testResult.request.method }}
                                        {{ testResult.request.path }}</u
                                    >
                                </div>
                            </div>
                            <div
                                class="lead mb-2 py-3 px-4"
                                :class="{
                                    'alert-success': testResult.successful,
                                    'alert-secondary':
                                        !testResult.testExecutions ||
                                        !testResult.testExecutions.data.length,
                                    'alert-danger':
                                        testResult.testExecutions &&
                                        testResult.testExecutions.data.length &&
                                        testResult.successful === false,
                                }"
                            >
                                {{
                                    !testResult.testExecutions ||
                                    !testResult.testExecutions.data.length
                                        ? 'Pending'
                                        : testResult.successful
                                        ? 'Pass'
                                        : 'Fail'
                                }}
                                <div v-if="testResult.exception">
                                    {{ testResult.exception }}
                                </div>
                            </div>
                            <div
                                class="py-2 px-4"
                                v-if="
                                    testResult.testExecutions &&
                                    testResult.testExecutions.data.length
                                "
                            >
                                <div class="d-flex mb-2">
                                    <strong
                                        class="lead d-block mr-auto font-weight-bold"
                                    >
                                        Performed tests
                                    </strong>
                                </div>
                                <ul class="m-0 p-0">
                                    <li
                                        class="d-flex flex-wrap py-2"
                                        v-for="(testExecution, i) in testResult
                                            .testExecutions.data"
                                        :key="`execution-${i}`"
                                    >
                                        <div class="d-flex align-items-center">
                                            <span
                                                class="badge d-flex align-items-center justify-content-center flex-shrink-0 h-4 w-5 mr-2 text-uppercase"
                                                :class="{
                                                    'bg-success':
                                                        testExecution.successful,
                                                    'bg-danger': !testExecution.successful,
                                                }"
                                            >
                                                {{
                                                    testExecution.successful
                                                        ? 'Pass'
                                                        : 'Fail'
                                                }}
                                            </span>
                                            <button
                                                v-b-modal="
                                                    `test-execution-${testExecution.id}`
                                                "
                                                class="btn btn-link p-0 font-weight-normal"
                                                type="button"
                                                v-if="
                                                    testExecution.actual ||
                                                    testExecution.expected ||
                                                    testExecution.exception
                                                "
                                            >
                                                {{ testExecution.name }}
                                            </button>
                                            <span v-else>
                                                {{ testExecution.name }}
                                            </span>
                                        </div>
                                        <b-modal
                                            :id="`test-execution-${testExecution.id}`"
                                            size="lg"
                                            centered
                                            hide-footer
                                            :title="testExecution.name"
                                            v-if="
                                                testExecution.actual ||
                                                testExecution.expected ||
                                                testExecution.exception
                                            "
                                        >
                                            <b-tabs
                                                nav-class="flex-nowrap"
                                                content-class="mt-2"
                                                justified
                                                v-if="
                                                    testExecution.actual ||
                                                    testExecution.expected
                                                "
                                            >
                                                <b-tab
                                                    title="Actual"
                                                    title-link-class="justify-content-center pt-0 pb-1 text-nowrap rounded-0"
                                                    v-if="testExecution.actual"
                                                >
                                                    <json-tree
                                                        :data="
                                                            testExecution.actual
                                                        "
                                                        :show-copy-btn="false"
                                                        :deep="1"
                                                        :show-line="false"
                                                        class="p-2"
                                                    ></json-tree>
                                                </b-tab>
                                                <b-tab
                                                    title="Expected"
                                                    title-link-class="justify-content-center pt-0 pb-1 text-nowrap rounded-0"
                                                    v-if="
                                                        testExecution.expected
                                                    "
                                                >
                                                    <json-tree
                                                        :data="
                                                            testExecution.expected
                                                        "
                                                        :show-copy-btn="false"
                                                        :deep="1"
                                                        :show-line="false"
                                                        class="p-2"
                                                    ></json-tree>
                                                </b-tab>
                                                <div
                                                    v-if="
                                                        testExecution.exception
                                                    "
                                                    class="p-2 alert-danger"
                                                >
                                                    <p class="mb-0">
                                                        {{
                                                            testExecution.exception
                                                        }}
                                                    </p>
                                                </div>
                                            </b-tabs>
                                            <div
                                                v-else-if="
                                                    testExecution.exception
                                                "
                                                class="p-2 alert-danger"
                                            >
                                                <p class="mb-0">
                                                    {{
                                                        testExecution.exception
                                                    }}
                                                </p>
                                            </div>
                                        </b-modal>
                                    </li>
                                </ul>
                            </div>
                            <div class="py-2 px-4" v-if="testResult.request">
                                <div class="d-flex mb-2">
                                    <strong
                                        class="lead d-block mr-auto mt-auto font-weight-bold"
                                    >
                                        Request
                                    </strong>
                                    <clipboard-json-to-curl
                                        :request="testResult.request"
                                    ></clipboard-json-to-curl>
                                </div>
                                <div class="border">
                                    <div class="d-flex">
                                        <div class="w-25 px-4 py-2 border">
                                            <strong>URL</strong>
                                        </div>
                                        <div class="w-75 px-4 py-2 border">
                                            {{ testResult.request.uri }}
                                        </div>
                                    </div>
                                    <div class="d-flex">
                                        <div class="w-25 px-4 py-2 border">
                                            <strong>Method</strong>
                                        </div>
                                        <div class="w-75 px-4 py-2 border">
                                            {{ testResult.request.method }}
                                        </div>
                                    </div>
                                    <div
                                        class="d-flex"
                                        v-if="
                                            testResult.request.headers !==
                                            undefined
                                        "
                                    >
                                        <div
                                            class="w-25 px-4 py-2 border dropdown-toggle"
                                            v-b-toggle="
                                                `request-headers-${testResult.id}`
                                            "
                                        >
                                            <strong>Headers</strong>
                                        </div>
                                        <div class="w-75 px-4 py-2 border">
                                            {{
                                                `(${collect(
                                                    testResult.request.headers
                                                ).count()}) params`
                                            }}
                                        </div>
                                    </div>
                                    <b-collapse
                                        :id="`request-headers-${testResult.id}`"
                                        v-if="
                                            testResult.request.headers !==
                                            undefined
                                        "
                                    >
                                        <div class="d-flex">
                                            <div
                                                class="w-25 px-4 py-2 border"
                                            ></div>
                                            <div class="w-75 px-4 py-2 border">
                                                <div class="mb-0 p-0">
                                                    <json-tree
                                                        :data="
                                                            testResult.request
                                                                .headers
                                                        "
                                                        :deep="1"
                                                        :show-line="false"
                                                        class="p-2"
                                                    ></json-tree>
                                                </div>
                                            </div>
                                        </div>
                                    </b-collapse>
                                    <div
                                        class="d-flex"
                                        v-if="
                                            testResult.request.body !==
                                                undefined &&
                                            testResult.request.body !==
                                                'empty_body'
                                        "
                                    >
                                        <div
                                            class="w-25 px-4 py-2 border dropdown-toggle"
                                            v-b-toggle="
                                                `request-body-${testResult.id}`
                                            "
                                        >
                                            <strong>Body</strong>
                                        </div>
                                        <div class="w-75 px-4 py-2 border">
                                            {{
                                                `(${collect(
                                                    testResult.request.body
                                                ).count()}) params`
                                            }}
                                        </div>
                                    </div>
                                    <b-collapse
                                        :id="`request-body-${testResult.id}`"
                                        v-if="
                                            testResult.request.body !==
                                                undefined &&
                                            testResult.request.body !==
                                                'empty_body'
                                        "
                                    >
                                        <div class="d-flex">
                                            <div
                                                class="w-25 px-4 py-2 border"
                                            ></div>
                                            <div class="w-75 px-4 py-2 border">
                                                <div class="mb-0 p-0">
                                                    <json-tree
                                                        :data="
                                                            testResult.request
                                                                .body
                                                        "
                                                        :deep="1"
                                                        :show-line="false"
                                                        class="p-2"
                                                    ></json-tree>
                                                </div>
                                            </div>
                                        </div>
                                    </b-collapse>
                                    <div
                                        class="d-flex"
                                        v-if="
                                            !collect(session.components.data)
                                                .where(
                                                    'id',
                                                    testResult.testStep.data
                                                        .source.id
                                                )
                                                .count() &&
                                            testResultRequestSetups.count()
                                        "
                                    >
                                        <div
                                            class="w-25 px-4 py-2 border dropdown-toggle"
                                            v-b-toggle="
                                                `request-overridden-${testResult.id}`
                                            "
                                        >
                                            <strong>Overridden</strong>
                                        </div>
                                        <div class="w-75 px-4 py-2 border">
                                            {{
                                                `(${testResultRequestSetups.count()}) params`
                                            }}
                                        </div>
                                    </div>
                                    <b-collapse
                                        :id="`request-overridden-${testResult.id}`"
                                        v-if="testResultRequestSetups.count()"
                                    >
                                        <div class="d-flex">
                                            <div
                                                class="w-25 px-4 py-2 border"
                                            ></div>
                                            <div class="w-75 px-4 py-2 border">
                                                <div class="mb-0 p-0">
                                                    <json-tree
                                                        :data="
                                                            testResultRequestSetups.all()
                                                        "
                                                        :deep="1"
                                                        :show-line="false"
                                                        class="p-2"
                                                    ></json-tree>
                                                </div>
                                            </div>
                                        </div>
                                    </b-collapse>
                                </div>
                            </div>
                            <div class="py-2 px-4" v-if="testResult.response">
                                <div class="d-flex mb-2">
                                    <strong
                                        class="lead d-block mr-auto font-weight-bold"
                                    >
                                        Response
                                    </strong>
                                </div>
                                <div class="border">
                                    <div class="d-flex">
                                        <div class="w-25 px-4 py-2 border">
                                            <strong>Status</strong>
                                        </div>
                                        <div class="w-75 px-4 py-2 border">
                                            {{ testResult.response.status }}
                                        </div>
                                    </div>
                                    <div
                                        class="d-flex"
                                        v-if="
                                            testResult.response.headers !==
                                            undefined
                                        "
                                    >
                                        <div
                                            class="w-25 px-4 py-2 border dropdown-toggle"
                                            v-b-toggle="
                                                `response-headers-${testResult.id}`
                                            "
                                        >
                                            <strong>Headers</strong>
                                        </div>
                                        <div class="w-75 px-4 py-2 border">
                                            {{
                                                `(${collect(
                                                    testResult.response.headers
                                                ).count()}) params`
                                            }}
                                        </div>
                                    </div>
                                    <b-collapse
                                        :id="`response-headers-${testResult.id}`"
                                        v-if="
                                            testResult.response.headers !==
                                            undefined
                                        "
                                    >
                                        <div class="d-flex">
                                            <div
                                                class="w-25 px-4 py-2 border"
                                            ></div>
                                            <div class="w-75 px-4 py-2 border">
                                                <div class="mb-0 p-0">
                                                    <json-tree
                                                        :data="
                                                            testResult.response
                                                                .headers
                                                        "
                                                        :deep="1"
                                                        :show-line="false"
                                                        class="p-2"
                                                    ></json-tree>
                                                </div>
                                            </div>
                                        </div>
                                    </b-collapse>
                                    <div
                                        class="d-flex"
                                        v-if="
                                            testResult.response.body !==
                                                undefined &&
                                            testResult.response.body !==
                                                'empty_body'
                                        "
                                    >
                                        <div
                                            class="w-25 px-4 py-2 border dropdown-toggle"
                                            v-b-toggle="
                                                `response-body-${testResult.id}`
                                            "
                                        >
                                            <strong>Body</strong>
                                        </div>
                                        <div class="w-75 px-4 py-2 border">
                                            {{
                                                `(${collect(
                                                    testResult.response.body
                                                ).count()}) params`
                                            }}
                                        </div>
                                    </div>
                                    <b-collapse
                                        :id="`response-body-${testResult.id}`"
                                        v-if="
                                            testResult.response.body !==
                                                undefined &&
                                            testResult.response.body !==
                                                'empty_body'
                                        "
                                    >
                                        <div class="d-flex">
                                            <div
                                                class="w-25 px-4 py-2 border"
                                            ></div>
                                            <div class="w-75 px-4 py-2 border">
                                                <div class="mb-0 p-0">
                                                    <json-tree
                                                        :data="
                                                            testResult.response
                                                                .body
                                                        "
                                                        :deep="1"
                                                        :show-line="false"
                                                        class="p-2"
                                                    ></json-tree>
                                                </div>
                                            </div>
                                        </div>
                                    </b-collapse>
                                    <div
                                        class="d-flex"
                                        v-if="
                                            !collect(session.components.data)
                                                .where(
                                                    'id',
                                                    testResult.testStep.data
                                                        .target.id
                                                )
                                                .count() &&
                                            testResultResponseSetups.count()
                                        "
                                    >
                                        <div
                                            class="w-25 px-4 py-2 border dropdown-toggle"
                                            v-b-toggle="
                                                `response-overridden-${testResult.id}`
                                            "
                                        >
                                            <strong>Overridden</strong>
                                        </div>
                                        <div class="w-75 px-4 py-2 border">
                                            {{
                                                `(${testResultResponseSetups.count()}) params`
                                            }}
                                        </div>
                                    </div>
                                    <b-collapse
                                        :id="`response-overridden-${testResult.id}`"
                                        v-if="testResultResponseSetups.count()"
                                    >
                                        <div class="d-flex">
                                            <div
                                                class="w-25 px-4 py-2 border"
                                            ></div>
                                            <div class="w-75 px-4 py-2 border">
                                                <div class="mb-0 p-0">
                                                    <json-tree
                                                        :data="
                                                            testResultResponseSetups.all()
                                                        "
                                                        :deep="1"
                                                        :show-line="false"
                                                        class="p-2"
                                                    ></json-tree>
                                                </div>
                                            </div>
                                        </div>
                                    </b-collapse>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/sessions/test-case';
import Diagram from '@/components/diagram';

export default {
    components: {
        Layout,
        Diagram,
    },
    props: {
        session: {
            type: Object,
            required: true,
        },
        components: {
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
        testRun: {
            type: Object,
            required: true,
        },
        testResult: {
            type: Object,
            required: true,
        },
        isAvailableRun: {
            type: Boolean,
            required: true,
        },
    },
    watch: {
        testRun: {
            deep: true,
            immediate: true,
            handler() {
                if (!this.testRun.completed_at) {
                    setTimeout(() => {
                        this.$inertia.reload(['testRun', 'testResult']);
                    }, 2000);
                }
            },
        },
    },
    computed: {
        testResultSteps() {
            return new Map(
                this.testRun.testResults.data.map((el) => [el.testStep.id, el])
            );
        },
        testResultRequestSetups() {
            let data = collect();
            if (!this.testResult.id) return data;
            collect(this.testResult.testStep.data.testSetups)
                .where('type', 'request')
                .each((item) => {
                    data = data.mergeRecursive(item.values);
                });
            return data;
        },
        testResultResponseSetups() {
            let data = collect();
            if (!this.testResult.id) return data;
            collect(this.testResult.testStep.data.testSetups)
                .where('type', 'response')
                .each((item) => {
                    data = data.mergeRecursive(item.values);
                });
            return data;
        },
    },
};
</script>
