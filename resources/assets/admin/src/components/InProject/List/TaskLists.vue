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
                </VToolbar>
                <transition name="slide-y-transition">
                    <NewListForm v-if="createList" :list="{}" />
                </transition>

                <div class="list-container">
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
        }
    },
    computed: {
        ...mapState('List', ['lists']),
        project_id () {
            return this.$route.params.id
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
    ul
        padding:0
        margin:0
        list-style:none
        li
            list-style: none
            padding: 0

</style>
