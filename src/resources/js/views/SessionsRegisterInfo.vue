<template>
    <div>
        <div class="card">
            <div class="row">
                <div class="col-6">
                    <div class="card-header border-0">
                        <h3 class="card-title">Session info</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">
                                Name
                            </label>
                            <input
                                name="name"
                                value=""
                                type="text"
                                class="form-control"
                            />
                            <span class="invalid-feedback">
                                <strong>Invalid field</strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                SUT
                            </label>
                            <input
                                type="text"
                                disabled
                                class="form-control"
                                value="Service Provider"
                            />
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
                            style="height: 320px"
                        >
                            <li class="list-group-item">
                                <b
                                    class="dropdown-toggle"
                                    v-b-toggle.suite-merchant
                                >
                                    Merchant-Initiated Merchant Payment
                                </b>
                                <b-collapse id="suite-merchant" visible>
                                    <ul class="list-group">
                                        <li
                                            class="list-group-item border-0 py-0"
                                        >
                                            <span
                                                class="d-inline-block dropdown-toggle py-2 font-weight-medium"
                                                v-b-toggle.positive-cases-happy-flow
                                            >
                                                Happy flow
                                            </span>
                                            <b-collapse
                                                id="positive-cases-happy-flow"
                                                visible
                                            >
                                                <ul class="list-group">
                                                    <li class="list-group-item">
                                                        <label
                                                            class="custom-control custom-checkbox mb-0"
                                                        >
                                                            <input
                                                                name="cases[]"
                                                                value=""
                                                                type="checkbox"
                                                                class="custom-control-input"
                                                            />
                                                            <span
                                                                class="custom-control-label"
                                                            >
                                                                Authorized
                                                                Transaction
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
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4 d-flex justify-content-between">
            <router-link
                :to="{ name: 'sessions.register.configuration' }"
                class="btn btn-outline-primary"
                >Back</router-link
            >
            <button type="submit" class="btn btn-primary">
                Create
            </button>
        </div>
    </div>
</template>
