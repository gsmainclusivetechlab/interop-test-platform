<template>
    <layout :components="components">
        <form @submit.prevent="submit" class="col-8 m-auto">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">Configure components</h3>
                </div>
                <div class="card-body">
                    <div v-for="connection in sut.connections.data">
                        <div class="mb-3">
                            <label class="form-label">
                                {{ connection.name }}
                            </label>
                            <div class="input-group">
                                <input
                                    :id="`testing-${connection.id}`"
                                    type="text"
                                    :value="route('testing.sut', [session.info.uuid, sut.uuid, connection.uuid])"
                                    class="form-control"
                                    readonly
                                />
                                <clipboard-copy-btn
                                    :target="`#testing-${connection.id}`"
                                    title="Copy"
                                ></clipboard-copy-btn>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <inertia-link
                    :href="route('sessions.register.info')"
                    class="btn btn-outline-primary"
                >
                    Back
                </inertia-link>
                <button type="submit" class="btn btn-primary">
                    <span
                        v-if="sending"
                        class="spinner-border spinner-border-sm mr-2"
                    ></span>
                    Confirm
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
        session: {
            type: Object,
            required: true
        },
        sut: {
            type: Object,
            required: true
        },
        components: {
            type: Object,
            required: true
        },
    },
    data() {
        return {
            sending: false,
            form: {},
        };
    },
    methods: {
        submit() {
            this.sending = true;
            this.$inertia
                .post(route('sessions.register.config.store'), this.form)
                .then(() => (this.sending = false));
        }
    }
};
</script>
