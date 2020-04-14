<template>
    <VLayout row>
        <VFlex
            xs12
        >
            <VCard>
                <VToolbar
                    flat
                    dense
                >
                    <VBtn
                        small
                        color="success"
                        @click="createMilestone = ! createMilestone"
                    >
                        Craete New Mailstone
                    </VBtn>

                    <VSpacer />

                    <VBtn
                        flat
                    >
                        Filter
                    </VBtn>
                </VToolbar>
                <Transition name="slide-y-transition">
                    <MilestoneForm
                        v-if="createMilestone"
                    />
                </Transition>
            </VCard>
        </VFlex>
    </VLayout>
</template>

<script>
import MilestoneForm from './MilestoneForm.vue'
export default {
    components: {
        MilestoneForm
    },

    data () {
        return {
            createMilestone: false
        }
    }

}
</script>
