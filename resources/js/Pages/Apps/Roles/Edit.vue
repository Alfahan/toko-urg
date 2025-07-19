<template>
    <Head>
        <title>Edit Role - Point Of Sale</title>
    </Head>
    <main class="c-main">
        <div class="container-fluid">
            <div class="fade-in">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card border-0 rounded-3 shadow border-top-purple">
                            <div class="card-header">
                                <span class="font-weight-bold"><i class="fa fa-shield-alt"></i> Edit Role</span>
                            </div>
                            <div class="card-body">
                                <form @submit.prevent="submit">

                                    <!-- Role Name -->
                                    <div class="mb-3">
                                        <label class="fw-bold">Role Name</label>
                                        <input
                                            class="form-control"
                                            v-model="form.name"
                                            :class="{ 'is-invalid': errors.name }"
                                            type="text"
                                            placeholder="Role Name"
                                        />
                                        <div v-if="errors.name" class="alert alert-danger mt-2">
                                            {{ errors.name }}
                                        </div>
                                    </div>

                                    <!-- Permissions -->
                                    <hr />
                                    <div class="mb-3">
                                        <label class="fw-bold">Permissions</label>
                                        <div class="d-flex justify-content-end mb-2">
                                            <button class="btn btn-sm btn-secondary" type="button" @click="toggleSelectAll">
                                                {{ isAllSelected ? 'Deselect All' : 'Select All' }}
                                            </button>
                                        </div>
                                        <div class="row">
                                            <div
                                                class="col-md-4 mb-2"
                                                v-for="permission in permissions"
                                                :key="permission.id"
                                            >
                                                <div class="form-check">
                                                    <input
                                                        class="form-check-input"
                                                        type="checkbox"
                                                        :id="`check-${permission.id}`"
                                                        :value="permission.name"
                                                        v-model="form.permissions"
                                                    />
                                                    <label class="form-check-label" :for="`check-${permission.id}`">
                                                        {{ permission.name }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <button class="btn btn-primary shadow-sm rounded-sm" type="submit">Update</button>
                                            <button class="btn btn-warning shadow-sm rounded-sm ms-3" type="reset">Reset</button>
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
import LayoutApp from '../../../Layouts/App.vue';
import { Head } from '@inertiajs/inertia-vue3';
import { reactive, computed } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import Swal from 'sweetalert2';

export default {
    layout: LayoutApp,
    components: { Head },

    props: {
        errors: Object,
        permissions: Array, // [{id: 1, name: 'user.create'}, ...]
        role: Object,        // { id: 2, name: 'Admin', permissions: [{name: 'user.create'}, ...] }
    },

    setup(props) {
        const form = reactive({
            name: props.role.name,
            permissions: props.role.permissions.map(p => p.name),
        });

        const isAllSelected = computed(() => {
            return form.permissions.length === props.permissions.length;
        });

        const toggleSelectAll = () => {
            if (isAllSelected.value) {
                form.permissions = [];
            } else {
                form.permissions = props.permissions.map(p => p.name);
            }
        };

        const submit = () => {
            Inertia.post(`/apps/roles/${props.role.id}`, {
                name: form.name,
                permissions: form.permissions,
            }, {
                headers: {
                    'X-HTTP-Method-Override': 'PUT',
                },
                onSuccess: () => {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Role updated successfully.',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 2000,
                    });
                },
            });
        };

        return {
            form,
            submit,
            isAllSelected,
            toggleSelectAll,
        };
    },
};
</script>
