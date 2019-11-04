/**
 * @var
 */

const BASE_URL = "http://192.168.1.32:8080";
const API_URL = "http://192.168.1.32:8080/api.php";

const CHART_REFRESH_INTERVAL = 10;
const LIMIT_DATA_TO_SHOW = (900 < window.innerWidth) ? 10 : 5;


const picker = document.getElementById('datetime-picker');
const last_temp_element = document.getElementById('last_temp');
const last_temp_date_element = document.getElementById('last_temp_date');
const lower_temp_element = document.getElementById('lower_temp_element');
const average_temp_element = document.getElementById('average_temp_element');
const highter_temp_element = document.getElementById('highter_temp_element');
const table_history = document.getElementById('table_history');
const no_data_element = document.getElementById('no_data_loaded');
var initialized = false;
var date = get_current_date();

/**
 * @functions
 */

function init() {
    console.log('init function');
    console.log('[!] Refresh every ' + CHART_REFRESH_INTERVAL + 's');
    if (0 === picker.value.length) picker.value = get_current_date();
    retrive_data_from_api(API_URL, 'sort=desc')
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

    if (0 === data.length) {
        last_temp_element.innerHTML = 'NaN';
        last_temp_date_element.innerHTML = 'NaN';

        lower_temp_element.innerHTML = 'NaN';
        average_temp_element.innerHTML = 'NaN';
        highter_temp_element.innerHTML = 'NaN';

        table_history.innerHTML = '';

        // set no data message
        let message = 'There is nothing to show for ';
        message += ('' === date) ? 'now' : date + '.';
        no_data_element.innerHTML = '* ' + message;

        // Update the chart
        window.chart.update();
        console.log('[-] ' + message);
        return;
    } else {
        // if error return by the api
        if (not_null_or_undefined(data.error)) {
            last_temp_element.innerHTML = 'NaN';
            last_temp_date_element.innerHTML = 'NaN';

            lower_temp_element.innerHTML = 'NaN';
            average_temp_element.innerHTML = 'NaN';
            highter_temp_element.innerHTML = 'NaN';

            table_history.innerHTML = '';

            no_data_element.innerHTML = '* The API seems to have problems, try again later.';

            // Update the chart
            window.chart.update();
            return;
        }

        // reverse array
        data = data.reverse();

        // set new data
        last_temp_element.innerHTML = format_weather_temperature(data[data.length - 1].temperature) + 'C';
        last_temp_date_element.innerHTML = data[data.length - 1].created_at;

        let limit = not_null_or_undefined(LIMIT_DATA_TO_SHOW) ? LIMIT_DATA_TO_SHOW : 10;
        let total_data = data.length;
        for (let i = 0; i < total_data; i++) {
            // limit data in the chart
            if (limit <= i) {
                break;
            }
            window.chart.data.datasets[0].data[(limit - i) - 1] = data[(total_data - i) - 1].temperature;
            window.chart.data.labels[(limit - i) - 1] = data[(total_data - i) - 1].created_at;
            let color_palette = generate_random_color_palette();
            window.chart.data.datasets[0].backgroundColor[i] = color_palette[0];
            window.chart.data.datasets[0].borderColor[i] = color_palette[1];
        }

        // set lower, average, highter temperature
        let lower_average_highter_temperature = get_lower_average_highter_from_array(data);

        //console.log(lower_average_highter_temperature);
        lower_temp_element.innerHTML = format_weather_temperature(lower_average_highter_temperature[0]);
        average_temp_element.innerHTML = '~' + format_weather_temperature(lower_average_highter_temperature[1]);
        highter_temp_element.innerHTML = format_weather_temperature(lower_average_highter_temperature[2]);

        let table_content = '';
        for (let i = 0; i < data.length; i++) {
            table_content += '<tr><td>' + (i + 1) + '</td><td>' + data[i].temperature + '°</td><td>' + data[i].created_at + '</td></tr>';
        }
        table_history.innerHTML = table_content;

        // remove no data message
        no_data_element.innerHTML = '';
    }
    // update the chart
    window.chart.update();
}

function retrive_data() {
    retrive_data_from_api(API_URL, 'sort=desc')
        .then((data) => {
            if (not_null_or_undefined(data)) update_chart(data);
        })
        .catch(error => console.warn(error));
}

function retrive_data_from_api(uri, args = '') {
    if (0 === date.length) {
        date = get_current_date();
    }
    uri += '?date=' + date + '&' + args;
    // Set limit data to retrieve from the api
    //uri += not_null_or_undefined(LIMIT_DATA_TO_SHOW) ? '&limit=' + LIMIT_DATA_TO_SHOW : '&limit=0';
    console.log('[!] ' + uri);

    return fetch(uri)
        .then((response) => {
            return response.json();
        })
        .then((json) => {
            return json;
        })
        .catch((error) => {
            last_temp_element.innerHTML = 'NaN';
            last_temp_date_element.innerHTML = 'NaN';

            lower_temp_element.innerHTML = 'NaN';
            average_temp_element.innerHTML = 'NaN';
            highter_temp_element.innerHTML = 'NaN';

            table_history.innerHTML = '';

            no_data_element.innerHTML = '* An error was occured, check the console logs for more infos.';
            console.warn(error);
        });
}

function get_lower_average_highter_from_array(data) {
    let max = -Infinity;
    let min = +Infinity;
    let total = 0;

    for (let i = 0; i < data.length; i++) {
        let n = format_number(data[i].temperature);
        if (n > max) max = n;
        if (n < min) min = n;
        total += parseInt(n, 10);
    }
    let result = [min, (total / data.length), max]
    return result;
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

function format_weather_temperature(temp) {
    temp = format_number(temp);
    return (0 < temp) ? '+' + temp + '°' : temp + '°';
}

function format_number(value) {
    return parseFloat((Math.round(value * 100) / 100).toFixed(2));
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
                label: 'Température',
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
    }, CHART_REFRESH_INTERVAL * 1000);
});