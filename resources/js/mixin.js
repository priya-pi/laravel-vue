export default {
    computed: {
        url() {
            return import.meta.env.VITE_APP_VERSION;
        },
    },
};
