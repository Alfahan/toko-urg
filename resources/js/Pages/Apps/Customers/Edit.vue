<template>
    <Head>
        <title>Edit Customers - Point Of Sale</title>
    </Head>
    <main class="c-main">
        <div class="container-fluid">
            <div class="fade-in">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card border-0 rounded-3 shadow border-top-purple">
                            <div class="card-header">
                                <span class="font-weight-bold"><i class="fa fa-user-circle"></i> EDIT CUSTOMER</span>
                            </div>
                            <div class="card-body">

                                <form @submit.prevent="submit">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Name</label>
                                                <input class="form-control" v-model="form.name" :class="{ 'is-invalid': errors.name }" type="text" placeholder="Name">
                                            </div>
                                            <div v-if="errors.name" class="alert alert-danger">
                                                {{ errors.name }}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">No. Telp</label>
                                                <input class="form-control" v-model="form.no_telp" :class="{ 'is-invalid': errors.no_telp }" type="text" placeholder="No. Telp">
                                            </div>
                                            <div v-if="errors.no_telp" class="alert alert-danger">
                                                {{ errors.no_telp }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="fw-bold">Address</label>
                                        <textarea class="form-control" v-model="form.address" :class="{ 'is-invalid': errors.address }" type="text" rows="4" placeholder="Address"></textarea>
                                    </div>
                                    <div v-if="errors.address" class="alert alert-danger">
                                        {{ errors.address }}
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <button class="btn btn-primary shadow-sm rounded-sm" type="submit">UPDATE</button>
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

    //import layout App
    import LayoutApp from '../../../Layouts/App.vue';

    //import Heade and Link from Inertia
    import { Head, Link } from '@inertiajs/inertia-vue3';

    //import reactive from vue
    import { reactive } from 'vue';

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
            customer: Object,
            errors: Object
        },

        //composition API
        setup(props) {

            //define form with reactive
            const form = reactive({
                name: props.customer.name,
                no_telp: props.customer.no_telp,
                address: props.customer.address
            });

            //method "submit"
            const submit = () => {

                //send data to server
                Inertia.post(`/apps/customers/${props.customer.id}`, {
                    //data
                    name: form.name,
                    no_telp: form.no_telp,
                    address: form.address
                },{
                    headers: {
                        'X-HTTP-Method-Override': 'PUT'
                    },
                    onSuccess: () => {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Customer updated successfully.',
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
            };

        }
    }
</script>

<style>

</style>
