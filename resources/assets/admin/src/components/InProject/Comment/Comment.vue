<template>
    <VCard flat>
        <VCardText primary-title>
            <div class="descriptipn">
                <div> {{ comment.content }} </div>
            </div>
            <div class="commenttime">
                <span>{{ momentFormat(comment.created_at.date, 'DD MMM YYYY') }}</span>
                <span>{{ momentFormat(comment.created_at.date, 'HH: MM A') }}</span>
            </div>
        </VCardText>
    </VCard>
</template>

<script>
export default {
    props: {
        comment: {
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
