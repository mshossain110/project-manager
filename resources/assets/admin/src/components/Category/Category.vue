<template>
    <v-layout
        d-block
    >
        <v-loayout
            v-if="loading"
            column
            align-center
            justify-center
        >
            <v-progress-circular

                :size="70"
                :width="7"
                color="purple"
                indeterminate
            />
        </v-loayout>

        <v-flex
            xs12
            d-flex
        >
            <div class="pageTitle">
                <h2 class="headline">
                    <v-toolbar-side-icon />Category
                </h2>
            </div>
        </v-flex>
        <v-layout>
            <v-flex
                xs12
                md6
            >
                <category-form />
            </v-flex>

            <v-flex
                xs12
                md6
            >
                <CategoryList />
            </v-flex>
        </v-layout>
    </v-layout>
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
            loading: false
        }
    },
    computed: {
        ...mapState('Users', ['roles'])
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
