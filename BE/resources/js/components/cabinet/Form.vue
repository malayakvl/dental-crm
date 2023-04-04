<template>
    <form>
        <div class="row">
            <div class="mb-3 col-6 col-lg-6">
                <label for="name" class="form-label">Наименование</label>
                <input type="text" class="form-control" id="name" v-model.trim="$v.name.$model" placeholder="Наименование">
                <div class="error" v-if="!$v.name.required && submitStatus === 'ERROR'">Обязательное поле</div>
                <div class="error" v-if="!$v.name.minLength">Не менее {{$v.name.$params.minLength.min}} символов</div>
                <div class="error" v-if="errorsData.name">{{errorsData.name[0]}}</div>
            </div>
        </div>
        <div class="row">
            <div class="mb-3 col-6 col-lg-6">
                <label for="name" class="form-label">Количество мест</label>
                <input type="number" min="1" class="form-control" id="place_count" v-model.trim="$v.place_count.$model" placeholder="Количество мест">
                <div class="error" v-if="!$v.place_count.required && submitStatus === 'ERROR'">Обязательное поле</div>
                <div class="error" v-if="errorsData.place_count">{{errorsData.place_count[0]}}</div>
            </div>
        </div>
        <div class="row">
            <div class="mb-3 col-6 col-lg-6">
                <button type="button" class="btn btn-warning violet" @click="onSubmit">Обновить</button>
            </div>
        </div>
    </form>
</template>
<script>
import { required, minLength } from 'vuelidate/lib/validators';
import { mapActions, mapGetters, mapMutations } from "vuex";
import * as cabinetActions from "../../store/modules/Cabinet/types/actions"
import * as cabinetGetters from "../../store/modules/Cabinet/types/getters";
import * as cabinetMutations from "../../store/modules/Cabinet/types/mutations";

export default {
    name: "CabinetForm",
    props: ["cabinet", "cabinetId"],
    data() {
        return {
            name: '',
            place_count: '',
            uploadError: '',
            submitStatus: null,
            errorsData: {}
        }
    },
    validations: {
        name: {
            required,
            minLength: minLength(4)
        },
        place_count: {
            required,
            minLength: minLength(1)
        },
    },
    mounted() {
        this.name = this.cabinet.name || '';
        this.place_count = this.cabinet.place_count || '';
    },
    computed: {
        ...mapGetters("Cabinet", {
            errors: cabinetGetters.GET_ERRORS,
            saved: cabinetGetters.DATA_SAVED
        }),
    },
    watch: {
        errors(value) {
            console.log(value);
        },
        saved(value) {
            this.setSaved(false);
            this.$router.push("/cabinet/list");
        }
    },
    methods: {
        ...mapActions('Cabinet', {
            updateCabinet: cabinetActions.UPDATE_CABINET,
            createCabinet: cabinetActions.CREATE_CABINET,
        }),
        ...mapMutations('Cabinet', {
            setSaved: cabinetMutations.SET_DATA_SAVED
        }),
        onSubmit() {
            this.$v.$touch()
            if (this.$v.$invalid) {
                this.submitStatus = 'ERROR'
            } else {
                this.errorsData = {};
                let formData = new FormData();
                formData.append('name', this.name);
                formData.append('place_count', this.place_count);
                if (this.cabinetId) {
                    this.updateCabinet({id:this.cabinetId, postData: formData});
                } else {
                    this.createCabinet(formData);
                }
                setTimeout(() => {
                    this.submitStatus = 'OK'
                }, 500)
            }
        }
    }
}

</script>
