<template>
    <Head>
        <title>Unit Of Measurement - Point Of Sale</title>
    </Head>
    <main class="c-main">
        <div class="container-fluid">
            <div class="fade-in">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card border-0 rounded-3 shadow border-top-purple">
                            <div class="card-header">
                                <span class="font-weight-bold"><i class="fa fa-folder"></i> UNIT OF MEASUREMENT</span>
                            </div>
                            <div class="card-body">
                                <form @submit.prevent="handleSearch">
                                    <div class="input-group mb-3">
                                        <Link href="/apps/unit_of_measurement/create" v-if="hasAnyPermission(['unit_of_measurement.create'])" class="btn btn-primary input-group-text"> <i class="fa fa-plus-circle me-2"></i> NEW</Link>
                                        <input type="text" v-model="search" class="form-control" placeholder="search by unit of measurement name...">

                                        <button class="btn btn-primary input-group-text" type="submit"> <i class="fa fa-search me-2"></i> SEARCH</button>

                                    </div>
                                </form>
<div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">Name</th>
                                            <th scope="col">Description</th>
                                            <th scope="col" style="width:20%" v-if="hasAnyPermission(['unit_of_measurement.delete']) || hasAnyPermission(['unit_of_measurement.edit'])">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(unit_of_measurement, index) in unit_of_measurements.data" :key="index">
                                            <td>{{ unit_of_measurement.name }}</td>
                                            <td>{{ unit_of_measurement.description }}</td>
                                            <td class="text-center" v-if="hasAnyPermission(['unit_of_measurement.delete']) || hasAnyPermission(['unit_of_measurement.edit'])">
                                                <Link :href="`/apps/unit_of_measurement/${unit_of_measurement.id}/edit`" v-if="hasAnyPermission(['unit_of_measurement.edit'])" class="btn btn-success btn-sm me-2"><i class="fa fa-pencil-alt me-1"></i> EDIT</Link>
                                                <button @click.prevent="destroy(unit_of_measurement.id)" v-if="hasAnyPermission(['unit_of_measurement.delete'])" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> DELETE</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
</div>
                                <Pagination :links="unit_of_measurements.links" align="end"/>
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

    //import Head and Link from Inertia
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

        //register component
        components: {
            Head,
            Link,
            Pagination
        },

        //props
        props: {
            unit_of_measurements: Object,
        },

        //composition API
        setup() {
            //define state search
            const search = ref('' || (new URL(document.location)).searchParams.get('q'));

            //define method search
            const handleSearch = () => {
            Inertia.get('/apps/unit_of_measurement', {
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

                        Inertia.post(`/apps/unit_of_measurement/${id}`, {}, {
                            headers: {
                                'X-HTTP-Method-Override': 'DELETE'
                            }
                        });

                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Unit Of Measurement deleted successfully.',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false,
                        });
                    }
                })
            }

            //return
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
