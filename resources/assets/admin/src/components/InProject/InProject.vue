<template>
    <VLayout
        d-block
    >
        <VFlex
            v-if="!isLoading"
            xs12
            d-flex
        >
            <VCard>
                <VToolbar
                    extended
                    dense
                    tabs
                >
                    <VToolbarTitle>
                        <VToolbarSideIcon /> {{ project.title }}
                    </VToolbarTitle>

                    <VSpacer />

                    <Assignee :assignees="project.assignees.data" />

                    <template v-slot:extension>
                        <VTabs
                            v-model="tabs"
                            centered
                            color="transparent"
                            light
                            slider-color="green"
                        >
                            <VTab :to="{name: 'list-page', params: {id: project_id}}">
                                List
                            </VTab>
                            <VTab :to="{name: 'discussion-page', params: {id: project_id}}">
                                Discussion
                            </VTab>
                            <VTab :to="{name: 'milestone-page', params: {id: project_id}}">
                                Milestone
                            </VTab>
                            <VTab :to="{name: 'file-page', params: {id: project_id}}">
                                File
                            </VTab>
                            <VTab :to="{name: 'activity-page', params: {id: project_id}}">
                                Activities
                            </VTab>
                            <VTab :to="{name: 'overview-page', params: {id: project_id}}">
                                Overview
                            </VTab>
                        </VTabs>
                    </template>
                </VToolbar>

                <RouterView />
            </VCard>
        </VFlex>
    </VLayout>
</template>

<script>
import { mapState } from 'vuex'
import Assignee from '@ac/Projects/Assignee.vue'

export default {
    components: {
        Assignee
    },
    data () {
        return {
            isLoading: false,

            tabs: ''
        }
    },
    computed: {
        ...mapState('Projects', ['project']),
        project_id () {
            return this.$route.params.id
        }
    },
    created () {
        this.isLoading = true
        this.$store.dispatch('Projects/getProject', { id: this.$route.params.id })
            .then(() => {
                this.isLoading = false
            })
    }
}
</script>
