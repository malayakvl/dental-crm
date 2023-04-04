import axios from "axios";
import clinicService from "../clinic/ClinicService";

const appUrl = process.env.MIX_APP_URL;

const staffService = {
    async getPatients(searchStr) {
        return  axios
            .get(`${appUrl}/api/staff/getPatients?search=${searchStr}`)
            .then(response => response.data)
            .catch(err => console.log(err));
    },
    async getStaff() {
        return  axios
            .get(`${appUrl}/api/staff/roles`)
            .then(response => response.data)
            .catch(err => console.log(err));
    },
    async getStaffList() {
        return  axios
            .get(`${appUrl}/api/staff/list`)
            .then(response => response.data)
            .catch(err => console.log(err));
    },
    async setColor(data) {
        return  axios
            .get(`${appUrl}/api/staff/setColor?color=${data.color}&staffId=${data.id}`)
            .then(response => response.data)
            .catch(err => console.log(err));
    },
    async setPermission(data) {
        return  axios
            .get(`${appUrl}/api/staff/setPermission?permissionId=${data.permissionId}&staffId=${data.id}`)
            .then(response => response.data)
            .catch(err => console.log(err));
    },
    async setRole(data) {
        return  axios
            .get(`${appUrl}/api/staff/setRole?role=${data.role}&staffId=${data.id}`)
            .then(response => response.data)
            .catch(err => console.log(err));
    },
    async findStaff(data) {
        return  axios
            .get(`${appUrl}/api/user/search?str=${data}`)
            .then(response => response.data)
            .catch(err => console.log(err));
    },
    async inviteStaff(data) {
        return  axios
            .post(`${appUrl}/api/user/invite`, data)
            .then(response => response.data)
            .catch(err => console.log(err));
    },
}

export default staffService;
