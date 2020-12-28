<template>
    <layout
        :session="session"
        :testCase="testCase"
        :isAvailableRun="isAvailableRun"
        :testStepFirstSource="testStepFirstSource"
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
                    <b>{{ `Run ID: #${testRun.id}` }}</b>
                </h2>
                <div class="card-options">
                    <span
                        class="text-success mr-2 align-items-center d-flex"
                        v-if="testRun.passed"
                    >
                        <icon name="circle-check" class="icon-md mr-1"></icon>
                        {{ `${testRun.passed} Pass` }}
                    </span>
                    <span
                        class="text-danger mr-2 align-items-center d-flex"
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
                                    v-for="testStep in testCase.testSteps.data"
                                >
                                    <li
                                        v-if="
                                            (testResultStep = collect(
                                                testRun.testResults.data
                                            ).firstWhere(
                                                'testStep.id',
                                                testStep.id
                                            ))
                                        "
                                        v-bind:class="{
                                            'active':
                                                testResult.testStep.data.id ===
                                                testStep.id,
                                            'list-group-item-action':
                                                testResult.testStep.data.id !==
                                                testStep.id,
                                        }"
                                        class="list-group-item p-0 rounded-0 border-0"
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
                                                        testResultStep.request
                                                    "
                                                >
                                                    <span
                                                        class="font-weight-bold"
                                                        v-bind:class="{
                                                            'text-orange':
                                                                testResultStep
                                                                    .request
                                                                    .method ===
                                                                'POST',
                                                            'text-blue':
                                                                testResultStep
                                                                    .request
                                                                    .method ===
                                                                'PUT',
                                                            'text-red':
                                                                testResultStep
                                                                    .request
                                                                    .method ===
                                                                'DELETE',
                                                            'text-mint':
                                                                testResultStep
                                                                    .request
                                                                    .method ===
                                                                'GET',
                                                        }"
                                                    >
                                                        {{
                                                            testResultStep
                                                                .request.method
                                                        }}
                                                    </span>
                                                    <span
                                                        class="d-inline-block ml-1 text-truncate"
                                                        :title="`${testResultStep.request.method} ${testResultStep.request.path}`"
                                                    >
                                                        {{
                                                            testResultStep
                                                                .request.path
                                                        }}
                                                    </span>
                                                </div>
                                            </div>
                                            <span
                                                class="flex-shrink-0 badge mr-0"
                                                v-bind:class="{
                                                    'bg-success':
                                                        testResultStep.successful,
                                                    'bg-danger': !testResultStep.successful,
                                                }"
                                            >
                                            </span>
                                        </inertia-link>
                                    </li>
                                    <li
                                        v-else
                                        class="list-group-item px-4 rounded-0 border-0 disabled"
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
                            <div class="lead py-3 px-4">
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
                                v-bind:class="{
                                    'alert-success': testResult.successful,
                                    'alert-danger': !testResult.successful,
                                }"
                            >
                                {{ testResult.successful ? 'Pass' : 'Fail' }}
                                <div v-if="testResult.exception">
                                    {{ testResult.exception }}
                                </div>
                            </div>
                            <div
                                class="py-2 px-4"
                                v-if="testResult.testExecutions.data.length"
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
                                        v-for="testExecution in testResult
                                            .testExecutions.data"
                                    >
                                        <div class="d-flex align-items-center">
                                            <span
                                                class="badge d-flex align-items-center justify-content-center flex-shrink-0 h-4 w-5 mr-2 text-uppercase"
                                                v-bind:class="{
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
                                                :id="`test-execution-${testExecution.id}`"
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
                                        <b-popover
                                            :target="`test-execution-${testExecution.id}`"
                                            triggers="click blur"
                                            placement="bottom"
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
                                        </b-popover>
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
                                        v-if="testResult.request.headers"
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
                                        v-if="testResult.request.headers"
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
                                            testResult.request &&
                                            collect(
                                                testResult.request.body
                                            ).count()
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
                                            testResult.request &&
                                            collect(
                                                testResult.request.body
                                            ).count()
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
                                        v-if="testResult.response.headers"
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
                                        v-if="testResult.response.headers"
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
                                            testResult.response &&
                                            collect(
                                                testResult.response.body
                                            ).count()
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
                                            testResult.response &&
                                            collect(
                                                testResult.response.body
                                            ).count()
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
        testRun: {
            type: Object,
            required: true,
        },
        testResult: {
            type: Object,
            required: true,
        },
        testStepFirstSource: {
            type: Object,
            required: true,
        },
        isAvailableRun: {
            type: Boolean,
            required: true,
        },
    },
    computed: {
        testResultRequestSetups: function () {
            let data = collect();
            collect(this.testResult.testStep.data.testSetups)
                .where('type', 'request')
                .each((item) => {
                    data = data.mergeRecursive(item.values);
                });
            return data;
        },
        testResultResponseSetups: function () {
            let data = collect();
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
