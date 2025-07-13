<template>
    <Head>
        <title>Purchase - Point Of Sale</title>
    </Head>
    <main class="c-main">
        <div class="container-fluid">
            <div class="fade-in">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card border-0 rounded-3 shadow">
                            <div class="card-body">

                                <div class="input-group mb-3">
                                    <VueMultiselect
                                        v-model="barcode"
                                        :custom-label="customLabel"
                                        track-by="barcode"
                                        :options="products"
                                        @close="searchProduct"
                                        placeholder="Search by barcode or title"
                                    />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Product Name</label>
                                    <input type="text" class="form-control" :value="product.title" placeholder="Product Name" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Qty</label>
                                    <input type="number" v-model="qty" class="form-control text-center" placeholder="Qty" min="1">
                                </div>
                                <div class="text-end">
                                    <button @click.prevent="clearSearch" class="btn btn-warning btn-md border-0 shadow text-uppercase mt-3 me-2" :disabled="!product.id">CLEAR</button>
                                    <button @click.prevent="addToCart" class="btn btn-success btn-md border-0 shadow text-uppercase mt-3" :disabled="!product.id">ADD ITEM</button>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">

                        <div v-if="session.error" class="alert alert-danger">
                            {{ session.error }}
                        </div>

                        <div v-if="session.success" class="alert alert-success">
                            {{ session.success }}
                        </div>

                        <div class="card border-0 rounded-3 shadow border-top-success">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 col-4">
                                        <h4 class="fw-bold">GRAND TOTAL</h4>
                                    </div>
                                    <div class="col-md-8 col-8 text-end">
                                        <h4 class="fw-bold">Rp. {{ formatPrice(grandTotal) }}</h4>
                                        <div v-if="change > 0">
                                            <hr>
                                            <h5 class="text-success">Change : <strong>Rp. {{ formatPrice(change) }}</strong></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 rounded-3 shadow">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="fw-bold">Admin</label>
                                        <input class="form-control" type="text" :value="auth.user.name" readonly>
                                    </div>
                                    <div class="col-md-6 float-end">
                                        <label class="fw-bold">Supplier</label>
                                        <VueMultiselect v-model="supplier_id" label="name_company" track-by="name_company" :options="suppliers"></VueMultiselect>
                                    </div>
                                </div>
                                <hr>
