<template>
    <layout>
        <div class="flex-fill d-flex flex-column justify-content-center">
            <div class="page-header">
                <h1 class="page-title text-center">
                    <b>{{ `Invite new user to ${group.name}` }}</b>
                </h1>
            </div>
            <div class="container">
                <div class="col-8 m-auto">
                    <form class="card" @submit.prevent="submit">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">
                                    User
                                </label>
                                <selectize
                                    v-model="form.user_id"
                                    class="form-select"
                                    placeholder="Select user..."
                                    :class="{
                                        'is-invalid': $page.errors.user_id,
                                    }"
                                    :load="loadUsersItems"
                                    :render="renderUsersItems"
                                    :preload="true"
                                    valueField="id"
                                    labelField="name"
                                    searchField="name"
                                />
                                <span
                                    v-if="$page.errors.user_id"
                                    class="invalid-feedback"
                                >
                                    <strong>
                                        {{ $page.errors.user_id }}
                                    </strong>
                                </span>
                                <div class="mt-1 text-muted small">
                                    {{ `You can only invite registered users whose email address matches ${group.domain}` }}
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <inertia-link
                                :href="route('groups.show', group.id)"
                                class="btn btn-link"
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
            sending: false,
            form: {
                user_id: null,
            },
            renderUsersItems: {
                option: function (item) {
                    return (
                        '<div class="option">' +
                        '<div>' +
                        item.name +
                        '</div>' +
                        '<div class="text-muted small">' +
                        item.company +
                        '</div>' +
                        '</div>'
                    );
                },
            },
        };
    },
    methods: {
        submit() {
            this.sending = true;
            this.$inertia
                .post(route('groups.users.store', this.group), this.form)
                .then(() => (this.sending = false));
        },
        loadUsersItems(query, callback) {
            axios
                .get(route('groups.users.candidates', this.group), {
                    params: { q: query },
                })
                .then((result) => {
                    callback(result.data.data);
                });
        },
    },
};
</script>
