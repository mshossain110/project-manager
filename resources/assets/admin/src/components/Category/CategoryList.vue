<template>
    <VCard>
        <VToolbar
            color="cyan"
            dark
        >
            <VToolbarSideIcon />

            <VToolbarTitle>Categories </VToolbarTitle>

            <VTextField
                v-model="search"
                append-icon="search"
                label="Search"
                single-line
                hide-details
            />
        </VToolbar>

        <VDataTable
            v-model="selected"
            :headers="headers"
            :items="categories"
            :search="search"
            item-key="id"
            select-all
            flat
            hide-actions
            class="elevation-1"
        >
            <template
                slot="items"
                slot-scope="props"
            >
                <tr>
                    <td>
                        <VCheckbox
                            v-model="props.selected"
                            primary
                            hide-details
                        />
                    </td>

                    <td>
                        <strong class="subheading">{{ props.item.title }}</strong>
                        <p>{{ props.item.description }}</p>
                    </td>

                    <td class="justify-center layout px-0">
                        <VIcon
                            small
                            class="mr-2"
                            @click="editItem(props.item)"
                        >
                            edit
                        </VIcon>
                        <VIcon
                            small
                            @click="deleteItem(props.item)"
                        >
                            delete
                        </VIcon>
                    </td>
                </tr>
            </template>
        </VDataTable>
    </VCard>
</template>

<script>
import { mapState } from 'vuex'

export default {
    components: {
    },
    data () {
        return {
            search: '',
            selected: [],
            headers: [
                {
                    text: 'Category',
                    value: 'category',
                    sortable: false
                },
                { text: 'Action', align: 'left', value: 'action' }
            ]
        }
    },

    computed: {
        ...mapState('Category', ['categories'])
    },
    created () {

    },
    methods: {
        editItem (category) {
            Bus.$emit('editCtegory', category)
        },
        deleteItem (category) {
            this.$store.dispatch('Category/deleteCategory', category.id)
        }
    }
}
</script>
