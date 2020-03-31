<template>
    <div role="tablist" >
        <b-card no-body class="mb-0 card">
            <b-card-header id="create-session" header-tag="header" class="p-1 card-header" role="tab">
                <b-button block href="#" v-b-toggle.accordion-1 variant="info" class="btn">
                    <h5 class="mb-0 scenario-accordion">How do I create a new session?</h5>
                </b-button>
            </b-card-header>
            <b-collapse visible id="accordion-1" accordion="my-accordion" role="tabpanel">
                <b-card-body>
                    <div id="demo-desc">
                        To start the interactive demo, simply press the "Start interactive demo" below and follow the red circle which will indicate where to click and give you additional information.
                    </div>
                    <div class="demo-outer-container">
                        <div class="demo-inner-container">
                            <div class="image-holder">
                                <div class="circle">
                                    <div class="circle-label">this is a label</div>
                                </div>
                                <img class="screenshot" src="{{ asset('images/tutorials/create-session/dashboard.png') }}"/>
                            </div>
                            <div class="demo-overlay"></div>
                            <div class="btn border-primary start-demo-btn">Start interactive demo</div>
                        </div>
                    </div>
                </b-card-body>
            </b-collapse>
        </b-card>

        <b-card no-body class="mb-0 card">
            <b-card-header id="service-provider" header-tag="header" class="p-1 card-header" role="tab">
                <b-button block href="#" v-b-toggle.accordion-2 variant="info" class="btn btn-link">
                    <h5 class="mb-0">How do I simulate a Service Provider?</h5>
                </b-button>
            </b-card-header>
            <b-collapse visible id="accordion-2" accordion="my-accordion" role="tabpanel">
                <b-card-body>
                    <b-card-text>Text here...</b-card-text>
                </b-card-body>
            </b-collapse>
        </b-card>

        <b-card no-body class="mb-0 card">
            <b-card-header header-tag="header" class="p-1 card-header" role="tab">
                <b-button block href="#" v-b-toggle.accordion-3 variant="info" class="btn btn-link">
                    <h5 class="mb-0">Tutorial #3</h5>
                </b-button>
            </b-card-header>
            <b-collapse id="accordion-3" accordion="my-accordion" role="tabpanel">
                <b-card-body>
                    <b-card-text>Text here...</b-card-text>
                </b-card-body>
            </b-collapse>
        </b-card>

        <b-card no-body class="mb-0 card">
            <b-card-header header-tag="header" class="p-1 card-header" role="tab">
                <b-button block href="#" v-b-toggle.accordion-4 variant="info" class="btn btn-link">
                    <h5 class="mb-0">Tutorial #4</h5>
                </b-button>
            </b-card-header>
            <b-collapse id="accordion-4" accordion="my-accordion" role="tabpanel">
                <b-card-body>
                    <b-card-text>Text here...</b-card-text>
                </b-card-body>
            </b-collapse>
        </b-card>

        <b-card no-body class="mb-0 card">
            <b-card-header header-tag="header" class="p-1 card-header" role="tab">
                <b-button block href="#" v-b-toggle.accordion-5 variant="info" class="btn btn-link">
                    <h5 class="mb-0">Tutorial #5</h5>
                </b-button>
            </b-card-header>
            <b-collapse id="accordion-5" accordion="my-accordion" role="tabpanel">
                <b-card-body>
                    <b-card-text>Text here...</b-card-text>
                </b-card-body>
            </b-collapse>
        </b-card>

        <b-card no-body class="mb-0 card">
            <b-card-header header-tag="header" class="p-1 card-header" role="tab">
                <b-button block href="#" v-b-toggle.accordion-6 variant="info" class="btn btn-link">
                    <h5 class="mb-0">Tutorial #6</h5>
                </b-button>
            </b-card-header>
            <b-collapse id="accordion-6" accordion="my-accordion" role="tabpanel">
                <b-card-body>
                    <b-card-text>Text here...</b-card-text>
                </b-card-body>
            </b-collapse>
        </b-card>
    </div>
</template>
