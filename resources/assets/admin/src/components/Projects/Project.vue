<template>
    <VCard
        class="project"
        @click="getInProject"
        @contextmenu="show"
    >
        <VCardTitle>
            <span class="title font-weight-light">{{ project.title }}</span>
            <VSpacer />

            <VMenu
                bottom
                left
                transition="slide-y-transition"
            >
                <template v-slot:activator="{ on }">
                    <VBtn
                        light
                        icon
                        v-on="on"
                    >
                        <VIcon>more_vert</VIcon>
                    </VBtn>
                </template>

                <VList
                    dense
                    class="project-menu"
                >
                    <VListTile
                        ripple
                        @click="changeStatus"
                    >
                        <VListTileAction>
                            <VIcon
                                v-if="isIncomplete"
                                small
                            >
                                check_circle
                            </VIcon>
                            <VIcon
                                v-if="!isIncomplete"
                                small
                            >
                                replay
                            </VIcon>
                        </VListTileAction>

                        <VListTileTitle v-if="isIncomplete">
                            Complete
                        </VListTileTitle>
                        <VListTileTitle v-if="!isIncomplete">
                            Incomplete
                        </VListTileTitle>
                    </VListTile>
                    <VListTile
                        ripple
                        @click="openform = true"
                    >
                        <VListTileAction>
                            <VIcon small>
                                edit
                            </VIcon>
                        </VListTileAction>
                        <VListTileTitle>Edit</VListTileTitle>
                    </VListTile>
                    <VListTile
                        ripple
                        @click="deleteProject"
                    >
                        <VListTileAction>
                            <VIcon small>
                                delete
                            </VIcon>
                        </VListTileAction>
                        <VListTileTitle>Delete</VListTileTitle>
                    </VListTile>
                </VList>
            </VMenu>
        </VCardTitle>

        <VCardText>
            {{ project.description }}
        </VCardText>

        <VProgressLinear v-model="valueDeterminate" />

        <VCardActions>
            <VListTile class="grow">
                <Assignee
                    :assignees="project.assignees.data"
                    size="36px"
                />
            </VListTile>
        </VCardActions>

        <ProjectMenu
            v-model="showMneu"
            :position-x="x"
            :position-y="y"
            absolute
            offset-y
        />
        <VDialog
            v-model="openform"
            max-width="500px"
        >
            <ProjectFrom
                :project="project"
                @close="openform = false"
            />
        </VDialog>
    </VCard>
</template>

<script>
import Assignee from '@ac/common/Assignee.vue'
import ProjectMenu from './ProjectMenu.vue'
import ProjectFrom from './ProjectFrom'

export default {
    components: {
        Assignee,
        ProjectMenu,
        ProjectFrom
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
            y: 0,
            openform: false
        }
    },
    computed: {
        isIncomplete () {
            return this.project.status === 'incomplete'
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
        getInProject (event) {
            if (event.target.closest('.v-btn')) {
                return
            }
            this.$router.push({
                name: 'inProject',
                params: {
                    project_id: this.project.id
                }
            })
        },
        changeStatus () {
            var project = {
                id: this.project.id,
                status: this.isIncomplete ? 'complete' : 'incomplete'
            }
            this.$store.dispatch('Projects/updateProject', project)
        },
        deleteProject () {
            var project = {
                id: this.project.id
            }
            this.$store.dispatch('Projects/deleteProject', project)
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
.project-menu .v-list__tile__action {
    min-width: 25px;
}
</style>
