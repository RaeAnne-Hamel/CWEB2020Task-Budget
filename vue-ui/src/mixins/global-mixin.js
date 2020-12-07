const TASK_API_URL = 'http://localhost:8003/index.php';

const GlobalMixin = ({
    data(){
        return {
            TASK_API_URL
        }
    }
});

export default GlobalMixin
