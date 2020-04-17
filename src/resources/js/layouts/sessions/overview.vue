<template>
    <layout>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="row border-bottom pb-4 align-items-center">
                        <div class="col-6 d-flex flex-wrap">
                            <h1 class="page-title mr-2">
                                <b>{{ session.name }}</b>
                            </h1>
                        </div>
                        <div class="ml-auto col-2">
                            <div class="mb-1">
                                Execution:
                                <icon name="briefcase" />
                                <small>
                                    {{ session.testCases ? collect(session.testCases.data).unique('use_case_id').count() : 0 }}
                                </small>
                                <icon name="file-text" />
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
                                <div class="card-header px-4">
                                    <h3 class="card-title">
                                        Select use cases
                                    </h3>
                                </div>
                                <div class="card-body p-0">
                                    <ul class="list-unstyled">
                                        <li v-for="useCase in collect(session.testCases.data).mapWithKeys(item => [item.useCase.id, item.useCase]).all()">
                                            <b class="d-block dropdown-toggle py-2 px-4 border-bottom" :v-b-toggle="`use-case-${useCase.id}`">
                                                {{ useCase.name }}
                                            </b>
                                            <b-collapse
                                                :id="`use-case-${useCase.id}`"
                                                visible
                                                v-if="collect(session.testCases.data).where('behavior', 'positive').count()"
                                            >
                                                <ul class="list-unstyled">
                                                    <li>
                                                        <span
                                                            class="dropdown-toggle d-block px-5 py-2 font-weight-medium border-bottom"
                                                            :v-b-toggle="`positive-test-cases-${useCase.id}`"
                                                            >
                                                            Happy flow
                                                        </span>
                                                        <b-collapse :id="`positive-test-cases-${useCase.id}`" visible>
                                                            <ul class="list-unstyled">
                                                                <li
                                                                    v-for="testCase in collect(session.testCases.data).where('behavior', 'positive').sortBy('name').all()"
                                                                    class="list-group-item-action d-flex justify-content-between align-items-center px-6 py-2 border-bottom"
                                                                >
                                                                    <inertia-link :href="route('sessions.test-cases.show', [session.id, testCase.id])">
                                                                        {{ testCase.name }}
                                                                    </inertia-link>
                                                                    <span
                                                                        v-if="testCase.lastTestRun && testCase.lastTestRun.completed_at && testCase.lastTestRun.successful"
                                                                        class="flex-shrink-0 mr-0 ml-1  badge bg-success"
                                                                    ></span>
                                                                    <span
                                                                        v-else-if="testCase.lastTestRun && testCase.lastTestRun.completed_at && !testCase.lastTestRun.successful"
                                                                        class="flex-shrink-0 mr-0 ml-1  badge bg-danger"
                                                                    ></span>
                                                                    <span
                                                                        v-else
                                                                        class="flex-shrink-0 mr-0 ml-1 badge bg-secondary"
                                                                    ></span>
                                                                </li>
                                                            </ul>
                                                        </b-collapse>
                                                    </li>
                                                </ul>
                                            </b-collapse>
                                            <b-collapse
                                                :id="`use-case-${useCase.id}`"
                                                visible
                                                v-if="collect(session.testCases.data).where('behavior', 'negative').count()"
                                            >
                                                <ul class="list-unstyled">
                                                    <li>
                                                        <span
                                                            class="dropdown-toggle d-block px-5 py-2 font-weight-medium border-bottom"
                                                            :v-b-toggle="`negative-test-cases-${useCase.id}`"
                                                        >
                                                            Unhappy flow
                                                        </span>
                                                        <b-collapse :id="`negative-test-cases-${useCase.id}`" visible>
                                                            <ul class="list-unstyled">
                                                                <li
                                                                    v-for="testCase in collect(session.testCases.data).where('behavior', 'negative').sortBy('name').all()"
                                                                    class="list-group-item-action d-flex justify-content-between align-items-center px-6 py-2 border-bottom"
                                                                >
                                                                    <inertia-link :href="route('sessions.test-cases.show', [session.id, testCase.id])">
                                                                        {{ testCase.name }}
                                                                    </inertia-link>
                                                                    <span
                                                                        v-if="testCase.lastTestRun && testCase.lastTestRun.completed_at && testCase.lastTestRun.successful"
                                                                        class="flex-shrink-0 mr-0 ml-1  badge bg-success"
                                                                    ></span>
                                                                    <span
                                                                        v-else-if="testCase.lastTestRun && testCase.lastTestRun.completed_at && !testCase.lastTestRun.successful"
                                                                        class="flex-shrink-0 mr-0 ml-1  badge bg-danger"
                                                                    ></span>
                                                                    <span
                                                                        v-else
                                                                        class="flex-shrink-0 mr-0 ml-1 badge bg-secondary"
                                                                    ></span>
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
    }
};
</script>
