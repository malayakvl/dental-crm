<template>
    <div>
        <div class="page-header">
            Кабинеты клиники
        </div>

        <div class="bg-white rounded-lg p-3">
            <div v-if="cabinets.length === 0" class="alert alert-primary" role="alert">
                У вас нет еще кабинетов
            </div>
            <vue-good-table
                class="text-sm"
                v-if="cabinets.length > 0"
                @on-selected-rows-change="selectionChanged"
                :columns="columns"
                :rows="cabinets"
                :select-options="{ enabled: true }"
                :search-options="{enabled: true}"
                :pagination-options="{enabled: true}"
            >
                <template slot="table-row" slot-scope="props">
                    <span v-if="props.column.field == 'actions'">
                        <router-link :to="{path: `/cabinet/edit/${props.formattedRow.id}`}" title="Редактировать"><i class="fas fa-edit"></i></router-link>
                        <a href="javascript:;" @click="deleteCabinet(props.formattedRow.id)" title="Удалить"><i class="fas fa-trash"></i></a>
                    </span>
                    <span v-if="!['actions'].includes(props.column.field)">
                        {{props.formattedRow[props.column.field]}}
                    </span>
                </template>

            </vue-good-table>
        </div>
    </div>
</template>
<script>
import 'vue-good-table/dist/vue-good-table.css'
import { VueGoodTable } from 'vue-good-table';
import { mapState, mapActions, mapGetters } from "vuex";
import * as cabinetGetters from "../../store/modules/Cabinet/types/getters";
import * as cabinetActions from "../../store/modules/Cabinet/types/actions"

export default {
    components: {
        VueGoodTable,
    },
    data() {
        return {
            rows: [],
            columns: [
                {
                    label: 'ID',
                    field: 'id',
                    sortable: false
                },
                {
                    label: 'Наименование',
                    field: 'name',
                },
                {
                    label: 'Количество мест',
                    field: 'place_count',
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
        this.getCabinets();
    },
    computed: {
        ...mapState(["clinicId", "user"]),
        ...mapGetters("Cabinet", {
            cabinets: cabinetGetters.GET_CABINETS,
        }),
    },
    methods: {
        ...mapActions("Cabinet", {
            getCabinets: cabinetActions.GET_CABINETS,
        }),
        deleteCabinet(cabinetId) {

        },
        selectionChanged() {

        }
    }
}
</script>
