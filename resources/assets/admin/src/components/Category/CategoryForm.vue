
<template>
    <VForm
        class="category-form"
        @submit.prevent="submit()"
    >
        <p class="title">
            Create New Category
        </p>
        <VTextField
            v-model="category.title"
            placeholder="title"
            light
            solo
        />

        <v-textarea
            v-model="category.description"
            solo
            name="description"
            label="Description"
        />

        <VBtn
            :loading="loading"
            :disable="loading"
            color="success"
            type="submit"
            depressed
            ripple
        >
            Submit
        </VBtn>
    </VForm>
</template>

<script>
import { mapState } from 'vuex'

export default {
    props: {
        category: {
            type: Object,
            default () {
                return {
                    title: '',
                    description: ''
                }
            }
        }
    },
    $_veeValidate: {
        validator: 'new'
    },
    data () {
        return {
            loading: false
        }
    },
    computed: {

    },
    created () {

    },
    methods: {
        submit () {
            this.loading = true
            const category = {
                id: this.category.id,
                title: this.category.title,
                description: this.category.description
            }
            if (!this.category.id) {
                this.$store.dispatch('Category/addCategory', category)
                    .then(() => {
                        this.loading = false
                        this.$emit('close', 'true')
                    })
                    .catch(() => {
                        this.loading = false
                    })
            } else {
                this.$store.dispatch('Category/updateCategory', category)
                    .then(() => {
                        this.loading = false
                        this.$emit('close', 'true')
                    })
                    .catch(() => {
                        this.loading = false
                    })
            }
        }
    }
}
</script>

<style lang="css">
form.category-form {
    width: 80%;
    overflow: hidden;
    display: block;
    padding: 10px;
}
</style>
