$(function () {

    $('[data-provides="anomaly.extension.sales_overview_widget"]').each(function () {

        var orders = $(this).data('orders');
        var revenue = $(this).data('revenue');

        // Chart Data
        var data = {
            labels: [
                'Revenue',
                'Orders'
            ],
            datasets: [
                {
                    data: orders.map(function (order) {

                        var date = order.date.split(/[- :]/);

                        return {
                            x: new Date(Date.UTC(date[0], date[1] - 1, date[2])),
                            y: order.count
                        };
                    }),
                    backgroundColor: 'rgba(56,181,230,0.05)',
                    pointBackgroundColor: '#ffffff',
                    borderColor: '#38b5e6',
                    yAxisID: 'orders',
                    label: 'Orders',
                    pointRadius: 4,
                    borderWidth: 3,
                    lineTension: 0
                },
                {
                    data: revenue.map(function (order) {

                        var date = order.date.split(/[- :]/);

                        return {
                            x: new Date(Date.UTC(date[0], date[1] - 1, date[2])),
                            y: order.revenue
                        };
                    }),
                    backgroundColor: 'rgba(36,206,123,0.1)',
                    pointBackgroundColor: '#ffffff',
                    borderColor: '#24ce7b',
                    yAxisID: 'revenue',
                    label: 'Revenue',
                    pointRadius: 4,
                    borderWidth: 3,
                    lineTension: 0
                }
            ]
        };

        // Chart Options
        var options = {
            legend: {
                display: false
            },
            scales: {
                xAxes: [
                    {
                        type: 'time',
                        time: {
                            unit: 'day',
                            tooltipFormat: 'MMM D',
                            max: new Date().setDate((new Date()).getDate()),
                            min: new Date().setDate((new Date()).getDate() - 30),
                            displayFormats: {
                                day: 'MMM D'
                            }
                        },
                        gridLines: {
                            display: false,
                            color: '#ebeded',
                            zeroLineColor: '#ebeded'
                        }
                    }
                ],
                yAxes: [
                    {
                        id: 'revenue'
                    },
                    {
                        id: 'orders',
                        position: 'right',
                        gridLines: {
                            display: false,
                            color: '#ebeded',
                            zeroLineColor: '#ebeded'
                        }
                    }
                ]
            },
            paddingTop: 0,
            paddingLeft: 0,
            paddingRight: 0,
            paddingBottom: 0
        };

        // And for a doughnut chart
        new Chart($(this), {
            type: 'line',
            data: data,
            options: options
        });
    });
});
