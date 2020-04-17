<template>
    <layout>
        <div class="col-9 mx-auto">
            <form method="POST" @submit.prevent="submit">
                <div class="card">
                    <div class="row">
                        <div class="col-6">
                            <div class="card-header border-0">
                                <h3 class="card-title">Session info</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-2">
                                    <label class="form-label">
                                        Name
                                    </label>
                                    <input
                                        name="name"
                                        type="text"
                                        class="form-control"
                                        v-model="form.name"
                                        :class="{ 'is-invalid': $page.errors.name }"
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
                                <div class="form-group">
                                    <label class="form-label">
                                        Description
                                    </label>
                                    <textarea
                                        name="description"
                                        class="form-control"
                                        rows="5"
                                        v-model="form.description"
                                        :class="{ 'is-invalid': $page.errors.description }"
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
                            <div class="card-header border-0">
                                <h3 class="card-title">Select use cases</h3>
                            </div>
                            <div class="card-body pl-0">
                                <ul
                                    class="list-group overflow-auto"
                                    style="height: 320px;"
                                >
                                    <li
                                        class="list-group-item"
                                        v-for="useCase in $page.useCases.data"
                                        :key="useCase.id"
                                    >
                                        <div class="d-flex align-items-center">
                                            <b
                                                class="dropdown-toggle"
                                                v-b-toggle.use-case-
                                            >
                                                {{ useCase.name }}
                                            </b>
                                            <button
                                                type="button"
                                                class="btn btn-link py-0 font-weight-normal text-decoration-none"
                                            >
                                                <icon name="checkbox" />
                                            </button>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary ml-auto">
                        <span
                            v-if="sending"
                            class="spinner-border spinner-border-sm mr-2"
                        ></span>
                        Next
                    </button>
                </div>
            </form>
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/main';

export default {
    components: {
        Layout
    },
    data() {
        return {
            sending: false,
            form: {
                name: null,
                description: null
            }
        }
    },
    methods: {
        submit() {
            this.sending = true;

            this.$inertia
                .post(route('sessions.register.store'), this.form)
                .then(() => (this.sending = false));
        }
    }
}
</script>
