<template>
    <VForm
        class="milestone-form"
        @submit.prevent="submit"
    >
        <div class="input-ourter">
            <input              
                type="text"
                v-model="milestone.title"
                placeholder="New Milestone Title"
            >
        </div>

        <textarea  
        v-model="milestone.description"         
            placeholder="Description"
        />
        <VBtn
            color="primary"
            type="submit"
        >
            Submit
        </VBtn>
    </VForm>
</template>

<script>
export default {
    props:{
        milestone:{
            type:Object,
            default:()=> ({
                title : "",
                description : "",
                order : "",
                status : "",
                project_id : ""
            })
        }
        
    },
    methods:{
        submit () {
            let data = {
                id: this.milestone.id,
                title: this.milestone.title,
                description: this.milestone.description,
                project_id: parseInt(this.$route.params.project_id),
                private: this.lock
            }
            if (typeof this.milestone.id === 'undefined') {
                this.$store.dispatch('Milestone/addMilestone', data)
                    .then(() => {
                        // Reset form using form class
                        Object.assign(this.$data, this.$options.data.call(this))
                    })
            } else {
                this.$store.dispatch('Milestone/updateMilestone', data)
                    .then(() => {
                    // Reset form using form class
                        Object.assign(this.$data, this.$options.data.call(this))
                    })
            }
        }

    }
}
</script>
<style lang="stylus">
.milestone-form
    .input-ourter
        position: relative
        overflow: hidden
        input
            padding: 10px
            width: 100%
            border-style: solid
            border-color: #dcd
            border-width: 0px 0px 1px 0px
            margin-bottom: 5px
            transition: all 0.2
            &:focus
                border-color: #666
                outline: none
        .icon-btns
            position: absolute;
            right: 20px;
            top: 6px;
            .v-btn
                padding: 0px;
                margin: 0px;

    textarea
        width: 100%
        padding: 10px
        border-bottom: 1px solid #dcd
        &:focus
            border-color: #666
            outline: none
</style>
