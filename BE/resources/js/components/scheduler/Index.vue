<template>
    <div>
        <div id="my_form" class="patient-form">
        </div>
        <div>
            <scheduler class="left-container" :events="events"></scheduler>
        </div>
    </div>
</template>

<script>
import Scheduler from './Scheduler.vue';
import PatientForm from './Form.vue';
import { mapGetters, mapMutations } from "vuex";
import * as cabinetGetters from "../../store/modules/Cabinet/types/getters";
import * as schedulerGetters from "../../store/modules/Scheduler/types/getters";
import * as schedulerMutations from "../../store/modules/Scheduler/types/mutations";

export default {
    name: 'app',
    components: { PatientForm, Scheduler },
    data () {
        return {
            events: [
                { id:1, start_date:"2021-10-19 10:00", end_date:"2021-10-19 12:00", text:"Task A-12458", section_id: 1, color: 'rgb(255, 127, 171)'},
            ],
            ModalText: 'Content of the modal',
            visible: false,
            confirmLoading: false,
        }
    },
    computed: {
        ...mapGetters("Cabinet", {
            cabinets: cabinetGetters.GET_CABINETS,
        }),
        ...mapGetters("Scheduler", {
            showModal: schedulerGetters.GET_SHOW_MODAL,
            date: schedulerGetters.GET_DATE,
        }),
    },
    watch: {
        showModal(value) {
            if (value) {
                this.$bvModal.show("modal-patient");
                this.setShowModal(false);
            }
        }
    },
    methods: {
        ...mapMutations('Scheduler', {
            setShowModal: schedulerMutations.SET_SHOW_MODAL,
        }),
    },
}
</script>
<style>
.left-container {
    overflow: hidden;
    position: relative;
    height: calc(100vh - 100px);
}

</style>

