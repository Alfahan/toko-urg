<template>
    <Head>
        <title>Add New Product - Point Of Sale</title>
    </Head>
    <main class="c-main">
        <div class="container-fluid">
            <div class="fade-in">
                <div class="row">
                    <div class="col-md-12">
                        <a href="/assets/excel/product.xlsx" target="_blank"
                            class="btn btn-md btn-success border-0 shadow mb-3 text-white" type="button"><i
                                class="fa fa-file-excel me-2"></i> Contoh Format</a>
                        <div class="card border-0 shadow">
                            <div class="card-body">
                                <h5><i class="fa fa-user"></i> Import Product</h5>
                                <hr>
                                <form @submit.prevent="submit">

                                    <div class="mb-4">
                                        <label>File Excel</label>
                                        <input type="file" class="form-control" @input="form.file = $event.target.files[0]">
                                        <div v-if="errors.file" class="alert alert-danger mt-2">
                                            {{ errors.file }}
                                        </div>
                                        <div v-if="errors[0]" class="alert alert-danger mt-2">
                                            {{ errors[0] }}
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-md btn-primary border-0 shadow me-2">Upload</button>
                                    <button type="reset" class="btn btn-md btn-warning border-0 shadow">Reset</button>
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

    //import sweet alert2
    import Swal from 'sweetalert2';

    import { Inertia } from '@inertiajs/inertia';

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
        },

        //composition API
        setup() {

            const form = reactive({
                file: ''
            });

            const submit = () => {
                Inertia.post('/apps/products/import', {
                    // data
                    file: form.file,
                }, {
                    onSuccess: () => {
                        //show success alert
                        Swal.fire({
                            title: 'Success!',
                            text: 'Import Siswa Berhasil Disimpan!.',
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
            }

        }
    }
</script>

<style>

</style>
