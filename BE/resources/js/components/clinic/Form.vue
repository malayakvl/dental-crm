<template>
    <form>
        <div class="mb-3 col-6 col-lg-3">
            <label for="logo" class="form-label">Загрузить логотип</label>
            <input class="form-control" type="file" id="logo" ref="logo">
            <div class="error" v-if="uploadError">{{uploadError}}</div>
            <div class="error" v-if="errorsData.file">{{errorsData.file[0]}}</div>
        </div>
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
                <label for="name" class="form-label">Сайт клиники</label>
                <input type="text" class="form-control" id="web" v-model="web" placeholder="https://google.com">
                <div class="error" v-if="errorsData.web">{{errorsData.web[0]}}</div>
            </div>
        </div>
        <div class="row">
            <div class="mb-3 col-6 col-lg-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" v-model.trim="$v.email.$model" placeholder="name@example.com">
                <div class="error" v-if="!$v.email.required && submitStatus === 'ERROR'">Обязательное поле</div>
                <div class="error" v-if="!$v.email.email">Неправильный формат</div>
                <div class="error" v-if="errorsData.email">{{errorsData.email[0]}}</div>
            </div>
        </div>
        <div class="row">
            <div class="mb-3 col-6 col-lg-6">
                <label for="phone" class="form-label">Телефон</label>
                <input type="text" class="form-control" id="phone" v-model.trim="$v.phone.$model" placeholder="+38097 111 11 11">
                <div class="error" v-if="!$v.phone.required && submitStatus === 'ERROR'">Обязательное поле</div>
                <div class="error" v-if="errorsData.phone">{{errorsData.phone[0]}}</div>
            </div>
        </div>
        <div class="row">
            <div class="mb-3 col-6 col-lg-6">
                <label for="address" class="form-label">Адрес</label>
                <input type="text" class="form-control" id="address" v-model.trim="$v.address.$model" placeholder="Киев, Дж. Маккейна 3">
                <div class="error" v-if="!$v.address.required && submitStatus === 'ERROR'">Обязательное поле</div>
                <div class="error" v-if="errorsData.address">{{errorsData.address[0]}}</div>
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
import { required, minLength, email } from 'vuelidate/lib/validators';
import {mapActions, mapGetters, mapMutations} from "vuex";
import * as clinicActions from "../../store/modules/Clinic/types/actions"
import * as clinicGetters from "../../store/modules/Clinic/types/getters";
import * as clinicMutations from "../../store/modules/Clinic/types/mutations";

export default {
    name: "ClinicForm",
    props: ["clinic", "clinicId"],
    data() {
        return {
            name: '',
            web: '',
            email: '',
            phone: '',
            address: '',
            files: [],
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
        phone: {
            required,
        },
        address: {
            required,
        },
        email: {
            required,
            email
        }
    },
    mounted() {
        this.name = this.clinic.name || '';
        this.email = this.clinic.email || '';
        this.web = this.clinic.web || '';
        this.phone = this.clinic.phone || '';
        this.address = this.clinic.address || '';
    },
    computed: {
        ...mapGetters("Clinic", {
            errors: clinicGetters.GET_ERRORS,
            saved: clinicGetters.DATA_SAVED
        }),
    },
    watch: {
        errors(value) {
            console.log(value);
        },
        saved(value) {
            this.setSaved(false);
            this.$router.push("/clinic/list");
        }
    },
    methods: {
        ...mapActions('Clinic', {
            updateClinic: clinicActions.UPDATE_CLINIC,
            createClinic: clinicActions.CREATE_CLINIC,
        }),
        ...mapMutations('Clinic', {
            setSaved: clinicMutations.SET_DATA_SAVED
        }),
        onSubmit() {
            this.$v.$touch()
            if (this.$v.$invalid) {
                this.submitStatus = 'ERROR'
            } else {
                // this.formErrors = {};
                this.uploadError = '';
                this.submitStatus = 'PENDING';
                for(let i=0;i<this.$refs.logo.files.length;i++) {
                    this.files.push(this.$refs.logo.files[i]);
                }
                let formData = new FormData();
                formData.append('name', this.name);
                formData.append('web', this.web);
                formData.append('email', this.email);
                formData.append('phone', this.phone);
                formData.append('address', this.address);
                const  extensions = ['jpeg','jpg','png','gif'];
                const uploadErrors = {};
                const $this = this;
                for( let i = 0; i < this.files.length; i++ ){
                    let file = this.files[i];
                    const ext = this.files[i].type.split('/');
                    if (!extensions.includes(ext[1]) || this.files[i].size > 10485760) {
                        this.uploadError = 'Файл цього типу не дозволено, або розмір перевищує 10MB';
                    }
                    formData.append('file', file);
                }
                if (Object.keys(uploadErrors).length === 0) {
                    if (this.clinicId) {
                        this.updateClinic({id:this.clinicId, postData: formData});
                    } else {
                        this.createClinic(formData);
                    }
                }
                setTimeout(() => {
                    this.submitStatus = 'OK'
                }, 500)
            }
        }
    }
}

</script>
