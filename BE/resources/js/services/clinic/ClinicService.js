import axios from "axios";

const appUrl = process.env.MIX_APP_URL;

const clinicService = {
    async getClinics() {
        return  axios
            .get(`${appUrl}/api/clinic/list`)
            .then(response => response.data)
            .catch(err => console.log(err));
    },
    async getClinic(id) {
        return  axios
            .get(`${appUrl}/api/clinic/edit/${id}`)
            .then(response => response.data)
            .catch(err => console.log(err));
    },
    async updateClinic(data) {
        return axios.post( `${appUrl}/api/clinic/update/${data.id}`,
            data.postData,
            {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }
        ).then(response => response.data);
    },
    async createClinic(data) {
        return axios.post( `${appUrl}/api/clinic/store`,
            data,
            {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }
        ).then(response => response.data);
    }
};

export default clinicService;
