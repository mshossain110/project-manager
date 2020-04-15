<template>
    <VCard>
        <VToolbar
            dark
            color="blue"
            dense
            flat
        >
            <VToolbarTitle>{{milestone.title}}</VToolbarTitle>

            <VSpacer />
            <VBtn
                icon
               @click="milestoneedit = !milestoneedit"
            >
                <VIcon>edit</VIcon>
            </VBtn>
            <VBtn
                icon
               @click="deleteMilestone(milestone)"
            >
                <VIcon>delete</VIcon>
            </VBtn>
        </VToolbar>
        <MilestoneForm v-if="milestoneedit" :milestone="milestone"/>
        <VCardText class="white">
            <div class="description text--primary">
              {{milestone.description}}
            </div>

           
        </VCardText>
    </VCard>
</template>

<script>
import MilestoneForm from './MilestoneForm'
export default {
    components:{
        MilestoneForm
    },
    props:{
        milestone:{
            type:Object,
            required:true
        }
    },
    data(){
        return{
            milestoneedit:false,
            showcomment:false
        }
    },
    methods:{
        deleteMilestone(milestone){
            this.$store.dispatch('Milestone/deleteMilestone', milestone)
        }
    }
}
</script>

<style lang="stylus">

</style>