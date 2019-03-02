<template>
    <VCard>
        <form @submit.prevent="submit()">
            <VCardTitle>
                <span
                    v-if="project.id"
                    class="headline"
                >
                    Update Project
                </span>
                <span
                    v-else
                    class="headline"
                >
                    New Project
                </span>
            </VCardTitle>

            <VCardText>
                <VContainer grid-list-md>
                    <VLayout wrap>
                        <VFlex
                            xs12
                        >
                            <VTextField
                                v-model="project.title"
                                :error-messages="errors.collect('title')"
                                label="Title"
                            />
                        </VFlex>
                        <VFlex
                            xs12
                        >
                            <VTextarea
                                v-model="project.description"
                                name="description"
                                box
                                label="Description"
                                :error-messages="errors.collect('description')"
                                auto-grow
                            />
                        </VFlex>
                        <VFlex xs12>
                            <CategorySelectForm v-model="project.categories" />
                        </VFlex>
                        <VFlex xs12>
                            <UserSelectForm v-model="project.assaingee" />
                        </VFlex>
                    </VLayout>
                </VContainer>

                <small>*indicates required field</small>
            </VCardText>
            <VDivider />
            <VCardActions>
                <VSpacer />
                <VBtn
                    color="blue darken-1"
                    flat
                    @click.native="$emit('close', false)"
                >
                    Close
                </VBtn>
                <VBtn
                    color="blue darken-1"
                    flat
                    type="submit"
                >
                    Save
                </VBtn>
            </VCardActions>
        </form>
    </VCard>
</template>

<script>
// import Multiselect from 'vue-multiselect'
import { mapState } from 'vuex'
import CategorySelectForm from '@ac/Category/CategorySelectForm.vue'
import UserSelectForm from '@ac/Users/UserSelectForm.vue'

export default {
    components: {
        CategorySelectForm, UserSelectForm
    },
    $_veeValidate: {
        validator: 'new'
    },
    props: {
        project: {
            type: Object,
            default () {
                return {
                    title: '',
                    description: '',
                    categories: [],
                    budget: '',
                    password: '',
                    assignees: []
                }
            }
        }
    },

    data: () => ({

    }),

    computed: {
        // ...mapState('Users', ['permissions'])
    },
    created () {

    },
    methods: {
        submit () {
            this.$validator.validateAll()

            let assignees = []; let categories = []
            this.project.assignees.map(u => {
                assignees.push({
                    user_id: u.id,
                    role_id: 1
                })
            })

            this.project.categories.map(c => {
                categories.push(c.id)
            })

            const project = {
                id: this.project.id,
                title: this.project.title,
                description: this.project.description,
                categories: categories,
                status: 'incomplete',
                assignees: assignees,
                budget: this.project.budget
            }
            if (!this.project.id) {
                this.$store.dispatch('Projects/addProject', project)
                    .then(() => {
                        this.clear()
                        this.$emit('close', false)
                    })
            } else {
                this.$store.dispatch('Users/updateUser', project)
                    .then(() => {
                        this.$emit('close', false)
                    })
            }
        },
        clear () {
            this.project = {
                title: '',
                description: '',
                categories: [],
                budget: '',
                password: '',
                assignees: []
            }
        }
    }
}
</script>
