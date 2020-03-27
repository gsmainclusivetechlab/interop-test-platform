<template>
    <div role="tablist" >
        <b-card no-body class="mb-0 card">
            <b-card-header header-tag="header" class="p-1 card-header" role="tab">
                <b-button block href="#" v-b-toggle.accordion-1 variant="info" class="btn">
                    <h5 class="mb-0 scenario-accordion">How do I create a new session?</h5>
                </b-button>
            </b-card-header>
            <b-collapse id="accordion-1" accordion="my-accordion" role="tabpanel">
                <b-card-body>
                    <div id="scenario-desc">
                        Use the interactive demo below which shows a step by step guide to creating a new session:
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
            <b-card-header header-tag="header" class="p-1 card-header" role="tab">
                <b-button block href="#" v-b-toggle.accordion-2 variant="info" class="btn btn-link">
                    <h5 class="mb-0">Tutorial #2</h5>
                </b-button>
            </b-card-header>
            <b-collapse id="accordion-2" accordion="my-accordion" role="tabpanel">
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
