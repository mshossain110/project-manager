import Projects from './Projects.vue'
import AllProject from './AllProjects.vue'
import ActiveProject from './ActiveProjects.vue'
import CompleteProject from './CompleteProjects.vue'
import FavouriteProject from './FavouriteProjects.vue'

const ProjectRoute = [
    {
        path: 'projects',
        component: Projects,
        children: [
            {
                path: 'active',
                name: 'project_active',
                component: ActiveProject

            },
            {
                path: 'complete',
                name: 'project_complete',
                component: CompleteProject

            },
            {
                path: 'all',
                name: 'project_all',
                component: AllProject

            },
            {
                path: 'favourite',
                name: 'project_favourite',
                component: FavouriteProject

            }
        ]
    }
]

export default ProjectRoute
