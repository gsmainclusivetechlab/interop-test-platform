<template>
    <div v-if="sortUseCases.length" class="card overflow-auto">
        <ul class="list-group card-body">
            <li
                class="list-group-item border-0 py-0"
                v-for="(useCase, i) in sortUseCases"
                :key="i"
            >
                <div class="d-flex align-items-center">
                    <button
                        type="button"
                        class="dropdown-toggle btn btn-link d-inline-block text-reset p-0 my-2"
                        v-b-toggle="`use-case-${useCase.id}`"
                    >
                        {{ useCase.name }}
                    </button>
                    <button
                        v-show="!isCompliance"
                        type="button"
                        class="btn btn-link p-0 ml-3"
                        @click.prevent="
                            toggleCbxList(
                                useCase.testCases.positive.concat(
                                    useCase.testCases.negative
                                )
                            )
                        "
                    >
                        <icon
                            v-show="
                                checkCbxList(
                                    useCase.testCases.positive.concat(
                                        useCase.testCases.negative
                                    ),
                                    testRuns
                                ) === 'empty'
                            "
                            name="square"
                        />
                        <icon
                            v-show="
                                checkCbxList(
                                    useCase.testCases.positive.concat(
                                        useCase.testCases.negative
                                    ),
                                    testRuns
                                ) === 'partial'
                            "
                            name="square-dot"
                        />
                        <icon
                            v-show="
                                checkCbxList(
                                    useCase.testCases.positive.concat(
                                        useCase.testCases.negative
                                    ),
                                    testRuns
                                ) === 'full'
                            "
                            name="checkbox"
                        />
                    </button>
                </div>

                <b-collapse :id="`use-case-${useCase.id}`" visible>
                    <ul
                        class="list-group"
                        v-if="useCase.testCases.positive.length"
                    >
                        <li class="list-group-item border-0 py-0">
                            <div class="d-flex align-items-center">
                                <button
                                    type="button"
                                    class="dropdown-toggle btn btn-link d-inline-block text-reset p-0 my-2"
                                    v-b-toggle="
                                        `positive-test-cases-${useCase.id}`
                                    "
                                >
                                    Happy flow
                                </button>
                                <button
                                    type="button"
                                    class="btn btn-link p-0 ml-3"
                                    @click.prevent="
                                        toggleCbxList(
                                            useCase.testCases.positive
                                        )
                                    "
                                >
                                    <icon
                                        v-show="
                                            checkCbxList(
                                                useCase.testCases.positive,
                                                testRuns
                                            ) === 'empty'
                                        "
                                        name="square"
                                    />
                                    <icon
                                        v-show="
                                            checkCbxList(
                                                useCase.testCases.positive,
                                                testRuns
                                            ) === 'partial'
                                        "
                                        name="square-dot"
                                    />
                                    <icon
                                        v-show="
                                            checkCbxList(
                                                useCase.testCases.positive,
                                                testRuns
                                            ) === 'full'
                                        "
                                        name="checkbox"
                                    />
                                </button>
                            </div>

                            <b-collapse
                                :id="`positive-test-cases-${useCase.id}`"
                                visible
                            >
                                <ul class="list-group">
                                    <li
                                        v-for="(testCase, i) in useCase
                                            .testCases.positive"
                                        class="list-group-item"
                                        :key="`${useCase.id}-${testCase.id}-${i}`"
                                    >
                                        <div class="d-flex align-items-center">
                                            <button
                                                type="button"
                                                class="dropdown-toggle btn btn-link d-inline-block text-reset p-0 my-2"
                                                v-b-toggle="
                                                `positive-test-cases-test-runs-${testCase.id}`
                                            "
                                            >
                                                {{ testCase.name }}
                                            </button>
                                            <button
                                                type="button"
                                                class="btn btn-link p-0 ml-3"
                                                @click.prevent="
                                                    toggleCbxList(
                                                        [testCase]
                                                    )
                                                "
                                            >
                                                <icon
                                                    v-show="
                                                        checkCbxList(
                                                            [testCase],
                                                            testRuns
                                                        ) === 'empty'
                                                    "
                                                    name="square"
                                                />
                                                <icon
                                                    v-show="
                                                        checkCbxList(
                                                            [testCase],
                                                            testRuns
                                                        ) === 'partial'
                                                    "
                                                    name="square-dot"
                                                />
                                                <icon
                                                    v-show="
                                                        checkCbxList(
                                                            [testCase],
                                                            testRuns
                                                        ) === 'full'
                                                    "
                                                    name="checkbox"
                                                />
                                            </button>
                                        </div>

                                        <b-collapse
                                            :id="`positive-test-cases-test-runs-${testCase.id}`"
                                            visible
                                        >
                                            <ul class="list-group">
                                                <li
                                                    v-for="(testRun, j) in testCase.testRuns"
                                                    class="list-group-item"
                                                    :key="`${useCase.id}-${testCase.id}-${i}-${testRun.id}-${j}`"
                                                >
                                                    <label class="form-check mb-0">
                                                        <input
                                                            :checked="
                                                                testRuns.includes(
                                                                    testRun.id
                                                                )
                                                            "
                                                            type="checkbox"
                                                            class="form-check-input"
                                                            @change="
                                                                toggleCbx(
                                                                    testRun.id,
                                                                    testRuns.includes(
                                                                        testRun.id
                                                                    )
                                                                )
                                                            "
                                                        />
                                                        <span class="form-check-label align-items-center">
                                                            #Run {{ testRun.id }} - {{ testRun.completed_at }}
                                                        </span>
                                                        <span
                                                            v-if="
                                                                    testRun.completed_at &&
                                                                    testRun.successful
                                                                "
                                                            class="d-inline-flex flex-shrink-0 align-items-center"
                                                        >
                                                            <span class="badge bg-success mr-2"></span>
                                                            Pass
                                                        </span>
                                                        <span
                                                            v-else-if="
                                                                testRun.completed_at &&
                                                                !testRun.successful
                                                            "
                                                            class="d-inline-flex flex-shrink-0 align-items-center"
                                                        >
                                                            <span class="badge bg-danger mr-2"></span>
                                                            Fail
                                                        </span>
                                                        <span
                                                            v-else
                                                            class="d-inline-flex flex-shrink-0 align-items-center"
                                                        >
                                                            <span class="badge bg-secondary mr-2"></span>
                                                            Incomplete
                                                        </span>
                                                    </label>
                                                </li>
                                            </ul>
                                        </b-collapse>
                                    </li>
                                </ul>
                            </b-collapse>
                        </li>
                    </ul>

                    <ul
                        class="list-group"
                        v-if="useCase.testCases.negative.length"
                    >
                        <li class="list-group-item border-0 py-0">
                            <div class="d-flex align-items-center">
                                <button
                                    type="button"
                                    class="dropdown-toggle btn btn-link d-inline-block text-reset p-0 my-2"
                                    v-b-toggle="
                                        `negative-test-cases-${useCase.id}`
                                    "
                                >
                                    Unhappy flow
                                </button>
                                <button
                                    type="button"
                                    class="btn btn-link p-0 ml-3"
                                    @click.prevent="
                                        toggleCbxList(
                                            useCase.testCases.negative
                                        )
                                    "
                                >
                                    <icon
                                        v-show="
                                            checkCbxList(
                                                useCase.testCases.negative,
                                                testRuns
                                            ) === 'empty'
                                        "
                                        name="square"
                                    />
                                    <icon
                                        v-show="
                                            checkCbxList(
                                                useCase.testCases.negative,
                                                testRuns
                                            ) === 'partial'
                                        "
                                        name="square-dot"
                                    />
                                    <icon
                                        v-show="
                                            checkCbxList(
                                                useCase.testCases.negative,
                                                testRuns
                                            ) === 'full'
                                        "
                                        name="checkbox"
                                    />
                                </button>
                            </div>

                            <b-collapse
                                :id="`negative-test-cases-${useCase.id}`"
                                visible
                            >
                                <ul class="list-group">
                                    <li
                                        v-for="(testCase, i) in useCase
                                            .testCases.negative"
                                        class="list-group-item"
                                        :key="`${useCase.id}-${testCase.id}-${i}`"
                                    >

                                        <div class="d-flex align-items-center">
                                            <button
                                                type="button"
                                                class="dropdown-toggle btn btn-link d-inline-block text-reset p-0 my-2"
                                                v-b-toggle="
                                                `negative-test-cases-test-runs-${testCase.id}`
                                            "
                                            >
                                                {{ testCase.name }}
                                            </button>
                                            <button
                                                type="button"
                                                class="btn btn-link p-0 ml-3"
                                                @click.prevent="
                                                    toggleCbxList(
                                                        [testCase]
                                                    )
                                                "
                                            >
                                                <icon
                                                    v-show="
                                                        checkCbxList(
                                                            [testCase],
                                                            testRuns
                                                        ) === 'empty'
                                                    "
                                                    name="square"
                                                />
                                                <icon
                                                    v-show="
                                                        checkCbxList(
                                                            [testCase],
                                                            testRuns
                                                        ) === 'partial'
                                                    "
                                                    name="square-dot"
                                                />
                                                <icon
                                                    v-show="
                                                        checkCbxList(
                                                            [testCase],
                                                            testRuns
                                                        ) === 'full'
                                                    "
                                                    name="checkbox"
                                                />
                                            </button>
                                        </div>
                                        <b-collapse
                                            :id="`negative-test-cases-test-runs-${testCase.id}`"
                                            visible
                                        >
                                            <ul class="list-group">
                                                <li
                                                    v-for="(testRun, j) in testCase.testRuns"
                                                    class="list-group-item"
                                                    :key="`${useCase.id}-${testCase.id}-${i}-${testRun.id}-${j}`"
                                                >
                                                    <label class="form-check mb-0">
                                                        <input
                                                            :checked="
                                                                testRuns.includes(
                                                                    testRun.id
                                                                )
                                                            "
                                                            type="checkbox"
                                                            class="form-check-input"
                                                            @change="
                                                                toggleCbx(
                                                                    testRun.id,
                                                                    testRuns.includes(
                                                                        testRun.id
                                                                    )
                                                                )
                                                            "
                                                        />
                                                        <span class="form-check-label align-items-center">
                                                            #Run {{ testRun.id }} - {{ testRun.completed_at }}
                                                        </span>
                                                        <span
                                                            v-if="
                                                                    testRun.completed_at &&
                                                                    testRun.successful
                                                                "
                                                            class="d-inline-flex flex-shrink-0 align-items-center"
                                                        >
                                                            <span class="badge bg-success mr-2"></span>
                                                            Pass
                                                        </span>
                                                        <span
                                                            v-else-if="
                                                                testRun.completed_at &&
                                                                !testRun.successful
                                                            "
                                                            class="d-inline-flex flex-shrink-0 align-items-center"
                                                        >
                                                            <span class="badge bg-danger mr-2"></span>
                                                            Fail
                                                        </span>
                                                        <span
                                                            v-else
                                                            class="d-inline-flex flex-shrink-0 align-items-center"
                                                        >
                                                            <span class="badge bg-secondary mr-2"></span>
                                                            Incomplete
                                                        </span>
                                                    </label>
                                                </li>
                                            </ul>
                                        </b-collapse>
                                    </li>
                                </ul>
                            </b-collapse>
                        </li>
                    </ul>
                </b-collapse>
            </li>
        </ul>
    </div>
