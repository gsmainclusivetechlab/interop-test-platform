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
                            v-if="testRun.successful"
                            class="text-success d-flex align-items-center"
                        >
                            <icon
                                name="circle-check"
                                class="icon-md mr-1"
                            ></icon>
                            Pass
                        </span>
                        <span
                            v-else
                            class="text-danger d-flex align-items-center"
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
                    <span
                        v-if="testRun.passed || testRun.failures"
                        class="font-weight-bold mr-2"
                        >Steps:</span
                    >
                    <span
                        v-if="testRun.passed"
                        class="text-success mr-2 align-items-center d-flex"
                    >
                        <icon name="circle-check" class="icon-md mr-1"></icon>
                        {{ `${testRun.passed} Pass` }}
                    </span>
                    <span
                        v-if="testRun.failures"
                        class="text-danger align-items-center d-flex"
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
                                        testResults[0].testStep &&
                                        component.id ==
                                            testResults[0].testStep.data.source
                                                .id &&
                                        connection.id ==
                                            testResults[0].testStep.data.target
                                                .id
                                    "
                                >
                                    |{{
                                        `Step ${testResults[0].testStep.data.position}`
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
                                            'list-group-item-action': testResultSteps.has(
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
                                            class="d-flex justify-content-between align-items-center py-2 px-4 text-reset"
                                        >
                                            <div class="mr-1 text-truncate">
                                                <span class="font-weight-bold">
                                                    {{
                                                        `Step ${testStep.position}`
                                                    }}
                                                </span>
                                                <div
                                                    v-if="
                                                        testStep.repeat.count >
                                                        0
                                                    "
                                                    class="text-muted"
                                                >
                                                    Iterations
                                                    <span
                                                        class="badge badge-secondary"
                                                        >{{
                                                            `${
                                                                testStep.repeat
                                                                    .count + 1
                                                            }`
                                                        }}</span
                                                    >
                                                </div>
                                                <div
                                                    v-if="
                                                        testResultSteps.get(
                                                            testStep.id
                                                        ).request
                                                    "
                                                    class="d-flex align-items-baseline"
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
                                            <span class="font-weight-bold">
                                                {{
                                                    `Step ${testStep.position}`
                                                }}
                                            </span>
                                            <div
                                                v-if="testStep.repeat.count > 0"
                                                class="text-muted"
                                            >
                                                Iterations
                                                <span
                                                    class="badge badge-secondary"
                                                    >{{
                                                        `${
                                                            testStep.repeat
                                                                .count + 1
                                                        }`
                                                    }}</span
                                                >
                                            </div>
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
                            <h4
                                v-if="testResults.length > 1"
                                class="px-3 py-2 mb-0"
                            >
                                Iterations:
                            </h4>
                            <b-tabs
                                :nav-class="{
                                    'd-none': testResults.length === 1,
                                }"
                            >
                                <b-tab
                                    v-for="(result, i) in testResults"
                                    :title="`№ ${result.iteration}`"
                                    title-link-class="justify-content-center py-1 text-nowrap rounded-0"
                                    :key="`result-${i}`"
                                >
                                    <div
                                        v-if="result.testStep"
                                        class="lead py-3 px-4"
                                    >
                                        <div
                                            class="d-flex justigy-content-between"
                                        >
                                            <b class="text-nowrap">
                                                {{
                                                    `Step ${result.testStep.data.position}`
                                                }}
                                            </b>
                                            <div class="d-inline-block ml-auto">
                                                <small
                                                    class="d-inline-block ml-2"
                                                    v-if="result.response"
                                                >
                                                    Status:
                                                    <span class="text-success">
                                                        {{
                                                            `HTTP ${result.response.status}`
                                                        }}
                                                    </span>
                                                </small>
                                                <small
                                                    class="d-inline-block ml-2"
                                                    v-if="result.duration"
                                                >
                                                    Duration:
                                                    <span class="text-success">
                                                        {{
                                                            `${result.duration} ms`
                                                        }}
                                                    </span>
                                                </small>
                                            </div>
                                        </div>
                                        <div
                                            class="text-truncate"
                                            v-if="result.request"
                                        >
                                            <u class="mr-2"
                                                >{{ result.request.method }}
                                                {{ result.request.path }}</u
                                            >
                                        </div>
                                    </div>
                                    <div
                                        class="lead mb-2 py-3 px-4"
                                        :class="{
                                            'alert-success': result.successful,
                                            'alert-secondary':
                                                !result.testExecutions ||
                                                !result.testExecutions.data
                                                    .length,
                                            'alert-danger':
                                                result.testExecutions &&
                                                result.testExecutions.data
                                                    .length &&
                                                result.successful === false,
                                        }"
                                    >
                                        {{
                                            !result.testExecutions ||
                                            !result.testExecutions.data.length
                                                ? 'Pending'
                                                : result.successful
                                                ? 'Pass'
                                                : 'Fail'
                                        }}
                                        <div v-if="result.exception">
                                            {{ result.exception }}
                                        </div>
                                    </div>
                                    <div
                                        class="py-2 px-4"
                                        v-if="
                                            result.testExecutions &&
                                            result.testExecutions.data.length
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
                                                v-for="(
                                                    testExecution, j
                                                ) in result.testExecutions.data"
                                                :key="`execution-${j}`"
                                            >
                                                <div
                                                    class="d-flex align-items-center"
                                                >
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
                                                            `test-execution-${testExecution.id}-${j}`
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
                                                    v-if="
                                                        testExecution.actual ||
                                                        testExecution.expected ||
                                                        testExecution.exception
                                                    "
                                                    :id="`test-execution-${testExecution.id}-${j}`"
                                                    size="lg"
                                                    centered
                                                    hide-footer
                                                    :title="testExecution.name"
                                                >
                                                    <b-tabs
                                                        v-if="
                                                            testExecution.actual ||
                                                            testExecution.expected
                                                        "
                                                        nav-class="flex-nowrap"
                                                        content-class="mt-2"
                                                        justified
                                                    >
                                                        <b-tab
                                                            v-if="
                                                                testExecution.actual
                                                            "
                                                            title="Actual"
                                                            title-link-class="justify-content-center pt-0 pb-1 text-nowrap rounded-0"
                                                        >
                                                            <json-tree
                                                                :data="
                                                                    testExecution.actual
                                                                "
                                                                :show-copy-btn="
                                                                    false
                                                                "
                                                                :deep="1"
                                                                :show-line="
                                                                    false
                                                                "
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
                                                                :show-copy-btn="
                                                                    false
                                                                "
                                                                :deep="1"
                                                                :show-line="
                                                                    false
                                                                "
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
                                    <div
                                        class="py-2 px-4"
                                        v-if="result.request"
                                    >
                                        <div class="d-flex mb-2">
                                            <strong
                                                class="lead d-block mr-auto mt-auto font-weight-bold"
                                            >
                                                Request
                                            </strong>
                                            <clipboard-json-to-curl
                                                :request="result.request"
                                            ></clipboard-json-to-curl>
                                        </div>
                                        <div class="border">
                                            <div class="d-flex">
                                                <div
                                                    class="w-25 px-4 py-2 border"
                                                >
                                                    <strong>URL</strong>
                                                </div>
                                                <div
                                                    class="w-75 px-4 py-2 border"
                                                >
                                                    {{ result.request.uri }}
                                                </div>
                                            </div>
                                            <div class="d-flex">
                                                <div
                                                    class="w-25 px-4 py-2 border"
                                                >
                                                    <strong>Method</strong>
                                                </div>
                                                <div
                                                    class="w-75 px-4 py-2 border"
                                                >
                                                    {{ result.request.method }}
                                                </div>
                                            </div>
                                            <div
                                                class="d-flex"
                                                v-if="
                                                    result.request.headers !==
                                                    undefined
                                                "
                                            >
                                                <button
                                                    type="button"
                                                    class="dropdown-toggle text-left bg-light border rounded-0 w-25 px-4 py-2"
                                                    v-b-toggle="
                                                        `request-headers-${result.id}`
                                                    "
                                                >
                                                    <strong>Headers</strong>
                                                </button>
                                                <div
                                                    class="w-75 px-4 py-2 border"
                                                >
                                                    {{
                                                        `(${collect(
                                                            result.request
                                                                .headers
                                                        ).count()}) params`
                                                    }}
                                                </div>
                                            </div>
                                            <b-collapse
                                                :id="`request-headers-${result.id}`"
                                                v-if="
                                                    result.request.headers !==
                                                    undefined
                                                "
                                            >
                                                <div class="d-flex">
                                                    <div
                                                        class="w-25 px-4 py-2 border"
                                                    ></div>
                                                    <div
                                                        class="w-75 px-4 py-2 border"
                                                    >
                                                        <div class="mb-0 p-0">
                                                            <json-tree
                                                                :data="
                                                                    result
                                                                        .request
                                                                        .headers
                                                                "
                                                                :deep="1"
                                                                :show-line="
                                                                    false
                                                                "
                                                                class="p-2"
                                                            ></json-tree>
                                                        </div>
                                                    </div>
                                                </div>
                                            </b-collapse>
                                            <div
                                                class="d-flex"
                                                v-if="
                                                    result.request.body !==
                                                        undefined &&
                                                    result.request.body !==
                                                        'empty_body'
                                                "
                                            >
                                                <button
                                                    type="button"
                                                    class="dropdown-toggle text-left bg-light border rounded-0 w-25 px-4 py-2"
                                                    v-b-toggle="
                                                        `request-body-${result.id}`
                                                    "
                                                >
                                                    <strong>Body</strong>
                                                </button>
                                                <div
                                                    class="w-75 px-4 py-2 border"
                                                >
                                                    {{
                                                        `(${collect(
                                                            result.request.body
                                                        ).count()}) params`
                                                    }}
                                                </div>
                                            </div>
                                            <b-collapse
                                                :id="`request-body-${result.id}`"
                                                v-if="
                                                    result.request.body !==
                                                        undefined &&
                                                    result.request.body !==
                                                        'empty_body'
                                                "
                                            >
                                                <div class="d-flex">
                                                    <div
                                                        class="w-25 px-4 py-2 border"
                                                    ></div>
                                                    <div
                                                        class="w-75 px-4 py-2 border"
                                                    >
                                                        <div class="mb-0 p-0">
                                                            <json-tree
                                                                :data="
                                                                    result
                                                                        .request
                                                                        .body
                                                                "
                                                                :deep="1"
                                                                :show-line="
                                                                    false
                                                                "
                                                                class="p-2"
                                                            ></json-tree>
                                                        </div>
                                                    </div>
                                                </div>
                                            </b-collapse>
                                            <div
                                                class="d-flex"
                                                v-if="
                                                    !collect(
                                                        session.components.data
                                                    )
                                                        .where(
                                                            'id',
                                                            result.testStep.data
                                                                .source.id
                                                        )
                                                        .count() &&
                                                    testResultRequestSetups.count()
                                                "
                                            >
                                                <button
                                                    type="button"
                                                    class="dropdown-toggle text-left bg-light border rounded-0 w-25 px-4 py-2"
                                                    v-b-toggle="
                                                        `request-overridden-${result.id}`
                                                    "
                                                >
                                                    <strong>Overridden</strong>
                                                </button>
                                                <div
                                                    class="w-75 px-4 py-2 border"
                                                >
                                                    {{
                                                        `(${testResultRequestSetups.count()}) params`
                                                    }}
                                                </div>
                                            </div>
                                            <b-collapse
                                                :id="`request-overridden-${result.id}`"
                                                v-if="
                                                    testResultRequestSetups.count()
                                                "
                                            >
                                                <div class="d-flex">
                                                    <div
                                                        class="w-25 px-4 py-2 border"
                                                    ></div>
                                                    <div
                                                        class="w-75 px-4 py-2 border"
                                                    >
                                                        <div class="mb-0 p-0">
                                                            <json-tree
                                                                :data="
                                                                    testResultRequestSetups.all()
                                                                "
                                                                :deep="1"
                                                                :show-line="
                                                                    false
                                                                "
                                                                class="p-2"
                                                            ></json-tree>
                                                        </div>
                                                    </div>
                                                </div>
                                            </b-collapse>
                                        </div>
                                    </div>
                                    <div
                                        class="py-2 px-4"
                                        v-if="result.response"
                                    >
                                        <div class="d-flex mb-2">
                                            <strong
                                                class="lead d-block mr-auto font-weight-bold"
                                            >
                                                Response
                                            </strong>
                                        </div>
                                        <div class="border">
                                            <div class="d-flex">
                                                <div
                                                    class="w-25 px-4 py-2 border"
                                                >
                                                    <strong>Status</strong>
                                                </div>
                                                <div
                                                    class="w-75 px-4 py-2 border"
                                                >
                                                    {{ result.response.status }}
                                                </div>
                                            </div>
                                            <div
                                                class="d-flex"
                                                v-if="
                                                    result.response.headers !==
                                                    undefined
                                                "
                                            >
                                                <button
                                                    type="button"
                                                    class="dropdown-toggle text-left bg-light border rounded-0 w-25 px-4 py-2"
                                                    v-b-toggle="
                                                        `response-headers-${result.id}`
                                                    "
                                                >
                                                    <strong>Headers</strong>
                                                </button>
                                                <div
                                                    class="w-75 px-4 py-2 border"
                                                >
                                                    {{
                                                        `(${collect(
                                                            result.response
                                                                .headers
                                                        ).count()}) params`
                                                    }}
                                                </div>
                                            </div>
                                            <b-collapse
                                                :id="`response-headers-${result.id}`"
                                                v-if="
                                                    result.response.headers !==
                                                    undefined
                                                "
                                            >
                                                <div class="d-flex">
                                                    <div
                                                        class="w-25 px-4 py-2 border"
                                                    ></div>
                                                    <div
                                                        class="w-75 px-4 py-2 border"
                                                    >
                                                        <div class="mb-0 p-0">
                                                            <json-tree
                                                                :data="
                                                                    result
                                                                        .response
                                                                        .headers
                                                                "
                                                                :deep="1"
                                                                :show-line="
                                                                    false
                                                                "
                                                                class="p-2"
                                                            ></json-tree>
                                                        </div>
                                                    </div>
                                                </div>
                                            </b-collapse>
                                            <div
                                                class="d-flex"
                                                v-if="
                                                    result.response.body !==
                                                        undefined &&
                                                    result.response.body !==
                                                        'empty_body'
                                                "
                                            >
                                                <button
                                                    type="button"
                                                    class="dropdown-toggle text-left bg-light border rounded-0 w-25 px-4 py-2"
                                                    v-b-toggle="
                                                        `response-body-${result.id}`
                                                    "
                                                >
                                                    <strong>Body</strong>
                                                </button>
                                                <div
                                                    class="w-75 px-4 py-2 border"
                                                >
                                                    {{
                                                        `(${collect(
                                                            result.response.body
                                                        ).count()}) params`
                                                    }}
                                                </div>
                                            </div>
                                            <b-collapse
                                                :id="`response-body-${result.id}`"
                                                v-if="
                                                    result.response.body !==
                                                        undefined &&
                                                    result.response.body !==
                                                        'empty_body'
                                                "
                                            >
                                                <div class="d-flex">
                                                    <div
                                                        class="w-25 px-4 py-2 border"
                                                    ></div>
                                                    <div
                                                        class="w-75 px-4 py-2 border"
                                                    >
                                                        <div class="mb-0 p-0">
                                                            <json-tree
                                                                :data="
                                                                    result
                                                                        .response
                                                                        .body
                                                                "
                                                                :deep="1"
                                                                :show-line="
                                                                    false
                                                                "
                                                                class="p-2"
                                                            ></json-tree>
                                                        </div>
                                                    </div>
                                                </div>
                                            </b-collapse>
                                            <div
                                                class="d-flex"
                                                v-if="
                                                    !collect(
                                                        session.components.data
                                                    )
                                                        .where(
                                                            'id',
                                                            result.testStep.data
                                                                .target.id
                                                        )
                                                        .count() &&
                                                    testResultResponseSetups.count()
                                                "
                                            >
                                                <button
                                                    type="button"
                                                    class="dropdown-toggle text-left bg-light border rounded-0 w-25 px-4 py-2"
                                                    v-b-toggle="
                                                        `response-overridden-${result.id}`
                                                    "
                                                >
                                                    <strong>Overridden</strong>
                                                </button>
                                                <div
                                                    class="w-75 px-4 py-2 border"
                                                >
                                                    {{
                                                        `(${testResultResponseSetups.count()}) params`
                                                    }}
                                                </div>
                                            </div>
                                            <b-collapse
                                                :id="`response-overridden-${result.id}`"
                                                v-if="
                                                    testResultResponseSetups.count()
                                                "
                                            >
                                                <div class="d-flex">
                                                    <div
                                                        class="w-25 px-4 py-2 border"
                                                    ></div>
                                                    <div
                                                        class="w-75 px-4 py-2 border"
                                                    >
                                                        <div class="mb-0 p-0">
                                                            <json-tree
                                                                :data="
                                                                    testResultResponseSetups.all()
                                                                "
                                                                :deep="1"
                                                                :show-line="
                                                                    false
                                                                "
                                                                class="p-2"
                                                            ></json-tree>
                                                        </div>
                                                    </div>
                                                </div>
                                            </b-collapse>
                                        </div>
                                    </div>
                                </b-tab>
                            </b-tabs>
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
        testResults: {
            type: Array,
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
                        this.$inertia.reload(['testRun', 'testResults']);
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
            if (!this.testResults[0].id) return data;
            collect(this.testResults[0].testStep.data.testSetups)
                .where('type', 'request')
                .each((item) => {
                    data = data.mergeRecursive(item.values);
                });
            return data;
        },
        testResultResponseSetups() {
            let data = collect();
            if (!this.testResults[0].id) return data;
            collect(this.testResults[0].testStep.data.testSetups)
                .where('type', 'response')
                .each((item) => {
                    data = data.mergeRecursive(item.values);
                });
            return data;
        },
    },
};
</script>
