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
                            toggleCheckboxes(
                                useCase.testCases.positive.concat(
                                    useCase.testCases.negative
                                )
                            )
                        "
                    >
                        <icon
                            v-show="
                                isListChecked(
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
                                isListChecked(
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
                                isListChecked(
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
                                        toggleCheckboxes(
                                            useCase.testCases.positive
                                        )
                                    "
                                >
                                    <icon
                                        v-show="
                                            isListChecked(
                                                useCase.testCases.positive,
                                                testCases
                                            ) === 'empty'
                                        "
                                        name="square"
                                    />
                                    <icon
                                        v-show="
                                            isListChecked(
                                                useCase.testCases.positive,
                                                testCases
                                            ) === 'partial'
                                        "
                                        name="square-dot"
                                    />
                                    <icon
                                        v-show="
                                            isListChecked(
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
                                            <input
                                                :checked="
                                                    testCases.includes(
                                                        testCase.id
                                                    )
                                                "
                                                type="checkbox"
                                                class="form-check-input"
                                                :disabled="isCompliance"
                                                @change="
                                                    changeCheckbox(
                                                        testCase.id,
                                                        testCases.includes(
                                                            testCase.id
                                                        )
                                                    )
                                                "
                                            />
                                            <span class="form-check-label">
                                                {{ testCase.name }}
                                                <test-case-update
                                                    :test-case="testCase"
                                                    :session-id="session.id"
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
                                        toggleCheckboxes(
                                            useCase.testCases.negative
                                        )
                                    "
                                >
                                    <icon
                                        v-show="
                                            isListChecked(
                                                useCase.testCases.negative,
                                                testCases
                                            ) === 'empty'
                                        "
                                        name="square"
                                    />
                                    <icon
                                        v-show="
                                            isListChecked(
                                                useCase.testCases.negative,
                                                testCases
                                            ) === 'partial'
                                        "
                                        name="square-dot"
                                    />
                                    <icon
                                        v-show="
                                            isListChecked(
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
                                                :checked="
                                                    testCases.includes(
                                                        testCase.id
                                                    )
                                                "
                                                type="checkbox"
                                                class="form-check-input"
                                                :disabled="isCompliance"
                                                @change="
                                                    changeCheckbox(
                                                        testCase.id,
                                                        testCases.includes(
                                                            testCase.id
                                                        )
                                                    )
                                                "
                                            />
                                            <span class="form-check-label">
                                                {{ testCase.name }}
                                                <test-case-update
                                                    :test-case="testCase"
                                                    :session-id="session.id"
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
                                    </li>
                                </ul>
                            </b-collapse>
                        </li>
                    </ul>
                </b-collapse>
            </li>
        </ul>
    </div>
    <div v-else class="alert alert-danger" role="alert">
        <icon name="alert-circle" class="mr-1" />
        Based on the results of questionnaire, no test cases can be suggested.
        Please revise
        <inertia-link
            :href="route('sessions.register.questionnaire.summary')"
            class="alert-link"
            >your questionnaire answers</inertia-link
        >
        or
        <inertia-link :href="route('sessions.register.type')" class="alert-link"
            >create a Test session</inertia-link
        >
        to select test cases by yourself.
    </div>
</template>
<script>
import TestCaseUpdate from '@/components/sessions/test-case-update';

export default {
    name: 'testCaseCheckboxes',
    components: {
        TestCaseUpdate,
    },
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
            sortUseCases: [],
        };
    },
    watch: {
        testCases: {
            immediate: true,
            handler(value) {
                this.$emit('input', value);
            },
        },
    },
    mounted() {
        this.sortUseCases = this.useCases.data?.map((useCase) => {
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
    methods: {
        toggleCheckboxes(testCases) {
            const testCasesIds = testCases?.map((el) => el.id);
            const allChecked =
                testCasesIds?.filter((id) => this.testCases.includes(id))
                    .length === testCases.length;

            testCasesIds?.forEach((id) => this.changeCheckbox(id, allChecked));
        },
        changeCheckbox(id, remove) {
            if (remove && this.testCases.includes(id)) {
                this.testCases.splice(this.testCases.indexOf(id), 1);
            } else if (!remove && !this.testCases.includes(id)) {
                this.testCases.push(id);
            }
        },
        updateVersion(versions) {
            if (this.testCases.includes(versions.current.id)) {
                this.testCases.splice(
                    [this.testCases.indexOf(versions.current.id)],
                    1,
                    versions.last.id
                );
            }
        },
        isListChecked(customList, checkList) {
            const checkedListLength = customList.filter((el) =>
                checkList.includes(el.id)
            ).length;
            const customListLength = customList.length;

            if (checkedListLength === 0) return 'empty';

            if (checkedListLength > 0 && checkedListLength !== customListLength)
                return 'partial';

            if (checkedListLength === customListLength) return 'full';
        },
    },
};
</script>
