<template>
    <div class="container-fluid">
        <div class="panel blue-panel">
            <h1><i class="fas fa-clinic-medical"></i>  Мои клиники</h1>
            <div v-if="clinics.length === 0" class="alert alert-primary" role="alert">
                У вас нет еще клиник
            </div>
            <vue-good-table
                v-if="clinics.length > 0"
                @on-selected-rows-change="selectionChanged"
                :columns="columns"
                :rows="clinics"
                :select-options="{ enabled: true }"
                :search-options="{enabled: true}"
                :pagination-options="{enabled: true}"
            >
                <template slot="table-row" slot-scope="props">
                    <span v-if="props.column.field === 'logo' && props.formattedRow[props.column.field]">
                      <img :src="`/storage/${props.formattedRow[props.column.field]}`" class="logo" alt="">
                    </span>
                    <span v-if="props.column.field === 'logo' && !props.formattedRow[props.column.field]">

                    </span>
                    <span v-if="props.column.field === 'pivot.invite_date'">
                        {{ props.formattedRow[props.column.field] }}
                    </span>
                    <span v-if="props.column.field === 'actions'">
                        <router-link :to="{path: `/clinic/edit/${props.formattedRow.id}`}" title="Редактировать"><i class="fas fa-edit"></i></router-link>
                        <a href="javascript:" @click="deleteClinic(props.formattedRow.id)" title="Удалить"><i class="fas fa-trash"></i></a>
                        <a href="javascript:" v-if="!clinicId || clinicId !== props.formattedRow.id" @click="enter(props.formattedRow.id)" title="Войти"><i class="fas fa-sign-in-alt"></i></a>
                        <a href="javascript:" v-if="clinicId === props.formattedRow.id" @click="enter(props.formattedRow.id)" title="Log out"><i class="fas fa-sign-out-alt"></i></a>
                    </span>
                    <span v-if="!['logo', 'actions','pivot.invite_date'].includes(props.column.field)">
                        {{props.formattedRow[props.column.field]}}
                    </span>
                </template>

            </vue-good-table>
        </div>
    </div>
</template>
<script>

import "vue-good-table/dist/vue-good-table.css";
import { VueGoodTable } from "vue-good-table";
import { mapState, mapActions, mapGetters } from "vuex";
import * as clinicGetters from "../../store/modules/Clinic/types/getters";
import * as clinicActions from "../../store/modules/Clinic/types/actions"

export default {
    components: {
        VueGoodTable,
    },
    data() {
        return {
            columns: [
                {
                    label: 'ID',
                    field: 'id',
                    sortable: false
                },
                {
                    label: 'Лого',
                    field: 'logo',
                    sortable: false
                },
                {
                    label: 'Наименование',
                    field: 'name',
                },
                {
                    label: 'Email',
                    field: 'email',
                },
                {
                    label: 'Сайт',
                    field: 'web',
                },
                {
                    label: 'Телефон',
                    field: 'phone',
                },
                {
                    label: 'Адрес',
                    field: 'address',
                },
                {
                    label: 'Действия',
                    field: 'actions',
                    sortable: false
                }
            ]
        }
    },
    mounted() {
        this.getClinis();
    },
    computed: {
        ...mapState(["clinicId", "user"]),
        ...mapGetters("Clinic", {
            clinics: clinicGetters.GET_CLINICS,
        }),
    },
    methods: {
        ...mapActions(["entranceClinic"]),
        ...mapActions("Clinic", {
            getClinis: clinicActions.GET_CLINICS,
        }),
        deleteClinic(clinicId) {

        },
        enter(clinicId) {
          this.entranceClinic(clinicId);
        },
        async entranceEmployeeClinic(clinicId) {
            const res = await fetch(`/api/clinic/employee-login/${clinicId}`, {
                method: "GET",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            if (res.ok) {
                const data = await res.json();
                this.$store.commit('setClinic', data);
            }
        },
        selectionChanged() {

        }
    }
}
</script>
