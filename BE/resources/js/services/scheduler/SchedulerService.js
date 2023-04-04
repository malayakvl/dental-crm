import axios from "axios";

const appUrl = process.env.MIX_APP_URL;

const schedulerService = {
    async getEvents(data) {
        return  axios
            .get(`${appUrl}/api/get-menu`)
            .then(response => response.data)
            .catch(err => console.log(err));
    },
    async createEvent(data) {
        return axios.post( `${appUrl}/api/scheduler/store`,
            data,
            {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }
        ).then(response => response.data);
    },
};

export default schedulerService;
