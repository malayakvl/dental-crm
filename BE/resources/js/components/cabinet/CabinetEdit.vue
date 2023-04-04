<template>
    <div class="container-fluid">
        <div class="panel blue-panel">
            <h1><i class="fas fa-door-open"></i>  Редактировать кабинет</h1>
            <CabinetForm v-if="currentCabinet" :cabinet="currentCabinet" :cabinetId="cabientId" />
        </div>
    </div>
</template>
<script>
import * as cabinetActions from "../../store/modules/Cabinet/types/actions"
import * as cabinetGetters from "../../store/modules/Cabinet/types/getters";
import CabinetForm from "./Form";
import { mapActions, mapGetters, mapMutations } from "vuex";

export default {
    components: {
        CabinetForm
    },
    data() {
        return {
            currentCabinet: null,
            cabientId: this.$route.params.id,

            name: '',
            errors: {},
            submitStatus: null,
        }
    },
    async mounted() {
        this.getCabinet(this.$route.params.id);
    },
    computed: {
        ...mapGetters("Cabinet", {
            cabinet: cabinetGetters.GET_CABINET,
            saved: cabinetGetters.DATA_SAVED
        }),
    },
    watch: {
        cabinet(value) {
            this.currentCabinet = value;
        },
    },
    methods: {
        ...mapActions('Cabinet', {
            getCabinet: cabinetActions.GET_CABINET,
        }),
    }
}

</script>
