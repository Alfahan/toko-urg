<template>
    <Head>
        <title>Detail Cost Report - Point Of Sale</title>
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
                                            <td>ADMIN</td>
                                            <td>:</td>
                                            <td>{{ transaction.admin.name }}</td>
                                        </tr>
                                    </table>

                                </div>
                                <div class="title" style="padding-bottom: 13px">

                                    <div class="transaction" style="text-align: center; width: 100%;">
                                        <table class="transaction-table" style="text-align: center; width: 100%;" cellspacing="0" cellpadding="0">
                                            <tr class="price-tr">
                                                <td colspan="3">
                                                    <div class="separate-line" style="border-top: 1px dashed #000;"></div>
                                                </td>
                                                <td colspan="3">
                                                    <div class="separate-line" style="border-top: 1px dashed #000;"></div>
                                                </td>
                                                <td colspan="3">
                                                    <div class="separate-line" style="border-top: 1px dashed #000;"></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: center" colspan="5">PRODUK</td>
                                                <td style="text-align: center">HARGA</td>
                                                <td style="text-align: center">QTY</td>
                                                <td style="text-align: center" colspan="5">SUB TOTAL</td>
                                            </tr>
                                            <tr class="price-tr">
                                                <td colspan="3">
                                                    <div class="separate-line" style="border-top: 1px dashed #000;"></div>
                                                </td>
                                                <td colspan="3">
                                                    <div class="separate-line" style="border-top: 1px dashed #000;"></div>
                                                </td>
                                                <td colspan="3">
                                                    <div class="separate-line" style="border-top: 1px dashed #000;"></div>
                                                </td>
                                            </tr>
                                            <tr v-for="(item, index) in transaction.cost_transaction_details" :key="index">
                                                <td style="text-align: center" colspan="5">{{ item.name_cost }}</td>
                                                <td style="text-align: center">{{ item.price_per_qty }}</td>
                                                <td style="text-align: center">{{ item.qty }}</td>
                                                <td style="text-align: center" colspan="5">{{ formatPrice(item.price) }}</td>
                                            </tr>
                                            <tr class="price-tr">
                                                <td colspan="3">
                                                    <div class="separate-line" style="border-top: 1px dashed #000;"></div>
                                                </td>
                                                <td colspan="3">
                                                    <div class="separate-line" style="border-top: 1px dashed #000;"></div>
                                                </td>
                                                <td colspan="3">
                                                    <div class="separate-line" style="border-top: 1px dashed #000;"></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="final-price" style="text-align: right">
                                                    TOTAL
                                                </td>
                                                <td colspan="3" class="final-price">
                                                    :
                                                </td>
                                                <td class="final-price" style="text-align: center">
                                                    {{ formatPrice(transaction.grand_total) }}
                                                </td>
                                            </tr>

                                            <tr class="price-tr">
                                                <td colspan="3">
                                                    <div class="separate-line" style="border-top: 1px dashed #000;"></div>
                                                </td>
                                                <td colspan="3">
                                                    <div class="separate-line" style="border-top: 1px dashed #000;"></div>
                                                </td>
                                                <td colspan="3">
                                                    <div class="separate-line" style="border-top: 1px dashed #000;"></div>
                                                </td>
                                            </tr>
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
