<template>
    <nav>
        <ul :class="`pagination justify-content-${align} mb-0`">
            <li v-for="(link, index) in links" :key="index"
                :class="['page-item', link.url == null ? 'disabled' : '', link.active ? 'active' : '']">
                <Link
                    class="page-link"
                    :href="link.url ? appendQuery(link.url) : '#'"
                    v-html="link.label">
                </Link>
            </li>
        </ul>
    </nav>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';

export default {
    props: {
        links: Array,
        align: String
    },

    components: {
        Link,
    },

    methods: {
        appendQuery(url) {
            const currentParams = new URLSearchParams(window.location.search);
            const searchParam = currentParams.get('q');
            if (searchParam) {
                // Jika URL sudah punya query, tambahkan dengan &
                return url.includes('?') ? `${url}&q=${searchParam}` : `${url}?q=${searchParam}`;
            }
            return url;
        }
    }
}
</script>
<style>

</style>