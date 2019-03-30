<template>
    <VLayout
        row
        class="activities-page"
    >
        <VFlex
            xs12
        >
            <VCard v-if="!isLoading">
                <div class="activities activities-container">
                    <VLayout
                        v-for="group in activitiesArray"
                        :key="group.id"
                        row
                    >
                        <VFlex xs1>
                            <div class="activity-date">
                                <span>{{ actiivtyGroupDate(group.date, 'DD') }}</span>
                                <br>
                                {{ actiivtyGroupDate(group.date, 'MMMM') }}
                            </div>
                        </VFlex>
                        <VFlex xs11>
                            <VTimeline dense>
                                <VTimelineItem
                                    v-for="activity in group.activities"
                                    :key="activity.id"
                                    small
                                    class="activity"
                                >
                                    <ActivityParser :activity="activity" />
                                    <div class="date col-4 sm-col-12">
                                        <time
                                            :datetime="momentFormat(activity.committed_at.date, activity.committed_at.time)"
                                            :title="momentFormat(activity.committed_at.date, activity.committed_at.time)"
                                        >
                                            <i>{{ momentFormat(activity.committed_at.date) }}, {{ momentFormat(activity.committed_at.datetime) }}</i>
                                        </time>
                                    </div>
                                </VTimelineItem>
                            </VTimeline>
                        </VFlex>
                    </VLayout>

                    <!-- <a
                        v-if="total_activity>loaded_activities"
                        href="#"
                        class="button load-more"
                        @click.prevent="loadMore()"
                    >{{ __( 'Load More ...', 'wedevs-project-manager') }}</a>
                    <span
                        v-show="show_spinner"
                        class="spinner"
                    /> -->
                    <div
                        v-if="!activities.length"
                        class="no-activity"
                    >
                        No activity found.
                    </div>
                </div>
            </VCard>
        </VFlex>
    </VLayout>
</template>

<script>
import moment from 'moment'
import ActivityParser from './ActivityParser'

export default {
    components: {
        ActivityParser
    },
    data () {
        return {
            activities: [],
            isLoading: true
        }
    },
    computed: {
        activitiesArray () {
            var records = this.activities

            var activities = _.chain(records)
                .groupBy(this.occurrenceDay)
                .map(this.groupToDay)
                .sortBy('day')
                .value()

            return activities
        },

        current_page () {
            if (typeof this.$route.params.current_page_number !== 'undefined') {
                return parseInt(this.$route.params.current_page_number, 10)
            } else {
                return 1
            }
        }
    },
    created () {
        this.getActivities()
    },
    methods: {
        getActivities () {
            this.isLoading = true
            let params = {
                project_id: this.$route.params.project_id
            }

            axios.get('/api/activities', { params })
                .then((res) => {
                    this.activities = res.data.data
                    this.isLoading = false
                })
                .catch((error) => {
                    this.$store.commit('setSnackbar',
                        {
                            message: error.response.data.message,
                            status: error.response.status,
                            color: 'error',
                            show: true
                        },
                        { root: true })
                })
        },
        occurrenceDay (occurrence) {
            return moment(occurrence.committed_at.date).startOf('day').format('YYYY-MM-DD')
        },

        groupToDay (group, day) {
            return {
                date: day,
                activities: group
            }
        },

        /**
             * WP settings date format convert to moment date format with time zone
             *
             * @param  string date
             *
             * @return string
             */
        actiivtyGroupDate: function (date, format) {
            if (!date) {
                return
            }
            date = new Date(date)
            return moment(date).format(format)
        }
    }
}
</script>
<style lang="stylus">
.activities-page
    .activity-date
        padding: 4px;
        text-align: center;
        font-size: 18px;
        border: 1px solid #ddd;
        margin: 10px;
        height: 88%;
    .activity
        padding: 5px 0px;
        border-bottom: 1px solid #ddd;
        .v-timeline-item__body
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-right: 50px;
</style>
