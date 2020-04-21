<template>
    <layout :scenario="scenario">
        <form method="POST" @submit.prevent="submit">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">Configure components</h3>
                </div>
                <div class="card-body">
                    <p class="mb-0">
                        Please add your custom URL for the component(s) you want
                        to use as SUT and select the existing Simulators for the
                        other Components
                    </p>
                    <div class="mt-4">
                        <div
                            class="form-group mb-3"
                            v-for="component in scenario.components.data"
                            :key="component.id"
                        >
                            <strong class="d-inline-block mb-1">
                                {{ component.name }}
                            </strong>
                            <div class="row">
                                <div class="col-5">
                                    <label
                                        class="form-label font-weight-normal"
                                    >
                                        Type
                                    </label>
                                    <div class="d-flex">
                                        <label
                                            class="form-selectgroup-item w-100 mb-0"
                                        >
                                            <input
                                                type="radio"
                                                :name="
                                                    `components[${component.id}][sut]`
                                                "
                                                value="1"
                                                class="form-selectgroup-input"
                                                @change="
                                                    toggleFieldAvailability
                                                "
                                            />
                                            <span
                                                class="form-selectgroup-label rounded-0 rounded-left"
                                            >
                                                SUT
                                            </span>
                                        </label>
                                        <label
                                            class="form-selectgroup-item w-100 mb-0"
                                        >
                                            <input
                                                type="radio"
                                                :name="
                                                    `components[${component.id}][sut]`
                                                "
                                                value="0"
                                                class="form-selectgroup-input"
                                                checked="true"
                                                @change="
                                                    toggleFieldAvailability
                                                "
                                            />
                                            <span
                                                class="form-selectgroup-label rounded-0 rounded-right"
                                            >
                                                Simulated
                                            </span>
                                        </label>
                                    </div>
                                    <!-- <span
                                        v-if="$page.errors.sut"
                                        class="invalid-feedback"
                                    >
                                        <strong>
                                            {{ $page.errors.sut }}
                                        </strong>
                                    </span> -->
                                </div>
                                <div class="col-7">
                                    <label class="form-label font-weight-normal"
                                        >URL</label
                                    >
                                    <input
                                        class="form-control"
                                        :name="
                                            `components[${component.id}][base_url]`
                                        "
                                        value=""
                                        readonly
                                    />
                                    <!-- <span
                                        v-if="$page.errors.base_url"
                                        class="invalid-feedback"
                                    >
                                        <strong>
                                            {{ $page.errors.base_url }}
                                        </strong>
                                    </span> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <a href="#" class="btn btn-outline-primary">
                    Back
                </a>
                <button type="submit" class="btn btn-primary">
                    Finish
                </button>
            </div>
        </form>
    </layout>
</template>

<script>
import Layout from '@/layouts/sessions/register';

export default {
    components: {
        Layout
    },
    props: {
        scenario: {
            type: Object,
            required: true
        },
        session: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            sending: false,
            form: {}
        };
    },
    methods: {
        toggleFieldAvailability(e) {},
        submit() {
            this.sending = true;

            this.$inertia
                .post(
                    route('sessions.register.config', this.session),
                    this.form
                )
                .then(() => (this.sending = false));
        }
    }
};
</script>
