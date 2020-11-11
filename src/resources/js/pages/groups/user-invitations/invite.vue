<template>
    <layout>
        <div class="flex-fill d-flex flex-column justify-content-center">
            <div class="page-header" v-once>
                <h1 class="page-title text-center">
                    <b>{{ `Invite new user to "${group.name}"` }}</b>
                </h1>
            </div>
            <div class="container">
                <div class="col-8 m-auto">
                    <form class="card" @submit.prevent="formSubmit">
                        <div class="card-body">
                            <label class="form-label">New user</label>
                            <input
                                v-model.trim="newUser.userEmail"
                                @blur="checkEmail"
                                class="form-control"
                                :class="{
                                    'is-invalid':
                                        $page.errors.user_email || !newUser.emailValid,
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
                            <span v-if="!newUser.emailValid" class="invalid-feedback">
                                <strong>
                                    Please input correct email address matches {{ group.domain }}
                                </strong>
                            </span>
                            <p class="text-muted small mt-3" v-once>
                                You can invite users email address
                                matches {{ group.domain }}
                            </p>
                            <p class="text-muted small">
                                User is already registered? Add them to the {{ group.name }}
                                <a
                                    :href="route('groups.users.create', group.id)"
                                    >here</a
                                >
                            </p>
                        </div>
                        <div class="card-footer text-right">
                            <inertia-link
                                :href="route('groups.user-invitations.index', group.id)"
                                class="btn btn-link mr-1"
                                v-once
                            >
                                Cancel
                            </inertia-link>
                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                <span
                                    v-if="newUser.formSending"
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
            <p>An invitation code and registration instructions will be sent to <b>{{ newUser.userEmail }}</b></p>
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
            newUser: {
                formSending: false,
                userEmail: '',
                emailValid: true,
            },
            emailPatterns: this.group.domain.replace(/@/g, '').split(', '),
        };
    },
    methods: {
        inviteNewUser() {
            this.newUser.formSending = true;

            this.hideModal();
            this.$inertia
                .post(
                    route('groups.user-invitations.store', this.group),
                    {
                        user_email: this.newUser.userEmail,
                    })
                .then(() => {
                    this.newUser.formSending = false;
                });
        },
        checkEmail() {
            for (let i = 0; i < this.emailPatterns.length; i++) {
                if (
                    this.newUser.userEmail.includes(this.emailPatterns[i]) &&
                    this.newUser.userEmail.split('@')[0]?.length > 0 &&
                    this.newUser.userEmail.split('@')[1]?.length ===
                        this.emailPatterns[i].length
                ) {
                    this.newUser.emailValid = true;

                    return;
                }
            }

            this.newUser.emailValid = false;
        },
        showModal() {
            this.$bvModal.show(`modal-response-${this.group.name}`);
        },
        hideModal() {
            this.$bvModal.hide(`modal-response-${this.group.name}`);
        },
        formSubmit() {
            this.checkEmail();

            if(this.newUser.emailValid) {
                this.showModal();
            }
        },
    },
};
</script>
