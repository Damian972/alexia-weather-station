/**
 * Script: Alexia's weather station [Smartech Sensor]
 * Author: Damian972
 * Version: 1.0
 * License: MIT
 */

/**
 * @var
 */

const LIMIT_DATA_TO_SHOW = 10;
const picker = document.getElementById('datetime-picker');
const last_temp_element = document.getElementById('last_temp');
const last_temp_date_element = document.getElementById('last_temp_date');
const no_data_element = document.getElementById('no_data_loaded');
var initialized = false;
var date = '';

/**
 * @functions
 */

function init() {
    console.log('init function');
    if (0 === picker.value.length) picker.value = get_current_date();
    retrive_data_from_api('http://192.168.1.32:8080/api.php', 'sort=asc')
        .then((data) => {
            if (not_null_or_undefined(data)) {
                update_chart(data);
                initialized = true;
            }
        })
        .catch(error => console.warn(error));
}

function update() {
    retrive_data();
}

function update_by_date() {
    if (0 === picker.value.length) {
        date = get_current_date();
        picker.value = date;
    } else date = picker.value;
    retrive_data();
}

function update_chart(data) {
    // clear old data
    window.chart.data.datasets[0].data = [];
    window.chart.data.labels = [];
    const no_data_p = document.getElementById('no_data_loaded');
    if (0 === data.length) {
        // set no data message
        let message = 'There is nothing to show for ';
        message += ('' === date) ? 'now' : date;
        no_data_p.innerHTML = '* ' + message;
        console.log('[-] ' + message);
    } else {
        // set new data
        for (let i = 0; i < data.length; i++) {
            window.chart.data.datasets[0].data[i] = data[i].temperature;
            window.chart.data.labels[i] = data[i].created_at;
            let color_palette = generate_random_color_palette();
            window.chart.data.datasets[0].backgroundColor[i] = color_palette[0];
            window.chart.data.datasets[0].borderColor[i] = color_palette[1];
        }
        // remove no data message
        no_data_p.innerHTML = '';
    }
    // update the chart
    window.chart.update();
}

function retrive_data_from_api(uri, args = '') {
    if (0 === date.length) {
        uri += '?' + args;
    } else uri += '?date=' + date + '&' + args;
    uri += not_null_or_undefined(LIMIT_DATA_TO_SHOW) ? '&limit=' + LIMIT_DATA_TO_SHOW : '&limit=0';
    console.log('[!] ' + uri);

    return fetch(uri)
        .then((response) => {
            return response.json();
        })
        .then((json) => {
            //console.log(json);
            return json;
        })
        .catch((error) => {
            last_temp_element.innerHTML = 'NaN';
            last_temp_date_element.innerHTML = 'NaN';
            no_data_element.innerHTML = '* An error was occured, check the console logs for more infos.';
            console.warn(error);
        });
}

function retrive_data_from_api(uri, args = '') {
    if (0 === date.length) {
        uri += '?' + args;
    } else uri += '?date=' + date + '&' + args;
    uri += not_null_or_undefined(LIMIT_DATA_TO_SHOW) ? '&limit=' + LIMIT_DATA_TO_SHOW : '&limit=0';
    console.log(uri);

    return fetch(uri)
        .then((response) => {
            return response.json();
        })
        .then((json) => {
            //console.log(json);
            return json;
        })
        .catch(error => console.warn(error));
}


function get_current_date() {
    return (new Date()).toISOString().slice(0, 10);
}

function generate_random_color_palette() {
    colors = [];
    for (let i = 0; i < 3; i++) {
        colors[i] = get_random_int(0, 255);
    };
    return [
        'rgba(' + colors[0] + ', ' + colors[1] + ', ' + colors[2] + ', 0.3)',
        'rgba(' + colors[0] + ', ' + colors[1] + ', ' + colors[2] + ', 1)'
    ];
}

function format_number(value) {
    return parseFloat(Math.round(value * 100) / 100).toFixed(2);
}

function get_random_int(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function not_null_or_undefined(v) {
    return undefined != v || null != v;
}

/**
 * Events
 */

window.onload = () => {
    const ctx = document.getElementById('chart').getContext('2d');
    window.chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Temperature',
                data: [],
                backgroundColor: [],
                borderColor: [],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
}

$(document).ready(() => {
    init();
    setInterval(() => {
        update();
    }, 5 * 1000);
});