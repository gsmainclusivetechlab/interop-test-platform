<template>
    <layout :session="session" :test-case="testCase">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <inertia-link
                        :href="route('sessions.test-cases.show', [session.id, testCase.id])"
                        class="text-decoration-none"
                    >
                        <icon name="chevron-left"></icon>
                    </inertia-link>
                    {{ `Run ID: ${testRun.uuid}` }}
                </h3>
                <div class="card-options">
                    <span class="text-success mr-2" v-if="testRun.passed">
                        <icon name="check"></icon>
                        {{ `${testRun.passed} Pass` }}
                    </span>
                    <span class="text-danger mr-2" v-if="testRun.failures">
                        <icon name="alert-circle"></icon>
                        {{ `${testRun.failures} Fail` }}
                    </span>
                </div>
            </div>
            <div class="card-body bg-light p-0">
                <div class="p-4">
                    <diagram>
                        graph LR;
                        <template v-for="component in session.scenario.data.components">
                            {{ component.id }}({{component.name}})<template v-if="collect(session.suts.data).contains('id', component.id)">:::is-active</template><template v-else></template>;
                            <template v-for="connection in component.paths">
                                {{ component.id }}
                                <template v-if="component.simulated && connection.simulated">--></template><template v-else>-.-></template>
                                <template v-if="component.id == testResult.testStep.data.source.id && connection.id == testResult.testStep.data.target.id">
                                    |{{ `Step ${testResult.testStep.data.position}` }}| {{ connection.id }};
                                </template>
                                <template v-else>
                                    {{ connection.id }};
                                </template>
                            </template>
                        </template>
                    </diagram>
                </div>
                <div class="rounded-0 bg-white border-top">
                    <div class="row">
                        <div class="col-3 pr-0">
                            <ul class="list-unstyled mb-0">
                                <template v-for="testStep in testCase.testSteps.data">
                                    <li
                                        v-if="testResultStep = collect(testRun.testResults.data).firstWhere('testStep.id', testStep.id)"
                                        v-bind:class="{'bg-light': testResult.testStep.data.id === testStep.id}"
                                        class="list-group-item-action"
                                    >
                                        <inertia-link
                                            :href="route('sessions.test-cases.test-runs.show', [session, testCase, testRun, testStep.position])"
                                            class="d-flex justify-content-between align-items-center py-2 px-4 text-reset text-decoration-none"
                                        >
                                            <div class="mr-1 text-truncate">
                                                <b>
                                                    {{ `Step ${testStep.position}` }}
                                                </b>
                                                <div class="d-flex align-items-baseline text-truncate">
                                                    <span
                                                        class="font-weight-bold"
                                                        v-bind:class="{
                                                            'text-orange': testResultStep.request.method === 'POST',
                                                            'text-text-blue': testResultStep.request.method === 'PUT',
                                                            'text-red': testResultStep.request.method === 'DELETE',
                                                            'text-mint': testResultStep.request.method === 'GET',
                                                        }"
                                                    >
                                                        {{ testResultStep.request.method }}
                                                    </span>
                                                    <span
                                                        class="d-inline-block ml-1 text-truncate"
                                                        :title="`${testResultStep.request.method} ${testResultStep.request.path}`"
                                                    >
                                                        {{ testResultStep.request.path }}
                                                    </span>
                                                </div>
                                            </div>
                                            <span
                                                class="flex-shrink-0 badge mr-0"
                                                v-bind:class="{
                                                    'bg-success': testResultStep.successful,
                                                    'bg-danger': !testResultStep.successful,
                                                }"
                                            >
                                            </span>
                                        </inertia-link>
                                    </li>
                                    <li
                                        v-else
                                        class="d-flex align-items-center py-2 px-4 text-black-50"
                                    >
                                        <div class="text-truncate">
                                            <b>
                                                {{ `Step ${testStep.position}` }}
                                            </b>
                                            <div class="text-truncate" :title="testStep.forward">
                                                {{ testStep.forward }}
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
                                        {{ `Step ${testResult.testStep.data.position}` }}
                                    </b>
                                    <small class="d-inline-block ml-auto">
                                        Status:
                                        <span class="text-success" v-if="testResult.response">
                                            {{ `HTTP ${testResult.response.status}` }}
                                        </span>
                                    </small>
                                </div>
                                <div class="text-truncate">
                                    <u class="mr-2">{{ testResult.request.method }} {{ testResult.request.path }}</u>
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
                            </div>
                            <div class="py-2 px-4" v-if="testResult.testExecutions.data.length">
                                <div class="d-flex mb-2">
                                    <strong class="lead d-block mr-auto font-weight-bold">
                                        Performed tests
                                    </strong>
                                </div>
                                <ul class="m-0 p-0">
                                    <li class="d-flex flex-wrap py-2" v-for="testExecution in testResult.testExecutions.data">
                                        <div class="d-flex align-items-center">
                                            <span
                                                class="badge d-flex align-items-center justify-content-center flex-shrink-0 h-4 w-5 mr-2 text-uppercase"
                                                v-bind:class="{
                                                    'bg-success': testExecution.successful,
                                                    'bg-danger': !testExecution.successful,
                                                }"
                                            >
                                                {{ testExecution.successful ? 'Pass' : 'Fail' }}
                                            </span>
                                            <button
                                                class="btn btn-link p-0 text-reset font-weight-normal"
                                                v-if="testExecution.message"
                                                :id="`test-execution-${testExecution.id}`"
                                            >
                                                {{ testExecution.name }}
                                            </button>
                                            <span v-else class="d-flex align-items-center">
                                                {{ testExecution.name }}
                                            </span>
                                        </div>
                                        <b-popover
                                            v-if="testExecution.message"
                                            :target="`test-execution-${testExecution.id}`"
                                            triggers="click"
                                            placement="bottom"
                                        >
                                            <b-tabs
                                                nav-class="flex-nowrap"
                                                content-class="mt-2"
                                                justified
                                            >
                                                <b-tab
                                                    active
                                                    title="Actual result"
                                                    title-link-class="justify-content-center pt-0 pb-1 text-nowrap rounded-0"
                                                >
                                                    <p class="mb-0">{{ testExecution.message }}</p>
                                                </b-tab>
                                                <b-tab
                                                    title="Expected result"
                                                    title-link-class="justify-content-center pt-0 pb-1 text-nowrap rounded-0"
                                                >
                                                    <p class="mb-0">Expected result content</p>
                                                </b-tab>
                                            </b-tabs>
                                        </b-popover>
                                    </li>
                                </ul>
                            </div>
                            <div class="py-2 px-4" v-if="testResult.testStep.data.testSetups.length">
                                <div class="d-flex mb-2">
                                    <strong class="lead d-block mr-auto font-weight-bold">
                                        Overridden data
                                    </strong>
                                </div>
                                <ul class="m-0 p-0">
                                    <li
                                        class="d-flex flex-wrap py-2"
                                        v-for="testSetup in testResult.testStep.data.testSetups"
                                    >
                                        <div class="d-flex align-items-center">
                                            <span
                                                class="d-flex align-items-center dropdown-toggle"
                                                v-b-toggle="`test-setup-${testSetup.id}`"
                                            >
                                                {{ testSetup.name }}
                                            </span>
                                        </div>
                                        <b-collapse
                                            :id="`test-setup-${testSetup.id}`"
                                            class="w-100"
                                        >
                                            <json-tree :data="testSetup.values" :deep="1" :show-line="false" class="p-2"></json-tree>
                                        </b-collapse>
                                    </li>
                                </ul>
                            </div>
                            <div class="py-2 px-4" v-if="testResult.request">
                                <div class="d-flex mb-2">
                                    <strong class="lead d-block mr-auto font-weight-bold">
                                        Request
                                    </strong>
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
                                    <div class="d-flex" v-if="testResult.request.headers">
                                        <div
                                            class="w-25 px-4 py-2 border dropdown-toggle"
                                            v-b-toggle="`request-headers-${testResult.id}`"
                                        >
                                            <strong>Headers</strong>
                                        </div>
                                        <div class="w-75 px-4 py-2 border">
                                            {{ `(${collect(testResult.request.headers).count()}) params` }}
                                        </div>
                                    </div>
                                    <b-collapse :id="`request-headers-${testResult.id}`" v-if="testResult.request.headers">
                                        <div class="d-flex">
                                            <div class="w-25 px-4 py-2 border"></div>
                                            <div class="w-75 px-4 py-2 border">
                                                <div class="mb-0 p-0 bg-transparent">
                                                    <json-tree :data="testResult.request.headers" :deep="1" :show-line="false" class="p-2"></json-tree>
                                                </div>
                                            </div>
                                        </div>
                                    </b-collapse>
                                    <div class="d-flex" v-if="testResult.request.body">
                                        <div
                                            class="w-25 px-4 py-2 border dropdown-toggle"
                                            v-b-toggle="`request-body-${testResult.id}`"
                                        >
                                            <strong>Body</strong>
                                        </div>
                                        <div class="w-75 px-4 py-2 border">
                                            {{ `(${collect(testResult.request.body).count()}) params` }}
                                        </div>
                                    </div>
                                    <b-collapse :id="`request-body-${testResult.id}`" v-if="testResult.request.body">
                                        <div class="d-flex">
                                            <div class="w-25 px-4 py-2 border"></div>
                                            <div class="w-75 px-4 py-2 border">
                                                <div class="mb-0 p-0 bg-transparent">
                                                    <json-tree :data="testResult.request.body" :deep="1" :show-line="false" class="p-2"></json-tree>
                                                </div>
                                            </div>
                                        </div>
                                    </b-collapse>
                                </div>
                            </div>
                            <div class="py-2 px-4" v-if="testResult.response">
                                <div class="d-flex mb-2">
                                    <strong class="lead d-block mr-auto font-weight-bold">
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
                                    <div class="d-flex" v-if="testResult.response.headers">
                                        <div
                                            class="w-25 px-4 py-2 border dropdown-toggle"
                                            v-b-toggle="`response-headers-${testResult.id}`"
                                        >
                                            <strong>Headers</strong>
                                        </div>
                                        <div class="w-75 px-4 py-2 border">
                                            {{ `(${collect(testResult.response.headers).count()}) params` }}
                                        </div>
                                    </div>
                                    <b-collapse :id="`response-headers-${testResult.id}`" v-if="testResult.response.headers">
                                        <div class="d-flex">
                                            <div class="w-25 px-4 py-2 border"></div>
                                            <div class="w-75 px-4 py-2 border">
                                                <div class="mb-0 p-0 bg-transparent">
                                                    <json-tree :data="testResult.response.headers" :deep="1" :show-line="false" class="p-2"></json-tree>
                                                </div>
                                            </div>
                                        </div>
                                    </b-collapse>
                                    <div class="d-flex" v-if="testResult.response.body">
                                        <div
                                            class="w-25 px-4 py-2 border dropdown-toggle"
                                            v-b-toggle="`response-body-${testResult.id}`">
                                            <strong>Body</strong>
                                        </div>
                                        <div class="w-75 px-4 py-2 border">
                                            {{ `(${collect(testResult.response.body).count()}) params` }}
                                        </div>
                                    </div>
                                    <b-collapse :id="`response-body-${testResult.id}`" v-if="testResult.response.body">
                                        <div class="d-flex">
                                            <div class="w-25 px-4 py-2 border"></div>
                                            <div class="w-75 px-4 py-2 border">
                                                <div class="mb-0 p-0 bg-transparent">
                                                    <json-tree :data="testResult.response.body" :deep="1" :show-line="false" class="p-2"></json-tree>
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
        Diagram
    },
    props: {
        session: {
            type: Object,
            required: true
        },
        testCase: {
            type: Object,
            required: true
        },
        testRun: {
            type: Object,
            required: true
        },
        testResult: {
            type: Object,
            required: true
        },
    },
};
</script>
