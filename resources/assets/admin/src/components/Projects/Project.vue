<template>
    <VCard
        class="project"
        @click="getInProject"
        @contextmenu="show"
    >
        <VCardTitle>
            <span class="title font-weight-light">{{ project.title }}</span>
        </VCardTitle>

        <VCardText>
            {{ project.description }}
        </VCardText>

        <VProgressLinear v-model="valueDeterminate" />

        <VCardActions>
            <VListTile class="grow">
                <Assignee :assignees="project.assignees.data" />
            </VListTile>
        </VCardActions>

        <ProjectMenu
            v-model="showMneu"
            :position-x="x"
            :position-y="y"
            absolute
            offset-y
        />
    </VCard>
</template>

<script>
import Assignee from './Assignee.vue'
import ProjectMenu from './ProjectMenu.vue'

export default {
    components: {
        Assignee,
        ProjectMenu
    },
    props: {
        project: {
            type: Object,
            required: true
        }
    },
    data () {
        return {
            valueDeterminate: 60,
            showMneu: false,
            x: 0,
            y: 0
        }
    },
    methods: {
        show (e) {
            e.preventDefault()
            this.showMenu = false
            this.x = e.clientX
            this.y = e.clientY
            this.$nextTick(() => {
                this.showMneu = true
            })
        },
        getInProject () {
            this.$router.push({
                name: 'inProject',
                params: {
                    id: this.project.id
                }
            })
        }
    }
}
</script>

<style>
.project {
    margin: 10px;
}
.project .v-progress-linear {
    margin: 0 !important;
}
</style>
