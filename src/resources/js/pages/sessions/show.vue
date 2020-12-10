<template>
    <layout :session="session" :useCases="useCases">
        <div class="card" v-if="isCompliance">
            <div class="card-header">
                <h2 class="card-title">Session info</h2>
            </div>

            <div class="card-body">
                <dl>
                    <dt>
                        Status:
                        <span
                            class="badge"
                            :class="{
                                'bg-secondary':
                                    session.status === 'in_execution' ||
                                    session.status === 'ready' ||
                                    session.status === 'in_verification',
                                'bg-success': session.status === 'approved',
                                'bg-danger': session.status === 'declined',
                            }"
                            >{{ session.statusName }}</span
                        >
                    </dt>
                    <dd>
                        <span v-if="session.status === 'ready'">
                            <i>
                                Your session is set up and ready to receive
                                requests from your system. Execute any of the
                                test cases below to continue.</i
                            >
                        </span>
                        <template v-else-if="session.status === 'in_execution'">
                            <template v-if="session.completable">
                                <span>
                                    <i
                                        >All of your test cases have now been
                                        executed at least one. When you are
                                        satisfied with your test results, you
                                        may submit them for verification.</i
                                    >
                                </span>
                                <div
                                    v-if="session.can.update"
                                    class="text-right"
                                >
                                    <confirm-link
                                        method="post"
                                        class="btn btn-outline-primary"
                                        :href="
                                            route(
                                                'sessions.complete',
                                                session.id
                                            )
                                        "
                                        :confirm-title="'Confirm completion'"
                                        :confirm-text="`This is a non-reversible action, and you will need to create a new
                                        session if you wish to execute any more test cases.`"
                                    >
                                        <icon name="checks"></icon>
                                        Submit Results
                                    </confirm-link>
                                </div>
                            </template>
                            <span v-else>
                                <i
                                    >Some of your test cases have been executed.
                                    When all test cases have recorded at least
                                    one attempt, you will be able to submit your
                                    results for verification.</i
                                >
                            </span>
                        </template>
                        <template
                            v-else-if="session.status === 'in_verification'"
                        >
                            <template v-if="$page.auth.user.is_admin">
                                <span
                                    ><i
                                        >Please review the test results, and
                                        then Approve or Decline the session.</i
                                    ></span
                                >
                                <div class="text-right">
                                    <change-status
                                        :href="
                                            route(
                                                'admin.compliance-sessions.update',
                                                session.id
                                            )
                                        "
                                        :confirm-title="'Approve session'"
                                        :status="'approved'"
                                    >
                                        <icon name="check"></icon>
                                        Approve
                                    </change-status>
                                    <change-status
                                        class="ml-1"
                                        :href="
                                            route(
                                                'admin.compliance-sessions.update',
                                                session.id
                                            )
                                        "
                                        :confirm-title="'Decline session'"
                                        :status="'declined'"
                                    >
                                        <icon name="x"></icon>
                                        Decline
                                    </change-status>
                                </div>
                            </template>
                            <span v-else>
                                Your test results have been submitted for
                                verification.
                            </span>
                        </template>
                    </dd>
                </dl>
                <dl v-if="session.reason">
                    <dt>Reason</dt>
                    <dd>{{ session.reason }}</dd>
                </dl>
            </div>
        </div>
        <div class="empty h-auto" v-if="!testRuns.data.length && !isCompliance">
            <div class="row">
                <div class="col-10 mx-auto">
                    <p class="empty-title h3 mb-3">
                        You have no test runs for this session
                    </p>
                    <p class="empty-subtitle text-muted mb-0">
                        Select any test case in the left menu to get more
                        information about it and make your first test run or
                        click the button below to learn more from our enhanced
                        Tutorial.
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
            <div v-if="isCompliance">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Questionnaire</h2>
                    </div>

                    <div
                        class="card-body"
                        v-for="(section, i) in questionnaire.data"
                        :key="i"
                    >
                        <h4
                            class="dropdown-toggle btn-link mb-0"
                            v-b-toggle="`test-case-section-${section.id}`"
                        >
                            <b>{{ section.name }}</b>
                        </h4>
                        <b-collapse :id="`test-case-section-${section.id}`">
                            <dl class="list-group list-group-flush q-a-list">
                                <div
                                    class="list-group-item"
                                    v-for="(question, i) in section.questions"
                                    :key="i"
                                >
                                    <dt>{{ question.question }}</dt>
                                    <dd
                                        v-for="(
                                            answer, i
                                        ) in question.answersNames"
                                        :key="i"
                                    >
                                        {{ answer }}
                                    </dd>
                                </div>
                            </dl>
                        </b-collapse>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Test runs</h2>
                    </div>

                    <div class="table-responsive mb-0">
                        <table
                            class="table table-striped table-hover card-table"
                        >
                            <thead>
                                <tr>
                                    <th class="text-nowrap w-auto">
                                        Test Case
                                    </th>
                                    <th class="text-nowrap w-auto">Status</th>
                                    <th class="text-nowrap w-auto">Duration</th>
                                    <th class="text-nowrap w-auto">Attempt</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(testCase, i) in session.testCases
                                        .data"
                                    :key="i"
                                >
                                    <td>
                                        <inertia-link
                                            :href="
                                                route(
                                                    'sessions.test-cases.show',
                                                    [session.id, testCase.id]
                                                )
                                            "
                                        >
                                            {{ testCase.name }}
                                        </inertia-link>
                                    </td>
                                    <td>
                                        <span
                                            v-if="
                                                testCase.lastTestRun &&
                                                testCase.lastTestRun
                                                    .completed_at &&
                                                testCase.lastTestRun.successful
                                            "
                                            class="d-flex align-items-center"
                                        >
                                            <span
                                                class="badge bg-success mr-2"
                                            ></span>
                                            <inertia-link
                                                :href="
                                                    route(
                                                        'sessions.test-cases.test-runs.show',
                                                        [
                                                            session.id,
                                                            testCase.id,
                                                            testCase.lastTestRun
                                                                .id,
                                                        ]
                                                    )
                                                "
                                            >
                                                Pass
                                            </inertia-link>
                                        </span>
                                        <span
                                            v-else-if="
                                                testCase.lastTestRun &&
                                                testCase.lastTestRun
                                                    .completed_at &&
                                                !testCase.lastTestRun.successful
                                            "
                                            class="d-flex align-items-center"
                                        >
                                            <span
                                                class="badge bg-danger mr-2"
                                            ></span>
                                            <inertia-link
                                                :href="
                                                    route(
                                                        'sessions.test-cases.test-runs.show',
                                                        [
                                                            session.id,
                                                            testCase.id,
                                                            testCase.lastTestRun
                                                                .id,
                                                        ]
                                                    )
                                                "
                                            >
                                                Fail
                                            </inertia-link>
                                        </span>
                                        <span
                                            v-else
                                            class="d-flex align-items-center"
                                        >
                                            <span
                                                class="badge bg-secondary mr-2"
                                            ></span>
                                            Incomplete
                                        </span>
                                    </td>
                                    <td>
                                        {{
                                            testCase.lastTestRun
                                                ? `${testCase.lastTestRun.duration} ms`
                                                : ''
                                        }}
                                    </td>
                                    <td>
                                        {{ testCase.attemptsCount }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div v-else>
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">
                            <b>Latest test runs</b>
                        </h2>
                    </div>

                    <div class="pt-4 border-bottom">
                        <session-chart :session="session" />
                    </div>

                    <div class="table-responsive mb-0">
                        <table
                            class="table table-striped table-hover card-table"
                        >
                            <thead>
                                <tr>
                                    <th class="text-nowrap w-auto">ID</th>
                                    <th class="text-nowrap w-auto">
                                        Test Case
                                    </th>
                                    <th class="text-nowrap w-auto">Status</th>
                                    <th class="text-nowrap w-auto">Duration</th>
                                    <th class="text-nowrap w-auto">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(testRun, i) in testRuns.data"
                                    :key="i"
                                >
                                    <td>
                                        <inertia-link
                                            :href="
                                                route(
                                                    'sessions.test-cases.test-runs.show',
                                                    [
                                                        testRun.session.id,
                                                        testRun.testCase.id,
                                                        testRun.id,
                                                    ]
                                                )
                                            "
                                        >
                                            #{{ testRun.id }}
                                        </inertia-link>
                                    </td>
                                    <td>
                                        <inertia-link
                                            :href="
                                                route(
                                                    'sessions.test-cases.show',
                                                    [
                                                        testRun.session.id,
                                                        testRun.testCase.id,
                                                    ]
                                                )
                                            "
                                        >
                                            {{ testRun.testCase.name }}
                                        </inertia-link>
                                    </td>
                                    <td>
                                        <span
                                            v-if="
                                                testRun.completed_at &&
                                                testRun.successful
                                            "
                                            class="d-flex align-items-center"
                                        >
                                            <span
                                                class="badge bg-success mr-2"
                                            ></span>
                                            Pass
                                        </span>
                                        <span
                                            v-else-if="
                                                testRun.completed_at &&
                                                !testRun.successful
                                            "
                                            class="d-flex align-items-center"
                                        >
                                            <span
                                                class="badge bg-danger mr-2"
                                            ></span>
                                            Fail
                                        </span>
                                        <span
                                            v-else
                                            class="d-flex align-items-center"
                                        >
                                            <span
                                                class="badge bg-secondary mr-2"
                                            ></span>
                                            Incomplete
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

                        <pagination
                            :meta="testRuns.meta"
                            :links="testRuns.links"
                            class="card-footer"
                        />
                    </div>
                </div>
            </div>
        </template>

        <div class="d-flex justify-content-end" v-if="isCompliance">
            <a
                :href="route('sessions.export', session.id)"
                class="btn btn-outline-primary"
            >
                Export to docx
            </a>
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/sessions/main';
import SessionChart from '@/components/sessions/chart';
import ChangeStatus from '@/components/sessions/change-status';

export default {
    components: {
        Layout,
        SessionChart,
        ChangeStatus,
    },
    props: {
        session: {
            type: Object,
            required: true,
        },
        questionnaire: {
            type: Object,
            required: true,
        },
        useCases: {
            type: Object,
            required: true,
        },
        testRuns: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            isCompliance: this.session.type === 'compliance',
        };
    },
};
</script>
