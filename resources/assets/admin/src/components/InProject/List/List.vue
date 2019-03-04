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
                <NewListForm :list="{}" />

                <VList>
                    <VListGroup
                        v-for="list in lists"
                        :key="list.id"
                    >
                        <template v-slot:activator>
                            <VListTile>
                                <VListTileContent>
                                    <VListTileTitle>{{ list.title }}</VListTileTitle>
                                </VListTileContent>
                            </VListTile>
                        </template>

                        <!-- <VListTile
                            v-for="subItem in item.items"
                            :key="subItem.title"
                        >
                            <VListTileContent>
                                <VListTileTitle>{{ subItem.title }}</VListTileTitle>
                            </VListTileContent>

                            <VListTileAction>
                                <VIcon>{{ subItem.action }}</VIcon>
                            </VListTileAction>
                        </VListTile> -->
                    </VListGroup>
                </VList>
            </VCard>
        </VFlex>
    </VLayout>
</template>

<script>

import NewListForm from './NewListForm.vue'
import { mapState } from 'vuex'

export default {
    components: {
        NewListForm
    },
    data () {
        return {
            isLoading: false
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
