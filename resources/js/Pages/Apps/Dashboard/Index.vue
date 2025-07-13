<template>

    <Head>
        <title>Dashboard - Point Of Sale</title>
    </Head>

    <main class="c-main">
        <div class="container-fluid">
            <div class="fade-in">
                <div class="row">
                    <div class="col-md-12">
                        <div class="header">
                            <h1>TRANSACTION</h1>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div v-if="hasAnyPermission(['dashboard.sales_chart'])" class="card border-0 rounded-3 shadow border-top-purple">
                            <div class="card-header">
                                <span class="font-weight-bold"><i class="fa fa-chart-bar"></i> SALES CHART 7 DAYS</span>
                            </div>
                            <div class="card-body">
                                <BarChart :chartData="chartSellWeek" :options="options" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div v-if="hasAnyPermission(['dashboard.best_selling_product'])" class="card border-0 rounded-3 shadow border-top-warning">
                            <div class="card-header">
                                <span class="font-weight-bold"><i class="fa fa-chart-pie"></i> BEST SELIING PRODUCT</span>
                            </div>
                            <div class="card-body">
                                <DoughnutChart :chartData="chartBestProduct" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div v-if="hasAnyPermission(['dashboard.sales_today'])" class="card border-0 rounded-3 shadow border-top-info mb-4">
                            <div class="card-header">
                                <span class="font-weight-bold"><i class="fa fa-chart-line"></i> SALES TODAY</span>
                            </div>
                            <div class="card-body">
                                <strong>
                                    {{ count_sales_today }}
                                </strong> SALES
                                <hr>
                                <h5 class="fw-bold">Rp. {{ formatPrice(sum_sales_today) }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div v-if="hasAnyPermission(['dashboard.sales_month'])" class="card border-0 rounded-3 shadow border-top-info mb-4">
                            <div class="card-header">
                                <span class="font-weight-bold"><i class="fa fa-chart-line"></i> SALES MONTH</span>
                            </div>
                            <div class="card-body">
                                <strong>
                                    {{ count_sales_month }}
                                </strong> SALES
                                <hr>
                                <h5 class="fw-bold">Rp. {{ formatPrice(sum_sales_month) }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div v-if="hasAnyPermission(['dashboard.profits_today'])" class="card border-0 rounded-3 shadow border-top-success">
                            <div class="card-header">
                                <span class="font-weight-bold"><i class="fa fa-chart-bar"></i> PROFITS TODAY</span>
                            </div>
                            <div class="card-body">
                                <h5 class="fw-bold">Rp. {{ formatPrice(sum_profits_today) }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div v-if="hasAnyPermission(['dashboard.profits_month'])" class="card border-0 rounded-3 shadow border-top-success">
                            <div class="card-header">
                                <span class="font-weight-bold"><i class="fa fa-chart-bar"></i> PROFITS MONTH</span>
                            </div>
                            <div class="card-body">
                                <h5 class="fw-bold">Rp. {{ formatPrice(sum_profits_month) }}</h5>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div v-if="hasAnyPermission(['dashboard.sales_all_transaction_month'])" class="card border-0 rounded-3 shadow border-top-success">
                            <div class="card-header">
                                <span class="font-weight-bold"><i class="fa fa-chart-bar"></i> SALES ALL TRANSACTION MONTH</span>
                            </div>
                            <div class="card-body">
                                <h5 class="fw-bold">Rp. {{ formatPrice(sales_all_transaction_month) }}</h5>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div v-if="hasAnyPermission(['dashboard.profit_all_transaction_month'])" class="card border-0 rounded-3 shadow border-top-success">
                            <div class="card-header">
                                <span class="font-weight-bold"><i class="fa fa-chart-bar"></i> PROFIT ALL TRANSACTION MONTH</span>
                            </div>
                            <div class="card-body">
                                <h5 class="fw-bold">Rp. {{ formatPrice(profit_all_transaction_month) }}</h5>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div v-if="hasAnyPermission(['dashboard.cost_month'])" class="card border-0 rounded-3 shadow border-top-success">
                            <div class="card-header">
                                <span class="font-weight-bold"><i class="fa fa-chart-bar"></i> COST MONTH</span>
                            </div>
                            <div class="card-body">
                                <h5 class="fw-bold">Rp. {{ formatPrice(cost_month) }}</h5>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div v-if="hasAnyPermission(['dashboard.profit_net_month'])"  class="card border-0 rounded-3 shadow border-top-success">
                            <div class="card-header">
                                <span class="font-weight-bold"><i class="fa fa-chart-bar"></i> PROFIT NET MONTH</span>
                            </div>
                            <div class="card-body">
                                <h5 class="fw-bold">Rp. {{ formatPrice(profit_net_month) }}</h5>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div v-if="hasAnyPermission(['dashboard.assets'])" class="card border-0 rounded-3 shadow border-top-success">
                            <div class="card-header">
                                <span class="font-weight-bold"><i class="fa fa-chart-bar"></i> ASSET</span>
                            </div>
                            <div class="card-body">
                                <h5 class="fw-bold">Rp. {{ formatPrice(assets) }}</h5>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div v-if="hasAnyPermission(['dashboard.product_stock'])" class="card border-0 rounded-3 shadow border-top-success">
                            <div class="card-header">
                                <span class="font-weight-bold"><i class="fa fa-box-open"></i> PRODUCT FAVORITE STOCK</span>
                            </div>
                            <div class="card-body">
                                <div v-if="products_limit_stock.length > 0">
                                    <ol class="list-group list-group-numbered">
                                        <li v-for="product in products_limit_stock" :key="product.id" class="list-group-item d-flex justify-content-between align-items-start">
                                            <div class="ms-2 me-auto">
                                                <div class="fw-bold">{{ product.title }}</div>
                                            </div>
                                            <template v-if="parseInt(product.stock) < parseInt(product.stock_minimal)">
                                                <span class="badge bg-danger rounded-pill">less {{ parseInt(product.stock_minimal) - parseInt(product.stock) }} than {{ parseInt(product.stock_minimal) }}</span>
                                                <span class="badge bg-danger rounded-pill">Rp.{{ formatPrice((parseInt(product.buy_price) * parseInt(product.stock)) - (parseInt(product.buy_price) * parseInt(product.stock_minimal))) }}</span>
                                            </template>
                                            <template v-else>
                                                <span class="badge bg-success rounded-pill">more {{ parseInt(product.stock) - parseInt(product.stock_minimal) }} than {{ parseInt(product.stock_minimal) }}</span>
                                                <span class="badge bg-success rounded-pill">Rp.{{ formatPrice((parseInt(product.buy_price) * parseInt(product.stock)) - (parseInt(product.buy_price) * parseInt(product.stock_minimal))) }}</span>
                                            </template>
                                        </li>
                                    </ol>
                                </div>
                                <div v-else class="alert alert-danger border-0 shadow rounded-3">
                                    Data Tidak Tersedia!.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
</template>

<script>
    //import layout
    import LayoutApp from '../../../Layouts/App.vue';

    //import Heade and useForm from Inertia
    import { Head } from '@inertiajs/inertia-vue3';

    //import ref from vue
    import { ref } from 'vue';

    //chart
    import { BarChart, DoughnutChart } from 'vue-chart-3';
    import { Chart, registerables } from "chart.js";

    //register chart
    Chart.register(...registerables);

    export default {

        //layout
        layout: LayoutApp,

        //register component
        components: {
            Head,
            BarChart,
            DoughnutChart
        },

        props: {
            //total penjualan hari ini 
            count_sales_today: Number,

            //total penjualan bulan ini 
            count_sales_month: Number,

            //jumlah (Rp.) penjualan hari ini 
            sum_sales_today: Number,

            //jumlah (Rp.) penjualan hari ini 
            sum_sales_month: Number,

            //jumlah profit/laba hari ini 
            sum_profits_today: Number,

            //jumlah profit/laba hari ini 
            sum_profits_month: Number,

            //chart sales 
            sales_date: Array,
            grand_total: Array,

            //produk terlaris 
            product: Array,
            total: Array,

            //produk limit stock
            products_limit_stock: Array,

            // Jumlah sales bulan ini dari seluruh transaction
            sales_all_transaction_month: Number,

            // Jumlah profit/laba bulan ini dari seluruh transaction
            profit_all_transaction_month: Number,

            // Jumlah cost/bulan
            cost_month: Number,

            // Profit Nett
            profit_net_month: Number,

            // asset
            assets: Number
        },

        setup(props) {

            //method random color
            function randomBackgroundColor(length) {
                var data = [];
                for (var i = 0; i < length; i++) {
                    data.push(getRandomColor());
                }
                return data;
            }

            //method generate random color
            function getRandomColor() {
                var letters = '0123456789ABCDEF'.split('');
                var color = '#';
                for (var i = 0; i < 6; i++) {
                    color += letters[Math.floor(Math.random() * 16)];
                }
                return color;
            }

            //option chart
            const options = ref({
                responsive: true,
                plugins: {
                    legend: {
                        display: false,
                    },
                    title: {
                        display: false,
                    },
                },
                beginZero: true
            });

            //chart sell week
            const chartSellWeek = {
                labels: props.sales_date,
                datasets: [{
                    data: props.grand_total,
                    backgroundColor: randomBackgroundColor(props.sales_date.length),
                }, ],
            };

            //chart produk terlaris
            const chartBestProduct = {
                labels: props.product,
                datasets: [{
                    data: props.total,
                    backgroundColor: randomBackgroundColor(5),
                }, ],
            };

            return {
                options,

                chartSellWeek,
                chartBestProduct,
            }

        }
    }
</script>

<style>

</style>
