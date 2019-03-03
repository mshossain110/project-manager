<template>
    <VMenu
        v-model="showMenu"
        v-bind="$attrs"
    >
        <VList>
            <VListTile
                v-for="(item, index) in items"
                :key="index"
            >
                <VListTileTitle>{{ item.title }}</VListTileTitle>
            </VListTile>
        </VList>
    </VMenu>
</template>

<script>
export default {
    inheritAttrs: false,
    props: {
        value: {
            type: Boolean
        }
    },
    data () {
        return {
            showMenu: this.value,
            x: 0,
            y: 0,
            items: [
                { title: 'Show' },
                { title: 'Favourite' },
                { title: 'Delete' },
                { title: 'Edit' }
            ]
        }
    },
    watch: {
        showMenu (val) {
            if (val !== this.value) {
                this.$emit('input', val)
            }
        },
        value (val) {
            if (val !== this.showMenu) {
                this.showMenu = val
            }
        }
    }
}
</script>
