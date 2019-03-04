<template>
    <VForm @submit.prevent="submit">
        <VContainer>
            <VLayout
                row
                wrap
            >
                <VFlex
                    xs12
                    sm6
                    md3
                >
                    <VTextField
                        v-model="list.title"
                        label="Title"
                    />
                </VFlex>
            </VLayout>
        </VContainer>
    </VForm>
</template>

<script>
export default {
    props: {
        list: {
            type: Object,
            required: true
        }
    },
    data () {
        return {

        }
    },
    methods: {
        submit () {
            let data = {
                id: this.list.id,
                title: this.list.title,
                decription: this.list.decription,
                private: this.list.private
            }
            if (typeof this.list.id === 'undefined') {
                this.$store.dispatch('List/addList', data)
            } else {
                this.$store.dispatch('List/updateList', data)
            }
        }
    }

}
</script>
