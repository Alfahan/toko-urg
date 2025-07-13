<template>
    <Head>
        <title>Edit Product - Point Of Sale</title>
    </Head>
    <main class="c-main">
        <div class="container-fluid">
            <div class="fade-in">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card border-0 rounded-3 shadow border-top-purple">
                            <div class="card-header">
                                <span class="font-weight-bold"><i class="fa fa-shopping-bag"></i> EDIT PRODUCT</span>
                            </div>
                            <div class="card-body">

                                <form @submit.prevent="submit">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Barcode</label>
                                                <input class="form-control" v-model="form.barcode" :class="{ 'is-invalid': errors.barcode }" type="text" placeholder="Barcode / Code Product">
                                            </div>
                                            <div v-if="errors.barcode" class="alert alert-danger">
                                                {{ errors.barcode }}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Unit Of Measurement</label>
                                                <select class="form-select" :class="{ 'is-invalid': errors.unit_of_measurement_id }" v-model="form.unit_of_measurement_id">
                                                    <option v-for="(unit_of_measurement, index) in unit_of_measurements" :key="index" :value="unit_of_measurement.id">{{ unit_of_measurement.name }}</option>
                                                </select>
                                            </div>
                                            <div v-if="errors.unit_of_measurement_id" class="alert alert-danger">
                                                {{ errors.unit_of_measurement_id }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Title Product</label>
                                                <input class="form-control" v-model="form.title" :class="{ 'is-invalid': errors.title }" type="text" placeholder="Title Product">
                                            </div>
                                            <div v-if="errors.title" class="alert alert-danger">
                                                {{ errors.title }}
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="fw-bold">Stock</label>
                                                <input class="form-control" v-model="form.stock" :class="{ 'is-invalid': errors.stock }" type="number" placeholder="Stock">
                                            </div>
                                            <div v-if="errors.stock" class="alert alert-danger">
                                                {{ errors.stock }}
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="fw-bold">Stock Minimal</label>
                                                <input class="form-control" v-model="form.stock_minimal" :class="{ 'is-invalid': errors.stock_minimal }" type="number" placeholder="Stock Minimal">
                                            </div>
                                            <div v-if="errors.stock_minimal" class="alert alert-danger">
                                                {{ errors.stock_minimal }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="fw-bold">Description</label>
                                        <textarea class="form-control" v-model="form.description" :class="{ 'is-invalid': errors.description }" type="text" rows="4" placeholder="Description"></textarea>
                                    </div>
                                    <div v-if="errors.description" class="alert alert-danger">
                                        {{ errors.description }}
                                    </div>

                                    <div class="mb-3">
                                        <label class="fw-bold">Buy Price</label>
                                        <input class="form-control" v-model="form.buy_price" @keyup="update" :class="{ 'is-invalid': errors.buy_price }" type="number" placeholder="Buy Price">
                                    </div>
                                    <div v-if="errors.buy_price" class="alert alert-danger">
                                        {{ errors.buy_price }}
                                    </div>

                                    <div class="mb-3">
                                        <label class="fw-bold">Sell Price </label>
                                        <input class="form-control" v-model="form.sell_price" @keyup="update" :class="{ 'is-invalid': errors.sell_price }" type="number" placeholder="Sell Price ">
                                    </div>
                                    <div v-if="errors.sell_price" class="alert alert-danger">
                                        {{ errors.sell_price }}
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <button class="btn btn-primary shadow-sm rounded-sm" type="submit"
                                            :disabled="
                                                form.buy_price <= 0 ||
                                                form.sell_price <= 0"
                                            >UPDATE</button>
                                            <button class="btn btn-warning shadow-sm rounded-sm ms-3" type="reset">RESET</button>
                                        </div>
                                    </div>
                                </form>

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

    //import reactive from vue
    import { reactive } from 'vue';

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
            Link
        },

        //props
        props: {
            errors: Object,
            unit_of_measurements: Array,
            product: Object
        },

        //composition API
        setup(props) {
            //define form with reactive
            const form = reactive({
                barcode: props.product.barcode,
                unit_of_measurement_id: props.product.unit_of_measurement_id,
                title: props.product.title,
                description: props.product.description,
                buy_price: props.product.buy_price,
                sell_price: props.product.sell_price,
                stock: props.product.stock,
                stock_minimal: props.product.stock_minimal
            });

            const update = () => {
                form.buy_price = form.buy_price;
                form.sell_price = form.sell_price;
                form.stock_minimal = form.stock_minimal;
            }

            //method "submit"
            const submit = () => {

                //send data to server
                Inertia.post(`/apps/products/${props.product.id}`, {
                    //data
                    _method: 'PUT',
                    barcode: form.barcode,
                    unit_of_measurement_id: form.unit_of_measurement_id,
                    title: form.title,
                    description: form.description,
                    buy_price: form.buy_price,
                    sell_price: form.sell_price,
                    stock: form.stock,
                    stock_minimal: form.stock_minimal
                }, {
                    onSuccess: () => {
                        //show success alert
                        Swal.fire({
                            title: 'Success!',
                            text: 'Product updated successfully.',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    },
                });

            }

            return {
                form,
                submit,
                update,
            }

        }
    }
</script>

<style>

</style>
