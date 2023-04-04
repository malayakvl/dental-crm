import Scheduler from "../../components/scheduler/Index.vue";

const schedulerRoutes = [
    {
        path: "/scheduler",
        name: "scheduler",
        component: Scheduler,
        meta: {
            requiresAuth: true,
        }
    }
];

export default schedulerRoutes;
