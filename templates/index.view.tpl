{extends file="default.view.tpl"}
{block name="title"}Dashboard{/block}

{block name="header_css"}<link rel="stylesheet" href="{$smarty.const.ASSETS|cat: '/css/chart.css'}">{/block}
{block name="header_js"}<script src="{$smarty.const.ASSETS|cat: '/js/chart.js'}"></script>
<script>
    // In seconds
    const CHART_REFRESH_INTERVAL = {$options.refresh_time_gui|default: '120'};
    const LIMIT_DATA_TO_SHOW = (900 < window.innerWidth) ? {$options.max_data_to_show|default: '10'} : 5;
</script>
{/block}

{block name="content"}
    <div class="container">
        <section>
            <div class="align-center">
                <h2>Température: <span id="last_temp"></span></h2>
                <p>Enregistré le: <span id="last_temp_date"></span></p>
            </div>
            <canvas id="chart" class="custom-chart"></canvas>
            <br>
            <div class="align-center">
                <div class="row">
                    <div class="col col-sm-12 col-md-8">
                        <input type="date" id="datetime-picker">
                    </div>
                    <div class="col col-sm-12 col-md-4">
                        <button class="button-primary" onclick="update_by_date()">Charger</button>
                    </div>
                    <p class="validation-error" id="no_data_loaded"></p>
                </div>
            </div>
        </section>
    </div>
    <section class="section-primary" style="padding: 15px;">
        <div class="container align-center" style="padding-bottom: 10px;">
            <div class="row">
                <div class="col col-sm-12 col-md-4">
                    <h3 id="lower_temp_element">NaN</h3>
                    <p>Température la plus basse</p>
                </div>
                <div class="col col-sm-12 col-md-4">
                    <h3 id="average_temp_element">NaN</h3>
                    <p>Température moyenne</p>
                </div>
                <div class="col col-sm-12 col-md-4">
                    <h3 id="highter_temp_element">NaN</h3>
                    <p>Température la plus haute</p>
                </div>
            </div>
            <small class="validation-error">*Résultats basés sur toutes les données de la journée</small>
        </div>
    </section>
    <div class="container align-center">
        <section>
            <h2>Historique des températures</h2>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Temperature</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody id="table_history">
                </tbody>
            </table>
        </section>
    </div>
{/block}

{block name="footer_js"}
    <script>
        /**
        * @var
        */
        const picker = document.getElementById('datetime-picker');
        const last_temp_element = document.getElementById('last_temp');
        const last_temp_date_element = document.getElementById('last_temp_date');
        const lower_temp_element = document.getElementById('lower_temp_element');
        const average_temp_element = document.getElementById('average_temp_element');
        const highter_temp_element = document.getElementById('highter_temp_element');
        const table_history = document.getElementById('table_history');
        const no_data_element = document.getElementById('no_data_loaded');
        var initialized = false;
        var date = '';

        /**
        * @functions
        */

        function init() {
            console.log('init function');
            console.log('[!] Refresh every ' + CHART_REFRESH_INTERVAL + 's for the GUI.');
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

                // set new data
                last_temp_element.innerHTML = format_weather_temperature(data[0].temperature) + 'C';
                last_temp_date_element.innerHTML = data[0].created_at;

                let limit = not_null_or_undefined(LIMIT_DATA_TO_SHOW) ? LIMIT_DATA_TO_SHOW : 10;
                let total_data = data.length;
                let _l = (data.length < limit) ? data.length : limit;
                for (let i = 0; i < total_data; i++) {
                    // limit data in the chart
                    if (limit <= i) {
                        break;
                    }
                    window.chart.data.datasets[0].data[i] = data[(_l - i) - 1].temperature;
                    window.chart.data.labels[i] = data[(_l - i) - 1].created_at;
                    let color_palette = generate_random_color_palette();
                    window.chart.data.datasets[0].backgroundColor[i] = color_palette[0];
                    window.chart.data.datasets[0].borderColor[i] = color_palette[1];
                }

                /*let total_chart_data = window.chart.data.datasets[0].data.length;
                let new_chart_data = [];
                for (let i = 0; i < total_chart_data; i++) {
                    new_chart_data[i] = window.chart.data.datasets[0].data[(total_chart_data - i) - 1];
                }
                window.chart.data.datasets[0].data = new_chart_data;

                console.log(new_chart_data);*/

                // set lower, average, highter temperature
                let lower_average_highter_temperature = get_lower_average_highter_from_array(data);

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
    </script>
{/block}