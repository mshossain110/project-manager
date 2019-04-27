<template>
    <VLayout row>
        <VFlex
            xs12
        >
            <VCard v-if="!isLoading">
                <VToolbar
                    flat
                    dense
                >
                    <VBtn
                        small
                        color="success"
                    >
                        Craete Task
                    </VBtn>
                    <VBtn
                        small
                        color="success"
                        @click="createList = !createList"
                    >
                        Craete List
                    </VBtn>

                    <VSpacer />

                    <VBtn
                        flat
                    >
                        Filter
                    </VBtn>
                    <VBtn
                        flat
                        icon
                        :color="sidebar? 'accent': ''"
                        @click="sidebar = !sidebar"
                    >
                        <VIcon v-if="sidebar">
                            vertical_split
                        </VIcon>
                        <VIcon v-if="!sidebar">
                            view_headline
                        </VIcon>
                    </VBtn>
                </VToolbar>

                <Transition name="slide-y-transition">
                    <NewListForm
                        v-if="createList"
                        :list="{}"
                    />
                </Transition>

                <div
                    class="list-container"
                    :class="{'side-bar': openSidebar}"
                >
                    <div class="list-wrap">
                        <ul>
                            <li
                                v-for="list in lists"
                                :key="list.id"
                            >
                                <TaskList
                                    :list="list"
                                />
                            </li>
                        </ul>
                    </div>
                    <Transition name="slide-x-transition">
                        <div
                            v-if="openSidebar"
                            class="sidebar-wrap"
                        >
                            <RouterView />
                        </div>
                    </Transition>
                </div>
            </VCard>
        </VFlex>
    </VLayout>
</template>

<script>
import NewListForm from './NewListForm.vue'
import TaskList from './TaskList.vue'
import { mapState } from 'vuex'

export default {
    components: {
        NewListForm, TaskList
    },
    data () {
        return {
            isLoading: false,
            createList: false,
            sidebar: true
        }
    },
    computed: {
        ...mapState('List', ['lists']),
        project_id () {
            return this.$route.params.project_id
        },
        openSidebar () {
            return this.sidebar && this.$route.name === 'list-single'
        }
    },
    created () {
        this.isLoading = true
        this.$store.dispatch('List/getLists', { project_id: this.project_id })
            .then(() => {
                this.isLoading = false
            })
    }
}
</script>

<style lang="stylus">
.list-container
    display: block
    .list-wrap
        flex-basis: 50%;
        ul
            padding:0
            margin:0
            list-style:none
            li
                list-style: none
                padding: 0
    &.side-bar
        display: flex;
        flex-basis: 50%;
        flex-wrap: nowrap;
        flex-grow: 1;
    .sidebar-wrap
        flex-basis: 50%;

</style>
