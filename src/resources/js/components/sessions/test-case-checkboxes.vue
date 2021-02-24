<template>
    <div v-if="useCases.data.length" class="card overflow-auto">
        <ul class="list-group card-body">
            <li
                class="list-group-item border-0 py-0"
                v-for="(useCase, i) in useCases.data"
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
                        @click.prevent="toggleCheckboxes"
                    >
                        <icon name="checkbox" />
                    </button>
                </div>

                <b-collapse :id="`use-case-${useCase.id}`" visible>
                    <ul
                        class="list-group"
                        v-if="
                            collect(useCase.testCases)
                                .where('behavior', 'positive')
                                .count()
                        "
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
                                    @click.prevent="toggleCheckboxes"
                                >
                                    <icon name="checkbox" />
                                </button>
                            </div>

                            <b-collapse
                                :id="`positive-test-cases-${useCase.id}`"
                                visible
                            >
                                <ul class="list-group">
                                    <li
                                        class="list-group-item"
                                        v-for="(testCase, i) in collect(
                                            useCase.testCases
                                        )
                                            .where('behavior', 'positive')
                                            .all()"
                                        :key="i"
                                    >
                                        <label class="form-check mb-0">
                                            <input
                                                :value="testCase.id"
                                                :disabled="isCompliance"
                                                v-model="testCases"
                                                type="checkbox"
                                                class="form-check-input"
                                            />
                                            <span class="form-check-label">
                                                {{ testCase.name }}
                                                <test-case-update
                                                    @update="updateVersion"
                                                    :test-case="testCase"
                                                    :session="session"
                                                    :is-compliance="
                                                        isCompliance
                                                    "
                                                />
                                                <icon
                                                    name="lock"
                                                    v-if="!testCase.public"
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
                        v-if="
                            collect(useCase.testCases)
                                .where('behavior', 'negative')
                                .count()
                        "
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
                                    @click.prevent="toggleCheckboxes"
                                >
                                    <icon name="checkbox" />
                                </button>
                            </div>

                            <b-collapse
                                :id="`negative-test-cases-${useCase.id}`"
                                visible
                            >
                                <ul class="list-group">
                                    <li
                                        class="list-group-item"
                                        v-for="(testCase, i) in collect(
                                            useCase.testCases
                                        )
                                            .where('behavior', 'negative')
                                            .all()"
                                        :key="i"
                                    >
                                        <label class="form-check mb-0">
                                            <input
                                                :value="testCase.id"
                                                :disabled="isCompliance"
                                                v-model="testCases"
                                                type="checkbox"
                                                class="form-check-input"
                                            />
                                            <span class="form-check-label">
                                                {{ testCase.name }}
                                                <test-case-update
                                                    @update="updateVersion"
                                                    :test-case="testCase"
                                                    :session="session"
                                                    :is-compliance="
                                                        isCompliance
                                                    "
                                                />
                                                <icon
                                                    name="lock"
                                                    v-if="!testCase.public"
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
        };
    },
    watch: {
        testCases: {
            immediate: true,
            handler: function (value) {
                this.$emit('input', value);
            },
        },
    },
    methods: {
        toggleCheckboxes(e) {
            const btn = e.target;
            const closestParentList = btn.closest('.list-group-item');
            const checkboxes = Array.from(
                closestParentList.querySelectorAll('input[type="checkbox"]')
            );
            const isChecked = checkboxes.every(
                (checkbox) => checkbox.checked === true
            );

            checkboxes.forEach((checkbox) => {
                checkbox.checked = !isChecked;
                checkbox.dispatchEvent(new Event('change'));
            });
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
    },
};
</script>
