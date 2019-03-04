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
                        v-model="task.title"
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
        task: {
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
                id: this.task.id,
                title: this.task.title,
                decription: this.task.decription,
                project_id: parseInt(this.$route.params.id),
                private: this.task.private
            }
            if (typeof this.task.id === 'undefined') {
                this.$store.dispatch('List/addTask', data)
            } else {
                this.$store.dispatch('List/updateTask', data)
            }
        }
    }

}
</script>