</template>
<script>

export default {
    name: 'testCaseRunsCheckboxes',
    props: {
        value: {
            type: Array,
            required: false,
        },
        session: {
            type: Object,
            required: true,
        },
        useCases: {
            type: Object,
            required: true,
        },
        isCompliance: {
            type: Boolean,
            required: true,
        },
    },
    data() {
        return {
            testRuns: [],
            sortUseCases: this.updateSortUseCases(),
        };
    },
    watch: {
        testRuns: {
            immediate: true,
            handler() {
                this.$emit('input', this.testRuns);
            },
        },
    },
    methods: {
        toggleCbxList(tcList) {
            const tcIdList = [];
            tcList?.forEach((testCase) => {
                testCase.testRuns?.forEach((testRun) => {
                    tcIdList.push(testRun.id);
                })
            });
            const isAllChecked =
                tcIdList?.filter((id) => this.testRuns.includes(id)).length ===
                tcIdList.length;
            tcIdList?.forEach((id) => this.toggleCbx(id, isAllChecked));
        },
        toggleCbx(tcId, remove) {
            if (remove && this.testRuns.includes(tcId)) {
                this.testRuns.splice(this.testRuns.indexOf(tcId), 1);
            } else if (!remove && !this.testRuns.includes(tcId)) {
                this.testRuns.push(tcId);
            }
        },
        checkCbxList(customList, mainList) {
            const testRuns = [];
            customList?.forEach((testCase) => testCase.testRuns?.forEach((testRun) => testRuns.push(testRun)));
            const mainListLength = testRuns?.filter((el) =>
                mainList.includes(el.id)
            ).length;
            const customListLength = testRuns.length;

            if (mainListLength === 0) return 'empty';

            if (mainListLength > 0 && mainListLength !== customListLength)
                return 'partial';

            if (mainListLength === customListLength) return 'full';
        },
        updateSortUseCases() {
            return this.useCases.data?.map((useCase) => {
                const sortUseCase = {
                    can: useCase.can,
                    description: useCase.description,
                    id: useCase.id,
                    name: useCase.name,
                    testCasesCount: useCase.testCasesCount,
                    testCases: {
                        positive: [],
                        negative: [],
                    },
                };

                useCase.testCases?.forEach((testCase) => {
                    if (testCase.behavior === 'positive') {
                        sortUseCase.testCases.positive.push(testCase);
                    }
                    if (testCase.behavior === 'negative') {
                        sortUseCase.testCases.negative.push(testCase);
                    }
                });

                return sortUseCase;
            });
        },
    },
};
</script>
