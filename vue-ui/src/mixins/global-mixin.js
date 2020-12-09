const TASK_API_URL = 'http://localhost:8003/index.php';

const GlobalMixin = ({
    props: {
        debug: {
            type: Boolean,
            default:() =>false
        }
    },
    data(){
        return {
            TASK_API_URL
        }
    },
    mounted() {
        if(this.debug){
            console.log(this.$props);
            console.log(this.$data);
        }
    }
});

export default GlobalMixin
