<template>
    <VCombobox
        v-model="user"
        :items="users"
        :loading="isLoading"
        placeholder="Select User"
        item-value="id"
        item-text="name"
        cache-items
        deletable-chips
        multiple
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
                {{ data.item.name }}
            </VChip>
        </template>
    </VCombobox>
</template>

<script>
import { mapState } from 'vuex'
export default {
    props: {
        value: {
            type: [Array, null],
            default () {
                return []
            }
        }
    },
    data () {
        return {
            user: this.value,
            isLoading: false
        }
    },
    computed: {
        ...mapState('Users', ['users'])
    },
    watch: {
        user (val) {
            if (val !== this.value) {
                this.$emit('input', val)
            }
        }
    },
    created () {
        this.isLoading = true
        this.$store.dispatch('Users/getUsers')
            .then(() => {
                this.isLoading = false
            })
    },
    methods: {

    }
}
</script>
