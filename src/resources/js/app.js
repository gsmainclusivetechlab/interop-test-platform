require('./bootstrap');

const dataTable = require('./modules/dataTable');
const confirmPopup = require('./modules/confirmPopup');
const flowChart = require('./modules/flowChart');

dataTable.init();
confirmPopup.init();
flowChart.init();
