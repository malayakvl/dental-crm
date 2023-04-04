import StaffRoles from "../../components/staff/StaffRoles.vue";
import StaffList from "../../components/staff/StaffList.vue";

const staffRoutes = [
    {
        path: "/staff/list",
        name: "listStaff",
        component: StaffList,
        meta: {
            requiresAuth: true,
        }
    },
    {
        path: "/staff/roles",
        name: "StaffRoles",
        component: StaffRoles,
        meta: {
            requiresAuth: true,
        }
    }
];

export default staffRoutes;
