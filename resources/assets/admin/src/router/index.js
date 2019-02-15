import DashRoute from '@ac/Dashboard/route'
import UserRoute from '@ac/Users/route'
let Routes = []

Routes.push(DashRoute)
Routes = Routes.concat(UserRoute)

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
