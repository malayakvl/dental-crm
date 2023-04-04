import ClinicRoleList from "../../components/_clinic-roles/ClinicRoleList.vue";

const roleRoutes = [
    {
        path: "/staff/list",
        name: "ClinicRoleList",
        component: ClinicRoleList,
        meta: {
            requiresAuth: true,
        }
    }
];

export default roleRoutes;
