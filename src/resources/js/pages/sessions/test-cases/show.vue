<template>
    <layout :session="session" :test-case="testCase">
        <div  class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <b>{{ `Test runs of ${testCase.name}` }}</b>
                </h2>
            </div>
            <div class="table-responsive mb-0">
                <table class="table table-striped table-hover card-table">
                    <thead class="thead-light">
                    <tr>
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
                    <tr v-if="!testRuns.data.length">
                        <td class="text-center" colspan="4">
                            No Results
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
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/sessions/test-case';

export default {
    components: {
        Layout
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
        testRuns: {
            type: Object,
            required: true
        },
    }
};
</script>