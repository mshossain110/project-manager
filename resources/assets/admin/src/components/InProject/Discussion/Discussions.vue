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
                        @click="createDiscuss = !createDiscuss"
                    >
                        Craete New Topic
                    </VBtn>

                    <VSpacer />

                    <VBtn
                        flat
                    >
                        Filter
                    </VBtn>
                </VToolbar>
                <Transition name="slide-y-transition">
                    <DiscussionForm
                        v-if="createDiscuss"
                        :discuss="{}"
                    />
                </Transition>

                <div class="discussion-container">
                    <VTimeline
                        align-top
                        dense
                    >
                        <VTimelineItem
                            v-for="discussion in discussions"
                            :key="discussion.id"
                            fill-dot
                        >
                            <template v-slot:icon>
                                <VAvatar>
                                    <img src="http://i.pravatar.cc/64">
                                </VAvatar>
                            </template>
                            <template v-slot:opposite>
                                <span>{{ momentFormat(discussion.created_at.date, 'DD MMM YYYY') }}</span>
                            </template>

                            <Discussion
                                :discussion="discussion"
                            />
                        </vtimelineitem>
                    </VTimeline>
                </div>
            </VCard>
        </VFlex>
    </VLayout>
</template>

<script>
import { mapState } from 'vuex'
import DiscussionForm from './DiscussionForm.vue'
import Discussion from './Discussion.vue'

export default {
    components: {
        DiscussionForm, Discussion
    },
    data () {
        return {
            isLoading: false,
            createDiscuss: false
        }
    },
    computed: {
        ...mapState('Discussion', ['discussions']),
        project_id () {
            return this.$route.params.id
        }
    },
    created () {
        this.isLoading = true
        this.$store.dispatch('Discussion/getDiscussions', { project_id: this.project_id })
            .then(() => {
                this.isLoading = false
            })
    }
}
</script>
<style lang="stylus">
.discussion-container
    padding: 20px
    .v-timeline-item__opposite
        flex: 1 1 auto;
        align-self: center;
        max-width: calc(50% - 48px);
        margin: 0 !important;
        position: absolute;
        left: -12px;
        display: block;
        top: 52px;
        background: #333;
        color: #fff;
        padding: 4px;
        font-size: 10px;
</style>
