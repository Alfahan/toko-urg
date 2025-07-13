<template>
    <Head>
        <title>Transactions Cost - Point Of Sale</title>
    </Head>
    <main class="c-main">
        <div class="container-fluid">
            <div class="fade-in">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card border-0 rounded-3 shadow">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Cost Name</label>
                                    <input type="text" class="form-control text-center" v-model="name_cost" placeholder="Cost Name">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Cost Price</label>
                                    <input type="number" class="form-control text-center" v-model="price_per_qty" placeholder="Price">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Qty</label>
                                    <input type="number" v-model="qty" class="form-control text-center" placeholder="Qty" min="1">
                                </div>
                                <div class="text-end">
                                    <button @click.prevent="clearAdd" class="btn btn-warning btn-md border-0 shadow text-uppercase mt-3 me-2">CLEAR</button>
                                    <button @click.prevent="addToCart" class="btn btn-success btn-md border-0 shadow text-uppercase mt-3">ADD ITEM</button>
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
                                            <td>{{ cart.name_cost }}</td>
                                            <td>Rp. {{ formatPrice(cart.price_per_qty) }}</td>
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
                                <div class="text-end mt-4">
                                    <button class="btn btn-warning btn-md border-0 shadow text-uppercase me-2">Cancel</button>
                                    <button @click.prevent="storeTransaction" class="btn btn-purple btn-md border-0 shadow text-uppercase">Add Cost</button>
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

    //import axios
    import axios from 'axios';

    //import ref form vue
    import { ref } from 'vue';

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
            carts_total: Number,
            session: Object,
            carts: Array
        },

        //composition API
        setup(props) {

            //define state
            const name_cost = ref("");
            const price_per_qty = ref(1);
            const qty = ref(1);
            const price = ref(1);
            const product = ({});

            //method "clearSearch"
            const clearAdd = () => {

                //set price_per_qty to "1"
                name_cost.value = "";

                //set price_per_qty to "1"
                price_per_qty.value =1;

                //set qty to "1"
                qty.value = 1;

                //set price to "1"
                price.value =1;

                //set state "product" to empty object
                product.value = {};

            }

            // define state grandTotal
            const grandTotal = ref(props.carts_total);

            //method add to cart
            const addToCart = () => {

                //send data to server
                Inertia.post('/apps/cost_transactions/addToCart', {
                    //data
                    name_cost: name_cost.value,
                    price_per_qty: price_per_qty.value,
                    qty: qty.value,
                    price: price.value,

                }, {
                    onSuccess: () => {

                        //call method "clearSaerch"
                        clearAdd();

                        //update state "grandTotal"
                        grandTotal.value = props.carts_total;
                    },
                });

            }

            //method "destroyCart"
            const destroyCart = (cart_id) => {
                Inertia.post('/apps/cost_transactions/destroyCart', {
                    cart_id: cart_id
                }, {
                    onSuccess: () => {
                        //call method "clearSaerch"
                        clearAdd();

                        // update state "grandTotal"
                        grandTotal.value = props.carts_total;

                    },
                })
            }

            // define state "admin_id"
            const admin_id = ref('');

            const storeTransaction = () => {
                // HTTP request
                axios.post('/apps/cost_transactions/store', {
                    //send data to server
                    admin_id: admin_id.value ? admin_id.value.id : '',
                    grand_total: grandTotal.value,
                }).then(response => {
                    //call method "clearSaerch"
                    clearAdd();

                    //set grandTotal
                    grandTotal.value = props.carts_total;

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
                            window.open(`/apps/cost_transactions/print?invoice=${response.data.data.invoice}`, '_blank');

                            //reload page
                            location.reload();

                        }, 50);
                    })
                })
            }

            return {
                product,
                clearAdd,
                name_cost,
                price_per_qty,
                qty,
                price,
                grandTotal,
                addToCart,
                destroyCart,
                admin_id,
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
