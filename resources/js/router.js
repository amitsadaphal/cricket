import Vue from 'vue';
import Vuex from 'vuex';
import VueRouter from 'vue-router';

import LoginComponent from './components/LoginComponent';
import AdminComponent from './components/AdminComponent';
import TeamComponent from './components/TeamComponent';
import PointComponent from './components/PointComponent';
import MatchComponent from './components/MatchComponent';

Vue.use(Vuex)

Vue.use(VueRouter)

const routes = [
    
    {
        path: '/',        
        redirect: '/login'
    },
   
    {
        path: '/login',
        component: LoginComponent,        
        name: 'Login',
       
    },
    {
        path: '/admin',
        component: AdminComponent,
        name: 'Admin',
        
          children: [
            {
                path: 'teams',
                component: TeamComponent,
                name: 'Teams'
            },
            {
                path: 'points',
                component: PointComponent,
                name: 'Points'
            },
            {
                path: 'matches',
                component: MatchComponent,
                name: 'Match'
            },
          ],
          beforeEnter: (to, from, next) => {
            axios.get('api/verify')
            .then(res => next())
            .catch(err =>next('/login'))
            // if(localStorage.getItem('token'))
            // {
            //     next();
            // }
            // else{
            //     next('/login');
            // }
          },
    },
    //This Route for if any route url after admin login not found 
    //you will be automatically redirect previous url
    {
        path: '/admin/*',        
        beforeEnter: (to, from, next) => {
           next(from)
         }
    },
]
const router = new VueRouter({ mode: 'history', routes})
router.beforeEach((to,from,next ) => {    
        const token = localStorage.getItem('token') || null
        window.axios.defaults.headers['Authorization'] = "Bearer " + token;
        next();
    
})
export default router

