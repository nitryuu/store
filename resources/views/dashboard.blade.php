@extends('layout.admin')

@section('title')
    Dashboard
@endsection

@section('content')
    DASHBOARD

    <div class="form-group mb-0 mt-5 float-right" style="align-items:center; display: flex;">
        <label for="date_filter" class="date_filter" style="width: 100%; margin-bottom: 0; ">Filter Tanggal: </label>
        <input type="text" name="date" class="form-control date_filter" data-date-format="yyyy-mm-dd">
    </div>

    <div style="display: flex; flex-direction: row; gap: 2rem; margin: 6rem 0; flex-wrap: wrap;">
        @for ($i = 0; $i < $branches; $i++)
            <div>
                <canvas id='chart{{ $i }}'></canvas>
            </div>
        @endfor
    </div>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.6.0/chart.min.js"
        integrity="sha512-GMGzUEevhWh8Tc/njS0bDpwgxdCJLQBWG3Z2Ct+JGOpVnEmjvNx6ts4v6A2XJf1HOrtOsfhv3hBKpK9kE5z8AQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $('.date_filter').datetimepicker({
            minView: 'month'
        })
        const branches = '{{ $branches }}'
        let data = []

        $('.date_filter').on('change', function() {
            let value = $('input[name="date"]').val()
            if (value) {
                changeChart()
            } else {
                initialChartData(false)
            }
        })

        initialChartData()

        function initialChartData(initial = true) {
            $.ajax({
                url: '/chart',
                success: function(response) {
                    for (let i = 0; i < branches; i++) {
                        let value = response.data
                        let income = response.data[i].income[0]
                        let outcome = response.data[i].orders[0]

                        value.forEach(branch => {
                            data.push({
                                'labels': [branch.name],
                                'datasets': [{
                                    label: ['Income'],
                                    data: [income ? income.income : 0],
                                    borderWidth: 1,
                                    backgroundColor: `#${Math.floor(Math.random() * 16777215).toString(16)}`
                                }, {
                                    label: ['Outcome'],
                                    data: [outcome ? outcome.outcome : 0],
                                    borderWidth: 1,
                                    backgroundColor: `#${Math.floor(Math.random() * 16777215).toString(16)}`
                                }]
                            })
                        });

                        if (initial) {
                            window["ctx" + i] = $(`#chart${i}`)
                            window["chart" + i] = new Chart(window["ctx" + i], {
                                type: 'bar',
                                data: data[i]
                            })
                        } else {
                            window["chart" + i].data = data[i]
                            window["chart" + i].update();
                        }

                        data = []
                    }
                }
            })
        }

        function changeChart() {
            let date = $('input[name="date"]').val()
            $.ajax({
                type: 'post',
                url: '/chart',
                data: {
                    date: date
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    for (let i = 0; i < branches; i++) {
                        let value = response.data
                        let income = response.data[i].income[0]
                        let outcome = response.data[i].orders[0]

                        value.forEach(branch => {
                            data.push({
                                'labels': [branch.name],
                                'datasets': [{
                                    label: ['Income'],
                                    data: [income ? income.income : 0],
                                    borderWidth: 1,
                                    backgroundColor: `#${Math.floor(Math.random() * 16777215).toString(16)}`
                                }, {
                                    label: ['Outcome'],
                                    data: [outcome ? outcome.outcome : 0],
                                    borderWidth: 1,
                                    backgroundColor: `#${Math.floor(Math.random() * 16777215).toString(16)}`
                                }]
                            })
                        });

                        window["chart" + i].data = data[i]
                        window["chart" + i].update();
                        data = []
                    }
                }
            })
        }
    </script>
@endsection
