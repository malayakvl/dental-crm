import axios from "axios";

const appUrl = process.env.MIX_APP_URL;

const menuService = {
    async getMenu() {
        return  axios
            .get(`${appUrl}/api/get-menu`)
            .then(response => response.data)
            .catch(err => console.log(err));
    },
};

export default menuService;
