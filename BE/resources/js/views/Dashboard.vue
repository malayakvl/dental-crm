<template>
    <div class="mt-4 mb-4 ml-5 mr-5 dashboard-content">
        <h1>Dashboard</h1>
        <div class="row mb-3 mt-3">
            <div class="col-lg-6 col-md-12 col-sm-12 col-xl-3">
                <div class="card overflow-hidden">
                    <div class="card-body mt-2">
                        <a-timeline>
                            <a-timeline-item>Create a services site 2015-09-01</a-timeline-item>
                            <a-timeline-item>Solve initial network problems 2015-09-01</a-timeline-item>
                            <a-timeline-item>Technical testing 2015-09-01</a-timeline-item>
                            <a-timeline-item>Network problems being solved 2015-09-01</a-timeline-item>
                        </a-timeline>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-lg-6 col-md-12 col-sm-12 col-xl-3">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h6 class="">Клиники</h6>
                                <h3 class="mb-2 number-font">34,516</h3>
                                <p class="text-muted mb-0">
                            <span class="text-primary">
                                <i class="fa fa-chevron-circle-up text-primary me-1"></i> 3%
                            </span>
                                    last month
                                </p>
                            </div>
                            <div class="col col-auto">
                                <div class="counter-icon bg-primary-gradient box-shadow-primary brround ms-auto">
                                    <i class="fas fa-clinic-medical text-white mb-5"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xl-3">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h6 class="">Пациенты</h6>
                                <h3 class="mb-2 number-font">34,516</h3>
                                <p class="text-muted mb-0">
                                <span class="text-primary">
                                    <i class="fa fa-chevron-circle-up text-primary me-1"></i> 3%
                                </span>
                                    last month
                                </p>
                            </div>
                            <div class="col col-auto">
                                <div class="counter-icon bg-danger-gradient box-shadow-danger brround  ms-auto">
                                    <i class="fas fa-users  text-white mb-5"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xl-3">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h6 class="">Сотрудники</h6>
                                <h3 class="mb-2 number-font">34,516</h3>
                                <p class="text-muted mb-0">
                                <span class="text-primary">
                                    <i class="fa fa-chevron-circle-up text-primary me-1"></i> 3%
                                </span>
                                    last month
                                </p>
                            </div>
                            <div class="col col-auto">
                                <div class="counter-icon bg-success-gradient box-shadow-success brround  ms-auto">
                                    <i class="fas fa-user-md text-white mb-5"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xl-3">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h6 class="">Доход</h6>
                                <h3 class="mb-2 number-font">34,516</h3>
                                <p class="text-muted mb-0">
                                <span class="text-primary">
                                    <i class="fa fa-chevron-circle-up text-primary me-1"></i> 3%
                                </span>
                                    last month
                                </p>
                            </div>
                            <div class="col col-auto">
                                <div class="counter-icon bg-secondary-gradient box-shadow-secondary brround ms-auto">
                                    <i class="fas fa-hryvnia  text-white mb-5"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-9">
                <div class="card">
                    <div class="card-header"><h3 class="card-title">Total Transactions</h3></div>
                    <div class="card-body pb-0">
                        <GChart
                            type="ComboChart"
                            :data="chartData"
                            :options="chartOptions"
                        />
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-3">
                <div class="card">
                    <div class="card-header"><h3 class="card-title">Recent visits</h3></div>
                    <div class="card-body pb-0">
                        <GChart
                            type="PieChart"
                            :data="chartData"
                            :options="chartOptions"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { GChart } from 'vue-google-charts'

export default {
    components: {
        GChart,
    },
    data() {
        return {
            chartData: [
                ['Month', 'Bolivia', 'Ecuador', 'Madagascar', 'Papua New Guinea', 'Rwanda', 'Average'],
                ['2004/05',  165,      938,         522,             998,           450,      614.6],
                ['2005/06',  135,      1120,        599,             1268,          288,      682],
                ['2006/07',  157,      1167,        587,             807,           397,      623],
                ['2007/08',  139,      1110,        615,             968,           215,      609.4],
                ['2008/09',  136,      691,         629,             1026,          366,      569.6]
            ],
            chartOptions: {
                title : 'Monthly Coffee Production by Country',
                vAxis: {title: 'Cups'},
                hAxis: {title: 'Month'},
                seriesType: 'bars',
                series: {5: {type: 'line'}}
            }
            // chartData: [
            //     ['Year', 'Sales', 'Expenses', 'Profit'],
            //     ['2014', 1000, 400, 200],
            //     ['2015', 1170, 460, 250],
            //     ['2016', 660, 1120, 300],
            //     ['2017', 1030, 540, 350]
            // ],
            // chartOptions: {
            //     chart: {
            //         title: 'Company Performance',
            //         subtitle: 'Sales, Expenses, and Profit: 2014-2017',
            //     }
            // },

        }
    },
    mounted() {
    },
    methods: {
    }
}
</script>
