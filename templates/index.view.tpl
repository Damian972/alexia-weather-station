{extends file="default.view.tpl"}
{block name="title"}Dashboard{/block}

{block name="header_css"}<link rel="stylesheet" href="{$smarty.const.ASSETS|cat: '/css/chart.css'}">{/block}
{block name="header_js"}<script src="{$smarty.const.ASSETS|cat: '/js/chart.js'}"></script>
<script>
    // In seconds
    const CHART_REFRESH_INTERVAL = {$options.refresh_time_gui|default: '30'};
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
                    <button onclick="update_by_date()">Charger</button>
                </div>
                <p class="validation-error" id="no_data_loaded"></p>
            </div>
                
                
                
            </div>
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
        const no_data_element = document.getElementById('no_data_loaded');
        var initialized = false;
        var date = '';

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
                // set no data message
                let message = 'There is nothing to show for ';
                message += ('' === date) ? 'now' : date;
                no_data_element.innerHTML = '* ' + message;
                console.log('[-] ' + message);
            } else {
                // reverse array
                data = data.reverse();

                // set new data
                last_temp_element.innerHTML = format_number(data[data.length - 1].temperature) + '°C';
                last_temp_date_element.innerHTML = data[data.length - 1].created_at;

                for (let i = 0; i < data.length; i++) {
                    window.chart.data.datasets[0].data[i] = data[i].temperature;
                    window.chart.data.labels[i] = data[i].created_at;
                    let color_palette = generate_random_color_palette();
                    window.chart.data.datasets[0].backgroundColor[i] = color_palette[0];
                    window.chart.data.datasets[0].borderColor[i] = color_palette[1];
                }
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