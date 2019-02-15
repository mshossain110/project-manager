import DashRoute from '@ac/Dashboard/route'
import UserRoute from '@ac/Users/route'
import Projects from '@ac/Projects/router'
let Routes = []

Routes.push(DashRoute)
Routes = Routes.concat(UserRoute)
Routes = Routes.concat(Projects)

export default [
    {
        path: '/',
        component: {
            render (c) {
                return c('router-view')
            }
        },
        redirect: { name: 'dashboard' },
        children: Routes

    }
]
