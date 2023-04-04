<template>
    <div class="container-fluid">
        <div class="panel blue-panel">
            <h1><i class="fas fa-clinic-medical"></i>  Редактировать клинику</h1>
            <ClinicForm v-if="currentClinic" :clinic="currentClinic" :clinicId="clinicId" />
        </div>
    </div>
</template>
<script>
import { mapActions, mapGetters, mapMutations } from "vuex";
import * as clinicActions from "../../store/modules/Clinic/types/actions"
import * as clinicGetters from "../../store/modules/Clinic/types/getters";
import * as clinicMutations from "../../store/modules/Clinic/types/mutations";
import ClinicForm from "./Form";

export default {
    components: {
        ClinicForm
    },
    data() {
        return {
            currentClinic: null,
            clinicId: this.$route.params.id
        }
    },
    mounted() {
        this.getClinic(this.$route.params.id);
    },
    computed: {
        ...mapGetters("Clinic", {
            clinic: clinicGetters.GET_CLINIC,
        }),
    },
    watch: {
        clinic(value) {
            this.currentClinic = value;
        },
    },
    methods: {
        ...mapActions('Clinic', {
            getClinic: clinicActions.GET_CLINIC,
        }),
    }
}

</script>
