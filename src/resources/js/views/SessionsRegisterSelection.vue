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
                        <h3 class="card-title">
                            Select SUT
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">
                                SUT
                            </label>
                            <select class="form-control custom-select">
                                <option value="sp">Service Provider</option>
                                <option value="mmo-1" disabled
                                    >Mobile Money Operator 1</option
                                >
                                <option value="mojaloop" disabled
                                    >Mojaloop</option
                                >
                                <option value="mmo-2" disabled
                                    >Mobily Money Operator 2</option
                                >
                            </select>
                            <small class="form-text text-muted">
                                Now only Service Provider can be set as System
                                Under Test.
                            </small>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <router-link
                        :to="{
                            name: 'sessions.register.configuration',
                        }"
                        class="btn btn-primary"
                        >Next</router-link
                    >
                </div>
            </div>
        </div>
    </div>
</template>
