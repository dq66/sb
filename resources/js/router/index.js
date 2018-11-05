import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

export default new Router({
    routes: [{
        path: '/login',
        component: require("../components/Login.vue"),
        meta: {
            auth: false
        },
    }, {
        path: '/',
        component: require("../components/Dashboard.vue"),
        meta: {
            auth: true
        },
        children: [{
            path: "back",
            component: require("../components/back")
        }, {
            path: "metas",
            component: require("../components/metas")
        }, {
            path: "tags",
            component: require("../components/tags")
        }, {
            path: "article",
            component: require("../components/article")
        },{
            path: "links",
            component: require("../components/links")
        },{
            path: "comment",
            component: require("../components/comment")
        }]
    }]
})
