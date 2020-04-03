window.onload = function () {

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
    $('.create-session-circle-label').text("Test");

// adjust the coordinates to match the actual height of the image
    function adjusted(length) {
        var originalHeight = 805; // the coordinates above were calculated assuming an arbitrary image height of 785px
        var currentHeight = $('.create-session-screenshot').height();
        var ratio = currentHeight / originalHeight;
        return length * ratio;
    }

    var radius = adjusted(40);

    function animateCircle(circleRadius, ms, cb) {
        var coordinates = steps[step][1];
        $('.create-session-circle')
            .animate({
                top: adjusted(coordinates[1]) - (circleRadius - radius),
                left: adjusted(coordinates[0]) - (circleRadius - radius),
                width: circleRadius * 2,
                height: circleRadius * 2,
                borderRadius: circleRadius
            }, {
                complete: cb,
                duration: ms
            });
    }

    function updateCircle() {
        stopPulsate(); // 1. stop pulsating temporarily
        animateCircle(adjusted(2000), 0/*ms*/, function () { // 2. make circle big
            animateCircle(radius, 750/*ms*/, function () { // 3. animate it shrinking
                startPulsate(); // 4. start pulsating again
            })
        });

        var labelText = steps[step][2];
        $('.create-session-circle-label').text(labelText);
    }

//pulsate
    var interval = null;

    function startPulsate() {
        interval = setInterval(function () {
            animateCircle(radius + adjusted(20), 200, function () { //grow the circle
                animateCircle(radius, 200) //shrink the circle
            })
        }, 1500);
    }

    function stopPulsate() {
        clearInterval(interval);
    }

    updateCircle(); //init circle at beginning of demo

    $('.create-session-circle').click(function () {
        step = (step + 1) % steps.length;
        var image = steps[step][0]; //image is 0th item in array
        $('.create-session-screenshot').attr('src', image);
        updateCircle();
    });
}

$('.create-session-start-demo-btn').click(function() {
    $('.create-session-start-demo-btn').hide();
    $('.create-session-demo-overlay').hide();
});
