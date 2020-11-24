const API_URL = 'http://localhost:8003/';

const GlobalMixin = ({
    data(){
        return {
            API_URL
        }
    }
});

export default GlobalMixin
