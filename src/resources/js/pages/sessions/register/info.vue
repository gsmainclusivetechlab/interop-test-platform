<template>
    <layout :sut="session.sut" :components="components">
        <form @submit.prevent="submit" class="col-12">
            <div class="card">
                <div class="row">
                    <div class="col-6">
                        <div class="card-header border-0">
                            <h3 class="card-title">Session info</h3>
                        </div>
                        <div class="card-body pt-0">
                            <div class="mb-3">
                                <label class="form-label"> Name </label>
                                <input
                                    name="name"
                                    type="text"
                                    class="form-control"
                                    v-model="form.name"
                                    :class="{
                                        'is-invalid': $page.errors.name,
                                    }"
                                />
                                <span
                                    v-if="$page.errors.name"
                                    class="invalid-feedback"
                                >
                                    <strong>
                                        {{ $page.errors.name }}
                                    </strong>
                                </span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"> Description </label>
                                <textarea
                                    name="description"
                                    class="form-control"
                                    rows="5"
                                    v-model="form.description"
                                    :class="{
                                        'is-invalid': $page.errors.description,
                                    }"
                                ></textarea>
                                <span
                                    v-if="$page.errors.description"
                                    class="invalid-feedback"
                                >
                                    <strong>
                                        {{ $page.errors.description }}
                                    </strong>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card-header pl-0 border-0">
                            <h3 class="card-title">Select use cases</h3>
                        </div>
                        <div class="card-body pt-0 pl-0">
                            <ul
                                class="list-group overflow-auto"
                                style="max-height: 320px"
                            >
                                <li
                                    class="list-group-item"
                                    v-for="useCase in useCases.data"
                                >
                                    <div class="d-flex align-items-center">
                                        <b
                                            class="dropdown-toggle"
                                            v-b-toggle="
                                                `use-case-${useCase.id}`
                                            "
                                        >
                                            {{ useCase.name }}
                                        </b>
                                        <button
                                            type="button"
                                            class="btn btn-link py-0 font-weight-normal text-decoration-none"
                                            @click.prevent="toggleCheckboxes"
                                        >
                                            <icon name="checkbox" />
                                        </button>
                                    </div>

                                    <b-collapse
                                        :id="`use-case-${useCase.id}`"
                                        visible
                                    >
                                        <ul
                                            class="list-group"
                                            v-if="
                                                collect(useCase.testCases)
                                                    .where(
                                                        'behavior',
                                                        'positive'
                                                    )
                                                    .count()
                                            "
                                        >
                                            <li
                                                class="list-group-item border-0 py-0"
                                            >
                                                <div
                                                    class="d-flex align-items-center"
                                                >
                                                    <span
                                                        class="d-inline-block dropdown-toggle py-2 font-weight-medium"
                                                        v-b-toggle="
                                                            `positive-test-cases-${useCase.id}`
                                                        "
                                                    >
                                                        Happy flow
                                                    </span>
                                                    <button
                                                        type="button"
                                                        class="btn btn-link py-0 font-weight-normal text-decoration-none"
                                                        @click.prevent="
                                                            toggleCheckboxes
                                                        "
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
                                                                .where(
                                                                    'behavior',
                                                                    'positive'
                                                                )
                                                                .all()"
                                                        >
                                                            <label
                                                                class="form-check mb-0"
                                                            >
                                                                <input
                                                                    :value="
                                                                        testCase.id
                                                                    "
                                                                    v-model="
                                                                        form.test_cases
                                                                    "
                                                                    type="checkbox"
                                                                    class="form-check-input"
                                                                />
                                                                <span
                                                                    class="form-check-label"
                                                                >
                                                                    {{
                                                                        testCase.name
                                                                    }}
                                                                    <icon
                                                                        name="lock"
                                                                        v-if="
                                                                            !testCase.public
                                                                        "
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
                                                    .where(
                                                        'behavior',
                                                        'negative'
                                                    )
                                                    .count()
                                            "
                                        >
                                            <li
                                                class="list-group-item border-0 py-0"
                                            >
                                                <div
                                                    class="d-flex align-items-center"
                                                >
                                                    <span
                                                        class="d-inline-block dropdown-toggle py-2 font-weight-medium"
                                                        v-b-toggle="
                                                            `negative-test-cases-${useCase.id}`
                                                        "
                                                    >
                                                        Unhappy flow
                                                    </span>
                                                    <button
                                                        type="button"
                                                        class="btn btn-link py-0 font-weight-normal text-decoration-none"
                                                        @click.prevent="
                                                            toggleCheckboxes
                                                        "
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
                                                                .where(
                                                                    'behavior',
                                                                    'negative'
                                                                )
                                                                .all()"
                                                        >
                                                            <label
                                                                class="form-check mb-0"
                                                            >
                                                                <input
                                                                    :value="
                                                                        testCase.id
                                                                    "
                                                                    v-model="
                                                                        form.test_cases
                                                                    "
                                                                    type="checkbox"
                                                                    class="form-check-input"
                                                                />
                                                                <span
                                                                    class="form-check-label"
                                                                >
                                                                    {{
                                                                        testCase.name
                                                                    }}
                                                                    <icon
                                                                        name="lock"
                                                                        v-if="
                                                                            !testCase.public
                                                                        "
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
                            <div
                                class="text-danger small mt-3"
                                v-if="$page.errors.test_cases"
                            >
                                <strong>{{ $page.errors.test_cases }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <inertia-link
                    :href="route('sessions.register.sut')"
                    class="btn btn-outline-primary"
                >
                    Back
                </inertia-link>
                <button type="submit" class="btn btn-primary">
                    <span
                        v-if="sending"
                        class="spinner-border spinner-border-sm mr-2"
                    ></span>
                    Next
                </button>
            </div>
        </form>
    </layout>
</template>

<script>
import Layout from '@/layouts/sessions/register';

export default {
    components: {
        Layout,
    },
    props: {
        session: {
            type: Object,
            required: true,
        },
        components: {
            type: Object,
            required: true,
        },
        useCases: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            sending: false,
            form: {
                name: this.session.info ? this.session.info.name : null,
                description: this.session.info
                    ? this.session.info.description
                    : null,
                test_cases: this.session.info
                    ? this.session.info.test_cases
                    : [],
            },
        };
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
        submit() {
            this.sending = true;
            this.$inertia
                .post(route('sessions.register.info.store'), this.form)
                .then(() => (this.sending = false));
        },
    },
};
</script>
