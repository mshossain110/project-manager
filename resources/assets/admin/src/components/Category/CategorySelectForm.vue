<template>
    <VCombobox
        v-model="category"
        :items="categories"
        :loading="isLoading"
        placeholder="Select Category"
        item-value="id"
        item-text="title"
        cache-items
        deletable-chips
        no-filter
        chips
        clearable
        hide-details
        hide-selected
    >
        <template
            slot="selection"
            slot-scope="data"
        >
            <VChip
                v-if="data.item.id"
                :key="data.item.id"
                :selected="data.selected"
                :disabled="data.disabled"
            >
                {{ data.item.title }}
            </VChip>
        </template>
    </VCombobox>
</template>

<script>
import { mapState } from 'vuex'
export default {
    props: {
        value: {
            type: [Object, null],
            default () {
                return null
            }
        }
    },
    data () {
        return {
            category: this.value,
            isLoading: false
        }
    },
    computed: {
        ...mapState('Category', ['categories'])
    },
    watch: {
        category (val) {
            if (val !== this.value) {
                this.$emit('input', val)
            }
        }
    },
    created () {
        this.isLoading = true
        this.$store.dispatch('Category/getCategories')
            .then(() => {
                this.isLoading = false
            })
    },
    methods: {

    }
}
</script>
