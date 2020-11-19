<template>
    <ul class="list-group overflow-auto">
        <li class="list-group-item" v-for="useCase in useCases.data">
            <div class="d-flex align-items-center">
                <b
                    class="dropdown-toggle"
                    v-b-toggle="`use-case-${useCase.id}`"
                >
                    {{ useCase.name }}
                </b>
                <button
                    v-show="!isCompliance"
                    type="button"
                    class="btn btn-link py-0 font-weight-normal text-decoration-none"
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
                            <span
                                class="d-inline-block dropdown-toggle py-2 font-weight-medium"
                                v-b-toggle="`positive-test-cases-${useCase.id}`"
                            >
                                Happy flow
                            </span>
                            <button
                                v-show="!isCompliance"
                                type="button"
                                class="btn btn-link py-0 font-weight-normal text-decoration-none"
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
                                    v-for="testCase in collect(
                                        useCase.testCases
                                    )
                                        .where('behavior', 'positive')
                                        .all()"
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
                            <span
                                class="d-inline-block dropdown-toggle py-2 font-weight-medium"
                                v-b-toggle="`negative-test-cases-${useCase.id}`"
                            >
                                Unhappy flow
                            </span>
                            <button
                                v-show="!isCompliance"
                                type="button"
                                class="btn btn-link py-0 font-weight-normal text-decoration-none"
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
                                    v-for="testCase in collect(
                                        useCase.testCases
                                    )
                                        .where('behavior', 'negative')
                                        .all()"
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
</template>
<script>
export default {
    props: {
        value: {
            type: Array,
            required: false,
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
    },
};
</script>
