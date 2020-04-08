$('.demo1').one('click', function() {
    var steps = [
        ['../../images/tutorials/create-session/dashboard.png', [1335, 40], 'Start by creating a new session', true],
        ['../../images/tutorials/create-session/select_sut.png', [535, 390], 'Select the System Under Test', true],
        ['../../images/tutorials/create-session/select_sut_2.png', [535, 385], "Let's select Service Provider", true],
        ['../../images/tutorials/create-session/select_sut_3.png', [1100, 473], 'Press Next', true],
        ['../../images/tutorials/create-session/configure_sut.png', [1100, 422], 'After configuration, press Next', true],
        ['../../images/tutorials/create-session/session_info.png', [742, 355], 'Select use cases by ticking the corresponding box', true],
        ['../../images/tutorials/create-session/session_info_2.png', [742, 395], 'Select use cases by ticking the corresponding box', true],
        ['../../images/tutorials/create-session/session_info_3.png', [742, 460], 'Select use cases by ticking the corresponding box', true],
        ['../../images/tutorials/create-session/session_info_4.png', [1093, 580], 'Press Create when you are finished', true],
        ['../../images/tutorials/create-session/session_created.png', [47, 243], 'You can access use cases here', true],
        ['../../images/tutorials/create-session/usecase_page.png', [1335, 40], 'Click here to start the demo again', true]
    ];

    var step = 0;

    function demo1_adjusted(length) {
        if ($(".create-session-screenshot[src*='session_info']").length == 1) { // height of these screenshots slightly higher
            var originalHeight = 699;
        } else if ($(".create-session-screenshot[src*='usecase_page']").length == 1) {
            var originalHeight = 940;
        } else {
            var originalHeight = 653;
        }
        var currentHeight = $('.create-session-screenshot').height();
        var ratio = currentHeight / originalHeight;
        return length * ratio;
    }

    var radius = demo1_adjusted(40);

    function demo1_animateCircle(circleRadius, ms, cb) {
        var coordinates = steps[step][1];
        $('.create-session-circle')
            .animate({
                top: demo1_adjusted(coordinates[1]) - (circleRadius - radius),
                left: demo1_adjusted(coordinates[0]) - (circleRadius - radius),
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

    function demo1_updateCircle() {
        demo1_stopPulsate(); // 1. stop pulsating temporarily
        demo1_animateCircle(demo1_adjusted(2000), 0/*ms*/, function() { // 2. make circle big
            demo1_animateCircle(radius, 750/*ms*/, function() { // 3. animate it shrinking
                demo1_startPulsate(); // 4. start pulsating again
            })
        });

        var labelText = steps[step][2];
        $('.create-session-circle-label').text(labelText);
    }

//pulsate
    var interval = null;

    function demo1_startPulsate() {
        interval = setInterval(function() {
            demo1_animateCircle(radius+demo1_adjusted(20), 250, function() { //grow the circle
                demo1_animateCircle(radius, 200) //shrink the circle
            })
        }, 2000);
    }

    function demo1_stopPulsate() {
        clearInterval(interval);
    }

    demo1_updateCircle(); //init circle at beginning of demo

    $('.create-session-circle').click(function() {
        step = (step + 1) % steps.length;
        var image = steps[step][0]; //image is 0th item in array
        $('.create-session-screenshot').attr('src', image);
        demo1_updateCircle();
    });

    $('.create-session-start-demo-btn').click(function() {
        $('.create-session-start-demo-btn').toggle();
        $('.create-session-demo-overlay').toggle();
    });

    $('#create-session-reset').click(function() {
        step = 0;
        var image = steps[step][0]; //image is 0th item in array
        $('.create-session-screenshot').attr('src', image);
        demo1_updateCircle();
        if ($(".create-session-start-demo-btn").is(":hidden")) {
            $('.create-session-start-demo-btn').toggle();
        }
        if ($(".create-session-demo-overlay").is(":hidden")) {
            $('.create-session-demo-overlay').toggle();
        }
    });
});
