<template>
    <VLayout class="comment-container">
        <VFlex xs12>
            <VContainer
                id="scroll-target"
                style="max-height: 400px"
                class="scroll-y"
            >
                <VTimeline
                    dense
                >
                    <VTimelineItem
                        v-for="comment in comments"
                        :key="comment.id"
                        class="comment"
                        fill-dot
                    >
                        <template v-slot:icon>
                            <VAvatar>
                                <img src="http://i.pravatar.cc/64">
                            </VAvatar>
                        </template>

                        <Comment
                            :comment="comment"
                            :commentable-type="commentableType"
                            :commentable-id="commentableId"
                        />
                    </vtimelineitem>
                </VTimeline>
            </VContainer>
            <CommentForm
                :commentable-type="commentableType"
                :commentable-id="commentableId"
                newcomment
            />
        </VFlex>
    </VLayout>
</template>

<script>
import Comment from './Comment'
import CommentForm from './CommentForm'
export default {
    components: {
        Comment,
        CommentForm
    },
    props: {
        comments: {
            type: Array,
            required: true
        },
        commentableType: {
            type: String,
            default () {
                return 'discussion_board'
            }
        },
        commentableId: {
            type: Number,
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
.comment-container
    .v-timeline
        padding-top 0
        min-height: 64px
        &:before
            display none
        .v-timeline-item
            padding-bottom 5px
            .v-card
                &:before
                &:after
                    display none
                .v-card__text
                    padding: 10px 2px
                    .commenttime
                        text-align right
                        font-size 10px
            &:hover .v-card__text
                background-color #f3f3f3
            .v-btn--icon
                margin 0
                padding 0
                width 16px
                height 16px
                display none;
            &:hover .v-btn--icon
                display inline

</style>
