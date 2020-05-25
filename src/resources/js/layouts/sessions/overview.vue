<template>
    <layout>
        <div class="row">
            <div class="col-md-12">
                <div class="row border-bottom pb-4 align-items-center">
                    <div class="col-6 d-flex flex-column">
                        <div class="page-pretitle font-weight-normal">
                            <ol class="breadcrumb breadcrumb-bullets" aria-label="breadcrumbs">
                                <li class="breadcrumb-item">
                                    <inertia-link
                                        :href="route('sessions.index')"
                                    >
                                        Sessions
                                    </inertia-link>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ session.name }}
                                </li>
                            </ol>
                            <breadcrumb class="breadcrumb-bullets" :items="breadcrumbs"></breadcrumb>
                        </div>
                        <h1 class="page-title mw-100 mr-2 text-break">
                            <b>{{ session.name }}</b>
                        </h1>
                    </div>
                    <div class="ml-auto col-2">
                        <div class="mb-1">
                            Execution:
                            <icon name="briefcase" v-b-tooltip.hover title="Use Case" />
                            <small>
                                {{ session.testCases ? collect(session.testCases.data).unique('use_case_id').count() : 0 }}
                            </small>
                            <icon name="file-text" v-b-tooltip.hover title="Test Case" />
                            <small>
                                {{ session.testCases ? session.testCases.data.length : 0  }}
                            </small>
                        </div>
                        <div style="min-width: 180px">
                            <session-progress :testCases="session.testCases.data" />
                        </div>
                    </div>
                </div>
                <div class="row align-items-start">
                    <div class="col-3 mt-3 pr-0">
                        <div class="card mb-0">
                            <div class="card-header px-3">
                                <h3 class="card-title">
                                    Select use cases
                                </h3>
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-unstyled">
                                    <li v-for="useCase in useCases.data">
                                        <b class="d-block dropdown-toggle py-2 px-3 border-bottom" v-b-toggle="`use-case-${useCase.id}`">
                                            {{ useCase.name }}
                                        </b>
                                        <b-collapse
                                            :id="`use-case-${useCase.id}`"
                                            visible
                                            v-if="collect(useCase.testCases).where('behavior', 'positive').count()"
                                        >
                                            <ul class="list-unstyled">
                                                <li>
                                                    <span
                                                        class="dropdown-toggle d-block pl-4 pr-3 py-2 font-weight-medium border-bottom"
                                                        v-b-toggle="`positive-test-cases-${useCase.id}`"
                                                        >
                                                        Happy flow
                                                    </span>
                                                    <b-collapse :id="`positive-test-cases-${useCase.id}`" visible>
                                                        <ul class="list-unstyled">
                                                            <li
                                                                v-for="positiveTestCase in collect(useCase.testCases).where('behavior', 'positive').all()"
                                                                class="list-group-item-action border-bottom"
                                                                v-bind:class="{'bg-body': (testCase !== undefined && positiveTestCase.id === testCase.id)}"
                                                            >
                                                                <inertia-link
                                                                    :href="route('sessions.test-cases.show', [session.id, positiveTestCase.id])"
                                                                    class="d-flex justify-content-between align-items-center pl-5 pr-4 py-2"
                                                                >
                                                                    <span>
                                                                        {{ positiveTestCase.name }}
                                                                    </span>

                                                                    <span
                                                                        v-if="positiveTestCase.lastTestRun && positiveTestCase.lastTestRun.completed_at && positiveTestCase.lastTestRun.successful"
                                                                        class="flex-shrink-0 mr-0 ml-1  badge bg-success"
                                                                    ></span>
                                                                    <span
                                                                        v-else-if="positiveTestCase.lastTestRun && positiveTestCase.lastTestRun.completed_at && !positiveTestCase.lastTestRun.successful"
                                                                        class="flex-shrink-0 mr-0 ml-1  badge bg-danger"
                                                                    ></span>
                                                                    <span
                                                                        v-else
                                                                        class="flex-shrink-0 mr-0 ml-1 badge bg-secondary"
                                                                    ></span>
                                                                </inertia-link>
                                                            </li>
                                                        </ul>
                                                    </b-collapse>
                                                </li>
                                            </ul>
                                        </b-collapse>
                                        <b-collapse
                                            :id="`use-case-${useCase.id}`"
                                            visible
                                            v-if="collect(useCase.testCases).where('behavior', 'negative').count()"
                                        >
                                            <ul class="list-unstyled">
                                                <li>
                                                    <span
                                                        class="dropdown-toggle d-block pl-4 pr-3 py-2 font-weight-medium border-bottom"
                                                        v-b-toggle="`negative-test-cases-${useCase.id}`"
                                                    >
                                                        Unhappy flow
                                                    </span>
                                                    <b-collapse :id="`negative-test-cases-${useCase.id}`" visible>
                                                        <ul class="list-unstyled">
                                                            <li
                                                                v-for="negativeTestCase in collect(useCase.testCases).where('behavior', 'negative').all()"
                                                                class="list-group-item-action border-bottom"
                                                                v-bind:class="{'bg-body': (testCase !== undefined && negativeTestCase.id === testCase.id)}"
                                                            >
                                                                <inertia-link
                                                                    :href="route('sessions.test-cases.show', [session.id, negativeTestCase.id])"
                                                                    class="d-flex justify-content-between align-items-center pl-5 pr-4 py-2"
                                                                >
                                                                    <span>
                                                                        {{ negativeTestCase.name }}
                                                                    </span>

                                                                    <span
                                                                        v-if="negativeTestCase.lastTestRun && negativeTestCase.lastTestRun.completed_at && negativeTestCase.lastTestRun.successful"
                                                                        class="flex-shrink-0 mr-0 ml-1  badge bg-success"
                                                                    ></span>
                                                                    <span
                                                                        v-else-if="negativeTestCase.lastTestRun && negativeTestCase.lastTestRun.completed_at && !negativeTestCase.lastTestRun.successful"
                                                                        class="flex-shrink-0 mr-0 ml-1  badge bg-danger"
                                                                    ></span>
                                                                    <span
                                                                        v-else
                                                                        class="flex-shrink-0 mr-0 ml-1 badge bg-secondary"
                                                                    ></span>
                                                                </inertia-link>
                                                            </li>
                                                        </ul>
                                                    </b-collapse>
                                                </li>
                                            </ul>
                                        </b-collapse>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-9 mt-3">
                        <div class="row">
                            <div class="col">
                                <slot></slot>
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
        SessionProgress
    },
    metaInfo() {
        return {
            title: this.session.name,
        }
    },
    props: {
        session: {
            type: Object,
            required: true
        },
        testCase: {
            type: Object,
            required: false
        },
        useCases: {
            type: Object,
            required: true
        },
    },
    data() {
        return {
            breadcrumbs: [
                {name: 'Sessions', url: route('sessions.index')},
                {name: this.session.name},
            ]
        };
    },
};
</script>
