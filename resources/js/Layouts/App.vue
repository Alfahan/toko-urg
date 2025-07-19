<template>
    <div class="c-app">
        <div class="c-sidebar c-sidebar-dark c-sidebar-fixed" id="sidebar">

            <button class="c-sidebar-close c-class-toggler d-lg-none" type="button" data-target="#sidebar" data-class="c-sidebar-show"></button>

            <div class="c-sidebar-brand d-lg-down-none" style="background: #2769a0;">
                <img src="/images/icon-cashier.png" class="bg-light rounded shadow-sm p-1" width="35"> <span class="ml-2 font-weight-bold">Point Of Sale</span>
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
            <div><strong>Point Of Sale Toko URG</strong> &copy; 2024 - Toko URG</div>
            </footer>
        </div>
        </div>
    </div>
</template>

<script>

    import Header from '../Components/Header.vue'
    import Sidebar from '../Components/Sidebar.vue'

    export default {

        //register components
        components: {
            Header,
            Sidebar
        }

    }
</script>

<style>
</style>
