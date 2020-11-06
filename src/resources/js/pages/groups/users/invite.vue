<template>
    <layout>
        <div class="flex-fill d-flex flex-column justify-content-center">
            <div class="page-header" v-once>
                <h1 class="page-title text-center">
                    <b>{{ `Invite user to "${group.name}"` }}</b>
                </h1>
            </div>
            <div class="container">
                <div class="col-8 m-auto">
                    <form class="card" @submit.prevent="inviteRegUser">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label"> User </label>
                                <selectize
                                    v-model.trim="user"
                                    class="form-select"
                                    placeholder="Select user..."
                                    :class="{
                                        'is-invalid': $page.errors.user_id,
                                    }"
                                    label="name"
                                    :keys="['name', 'email']"
                                    :options="userList"
                                    :createItem="false"
                                    :searchFn="searchUser"
                                >
                                    <template #option="{ option }">
                                        <div>{{ option.name }}</div>
                                        <div class="text-muted small">
                                            {{ option.company }}
                                        </div>
                                    </template>
                                </selectize>
                                <span
                                    v-if="$page.errors.user_id"
                                    class="invalid-feedback"
                                >
                                    <strong>
                                        {{ $page.errors.user_id }}
                                    </strong>
                                </span>
                                <div class="mt-1 text-muted small" v-once>
                                    {{
                                        `You can invite registered users or input email address matches ${group.domain}`
                                    }}
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <inertia-link
                                :href="route('groups.users.index', group.id)"
                                class="btn btn-link"
                                v-once
                            >
                                Cancel
                            </inertia-link>
                            <button
                                v-if="regUser.user_id"
                                type="submit"
                                class="btn btn-primary"
                            >
                                <span
                                    v-if="regUser.sending"
                                    class="spinner-border spinner-border-sm mr-2"
                                ></span>
                                Invite
                            </button>
                            <button
                                v-if="!regUser.user_id"
                                v-b-modal="`modal-response-${group.name}`"
                                type="button"
                                class="btn btn-primary"
                            >
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
            <form @submit.prevent="inviteNewUser">
                <label class="form-label">New user</label>
                <input
                    v-model.trim="newUser.user_email"
                    class="form-control"
                    :class="{
                        'is-invalid': !checkEmail,
                    }"
                    type="email"
                />
                <span v-if="!checkEmail" class="invalid-feedback">
                    <strong>
                        {{
                            `Please input correct email address matches ${group.domain}`
                        }}
                    </strong>
                </span>
                <div class="mt-1 text-muted small" v-once>
                    {{
                        `You can invite new users by email address matches ${group.domain}`
                    }}
                </div>
                <div class="text-right">
                    <button
                        type="submit"
                        class="btn btn-primary"
                        :disabled="!checkEmail"
                    >
                        <span
                            v-if="newUser.sending"
                            class="spinner-border spinner-border-sm mr-2"
                        ></span>
                        Invite
                    </button>
                </div>
            </form>
        </b-modal>
    </layout>
</template>

<script>
import Layout from '@/layouts/main';
import { strToRegexp } from '@/helpers/helpers';

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
            user: null,
            userList: [],
            regUser: {
                sending: false,
                user_id: null,
            },
            newUser: {
                sending: false,
                user_email: '',
            },
        };
    },
    watch: {
        user: {
            immediate: true,
            handler: function (value) {
                this.regUser.user_id = value ? value.id : null;
            },
        },
    },
    mounted() {
        this.loadUserList();
    },
    methods: {
        inviteRegUser() {
            this.regUser.sending = true;

            this.$inertia
                .post(route('groups.users.store', this.group), this.regUser)
                .then(() => (this.regUser.sending = false));
        },
        inviteNewUser() {
            this.newUser.sending = true;

            this.$inertia
                // TODO - add correct route for invite new user by email
                .post(
                    route('groups.user-invitations.store', this.group),
                    this.newUser
                )
                .then(() => (this.newUser.sending = false));
        },
        loadUserList(query = '') {
            axios
                .get(route('groups.users.candidates', this.group), {
                    params: { q: query },
                })
                .then((result) => {
                    this.userList = result.data.data;
                });
        },
        searchUser(query) {
            this.newUser.user_email = query;
        },
    },
    computed: {
        checkEmail() {
            const strValidate = strToRegexp(this.group.domain);

            if (this.newUser.user_email.match(strValidate)) {
                return true;
            }

            return false;
        },
    },
};
</script>
