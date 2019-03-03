import DashRoute from '@ac/Dashboard/route'
import UserRoute from '@ac/Users/route'
import Projects from '@ac/Projects/router'
import Category from '@ac/Category/router'
import InProject from '@ac/InProject/router'
let Routes = []

Routes.push(DashRoute)
Routes = Routes.concat(UserRoute)
Routes = Routes.concat(Category)
Routes = Routes.concat(Projects)
Routes = Routes.concat(InProject)

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
