<template>
    <div
        v-if="assignees.length"
        class="assignee-container"
    >
        <VListTileAvatar
            v-for="assignee in assignees"
            :key="assignee.id"
            :size="size"
            color="grey darken-3"
        >
            <VTooltip bottom>
                <template v-slot:activator="{ on }">
                    <VImg
                        class="elevation-6"
                        :src="assignee.avatar"
                        v-on="on"
                    />
                </template>
                <span>{{ assignee.name }}</span>
            </VTooltip>
        </VListTileAvatar>
    </div>
</template>

<script>
export default {
    props: {
        assignees: {
            type: Array,
            required: true
        },
        size: {
            type: String,
            default () {
                return '24px'
            }
        }
    },
    data () {
        return {

        }
    }
}
</script>
<style lang="stylus">
    .assignee-container
        .v-list__tile__avatar
            min-width: auto
</style>
