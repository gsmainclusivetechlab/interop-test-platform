<template>
    <layout :session="session">
        <div class="col-3 mt-3 pr-0">
            <div class="card mb-0">
                <button
                    type="button"
                    class="btn btn-primary m-3"
                    v-b-modal="'execute-all-modal'"
                >
                    <icon name="bike"></icon>
                    Execute All
                </button>
                <b-modal
                    id="execute-all-modal"
                    :title="`Execute all Test Cases`"
                    ok-title="Execute"
                    centered
                    @ok="submit"
                    :ok-disabled="!isExecuteAll"
                >
                    <p>
                        The <b>total number</b> of Test Cases in this session is
                        {{ session.testCasesCount }}.
                    </p>
                    <p>
                        The number of Test Cases that
                        <b>will be executed automatically</b> is
                        {{
                            this.session
                                .testCasesExecuteAvailableWithoutSutInitiator
                                .length
                        }}:
                    </p>
                    <ol>
                        <li
                            v-for="testCasesExecuteAvailableWithoutSutInitiator in session.testCasesExecuteAvailableWithoutSutInitiator"
                            :key="
                                testCasesExecuteAvailableWithoutSutInitiator.id
                            "
                        >
                            {{
                                testCasesExecuteAvailableWithoutSutInitiator.name
                            }}
                        </li>
                    </ol>
                    <p v-if="0 < session.testCasesReachedLimit.length">
                        Please note that
                        {{ session.testCasesReachedLimit.length }} Test Case(s)
                        <b>reached execution limits:</b>
                    </p>
                    <ol>
                        <li
                            v-for="testCasesReachedLimit in session.testCasesReachedLimit"
                            :key="testCasesReachedLimit.id"
                        >
                            {{ testCasesReachedLimit.name }}
                        </li>
                    </ol>
                    <p
                        v-if="
                            0 <
                            session.testCasesExecuteAvailableWithSutInitiator
                                .length
                        "
                    >
                        Please note that session's SUT(s) are initiators of
                        {{
                            session.testCasesExecuteAvailableWithSutInitiator
                                .length
                        }}
                        Test Cases, so these Test Cases
                        <b>should be executed manually</b>:
                    </p>
                    <ol>
                        <li
                            v-for="testCaseWithFirstSut in session.testCasesExecuteAvailableWithSutInitiator"
                            :key="testCaseWithFirstSut.id"
                        >
                            {{ testCaseWithFirstSut.name }}
                        </li>
                    </ol>
                </b-modal>
                <div class="card-header px-3">
                    <h3 class="card-title">Select use cases</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="list-unstyled">
                        <li v-for="useCase in useCases.data" :key="useCase.id">
                            <b
                                class="d-block dropdown-toggle py-2 px-3 border-bottom font-weight-bold"
                                v-b-toggle="`use-case-${useCase.id}`"
                            >
                                {{ useCase.name }}
                            </b>
                            <b-collapse :id="`use-case-${useCase.id}`" visible>
                                <ul class="list-unstyled">
                                    <template
                                        v-for="scenario in scenarios.data"
                                    >
                                        <li
                                            v-if="
                                                scenario.use_case_id ==
                                                useCase.id
                                            "
                                            :key="scenario.id"
                                        >
                                            <b
                                                class="d-block dropdown-toggle pl-4 py-2 px-3 border-bottom"
                                                v-b-toggle="
                                                    `scenario-${scenario.id}`
                                                "
                                            >
                                                {{ scenario.name }}
                                            </b>
                                            <b-collapse
                                                :id="`scenario-${scenario.id}`"
                                                visible
                                                v-if="
                                                    collect(scenario.testCases)
                                                        .where(
                                                            'behavior',
                                                            'positive'
                                                        )
                                                        .count()
                                                "
                                            >
                                                <ul class="list-unstyled">
                                                    <li>
                                                        <span
                                                            class="dropdown-toggle d-block pl-5 pr-3 py-2 font-weight-medium border-bottom"
                                                            v-b-toggle="
                                                                `positive-test-cases-${scenario.id}`
                                                            "
                                                        >
                                                            Happy flow
                                                        </span>
                                                        <b-collapse
                                                            :id="`positive-test-cases-${scenario.id}`"
                                                            visible
                                                        >
                                                            <ul
                                                                class="list-unstyled"
                                                            >
                                                                <li
                                                                    v-for="positiveTestCase in collect(
                                                                        scenario.testCases
                                                                    )
                                                                        .where(
                                                                            'behavior',
                                                                            'positive'
                                                                        )
                                                                        .all()"
                                                                    :key="
                                                                        positiveTestCase.id
                                                                    "
                                                                    class="list-group-item-action border-bottom"
                                                                    :class="{
                                                                        'bg-body':
                                                                            testCase !==
                                                                                undefined &&
                                                                            positiveTestCase.id ===
                                                                                testCase.id,
                                                                    }"
                                                                >
                                                                    <inertia-link
                                                                        :href="
                                                                            route(
                                                                                'sessions.test-cases.show',
                                                                                [
                                                                                    session.id,
                                                                                    positiveTestCase.id,
                                                                                ]
                                                                            )
                                                                        "
                                                                        class="d-flex justify-content-between align-items-center pl-6 pr-4 py-2"
                                                                    >
                                                                        <span>
                                                                            {{
                                                                                positiveTestCase.name
                                                                            }}
                                                                        </span>

                                                                        <span
                                                                            v-if="
                                                                                positiveTestCase.lastTestRun &&
                                                                                positiveTestCase
                                                                                    .lastTestRun
                                                                                    .completed_at &&
                                                                                positiveTestCase
                                                                                    .lastTestRun
                                                                                    .successful
                                                                            "
                                                                            class="flex-shrink-0 mr-0 ml-1 badge bg-success"
                                                                        ></span>
                                                                        <span
                                                                            v-else-if="
                                                                                positiveTestCase.lastTestRun &&
                                                                                positiveTestCase
                                                                                    .lastTestRun
                                                                                    .completed_at &&
                                                                                !positiveTestCase
                                                                                    .lastTestRun
                                                                                    .successful
                                                                            "
                                                                            class="flex-shrink-0 mr-0 ml-1 badge bg-danger"
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
                                                :id="`scenario-${scenario.id}`"
                                                visible
                                                v-if="
                                                    collect(scenario.testCases)
                                                        .where(
                                                            'behavior',
                                                            'negative'
                                                        )
                                                        .count()
                                                "
                                            >
                                                <ul class="list-unstyled">
                                                    <li>
                                                        <span
                                                            class="dropdown-toggle d-block pl-5 pr-3 py-2 font-weight-medium border-bottom"
                                                            v-b-toggle="
                                                                `negative-test-cases-${scenario.id}`
                                                            "
                                                        >
                                                            Unhappy flow
                                                        </span>
                                                        <b-collapse
                                                            :id="`negative-test-cases-${scenario.id}`"
                                                            visible
                                                        >
                                                            <ul
                                                                class="list-unstyled"
                                                            >
                                                                <li
                                                                    v-for="negativeTestCase in collect(
                                                                        scenario.testCases
                                                                    )
                                                                        .where(
                                                                            'behavior',
                                                                            'negative'
                                                                        )
                                                                        .all()"
                                                                    :key="
                                                                        negativeTestCase.id
                                                                    "
                                                                    class="list-group-item-action border-bottom"
                                                                    :class="{
                                                                        'bg-body':
                                                                            testCase !==
                                                                                undefined &&
                                                                            negativeTestCase.id ===
                                                                                testCase.id,
                                                                    }"
                                                                >
                                                                    <inertia-link
                                                                        :href="
                                                                            route(
                                                                                'sessions.test-cases.show',
                                                                                [
                                                                                    session.id,
                                                                                    negativeTestCase.id,
                                                                                ]
                                                                            )
                                                                        "
                                                                        class="d-flex justify-content-between align-items-center pl-6 pr-4 py-2"
                                                                    >
                                                                        <span>
                                                                            {{
                                                                                negativeTestCase.name
                                                                            }}
                                                                        </span>

                                                                        <span
                                                                            v-if="
                                                                                negativeTestCase.lastTestRun &&
                                                                                negativeTestCase
                                                                                    .lastTestRun
                                                                                    .completed_at &&
                                                                                negativeTestCase
                                                                                    .lastTestRun
                                                                                    .successful
                                                                            "
                                                                            class="flex-shrink-0 mr-0 ml-1 badge bg-success"
                                                                        ></span>
                                                                        <span
                                                                            v-else-if="
                                                                                negativeTestCase.lastTestRun &&
                                                                                negativeTestCase
                                                                                    .lastTestRun
                                                                                    .completed_at &&
                                                                                !negativeTestCase
                                                                                    .lastTestRun
                                                                                    .successful
                                                                            "
                                                                            class="flex-shrink-0 mr-0 ml-1 badge bg-danger"
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
                                    </template>
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
                required: false,
            },
            useCases: {
                type: Object,
                required: true,
            },
            scenarios: {
                type: Object,
                required: true,
            },
        },
        methods: {
            submit() {
                this.sending = true;
                this.$inertia
                    .post(route('sessions.test-cases.run-all', this.session))
                    .then(() => (this.sending = false));
            },
        },
        data() {
            return {
                isExecuteAll:
                    0 <
                    this.session.testCasesExecuteAvailableWithoutSutInitiator
                        .length,
            };
        },
    };
</script>
