<template>
    <layout>
        <div class="flex-fill d-flex flex-column justify-content-center">
            <div class="page-header">
                <h1 class="page-title text-center">
                    <b>Create new session</b>
                </h1>
            </div>
            <div class="container">
                <div class="row">
                    <form @submit.prevent="submit" class="col-8 m-auto">
                        <div class="card">
                            <div class="card-header border-0">
                                <h3 class="card-title">
                                    Session type selection
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label" for="types"
                                        >Type</label
                                    >
                                    <selectize
                                        v-model="type"
                                        :class="{
                                            'is-invalid': $page.errors.type,
                                        }"
                                        :options="types"
                                        :createItem="false"
                                        class="form-select"
                                        placeholder="Select Type..."
                                    />
                                    <span
                                        v-if="$page.errors.type"
                                        class="invalid-feedback"
                                    >
                                        {{ $page.errors.type }}
                                    </span>
                                </div>
                                <div class="mb-3">
                                    <p>
                                        <icon name="chevron-right" /><b>Test</b>
                                        is a common session where you can select
                                        any number of test cases, execute them
                                        any times, and change the chosen set of
                                        test cases any time.
                                    </p>
                                    <p>
                                        <icon name="chevron-right" /><b
                                            >Complience</b
                                        >
                                        is a specific type of session that is
                                        being validated by the platfrom admins.
                                        In contrast to a Test session you wan't
                                        be able to choose test cases manually
                                        and they will be assigned to you
                                        automatically after passing a
                                        questionnaire. Then you'll have a
                                        limited number of {nubmber for env.}
                                        attempts to execute any test case before
                                        sending our session for admin's review.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button
                                type="submit"
                                class="btn btn-primary ml-auto"
                            >
                                <span
                                    v-if="sending"
                                    class="spinner-border spinner-border-sm mr-2"
                                ></span>
                                Next
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/main';

export default {
    components: {
        Layout,
    },
    props: {
        session: {
            type: Object,
            required: false,
        },
        types: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            sending: false,
            type:
                this.session && this.session.type
                    ? collect(this.types).where('id', this.session.type).first()
                    : null,
            form: {
                type: null,
            },
        };
    },
    watch: {
        type: {
            immediate: true,
            handler: function (value) {
                this.form.type = value ? value.id : null;
            },
        },
    },
    methods: {
        submit() {
            this.sending = true;
            this.$inertia
                .post(route('sessions.register.type.store'), this.form)
                .then(() => (this.sending = false));
        },
    },
};
</script>
