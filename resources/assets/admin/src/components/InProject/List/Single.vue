<template>
    <div class="single-wrap">
        <SingleList v-if="isList" />
        <SingleTask v-if="!isList" />
    </div>
</template>

<script>
import SingleList from './SingleList'
import SingleTask from './SingleTask'
export default {
    components: {
        SingleList, SingleTask
    },
    data () {
        return {
            route: this.$route.params
        }
    },
    computed: {
        isList () {
            return this.$route.params.type === 'list'
        }
    },
    created () {

    },
    methods: {

    }
}
</script>
