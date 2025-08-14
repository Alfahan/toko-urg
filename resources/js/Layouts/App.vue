<template>
    <div class="c-app">
        <div :class="['c-sidebar c-sidebar-dark c-sidebar-fixed', { 'c-sidebar-show': isSidebarVisible }]" id="sidebar">

            <div class="position-relative">
                <div class="c-sidebar-brand d-lg-down-none" style="background: #2769a0;">
                <img src="/images/icon-cashier.png" class="bg-light rounded shadow-sm p-1" width="35">
                <span class="ml-2 font-weight-bold text-white">Point Of Sale</span>
                </div>

                <!-- Tombol Close -->
                <button
                    class="btn btn-close-sidebar d-lg-none"
                    type="button"
                    @click="toggleSidebar"
                aria-label="Close sidebar">
                ✕
                </button>
            </div>

            <!-- sidebar -->
            <Sidebar />
            <!-- end sidebar -->
        </div>
        <div class="c-wrapper c-fixed-components">

        <!-- header -->
        <Header />
        <!-- end header -->

        <div class="c-body">

            <!-- content -->
            <slot />
            <!-- end content -->

            <footer class="c-footer">
            <div><strong>Toko URG</strong> &copy; 2025 - Toko URG</div>
            </footer>
        </div>
        </div>
    </div>
</template>

<script>
    import Header from '../Components/Header.vue'
    import Sidebar from '../Components/Sidebar.vue'
    import { ref } from 'vue'

    export default {
        components: { Header, Sidebar },

        setup() {
            const isSidebarVisible = ref(false)

            const toggleSidebar = () => {
                isSidebarVisible.value = !isSidebarVisible.value
            }

            return {
                isSidebarVisible,
                toggleSidebar
            }
        }
    }
</script>

<style>
    .btn-close-sidebar {
        position: absolute;
        top: 2px;
        right: 12px;
        font-size: 1.5rem;
        color: white;
        background: transparent;
        border: none;
        z-index: 9999; /* pastikan berada di atas link */
    }

    .c-sidebar .c-sidebar-brand {
        padding-right: 40px; /* beri ruang di kanan supaya tidak nabrak */
    }
</style>
