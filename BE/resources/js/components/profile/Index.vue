<template>
    <div class="container-fluid">
        <div class="panel blue-panel">
            <h1><i class="fas fa-user"></i>  Редактировать профиль</h1>
            <div v-if="updateSuccess" class="alert alert-success" role="alert">
                Профиль успешно обновлен
            </div>
            <form>
                <div class="mb-3 col-6 col-lg-3">
                    <label for="photo" class="form-label">
                        Загрузить фото
                        <img v-if="photo" :style="{'background-image': `url(/storage/${photo})`}" class="avatar float-start mr-2">
                    </label>
                    <input class="form-control" type="file" id="photo" ref="photo">
                    <div class="error" v-if="uploadError">{{uploadError}}</div>
                    <div class="error" v-if="errors.file">{{errors.file[0]}}</div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6 col-lg-6">
                        <label for="name" class="form-label">ФИО</label>
                        <input type="text" class="form-control" id="name" v-model.trim="$v.name.$model" placeholder="Наименование">
                        <div class="error" v-if="!$v.name.required && submitStatus === 'ERROR'">Обязательное поле</div>
                        <div class="error" v-if="!$v.name.minLength">Не менее {{$v.name.$params.minLength.min}} символов</div>
                        <div class="error" v-if="errors.name">{{errors.name[0]}}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6 col-lg-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" v-model.trim="$v.email.$model" placeholder="name@example.com">
                        <div class="error" v-if="!$v.email.required && submitStatus === 'ERROR'">Обязательное поле</div>
                        <div class="error" v-if="!$v.email.email">Неправильный формат</div>
                        <div class="error" v-if="errors.email">{{errors.email[0]}}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6 col-lg-6">
                        <label for="phone" class="form-label">Телефон</label>
                        <input type="text" class="form-control" id="phone" v-model.trim="$v.phone.$model" placeholder="+38097 111 11 11">
                        <div class="error" v-if="!$v.phone.required && submitStatus === 'ERROR'">Обязательное поле</div>
                        <div class="error" v-if="errors.phone">{{errors.phone[0]}}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6 col-lg-6">
                        <button type="button" class="btn btn-warning pink" @click="onSubmit">Обновить</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
<script>
import { required, minLength, email } from 'vuelidate/lib/validators';

export default {
    data() {
        return {
            name: '',
            email: '',
            phone: '',
            files: [],
            errors: {},
            uploadError: '',
            submitStatus: null,
            photo: '',
            updateSuccess: false,
            timeout: null,
            debounceMilliseconds: 3000,
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
        email: {
            required,
            email
        },
    },
    async mounted() {
        await this.getUser();
    },
    methods: {
        async getUser() {
            const res = await fetch("/api/user/edit", {
                method: "GET",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            if (res.ok) {
                const data = await res.json();
                this.name = data.name;
                this.email = data.email;
                this.phone = data.phone;
                this.photo = data.photo;
            }
        },
        onSubmit() {
            this.$v.$touch()
            if (this.$v.$invalid) {
                this.submitStatus = 'ERROR'
            } else {
                this.errors = {};
                this.uploadError = '';
                // do your submit logic here
                this.submitStatus = 'PENDING';
                for(let i=0;i<this.$refs.photo.files.length;i++) {
                    this.files.push(this.$refs.photo.files[i]);
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
                    axios.post( `/api/user/update`,
                        formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }
                    ).then(function(response){
                        console.log(response);
                        $this.updateSuccess = true;
                        clearTimeout($this.timeout);
                        $this.timeout = setTimeout(() => {
                            $this.updateSuccess = false;
                        }, $this.debounceMilliseconds);
                        // $this.$router.push("/profile");
                    })
                        .catch(e => {
                            console.log('FAILURE!!', e.message);
                            if (e.response) {
                                const errors = e.response.data.errors;
                                this.errors = errors;
                            }
                        });
                }
                setTimeout(() => {
                    this.submitStatus = 'OK'
                }, 500)
            }
        }
    }
}

</script>
