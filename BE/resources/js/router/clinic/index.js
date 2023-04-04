import ClinicCreate from "../../components/clinic/ClinicCreate.vue";
import ClinicEdit from "../../components/clinic/ClinicEdit.vue";
import ClinicList from "../../components/clinic/ClinicList.vue";

const clinicRoutes = [
    {
        path: "/clinic/create",
        name: "createClinic",
        component: ClinicCreate,
        meta: {
            requiresAuth: true,
        }
    },
    {
        path: "/clinic/edit/:id",
        name: "editClinic",
        component: ClinicEdit,
        meta: {
            requiresAuth: true,
        }
    },
    {
        path: "/clinic/list",
        name: "listClinic",
        component: ClinicList,
        meta: {
            requiresAuth: true,
        }
    }
];

export default clinicRoutes;
