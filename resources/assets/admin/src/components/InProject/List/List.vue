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
                        v-for="item in items"
                        :key="item.title"
                        v-model="item.active"
                        :prepend-icon="item.action"
                        no-action
                    >
                        <template v-slot:activator>
                            <VListTile>
                                <VListTileContent>
                                    <VListTileTitle>{{ item.title }}</VListTileTitle>
                                </VListTileContent>
                            </VListTile>
                        </template>

                        <VListTile
                            v-for="subItem in item.items"
                            :key="subItem.title"
                        >
                            <VListTileContent>
                                <VListTileTitle>{{ subItem.title }}</VListTileTitle>
                            </VListTileContent>

                            <VListTileAction>
                                <VIcon>{{ subItem.action }}</VIcon>
                            </VListTileAction>
                        </VListTile>
                    </VListGroup>
                </VList>
            </VCard>
        </VFlex>
    </VLayout>
</template>

<script>

import NewListForm from './NewListForm.vue'

export default {
    components: {
        NewListForm
    },
    data () {
        return {
            items: [
                {
                    action: 'local_activity',
                    title: 'Attractions',
                    items: [
                        { title: 'List Item' }
                    ]
                },
                {
                    action: 'restaurant',
                    title: 'Dining',
                    active: true,
                    items: [
                        { title: 'Breakfast & brunch' },
                        { title: 'New American' },
                        { title: 'Sushi' }
                    ]
                },
                {
                    action: 'school',
                    title: 'Education',
                    items: [
                        { title: 'List Item' }
                    ]
                },
                {
                    action: 'directions_run',
                    title: 'Family',
                    items: [
                        { title: 'List Item' }
                    ]
                },
                {
                    action: 'healing',
                    title: 'Health',
                    items: [
                        { title: 'List Item' }
                    ]
                },
                {
                    action: 'content_cut',
                    title: 'Office',
                    items: [
                        { title: 'List Item' }
                    ]
                },
                {
                    action: 'local_offer',
                    title: 'Promotions',
                    items: [
                        { title: 'List Item' }
                    ]
                }
            ]
        }
    }
}
</script>
