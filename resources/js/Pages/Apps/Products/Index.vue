<template>
    <Head>
        <title>Products - Point Of Sale</title>
    </Head>
    <main class="c-main">
        <div class="container-fluid">
            <div class="fade-in">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card border-0 rounded-3 shadow border-top-purple">
                            <div class="card-header">
                                <span class="font-weight-bold"><i class="fa fa-shopping-bag"></i> PRODUCTS</span>
                            </div>
                            <div class="card-body">
                                <form @submit.prevent="handleSearch">
                                    <div class="input-group mb-3">
                                        <Link href="/apps/products/create" v-if="hasAnyPermission(['products.create'])" class="btn btn-primary input-group-text"> <i class="fa fa-plus-circle me-2"></i> NEW</Link>
                                        <input type="text" v-model="search" class="form-control" placeholder="search by product title...">

                                        <button class="btn btn-primary input-group-text" type="submit"> <i class="fa fa-search me-2"></i> SEARCH</button>

                                    </div>
                                </form>
                                <hr>
                                <div class="export text-end mb-3">
                                    <a :href="`/apps/products/import`" class="btn btn-secondary btn-md border-0 shadow me-3"><i class="fa fa-file-excel"></i> IMPORT</a>
                                    <a :href="`/apps/products/export`" target="_blank" class="btn btn-success btn-md border-0 shadow me-3"><i class="fa fa-file-excel"></i> EXCEL</a>
                                    <a :href="`/apps/products/pdf`" target="_blank" class="btn btn-secondary btn-md border-0 shadow"><i class="fa fa-file-pdf"></i> PDF</a>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col">Barcode</th>
                                                <th scope="col">Title</th>
                                                <th scope="col" v-if="hasAnyPermission(['products.buy_price'])">Buy Price</th>
                                                <th scope="col">Sell Price </th>
                                                <th scope="col">Stock</th>
                                                <th scope="col">Stock Minimal</th>
                                                <th scope="col">UoM</th>
                                                <th scope="col" v-if="hasAnyPermission(['products.edit']) || hasAnyPermission(['products.delete'])" style="width:20%">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(product, index) in products.data" :key="index">
                                                <td class="text-center">
                                                    <Barcode
                                                        :value="product.barcode"
                                                        :format="'CODE39'"
                                                        :lineColor="'#000'"
                                                        :width="1"
                                                        :height="20"
                                                    />
                                                </td>
                                                <td>{{ product.title }}</td>
                                                <td v-if="hasAnyPermission(['products.buy_price'])">Rp. {{ formatPrice(product.buy_price) }}</td>
                                                <td>Rp. {{ formatPrice(product.sell_price) }}</td>
                                                <td>{{ product.stock }}</td>
                                                <td>{{ product.stock_minimal }}</td>
                                                <td>{{ product.unit_of_measurement.name }}</td>
                                                <td class="text-center" v-if="hasAnyPermission(['products.edit']) || hasAnyPermission(['products.delete'])">
                                                    <Link :href="`/apps/products/${product.id}/edit`" v-if="hasAnyPermission(['products.edit'])" class="btn btn-success btn-sm me-2"><i class="fa fa-pencil-alt me-1"></i> EDIT</Link>
                                                    <button @click.prevent="destroy(product.id)" v-if="hasAnyPermission(['products.delete'])" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> DELETE</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <Pagination :links="products.links" align="end"/>
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

    //import component pagination
    import Pagination from '../../../Components/Pagination.vue';

    //import Heade and Link from Inertia
    import { Head, Link } from '@inertiajs/inertia-vue3';

    //import ref from vue
    import { ref } from 'vue';

    //import inertia adapter
    import { Inertia } from '@inertiajs/inertia';

    //import sweet alert
    import Swal from 'sweetalert2';

    //import component barcode
    import Barcode from '../../../Components/Barcode.vue';

    export default {
        //layout
        layout: LayoutApp,

        //register components
        components: {
            Head,
            Link,
            Pagination,
            Barcode
        },

        //props
        props: {
            products: Object,
        },

        //composition API
        setup() {
            //define state search
            const search = ref('' || (new URL(document.location)).searchParams.get('q'));

            //define method search
            const handleSearch = () => {
            Inertia.get('/apps/products', {
                    //send params "q" with value from state "search"
                    q: search.value,
                });
            }

            //method "destroy"
            const destroy = (id) => {

                //show confirm
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                })
                .then((result) => {
                    if (result.isConfirmed) {

                        //send to server
                        Inertia.post(`/apps/products/${id}`, {}, {
                            headers: {
                                'X-HTTP-Method-Override': 'DELETE'
                            }
                        });

                        //show alert
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Product deleted successfully.',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false,
                        });
                    }
                })
            }

            return {
                search,
                handleSearch,
                destroy
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
