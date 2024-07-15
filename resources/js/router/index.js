import { createWebHistory, createRouter } from "vue-router"

import Registration from '../pages/Registration.vue'
import Login from '../pages/Login.vue'
import Admin from '../pages/Admin.vue'
import Home from '../pages/Home.vue'
import Catalog from '../pages/Catalog.vue'
import Userpage from '../pages/Userpage.vue'
import Edituser from '../pages/Edituser.vue'
import Liked from '../pages/Liked.vue'
import Subscribtions from '../pages/Subscribtions.vue'
import Collections from '../pages/Collections.vue'
import Collection_page from '../pages/Collection_page.vue'
import Pockets_buy from '../pages/Pockets_buy.vue'
import Bank from '../pages/Bank.vue'
import Material from '../pages/Material.vue'
import Bougth from '../pages/Bougth.vue'
import User_delete from "../pages/User_delete.vue"
import Price_modering from "../pages/Price_modering.vue"
import FAQ from "../pages/FAQ.vue"
import License from "../pages/License.vue"
import Contact from "../pages/Contact.vue"

export const routes = [
    {
        name: 'Registration',
        path: '/registration',
        component: Registration
    },
    {
        name: 'Login',
        path: '/login',
        component: Login
    },
    {
        name: 'Admin',
        path: '/admin',
        component: Admin
    },
    {
        name: 'Home',
        path: '/',
        component: Home
    },
    {
        name: 'Catalog',
        path: '/catalog',
        component: Catalog
    },
    {
        name: 'Userpage',
        path: '/user/:id',
        component: Userpage
    },
    {
        name: 'Edituser',
        path: '/edit',
        component: Edituser
    },
    {
        name: 'Liked',
        path: '/liked',
        component: Liked
    },
    {
        name: 'Subscribtions',
        path: '/subscribtions',
        component: Subscribtions
    },
    {
        name: 'Collections',
        path: '/collections',
        component: Collections
    },
    {
        name: 'Collection_page',
        path: '/collection/:id',
        component: Collection_page
    },
    {
        name: 'Pockets_buy',
        path: '/pockets',
        component: Pockets_buy
    },
    {
        name: 'Bank',
        path: '/bank',
        component: Bank
    },
    {
        name: 'Material',
        path: '/material/:id',
        component: Material
    },
    {
        name: 'Bougth',
        path: '/bougth',
        component: Bougth
    },
    {
        name: 'User_delete',
        path: '/userdelete',
        component: User_delete
    },
    {
        name: 'Price_modering',
        path: '/pricemodering',
        component: Price_modering
    },
    {
        name: 'FAQ',
        path: '/faq',
        component: FAQ
    },
    {
        name: 'License',
        path: '/license',
        component: License
    },
    {
        name: 'Contact',
        path: '/contacts',
        component: Contact
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
})

export default router;

