import Vue from "vue";
import VueRouter from "vue-router";
import Dashboard from "../views/Dashboard.vue";
import NotificationList from "../components/notification/NotificationList.vue";
import Profile from "../components/profile/Index.vue";
import cabinetRoutes from "./cabinet/index";
import clinicRoutes from "./clinic/index";
import staffRoutes from "./staff/index";
import schedulerRoutes from "./scheduler/index";

Vue.use(VueRouter);

const routes = [
    {
        path: "/dashboard",
        name: "Dashboard",
        component: Dashboard,
        meta: {
          requiresAuth: true,
        }
    },
    {
        path: "/notifications",
        name: "NotificationList",
        component: NotificationList,
        meta: {
            requiresAuth: true,
        }
    },
    {
        path: "/profile",
        name: "Profile",
        component: Profile,
        meta: {
            requiresAuth: true,
        }
    }
];
routes.push(...clinicRoutes);
routes.push(...cabinetRoutes);
routes.push(...staffRoutes);
routes.push(...schedulerRoutes);

const router = new VueRouter({
  mode: "history",
  base: process.env.BASE_URL,
  routes,
});

// router.beforeEach(async (to, from, next) => {
//   if (
//     to.matched.some((record) => record.meta.requiresAuth) &&
//     !store.state.user
//   ) {
//     await store.dispatch("loadUser");
//     next(store.state.user ? {} : { path: "/signup" });
//   } else {
//     next();
//   }
// });

export default router;
