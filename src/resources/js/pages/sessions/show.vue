<template>
    <layout :session="session" :useCases="useCases">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <b>Latest test runs</b>
                </h2>
            </div>

            <div class="empty h-auto" v-if="!testRuns.data.length">
                <div class="row">
                    <div class="col-10 mx-auto">
                        <p class="empty-title h3 mb-3">You have no test runs for session</p>
                        <p class="empty-subtitle text-muted mb-0">
                            Select any test case in the left menu to get more information about it and make your first test run or click the button below to learn more from our enhanced Tutorial.
                        </p>
                        <div class="empty-action">
                            <inertia-link
                                :href="route('tutorials')"
                                class="btn btn-primary"
                            >
                                <icon name="help" />
                                Visit Tutorial
                            </inertia-link>
                        </div>
                    </div>
                </div>
            </div>

            <template v-else>
                <div class="pt-4 border-bottom">
                    <session-chart :session="session" />
                </div>

                <div class="table-responsive mb-0">
                    <table class="table table-striped table-hover card-table">
                        <thead>
                        <tr>
                            <th class="text-nowrap w-auto">Test Case</th>
                            <th class="text-nowrap w-auto">Run ID</th>
                            <th class="text-nowrap w-auto">Status</th>
                            <th class="text-nowrap w-auto">Duration</th>
                            <th class="text-nowrap w-auto">Date</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr v-for="testRun in testRuns.data">
                                <td>
                                    <inertia-link
                                        :href="route('sessions.test-cases.show', [testRun.session.id, testRun.testCase.id])"
                                    >
                                        {{ testRun.testCase.name }}
                                    </inertia-link>
                                </td>
                                <td>
                                    <inertia-link
                                        :href="route('sessions.test-cases.test-runs.show', [testRun.session.id, testRun.testCase.id, testRun.id])"
                                    >
                                        {{ testRun.uuid }}
                                    </inertia-link>
                                </td>
                                <td>
                                    <span v-if="testRun.completed_at && testRun.successful" class="d-flex align-items-center">
                                        <span class="badge bg-success mr-2"></span> Pass
                                    </span>
                                    <span v-else-if="testRun.completed_at && !testRun.successful" class="d-flex align-items-center">
                                        <span class="badge bg-danger mr-2"></span> Fail
                                    </span>
                                    <span v-else class="d-flex align-items-center">
                                        <span class="badge bg-secondary mr-2"></span> Incomplete
                                    </span>
                                </td>
                                <td>
                                    {{ `${testRun.duration} ms` }}
                                </td>
                                <td>
                                    {{ testRun.created_at }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <pagination
                    :meta="testRuns.meta"
                    :links="testRuns.links"
                    class="card-footer"
                />
            </template>
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/sessions/overview';
import SessionChart from '@/components/sessions/chart';

export default {
    components: {
        Layout,
        SessionChart
    },
    props: {
        session: {
            type: Object,
            required: true
        },
        useCases: {
            type: Object,
            required: true
        },
        testRuns: {
            type: Object,
            required: true
        },
    }
};
</script>
