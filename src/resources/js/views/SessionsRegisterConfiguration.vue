<template>
    <div class="row">
        <div class="col-3">
            <div class="flow-chart-legend d-flex flex-column">
                <div class="d-inline-flex align-items-center">
                    <span class="ic-arrow-right mr-2"></span>
                    <small>Mobile Money API</small>
                </div>
                <div class="d-inline-flex align-items-center">
                    <span class="ic-arrow-right mr-2 border-dashed"></span>
                    <small>Mojaloop API</small>
                </div>
            </div>
        </div>
        <div class="col-9">
            <div class="flow-chart-content">
                <div class="card">
                    <div class="card-header border-0">
                        <h3 class="card-title">Configure SUT</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <h5>
                                Service Provider &gt; Mobile Money Operator 1
                            </h5>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                URL
                            </label>
                            <input
                                type="text"
                                disabled
                                class="form-control"
                                value="https://sandbox.mobilemoneyapi.io/v1.0/passthrough/mm"
                            />
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                Key
                            </label>
                            <input type="text" class="form-control" />
                        </div>
                        <hr />
                        <div class="form-group">
                            <h5>
                                Mobile Money Operator 1 &lt; Service Provider
                            </h5>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                Key
                            </label>
                            <input type="text" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <router-link
                        :to="{ name: 'sessions.register.selection' }"
                        class="btn btn-outline-primary"
                    >
                        Back
                    </router-link>
                    <router-link
                        :to="{ name: 'sessions.register.info' }"
                        class="btn btn-primary"
                    >
                        Next
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>
