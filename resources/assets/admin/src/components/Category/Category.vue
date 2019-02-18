<template>
    <VLayout
        d-block
    >
        <VLayout
            v-if="loading"
            column
            align-center
            justify-center
        >
            <VProgressCircular

                :size="70"
                :width="7"
                color="purple"
                indeterminate
            />
        </VLayout>

        <VFlex
            xs12
            d-flex
        >
            <div class="pageTitle">
                <h2 class="headline">
                    <VToolbarSideIcon />Category
                </h2>
            </div>
        </VFlex>
        <VLayout>
            <VFlex
                xs12
                md6
            >
                <CategoryForm :category="category" />
            </VFlex>

            <VFlex
                xs12
                md6
            >
                <CategoryList />
            </VFlex>
        </VLayout>
    </VLayout>
</template>

<script>
import { mapState } from 'vuex'
import CategoryForm from './CategoryForm.vue'
import CategoryList from './CategoryList'

export default {
    components: {
        CategoryForm,
        CategoryList
    },
    data () {
        return {
            loading: false,
            category: {}
        }
    },
    computed: {

    },
    mounted () {
        Bus.$on('editCtegory', (data) => {
            this.category = data
        })
    },
    created () {
        this.loading = true
        this.$store.dispatch('Category/getCategories')
            .then(() => {
                this.loading = false
            })
    },
    methods: {
    }
}
</script>

<style lang="css">
.role-action {
    flex-direction: row;
    justify-content: flex-end;
    align-items: center;
}
</style>
