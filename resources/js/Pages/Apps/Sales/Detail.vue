<template>
    <Head>
        <title>Detail Sales  - Point Of Sale</title>
    </Head>

    <main class="c-main">
        <div class="content">

            <div class="container-fluid">
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card border-0 rounded-3 shadow border-top-purple">
                                <div class="card-header">
                                    <span class="font-weight-bold"><i class="fa fa-user-circle"></i> {{ transaction.invoice }}</span>
                                </div>

                                <div class="card-body" style="text-align: right;">
                                    <table class="transaction-table" style="text-align: right;" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td>TANGGAL</td>
                                            <td>:</td>
                                            <td>{{ transaction.created_at }}</td>
                                        </tr>
                                        <tr>
                                            <td>FAKTUR</td>
                                            <td>:</td>
                                            <td>{{ transaction.invoice }}</td>
                                        </tr>
                                        <tr>
                                            <td>KASIR</td>
                                            <td>:</td>
                                            <td>{{ transaction.cashier.name }}</td>
                                        </tr>
                                        <tr>
                                            <td>PEMBELI</td>
                                            <td>:</td>
                                            <td>{{ transaction.customer ? transaction.customer.name : 'Umum' }}</td>
                                        </tr>
                                    </table>

                                </div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th scope="col">PRODUK</th>
                                                    <th scope="col">HARGA</th>
                                                    <th scope="col">QTY</th>
                                                    <th scope="col">SUB TOTAL</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(item, index) in transaction.transaction_details" :key="index">
                                                    <td>{{ item.product.title }}</td>
                                                    <td>Rp. {{ formatPrice(item.price_per_qty) }}</td>
                                                    <td>{{ item.qty }}</td>
                                                    <td>Rp. {{ formatPrice(item.price) }}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" class="text-end fw-bold" style="background-color: #e6e6e7;">
                                                        TOTAL
                                                    </td>
                                                    <td class="text-end fw-bold" style="background-color: #e6e6e7;">
                                                        Rp. {{ formatPrice(transaction.grand_total) }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
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

    //import Heade and Link from Inertia
    import { Head, Link } from '@inertiajs/inertia-vue3';

    export default {
        //layout
        layout: LayoutApp,

        //register components
        components: {
            Head,
            Link,
        },

        //props
        props: {
            transaction: Object,
        },
    }
</script>

<style>

</style>
