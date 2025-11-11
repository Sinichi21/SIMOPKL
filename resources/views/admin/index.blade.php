<x-admin.layout>

    <!-- Page Heading -->
    <div class="mb-4 d-sm-flex align-items-center justify-content-between">
        <h1 class="mb-0 text-gray-800 h3">Dashboard</h1>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="mb-4 col-xl-3 col-md-6">
            <div class="py-2 shadow card border-left-primary h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="mr-2 col">
                            <div class="mb-1 text-xs font-weight-bold text-primary text-uppercase">
                                Awardees</div>
                            <div class="mb-0 text-gray-800 h5 font-weight-bold">{{$awardeeCount}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="text-gray-300 fas fa-user-graduate fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="mb-4 col-xl-3 col-md-6">
            <div class="py-2 shadow card border-left-warning h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="mr-2 col">
                            <div class="mb-1 text-xs font-weight-bold text-warning text-uppercase">
                                Need Approval</div>
                            <div class="mb-0 text-gray-800 h5 font-weight-bold">{{$approvalCount}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="text-gray-300 fas fa-user-graduate fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="mb-4 col-xl-3 col-md-6">
            <div class="py-2 shadow card border-left-success h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="mr-2 col">
                            <div class="mb-1 text-xs font-weight-bold text-success text-uppercase">
                                Complaint</div>
                            <div class="mb-0 text-gray-800 h5 font-weight-bold">{{$complaintCount}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="text-gray-300 fas fa-comments fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="mb-4 col-xl-3 col-md-6">
            <div class="py-2 shadow card border-left-info h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="mr-2 col">
                            <div class="mb-1 text-xs font-weight-bold text-info text-uppercase">FAQ
                            </div>
                            <div class="mb-0 text-gray-800 h5 font-weight-bold">{{$faqCount}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="text-gray-300 fas fa-question-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->

    <div class="row">

        <!-- Area Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="mb-4 shadow card">
                <!-- Card Header - Dropdown -->
                <div class="flex-row py-3 card-header d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Aduan dalam beberapa bulan terakhir</h6>
                    {{-- <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="text-gray-400 fas fa-ellipsis-v fa-sm fa-fw"></i>
                        </a>
                        <div class="shadow dropdown-menu dropdown-menu-right animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Dropdown Header:</div>
                            <a class="dropdown-item" href="#">Action</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Something else here</a>
                        </div>
                    </div> --}}
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="complaintAreaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="mb-4 shadow card">
                <!-- Card Header - Dropdown -->
                <div class="flex-row py-3 card-header d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Jenis aduan</h6>
                    {{-- <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="text-gray-400 fas fa-ellipsis-v fa-sm fa-fw"></i>
                        </a>
                        <div class="shadow dropdown-menu dropdown-menu-right animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Dropdown Header:</div>
                            <a class="dropdown-item" href="#">Action</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Something else here</a>
                        </div>
                    </div> --}}
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="pt-4 pb-2 chart-pie">
                        <canvas id="complaintPieChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        @foreach ($complaintTypes as $complaintType)
                        <span class="mr-2">
                            {{$complaintType->title}}
                        </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('script')
    <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>

    <script type="text/javascript">
        var complaintInLastSixMonth = @json($complaintInLastSixMonth);
        var months = [];
        var counts = [];

        complaintInLastSixMonth.forEach(item => {
            // console.log(item);
            months.push(item.month);
            counts.push(item.count);
        });

        // console.log(months);
        // console.log(counts);

        // Area Chart Example
        var ctx = document.getElementById("complaintAreaChart");
        var myLineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                label: "Complaints",
                lineTension: 0.3,
                backgroundColor: "rgba(78, 115, 223, 0.05)",
                borderColor: "rgba(78, 115, 223, 1)",
                pointRadius: 3,
                pointBackgroundColor: "rgba(78, 115, 223, 1)",
                pointBorderColor: "rgba(78, 115, 223, 1)",
                pointHoverRadius: 3,
                pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                pointHitRadius: 10,
                pointBorderWidth: 2,
                data: counts,
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                padding: {
                    left: 10,
                    right: 25,
                    top: 25,
                    bottom: 0
                }
                },
                scales: {
                    xAxes: [{
                        time: {
                        unit: 'date'
                        },
                        gridLines: {
                        display: false,
                        drawBorder: false
                        },
                        ticks: {
                        maxTicksLimit: 7
                        }
                    }],
                    yAxes: [{
                        ticks: {
                        maxTicksLimit: 5,
                        padding: 10,
                        // Include a dollar sign in the ticks
                        // callback: function(value, index, values) {
                        //     return '$' + number_format(value);
                        // }
                        },
                        gridLines: {
                        color: "rgb(234, 236, 244)",
                        zeroLineColor: "rgb(234, 236, 244)",
                        drawBorder: false,
                        borderDash: [2],
                        zeroLineBorderDash: [2]
                        }
                    }],
                },
                legend: {
                    display: false
                },
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    intersect: false,
                    mode: 'index',
                    caretPadding: 10,
                    // callbacks: {
                    //     label: function(tooltipItem, chart) {
                    //     var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                    //     return datasetLabel + ': $' + number_format(tooltipItem.yLabel);
                    //     }
                    // }
                }
            }
        });
    </script>

    <script type="text/javascript">
        // Set new default font family and font color to mimic Bootstrap's default styling
        (Chart.defaults.global.defaultFontFamily = "Nunito"),
            '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
        Chart.defaults.global.defaultFontColor = "#858796";

        var complaintTypes = @json($complaintTypes);
        var labels = [];
        var data = [];
        var backgroundColor = [];
        var hoverBackgroundColor = [];

        // Function to generate a random color in hex format
        function getRandomColor() {
            const letters = '0123456789ABCDEF';
            let color = '#';
            for (let i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }

        const newShade = (hexColor, magnitude) => {
            hexColor = hexColor.replace(`#`, ``);
            if (hexColor.length === 6) {
                const decimalColor = parseInt(hexColor, 16);
                let r = (decimalColor >> 16) + magnitude;
                r > 255 && (r = 255);
                r < 0 && (r = 0);
                let g = (decimalColor & 0x0000ff) + magnitude;
                g > 255 && (g = 255);
                g < 0 && (g = 0);
                let b = ((decimalColor >> 8) & 0x00ff) + magnitude;
                b > 255 && (b = 255);
                b < 0 && (b = 0);
                return `#${(g | (b << 8) | (r << 16)).toString(16)}`;
            } else {
                return hexColor;
            }
        };

        complaintTypes.forEach(item => {
            console.log(item);
            labels.push(item.title)
            data.push(item.complaints_count)

            color = getRandomColor()
            backgroundColor.push(color)
            hoverBackgroundColor.push(newShade(color, -10))
        })

        // Pie Chart Example
        var ctx = document.getElementById("complaintPieChart");
        var myPieChart = new Chart(ctx, {
            type: "doughnut",
            data: {
                labels: labels,
                datasets: [
                    {
                        data: data,
                        backgroundColor: backgroundColor,
                        hoverBackgroundColor: hoverBackgroundColor,
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    },
                ],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: "#dddfeb",
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                legend: {
                    display: false,
                },
                cutoutPercentage: 80,
            },
        });
    </script>
    @endsection

</x-admin.layout>
