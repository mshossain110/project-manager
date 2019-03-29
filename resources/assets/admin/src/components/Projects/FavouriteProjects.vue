<template>
    <VLayout
        row
        wrap
    >
        <VFlex
            v-for="project in projects"
            :key="project.id"
            xs12
            sm6
            md4
        >
            <Project :project="project" />
        </VFlex>
    </VLayout>
</template>

<script>
import { mapState } from 'vuex'
import Project from './Project'

export default {
    components: {
        Project
    },
    data () {
        return {

        }
    },
    computed: {
        ...mapState('Projects', ['projects'])
    },
    created () {
        this.$store.dispatch('Projects/getProjects', { status: 'incomplete' })
    }

}
</script>
