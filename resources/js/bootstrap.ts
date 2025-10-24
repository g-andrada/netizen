import axios from 'axios';

axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;

axios.get('/sanctum/csrf-cookie').then(response => {
    console.log(response);
});