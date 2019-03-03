<template>
    <div class="assignee-container">
        <VListTileAvatar
            v-for="assignee in assignees"
            :key="assignee.id"
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
        }
    },
    data () {
        return {

        }
    }
}
</script>
