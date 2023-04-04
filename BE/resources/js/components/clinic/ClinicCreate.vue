<template>
    <div class="container-fluid">
        <div class="panel blue-panel">
            <h1><i class="fas fa-clinic-medical"></i>  Добавить клинику</h1>
            <ClinicForm :clinic="{}" />
        </div>
    </div>
</template>
<script>
import ClinicForm from "./Form";

export default {
    components: {
        ClinicForm
    },
}

</script>
