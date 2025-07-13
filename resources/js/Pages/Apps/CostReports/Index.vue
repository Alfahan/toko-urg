<template>
    <Head>
        <title>Report Cost - Point Of Sale</title>
    </Head>
    <main class="c-main">
        <div class="container-fluid">
            <div class="fade-in">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card border-0 rounded-3 shadow border-top-purple">
                            <div class="card-header">
                                <span class="font-weight-bold"><i class="fa fa-chart-bar"></i> REPORT COST</span>
                            </div>
                            <div class="card-body">
                                <form @submit.prevent="searchInvoice">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">INVOICE</label>
                                                <input type="text" v-model="invoice" class="form-control">
                                            </div>
                                            <div v-if="errors.invoice" class="alert alert-danger">
                                                {{ errors.invoice }}
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold text-white">*</label>
                                                <button class="btn btn-md btn-purple border-0 shadow w-100"><i class="fa fa-search"></i> SEARCH</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <form @submit.prevent="filter">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">START DATE</label>
                                                <input type="date" v-model="start_date" class="form-control">
                                            </div>
                                            <div v-if="errors.start_date" class="alert alert-danger">
                                                {{ errors.start_date }}
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">END DATE</label>
                                                <input type="date" v-model="end_date" class="form-control">
                                            </div>
                                            <div v-if="errors.end_date" class="alert alert-danger">
                                                {{ errors.end_date }}
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold text-white">*</label>
                                                <button class="btn btn-md btn-purple border-0 shadow w-100"><i class="fa fa-filter"></i> FILTER</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <div v-if="cost">
                                    <hr>
                                    <div class="export text-end mb-3">
                                        <a :href="`/apps/cost_report/export?start_date=${start_date}&end_date=${end_date}`" target="_blank" class="btn btn-success btn-md border-0 shadow me-3"><i class="fa fa-file-excel"></i> EXCEL</a>
                                        <a :href="`/apps/cost_report/pdf?start_date=${start_date}&end_date=${end_date}`" target="_blank" class="btn btn-secondary btn-md border-0 shadow"><i class="fa fa-file-pdf"></i> PDF</a>
                                    </div>
<div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr style="background-color: #e6e6e7;">
                                                <th scope="col">Date</th>
                                                <th scope="col">Invoice</th>
                                                <th scope="col">Admin</th>
                                                <th scope="col">Name Item</th>
                                                <th scope="col">Price Item</th>
                                                <th scope="col">Qty</th>
                                                <th scope="col">Total</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="item in cost" :key="item.id">
                                                <td>{{ item.created_at }}</td>
                                                <td class="text-center">{{ item.cost_transaction.invoice }}</td>
                                                <td class="text-center">{{ item.cost_transaction.admin.name }}</td>
                                                <td>{{ item.name_cost }}</td>
                                                <td>Rp.{{ formatPrice(item.price_per_qty) }}</td>
                                                <td class="text-end">{{ item.qty }}</td>
                                                <td class="text-end">Rp. {{ formatPrice(item.price) }}</td>
                                                <td>
                                                    <Link :href="`/apps/cost_report/detail_cost/${item.cost_transaction.invoice}`" class="btn btn-success btn-sm me-2 text-uppercase"><i class="fa fa-info me-1"></i> Detail</Link>
                                                    <button @click.prevent="print_invoice(item.cost_transaction.invoice)" class="btn btn-purple btn-md border-0 shadow text-uppercase">Print</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="6" class="text-end fw-bold" style="background-color: #e6e6e7;">TOTAL</td>
                                                <td class="text-end fw-bold" style="background-color: #e6e6e7;">Rp. {{ formatPrice(total) }}</td>
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
    //import layout App
    import LayoutApp from '../../../Layouts/App.vue';

    //import Heade and Link from Inertia
    import { Head, Link } from '@inertiajs/inertia-vue3';

    //import hook ref
    import { ref } from 'vue';

	//import adapter inertia
    import { Inertia } from '@inertiajs/inertia';

    export default {
        //layout App
        layout: LayoutApp,

        //register components
        components: {
            Head,
            Link
        },

        //props
        props: {
            errors: Object,
            cost: Array,
            total: Number
        },

        //composition API
        setup() {

            //define state
            const invoice = ref('' || (new URL(document.location)).searchParams.get('invoice'));
            const start_date = ref('' || (new URL(document.location)).searchParams.get('start_date'));
            const end_date = ref('' || (new URL(document.location)).searchParams.get('end_date'));


            //define methods filter
            const filter = () => {

                //HTTP request
                Inertia.get('/apps/cost_report/filter', {

                    //send data to server
                    start_date: start_date.value,
                    end_date: end_date.value,
                });

            }

            const searchInvoice = () => {
                // HTTP request
                Inertia.get('/apps/cost_report/search', {
                    // send data to server
                    invoice: invoice.value
                })
            }

            // print
            const print_invoice = (invoice) => {

                setTimeout(() => {

                    //print
                    window.open(`/apps/cost_transactions/print?invoice=${invoice}`, '_blank');

                    //reload page
                    location.reload();

                }, 50);
            }


            return {
                invoice,
                start_date,
                end_date,
                searchInvoice,
                filter,
                print_invoice
            }

        }
    }
</script>

<style>
.table-responsive {
    overflow-x: auto;
    width: 100%;
}

</style>