<div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr style="background-color: #e6e6e7;">
                                            <th scope="col">#</th>
                                            <th scope="col">Product Name</th>
                                            <th scope="col">Price</th>
                                            <th scope="col">Qty</th>
                                            <th scope="col">Sub Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="cart in carts" :key="cart.id">
                                            <td class="text-center">
                                                <button @click.prevent="destroyCart(cart.id)" class="btn btn-danger btn-sm rounded-pill"><i class="fa fa-trash"></i></button>
                                            </td>
                                            <td>{{ cart.product.title }}</td>
                                            <td>Rp. {{ formatPrice(cart.product.buy_price) }}</td>
                                            <td class="text-center">{{ cart.qty }}</td>
                                            <td class="text-end">Rp. {{ formatPrice(cart.price) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-end fw-bold" style="background-color: #e6e6e7;">TOTAL</td>
                                            <td class="text-end fw-bold" style="background-color: #e6e6e7;">Rp. {{ formatPrice(carts_total) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
</div>
                                <hr>
                                <div class="d-flex align-items-end flex-column bd-highlight mb-3">
                                    <div class="bd-highlight mt-4">
                                        <label>Pay (Rp.)</label>
                                        <input type="number" v-model="cash" @keyup="setChange" class="form-control" placeholder="Pay (Rp.)">
                                    </div>
                                </div>
                                <div class="text-end mt-4">
                                    <button class="btn btn-warning btn-md border-0 shadow text-uppercase me-2">Cancel</button>

                                    <button v-if="hasAnyPermission(['purchase_transactions.order'])" @click.prevent="storeTransaction" class="btn btn-purple btn-md border-0 shadow text-uppercase me-2" :disabled="cash < grandTotal || grandTotal <= 0 ">Purchase Order & Print</button>

                                    <button v-if="hasAnyPermission(['purchase_transactions.retur'])" @click.prevent="storeTransaction" class="btn btn-danger btn-md border-0 shadow text-uppercase" :disabled="cash > grandTotal ||grandTotal >= 0">Purchase Retur & Print</button>
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

    //import Heade from Inertia
    import { Head } from '@inertiajs/inertia-vue3';

    //import VueMultiselect
    import VueMultiselect from 'vue-multiselect';
    import 'vue-multiselect/dist/vue-multiselect.css';

    //import ref form vue
    import { ref } from 'vue';

    //import axios
    import axios from 'axios';

    //import inerita adapter
    import { Inertia } from '@inertiajs/inertia';

    //import sweet alert2
    import Swal from 'sweetalert2';

    export default {
        //layout
        layout: LayoutApp,

        //register components
        components: {
            Head,
            VueMultiselect
        },

        //props
        props: {
            auth: Object,
            products: Array,
            suppliers: Array,
            carts_total: Number,
            session: Object,
            carts: Array
        },

        //composition API
        setup(props) {

            //define state
            const barcode = ref('');
            const product = ref({});
            const qty = ref(1);

            const customLabel = (option) => {
                return `${option.barcode} - ${option.title}`;
            }

            //metho "searchProduct"
            const searchProduct = async () => {

                //fetch with axios
                await axios.post('/apps/transactions/searchProduct', {

                    //send data "barcode"
                    barcode: barcode.value

                }).then(response => {
                    if(response.data.success) {

                        //assign response to state "product"
                        product.value = response.data.data;
                    } else {

                        //set state "product" to empty object
                        product.value = {};
                    }
                });
            }


            //method "clearSearch"
            const clearSearch = () => {

                //set state "product" to empty object
                product.value = {};

                //set state "barcode" to empty string
                barcode.value = '';
            }

            // define state grandTotal
            const grandTotal = ref(props.carts_total);

            //method add to cart
            const addToCart = () => {

                //send data to server
                Inertia.post('/apps/purchase_transactions/addToCart', {

                    //data
                    product_id: product.value.id,
                    qty: qty.value,
                    buy_price: product.value.buy_price,

                }, {
                    onSuccess: () => {

                        //call method "clearSaerch"
                        clearSearch();

                        //set qty to "1"
                        qty.value = 1;

                        //update state "grandTotal"
                        grandTotal.value = props.carts_total;
                    },
                });

            }


            //method "destroyCart"
            const destroyCart = (cart_id) => {
                Inertia.post('/apps/purchase_transactions/destroyCart', {
                    cart_id: cart_id
                }, {
                    onSuccess: () => {

                        // update state "grandTotal"
                        grandTotal.value = props.carts_total;

                        // set cash to "0"
                        cash.value = 0

                        // set change to "0"
                        change.value = 0
                    },
                })
            }

            //define state "cash", "change"
            const cash      = ref(0);
            const change    = ref(0);

            //method "setChange"
            const setChange = () => {

                //set change
                change.value = cash.value - grandTotal.value;
            }

            // define state "supplier_id"
            const supplier_id = ref('');

            const storeTransaction = () => {
                // HTTP request
                axios.post('/apps/purchase_transactions/store', {
                    //send data to server
                    supplier_id: supplier_id.value ? supplier_id.value.id : '',
                    grand_total: grandTotal.value,
                    cash: cash.value,
                    change: change.value
                }).then(response => {
                    //call method "clearSaerch"
                    clearSearch();

                    //set qty to "1"
                    qty.value = 1;

                    //set grandTotal
                    grandTotal.value = props.carts_total;

                    //set cash to "0"
                    cash.value = 0;

                    //set change to "0"
                    change.value = 0;

                    //set supplier_id to ""
                    supplier_id.value = '';

                    //show success alert
                    Swal.fire({
                        title: 'Success!',
                        text: 'Transaction Successfully.',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {

                        setTimeout(() => {

                            //print
                            window.open(`/apps/purchase_transactions/print?invoice=${response.data.data.invoice}`, '_blank');

                            //reload page
                            location.reload();

                        }, 50);
                    })
                })
            }

            return {
                customLabel,
                barcode,
                product,
                searchProduct,
                clearSearch,
                qty,
                grandTotal,
                addToCart,
                destroyCart,
                cash,
                change,
                setChange,
                supplier_id,
                storeTransaction
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
