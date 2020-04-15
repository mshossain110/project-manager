<template>
    <VLayout row>
        <VFlex
            xs12
        >
            <VCard>
                <VToolbar
                    flat
                    dense
                >
                    <VBtn
                        small
                        color="success"
                        @click="createMilestone = ! createMilestone"
                    >
                        Craete New Mailstone
                    </VBtn>

                    <VSpacer />

                    <VBtn
                        flat
                    >
                        Filter
                    </VBtn>
                </VToolbar>
                <Transition name="slide-y-transition">
                    <MilestoneForm
                        v-if="createMilestone"
                        
                    />
                </Transition>
                <div class="milestone-container">
                    <VTimeline
                        align-top
                        dense
                    >
                        <VTimelineItem
                            v-for="milestone in milestones"
                            :key="milestone.id"
                            fill-dot
                        >
                            <template v-slot:icon>
                                <VAvatar>
                                    <img src="http://i.pravatar.cc/64">
                                </VAvatar>
                            </template>
                            <template v-slot:opposite>
                                <span>{{ momentFormat(milestone.created_at.date, 'DD MMM YYYY') }}</span>
                            </template>

                            <Milestone :milestone="milestone" />
                        </vtimelineitem>
                    </VTimeline>
                </div>
            </VCard>
        </VFlex>
    </VLayout>
</template>

<script>
import { mapState, mapMutations } from 'vuex'
import MilestoneForm from './MilestoneForm.vue'
import Milestone from './Milestone.vue'
export default {
    components: {
        MilestoneForm,
        Milestone
    },

    data () {
        return {
            createMilestone: false
        }
    },
    computed: {
        ...mapState('Milestone', ['milestones']),
        project_id () {
            return this.$route.params.project_id
        }
    },
    created(){
         this.isLoading = true
        this.$store.dispatch('Milestone/getMilestones', { project_id: this.project_id})
            .then(() => {
                this.isLoading = false
            })
    }

}
</script>
