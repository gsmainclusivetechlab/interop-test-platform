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
                    <form class="card" @submit.prevent="showModal">
                        <div class="card-body">
                            <label class="form-label">New user</label>
                            <input
                                v-model.trim="newUser.user_email"
                                class="form-control"
                                :class="{
                                    'is-invalid':
                                        $page.errors.user_email || !checkEmail,
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
                            <span v-if="!checkEmail" class="invalid-feedback">
                                <strong>
                                    Please input correct email address matches {{ group.domain }}
                                </strong>
                            </span>
                            <p class="text-muted small mt-3">
                                If you want invite registered user, please
                                follow to
                                <a
                                    :href="route('groups.users.create', group.id)"
                                    >this link</a
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
                                :disabled="!checkEmail"
                                class="btn btn-primary"
                            >
                                <span
                                    v-if="newUser.sending"
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
            title="Invite new user"
        >
            <label class="form-label">Email: {{ newUser.user_email }}</label>
            <p>Are you sure to invite a new user by this email address?</p>
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
                sending: false,
                user_email: '',
            },
            emailPatterns: this.group.domain.replace('@', '').split(', '),
        };
    },
    methods: {
        inviteNewUser() {
            this.newUser.sending = true;

            this.hideModal();
            this.$inertia
                .post(
                    route('groups.user-invitations.store', this.group),
                    this.newUser
                )
                .then(() => {
                    this.newUser.sending = false;
                });
        },
        showModal() {
            this.$bvModal.show(`modal-response-${this.group.name}`);
        },
        hideModal() {
            this.$bvModal.hide(`modal-response-${this.group.name}`);
        },
    },
    computed: {
        checkEmail() {
            for (let i = 0; i < this.emailPatterns.length; i++) {
                if (
                    this.newUser.user_email.includes(this.emailPatterns[i]) &&
                    this.newUser.user_email.split('@')[0]?.length > 0 &&
                    this.newUser.user_email.split('@')[1]?.length ===
                        this.emailPatterns[i].length
                ) {
                    return true;
                }
            }

            return false;
        },
    },
};
</script>
