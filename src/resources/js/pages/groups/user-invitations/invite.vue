<template>
    <layout>
        <div class="flex-fill d-flex flex-column justify-content-center">
            <div class="page-header" v-once>
                <h1 class="page-title text-center">
                    <b>Add new user to "{{ group.name }}"</b>
                </h1>
            </div>
            <div class="container">
                <div class="col-8 m-auto">
                    <form class="card" @submit.prevent="submit">
                        <div class="card-body">
                            <label class="form-label">New user</label>
                            <input
                                v-model.trim="form.userEmail"
                                @blur="checkEmail"
                                class="form-control"
                                :class="{
                                    'is-invalid':
                                        $page.errors.user_email || !emailValid,
                                }"
                                type="email"
                            />
                            <span
                                v-if="$page.errors.user_email"
                                class="invalid-feedback"
                            >
                                <strong>
                                    {{ $page.errors.user_email }}
                                </strong>
                            </span>
                            <span v-if="!emailValid" class="invalid-feedback">
                                <strong>
                                    Please input correct email address matches
                                    {{ group.domain }}
                                </strong>
                            </span>
                            <p class="text-muted small mt-3" v-once>
                                You can invite users email address matches
                                {{ group.domain }}
                            </p>
                            <p class="text-muted small">
                                User is already registered? Add them to the
                                {{ group.name }}
                                <a
                                    :href="
                                        route('groups.users.create', group.id)
                                    "
                                    >here</a
                                >
                            </p>
                        </div>
                        <div class="card-footer text-right">
                            <inertia-link
                                :href="
                                    route(
                                        'groups.user-invitations.index',
                                        group.id
                                    )
                                "
                                class="btn btn-link mr-1"
                                v-once
                            >
                                Cancel
                            </inertia-link>
                            <button type="submit" class="btn btn-primary">
                                <span
                                    v-if="sending"
                                    class="spinner-border spinner-border-sm mr-2"
                                ></span>
                                Invite
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <b-modal
            :id="`modal-response-${group.name}`"
            size="lg"
            centered
            hide-footer
            title="Are you sure?"
        >
            <p>
                An invitation code and registration instructions will be sent to
                <b>{{ form.userEmail }}</b>
            </p>
            <div class="text-right">
                <button
                    type="button"
                    @click.prevent="hideModal"
                    class="btn btn-link mr-1"
                >
                    Cancel
                </button>
                <button
                    type="button"
                    @click.prevent="inviteNewUser"
                    class="btn btn-primary"
                >
                    Ok
                </button>
            </div>
        </b-modal>
    </layout>
</template>

<script>
import Layout from '@/layouts/main';

export default {
    metaInfo() {
        return {
            title: `Invite new user to ${this.group.name}`,
        };
    },
    components: {
        Layout,
    },
    props: {
        group: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            form: {
                userEmail: '',
            },
            emailPatterns: this.group.domain.replace(/@/g, '').split(', '),
            emailValid: true,
            formSending: false,
        };
    },
    methods: {
        inviteNewUser() {
            this.sending = true;

            this.hideModal();
            this.$inertia
                .post(route('groups.user-invitations.store', this.group), {
                    user_email: this.form.userEmail,
                })
                .then(() => {
                    this.sending = false;
                });
        },
        checkEmail() {
            for (let i = 0; i < this.emailPatterns.length; i++) {
                if (
                    this.form.userEmail.includes(this.emailPatterns[i]) &&
                    this.form.userEmail.split('@')[0]?.length > 0 &&
                    this.form.userEmail.split('@')[1]?.length ===
                        this.emailPatterns[i].length
                ) {
                    this.emailValid = true;

                    return;
                }
            }

            this.emailValid = false;
        },
        showModal() {
            this.$bvModal.show(`modal-response-${this.group.name}`);
        },
        hideModal() {
            this.$bvModal.hide(`modal-response-${this.group.name}`);
        },
        submit() {
            this.checkEmail();

            if (this.emailValid) {
                this.showModal();
            }
        },
    },
};
</script>
