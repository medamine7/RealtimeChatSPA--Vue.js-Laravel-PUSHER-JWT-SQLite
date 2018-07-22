import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

import Home from './components/views/home'
import Chat from './components/views/chat'

const router= new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home,
        },
        {
            path: '/chat',
            name: 'chat',
            component: Chat,
            meta: { requiresAuth: true }
        }
    ],
});


router.beforeEach((to, from, next) => {
    if(to.meta.requiresAuth)
        if (window.localStorage.getItem('token'))
            next();
        else
            next({name:'home'});
    else
        if (window.localStorage.getItem('token'))
            next({name:'chat'});
        else
            next();
    
})

export default router;

