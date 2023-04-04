import CabinetCreate from "../../components/cabinet/CabinetCreate.vue";
import CabinetEdit from "../../components/cabinet/CabinetEdit.vue";
import CabinetList from "../../components/cabinet/CabinetList.vue";

const cabinetRoutes = [
    {
        path: "/cabinet/create",
        name: "createCabinet",
        component: CabinetCreate,
        meta: {
            requiresAuth: true,
        }
    },
    {
        path: "/cabinet/edit/:id",
        name: "editCabinet",
        component: CabinetEdit,
        meta: {
            requiresAuth: true,
        }
    },
    {
        path: "/cabinet/list",
        name: "listCabinet",
        component: CabinetList,
        meta: {
            requiresAuth: true,
        }
    }
];

export default cabinetRoutes;
