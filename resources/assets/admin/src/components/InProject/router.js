import InProject from './InProject.vue'
import List from './List/List.vue'
import Empty from './List.vue'

const ProjectRoute = [
    {
        path: '/projects/:id',
        component: InProject,
        children: [
            {
                path: '/',
                redirect: 'list',
                name: 'inProject'
            },
            {
                path: 'list',
                component: List,
                name: 'list-page'
            },
            {
                path: 'discussaion',
                component: Empty,
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
