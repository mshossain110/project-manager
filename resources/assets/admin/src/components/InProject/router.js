import InProject from './InProject.vue'
import TaskLists from './List/TaskLists.vue'
import Single from './List/Single.vue'
import Discussions from './Discussion/Discussions.vue'
import Empty from './List.vue'

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
                component: Empty,
                name: 'milestone-page'
            },
            {
                path: 'file',
                component: Empty,
                name: 'file-page'
            },
            {
                path: 'activity',
                component: Empty,
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
