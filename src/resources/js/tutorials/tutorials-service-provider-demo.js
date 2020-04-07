$('.demo2').click(function() {
    var steps = [
        ['../../images/tutorials/service-provider/1-session-page.png', [85, 232], 'First, select the session you would like to execute.'],
        ['../../images/tutorials/service-provider/2-session-usecase.png', [101, 270], 'Here you can see all your selected use cases, select the one you would like to test.'],
        ['../../images/tutorials/service-provider/3-test-runs.png', [1344, 104], 'This page details the use case. You can also view the use case flow and test data example. Copy the link to use in your preferred API testing tool.'],
        ['../../images/tutorials/service-provider/7-postman-1.png', [1241, 72], 'Here we are using Postman. Collections can be found on the tutorials page. Once all the information is correct, press "Send".', true],
        ['../../images/tutorials/service-provider/8-postman-2.png', [412, 567], 'Check the response is correct, and go back to the test platform.', true],
        ['../../images/tutorials/service-provider/9-test-runs.png', [447, 243], 'You should now see your test run. Click to be taken to the test run page.', true],
        ['../../images/tutorials/service-provider/10-test-details-1.png', [618, 527], 'On this page are all the details about the test run. Clicking on a failed test will give you more information. ', true],
        ['../../images/tutorials/service-provider/11-test-details-2.png', [102, 45], 'You can also see the request and response data for each step of the use case flow. To start the demo again click the sessions tab.', true]
    ];

    var step = 0;

// adjust the coordinates to match the actual height of the image
    function demo2_adjusted(length) {
        var originalHeight = 805; // the coordinates above were calculated assuming an arbitrary image height of 785px
        var currentHeight = $('.service-provider-screenshot').height();
        var ratio = currentHeight / originalHeight;
        return length * ratio;
    }

    var radius = demo2_adjusted(40);

    function demo2_animateCircle(circleRadius, ms, cb) {
        var coordinates = steps[step][1];
        $('.service-provider-circle')
            .animate({
                top: demo2_adjusted(coordinates[1]) - (circleRadius - radius),
                left: demo2_adjusted(coordinates[0]) - (circleRadius - radius),
                width: circleRadius * 2,
                height: circleRadius * 2,
                borderTopLeftRadius: circleRadius,
                borderTopRightRadius: circleRadius,
                borderBottomLeftRadius: circleRadius,
                borderBottomRightRadius: circleRadius
            }, {
                complete: cb,
                duration: ms
            });
    }

    function demo2_updateCircle() {
        demo2_stopPulsate(); // 1. stop pulsating temporarily
        demo2_animateCircle(demo2_adjusted(2000), 0/*ms*/, function() { // 2. make circle big
            demo2_animateCircle(radius, 750/*ms*/, function() { // 3. animate it shrinking
                demo2_startPulsate(); // 4. start pulsating again
            })
        });

        var labelText = steps[step][2];
        $('.service-provider-circle-label').text(labelText);
    }

//pulsate
    var interval = null;

    function demo2_startPulsate() {
        interval = setInterval(function() {
            demo2_animateCircle(radius+demo2_adjusted(20), 250, function() { //grow the circle
                demo2_animateCircle(radius, 200) //shrink the circle
            })
        }, 2000);
    }

    function demo2_stopPulsate() {
        clearInterval(interval);
    }

    demo2_updateCircle(); //init circle at beginning of demo

    $('.service-provider-circle').click(function() {
        step = (step + 1) % steps.length;
        var image = steps[step][0]; //image is 0th item in array
        $('.service-provider-screenshot').attr('src', image);
        demo2_updateCircle();
    });

    $('.service-provider-start-demo-btn').click(function() {
        $('.service-provider-start-demo-btn').hide();
        $('.service-provider-demo-overlay').hide();
    });
});



