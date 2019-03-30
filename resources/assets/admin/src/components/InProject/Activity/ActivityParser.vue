<template>
    <div v-html="parseMessage" />
</template>

<script>
import moment from 'moment'
export default {
    props: {
        activity: {
            type: Object,
            required: true
        },
        page: {
            type: String,
            default: ''
        }
    },
    data () {
        return {

        }
    },

    computed: {
        parseMessage () {
            var obj = this.activity

            var identifiers = this.fetchIdentifiers(obj.message)

            for (let i = 0; i < identifiers.length; i++) {
                let identifier = identifiers[i].replace(/\{|\}/g, '')

                let link = this.isLinkable(identifier)

                if (link) {
                    obj.message = obj.message.replace(identifiers[i], link)
                } else {
                    obj.message = obj.message.replace(identifiers[i], this.getIdentifierValue(identifier))
                }
            }

            return obj.message
        }
    },

    methods: {
        fetchIdentifiers (message) {
            var regex = /\{[a-zA-Z._0-9$]*\}/g

            var match = []
            do {
                var m = regex.exec(message)
                if (m) {
                    match.push(m[0])
                }
            } while (m)
            return match
        },
        linkableIdentifiers () {
            var obj = this.activity
            return {
                'actor.data.name': "<a href='" + this.actor_url() + "'>" + obj.actor.data.name + '</a>',
                'meta.project_title': "<a href='" + this.resolve_url() + "'>" + obj.meta.project_title + '</a>',
                'meta.project_title_old': "<a href='" + this.resolve_url() + "'>" + obj.meta.project_title_old + '</a>',
                'meta.old_project_title': "<a href='" + this.resolve_url() + "'>" + obj.meta.old_project_title + '</a>',
                'meta.project_title_new': "<a href='" + this.resolve_url() + "'>" + obj.meta.project_title_new + '</a>',
                'meta.discussion_board_title': "<a href='" + this.resolve_url() + "'>" + obj.meta.discussion_board_title + '</a>',
                'meta.deleted_discussion_board_title': '<strong>' + obj.meta.deleted_discussion_board_title + '</strong>',
                'meta.discussion_board_title_old': "<a href='" + this.resolve_url() + "'>" + obj.meta.discussion_board_title_old + '</a>',
                'meta.discussion_board_title_new': "<a href='" + this.resolve_url() + "'>" + obj.meta.discussion_board_title_new + '</a>',
                'meta.task_list_title': "<a href='" + this.resolve_url() + "'>" + obj.meta.task_list_title + '</a>',
                'meta.deleted_task_list_title': '<strong>' + obj.meta.deleted_task_list_title + '</strong>',
                'meta.task_list_title_old': "<a href='" + this.resolve_url() + "'>" + obj.meta.task_list_title_old + '</a>',
                'meta.task_list_title_new': "<a href='" + this.resolve_url() + "'>" + obj.meta.task_list_title_new + '</a>',
                'meta.milestone_title': "<a href='" + this.resolve_url() + "'>" + obj.meta.milestone_title + '</a>',
                'meta.deleted_milestone_title': '<strong>' + obj.meta.deleted_milestone_title + '</strong>',
                'meta.milestone_title_old': "<a href='" + this.resolve_url() + "'>" + obj.meta.milestone_title_old + '</a>',
                'meta.milestone_title_new': "<a href='" + this.resolve_url() + "'>" + obj.meta.milestone_title_new + '</a>',
                'meta.task_title': "<a href='" + this.resolve_url() + "'>" + obj.meta.task_title + '</a>',
                'meta.deleted_task_title': '<strong>' + obj.meta.deleted_task_title + '</strong>',
                'meta.task_title_old': "<a href='" + this.resolve_url() + "'>" + obj.meta.task_title_old + '</a>',
                'meta.task_title_new': "<a href='" + this.resolve_url() + "'>" + obj.meta.task_title_new + '</a>',
                'meta.file_title': "<a href='" + this.resolve_url() + "'>" + obj.meta.file_title + '</a>',
                'meta.file_title_old': "<a href='" + this.resolve_url() + "'>" + obj.meta.file_title_old + '</a>',
                'meta.file_title_new': "<a href='" + this.resolve_url() + "'>" + obj.meta.file_title_new + '</a>',
                'meta.task_status_old': '<strong>' + obj.meta.task_status_old + '</strong>',
                'meta.task_status_new': '<strong>' + obj.meta.task_status_new + '</strong>',
                'meta.project_status_new': '<strong>' + obj.meta.project_status_new + '</strong>',
                'meta.project_status_old': '<strong>' + obj.meta.project_status_old + '</strong>',
                'meta.task_start_at_new': obj.meta.task_start_at_new !== null ? '<strong>' + moment(obj.meta.task_start_at_new).format('MMMM Do YYYY') + '</strong>' : '<strong>...</strong>',
                'meta.task_start_at_old': obj.meta.task_start_at_old !== null ? '<strong>' + moment(obj.meta.task_start_at_old).format('MMMM Do YYYY') + '</strong>' : '<strong>...</strong>',
                'meta.task_due_date_new': obj.meta.task_due_date_new !== null ? '<strong>' + moment(obj.meta.task_due_date_new).format('MMMM Do YYYY') + '</strong>' : '<strong>...</strong>',
                'meta.task_due_date_old': obj.meta.task_due_date_old !== null ? '<strong>' + moment(obj.meta.task_due_date_old).format('MMMM Do YYYY') + '</strong>' : '<strong>...</strong>'
            }
        },
        isLinkable (identifier) {
            var identifiers = this.linkableIdentifiers()

            for (const prop in identifiers) {
                if (prop === identifier) { return identifiers[prop] }
            }

            return false
        },
        getIdentifierValue (identifier) {
            var props = identifier.split('.')

            var count = 0

            var prop = props[count]

            var value = this.activity

            while (count < props.length) {
                console.log(value, prop)
                value = value[prop]
                count = count + 1
                prop = props[count]
            }

            return value
        },

        resolve_url () {
            return '#'
        },

        actor_url () {
            return '#'
        }
    }
}
</script>
