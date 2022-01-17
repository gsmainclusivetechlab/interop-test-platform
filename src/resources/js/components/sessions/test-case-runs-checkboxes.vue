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
                                    testCases
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
                                    testCases
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
                                    testCases
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
                                    v-show="!isCompliance"
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
                                                testCases
                                            ) === 'empty'
                                        "
                                        name="square"
                                    />
                                    <icon
                                        v-show="
                                            checkCbxList(
                                                useCase.testCases.positive,
                                                testCases
                                            ) === 'partial'
                                        "
                                        name="square-dot"
                                    />
                                    <icon
                                        v-show="
                                            checkCbxList(
                                                useCase.testCases.positive,
                                                testCases
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
                                        <label class="form-check mb-0">
<!--                                            <input-->
<!--                                                type="checkbox"-->
<!--                                                class="form-check-input"-->
<!--                                                :disabled="isCompliance"-->
<!--                                            />-->
                                            <span class="form-check-label">
                                                {{ testCase.name }}
                                                <icon
                                                    v-if="!testCase.public"
                                                    name="lock"
                                                    class="text-muted"
                                                />
                                            </span>
                                        </label>

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
                                                            type="checkbox"
                                                            class="form-check-input"
                                                        />
                                                        <span class="form-check-label">
                                                            #{{ testRun.id }} - {{testRun.completed_at}}
                                                            <span
                                                                v-if="
                                                                testRun.completed_at &&
                                                                testRun.successful
                                                            "
                                                                class="flex-shrink-0 mr-0 ml-1 badge bg-success"
                                                            ></span>
                                                        <span
                                                            v-else-if="
                                                               testRun.completed_at &&
                                                                !testRun.successful
                                                            "
                                                            class="flex-shrink-0 mr-0 ml-1 badge bg-danger"
                                                        ></span>
                                                        <span
                                                            v-else
                                                            class="flex-shrink-0 mr-0 ml-1 badge bg-secondary"
                                                        ></span>
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
                                    v-show="!isCompliance"
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
                                                testCases
                                            ) === 'empty'
                                        "
                                        name="square"
                                    />
                                    <icon
                                        v-show="
                                            checkCbxList(
                                                useCase.testCases.negative,
                                                testCases
                                            ) === 'partial'
                                        "
                                        name="square-dot"
                                    />
                                    <icon
                                        v-show="
                                            checkCbxList(
                                                useCase.testCases.negative,
                                                testCases
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
                                        <label class="form-check mb-0">
                                            <input
                                                type="checkbox"
                                                class="form-check-input"
                                                :disabled="isCompliance"
                                            />
                                            <span class="form-check-label">
                                                {{ testCase.name }}
                                                <test-case-update
                                                    :test-case="testCase"
                                                    :session="session"
                                                    :is-compliance="
                                                        isCompliance
                                                    "
                                                    @update="updateVersion"
                                                />
                                                <icon
                                                    v-if="!testCase.public"
                                                    name="lock"
                                                    class="text-muted"
                                                />
                                            </span>
                                        </label>
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
                                                        type="checkbox"
                                                        class="form-check-input"
                                                    />
                                                    <span class="form-check-label">
                                                            #{{ testRun.id }} - {{testRun.completed_at}}
                                                            <span
                                                                v-if="
                                                                testRun.completed_at &&
                                                                testRun.successful
                                                            "
                                                                class="flex-shrink-0 mr-0 ml-1 badge bg-success"
                                                            ></span>
                                                        <span
                                                            v-else-if="
                                                               testRun.completed_at &&
                                                                !testRun.successful
                                                            "
                                                            class="flex-shrink-0 mr-0 ml-1 badge bg-danger"
                                                        ></span>
                                                        <span
                                                            v-else
                                                            class="flex-shrink-0 mr-0 ml-1 badge bg-secondary"
                                                        ></span>
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
            testCases: this.value,
            sortUseCases: this.updateSortUseCases(),
        };
    },
    watch: {
        testCases: {
            immediate: true,
            handler() {
                this.$emit('input', this.testCases);
            },
        },
    },
    methods: {
        toggleCbxList(tcList) {
            const tcIdList = tcList?.map((el) => el.id);
            const isAllChecked =
                tcIdList?.filter((id) => this.testCases.includes(id)).length ===
                tcList.length;

            tcIdList?.forEach((id) => this.toggleCbx(id, isAllChecked));
        },
        toggleCbx(tcId, remove) {
            if (remove && this.testCases.includes(tcId)) {
                this.testCases.splice(this.testCases.indexOf(tcId), 1);
            } else if (!remove && !this.testCases.includes(tcId)) {
                this.testCases.push(tcId);
            }
        },
        checkCbxList(customList, mainList) {
            const mainListLength = customList?.filter((el) =>
                mainList.includes(el.id)
            ).length;
            const customListLength = customList.length;

            if (mainListLength === 0) return 'empty';

            if (mainListLength > 0 && mainListLength !== customListLength)
                return 'partial';

            if (mainListLength === customListLength) return 'full';
        },
        updateVersion(versions) {
            if (this.testCases.includes(versions.current.id)) {
                this.testCases.splice(
                    this.testCases.indexOf(versions.current.id),
                    1,
                    versions.last.id
                );
            }
            this.sortUseCases = this.updateSortUseCases();
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
