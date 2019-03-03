import InProject from './InProject.vue'
import List from './List.vue'

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
                component: List,
                name: 'discussion-page'
            },
            {
                path: 'milestone',
                component: List,
                name: 'milestone-page'
            },
            {
                path: 'file',
                component: List,
                name: 'file-page'
            },
            {
                path: 'activity',
                component: List,
                name: 'activity-page'
            },
            {
                path: 'voerview',
                component: List,
                name: 'overview-page'
            }
        ]
    }
]

export default ProjectRoute
