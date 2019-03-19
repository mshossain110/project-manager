<template>
    <VLayout
        row
        class="discussion"
    >
        <VFlex xs12>
            <div class="header">
                <VAvatar
                    size="40"
                    color="grey lighten-4"
                >
                    <img
                        src="https://vuetifyjs.com/apple-touch-icon-180x180.png"
                        alt="avatar"
                    >
                </VAvatar>
                <div class="content">
                    <div class="title">
                        {{ discussion.title }}
                    </div>
                    <div class="description">
                        {{ discussion.description }}
                    </div>
                </div>

                <div class="action">
                    <VBadge left>
                        <template v-slot:badge>
                            <span>6</span>
                        </template>
                        <VBtn
                            flat
                            icon
                            color="gray lighten-2"
                        >
                            <VIcon>
                                comment
                            </VIcon>
                        </VBtn>
                    </VBadge>
                    <VBtn
                        flat
                        icon
                        color="gray lighten-2"
                    >
                        <VIcon>edit</VIcon>
                    </VBtn>

                    <VBtn
                        flat
                        icon
                        color="red lighten-2"
                    >
                        <VIcon>delete</VIcon>
                    </VBtn>
                </div>
            </div>
        </VFlex>
    </VLayout>
</template>

<script>
export default {
    props: {
        discussion: {
            type: Object,
            required: true
        }
    },
    data () {
        return {

        }
    },
    computed: {

    },
    created () {

    },
    methods: {

    }
}
</script>
<style lang="stylus">
.discussion
    padding: 3px 10px;
    .v-avatar
        margin-right 15px
    .header
        display: flex;
        justify-content: space-between;
        .content
            flex-grow 1
            margin-right 20px
        .title
            margin-bottom: 8px

        .action
            display flex
            .v-btn
                margin 0

</style>
