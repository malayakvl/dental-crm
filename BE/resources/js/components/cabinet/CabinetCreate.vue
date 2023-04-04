<template>
    <div class="container-fluid">
        <div class="panel blue-panel">
            <h1><i class="fas fa-door-open"></i>  Добавить кабинет</h1>
            <CabinetForm :cabinet="{}" />
        </div>
    </div>
</template>
<script>
import CabinetForm from "./Form";

export default {
    components: {
        CabinetForm
    },
}

</script>
