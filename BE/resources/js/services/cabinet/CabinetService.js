import axios from "axios";

const appUrl = process.env.MIX_APP_URL;

const cabinetService = {
    async getCabinets() {
        return  axios
            .get(`${appUrl}/api/cabinet/list`)
            .then(response => response.data)
            .catch(err => console.log(err));
    },
    async getCabinet(id) {
        return  axios
            .get(`${appUrl}/api/cabinet/edit/${id}`)
            .then(response => response.data)
            .catch(err => console.log(err));
    },
    async updateCabinet(data) {
        return axios.post( `${appUrl}/api/cabinet/update/${data.id}`,
            data.postData,
            {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }
        ).then(response => response.data);
    },
    async createCabinet(data) {
        return axios.post( `${appUrl}/api/cabinet/store`,
            data,
            {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }
        ).then(response => response.data);
    }
};

export default cabinetService;
