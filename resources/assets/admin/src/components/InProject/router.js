import InProject from './InProject.vue'
import TaskLists from './List/TaskLists.vue'
import Single from './List/Single.vue'
import Discussions from './Discussion/Discussions.vue'
import Activities from './Activity/Activities.vue'
import Empty from './List.vue'
import Milestone from './Milestone/Milestones.vue'

const ProjectRoute = [
    {
        path: '/projects/:project_id',
        component: InProject,
        children: [
            {
                path: '/',
                redirect: 'lists',
                name: 'inProject'
            },
            {
                path: 'lists',
                component: TaskLists,
                name: 'list-page',
                children: [
                    {
                        path: ':type/:id',
                        name: 'list-single',
                        component: Single
                    }
                ]
            },
            {
                path: 'discussaion',
                component: Discussions,
                name: 'discussion-page'
            },
            {
                path: 'milestone',
                component: Milestone,
                name: 'milestone-page'
            },
            {
                path: 'file',
                component: Empty,
                name: 'file-page'
            },
            {
                path: 'activity',
                component: Activities,
                name: 'activity-page'
            },
            {
                path: 'voerview',
                component: Empty,
                name: 'overview-page'
            }
        ]
    }
]

export default ProjectRoute
