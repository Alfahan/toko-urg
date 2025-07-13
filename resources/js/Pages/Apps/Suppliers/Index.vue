<template>
    <Head>
        <title>Suppliers - Point Of Sale</title>
    </Head>
    <main class="c-main">
        <div class="container-fluid">
            <div class="fade-in">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card border-0 rounded-3 shadow border-top-purple">
                            <div class="card-header">
                                <span class="font-weight-bold"><i class="fa fa-user-circle"></i> SUPPLIERS</span>
                            </div>
                            <div class="card-body">
                                <form @submit.prevent="handleSearch">
                                    <div class="input-group mb-3">
                                        <Link href="/apps/suppliers/create" v-if="hasAnyPermission(['suppliers.create'])" class="btn btn-primary input-group-text"> <i class="fa fa-plus-circle me-2"></i> NEW</Link>
                                        <input type="text" v-model="search" class="form-control" placeholder="search by suppliers name company...">

                                        <button class="btn btn-primary input-group-text" type="submit"> <i class="fa fa-search me-2"></i> SEARCH</button>

                                    </div>
                                </form>
                                <hr>
                                <div class="export text-end mb-3">
                                    <a :href="`/apps/suppliers_report/export`" target="_blank" class="btn btn-success btn-md border-0 shadow me-3"><i class="fa fa-file-excel"></i> EXCEL</a>
                                    <a :href="`/apps/suppliers_report/pdf`" target="_blank" class="btn btn-secondary btn-md border-0 shadow"><i class="fa fa-file-pdf"></i> PDF</a>
                                </div>
<div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">Name Company</th>
                                            <th scope="col">No. Telp</th>
                                            <th scope="col">Address</th>
                                            <th scope="col" style="width:20%" v-if="hasAnyPermission(['suppliers.edit']) || hasAnyPermission(['suppliers.delete'])">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(supplier, index) in suppliers.data" :key="index">
                                            <td>{{ supplier.name_company }}</td>
                                            <td>{{ supplier.no_telp }}</td>
                                            <td>{{ supplier.address }}</td>
                                            <td class="text-center" v-if="hasAnyPermission(['suppliers.edit']) || hasAnyPermission(['suppliers.delete'])">
                                                <Link :href="`/apps/suppliers/${supplier.id}/edit`" v-if="hasAnyPermission(['suppliers.edit'])" class="btn btn-success btn-sm me-2"><i class="fa fa-pencil-alt me-1"></i> EDIT</Link>
                                                <button @click.prevent="destroy(supplier.id)" v-if="hasAnyPermission(['suppliers.delete'])" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> DELETE</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
</div>
                                <Pagination :links="suppliers.links" align="end"/>
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

    //import sweet alert2
    import Swal from 'sweetalert2';

    export default {
        //layout
        layout: LayoutApp,

        //register components
        components: {
            Head,
            Link,
            Pagination
        },

        //props
        props: {
            suppliers: Object,
        },

        //composition API
        setup() {

            //define state search
            const search = ref('' || (new URL(document.location)).searchParams.get('q'));

            //define method search
            const handleSearch = () => {
                Inertia.get('/apps/suppliers', {

                    //send params "q" with value from state "search"
                    q: search.value,
                });
            }

            //method destroy
            const destroy = (id) => {
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

                        Inertia.post(`/apps/suppliers/${id}`, {}, {
                            headers: {
                                'X-HTTP-Method-Override': 'DELETE'
                            }
                        });

                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Suppliers deleted successfully.',
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
