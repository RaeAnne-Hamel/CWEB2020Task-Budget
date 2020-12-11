const TRANSACTION_API_URL = 'http://localhost:8003/transactionapi.php';
const TASK_API_URL = 'http://localhost:8003/index.php';
const GlobalMixin = ({
    props:{
        debug:{
            type: Boolean,
            default:()=>(false)
        }
    },
    data: function(){
        return {
            TRANSACTION_API_URL,
            TASK_API_URL
        }
    },
    mounted(){
        if(this.debug)
        {
            console.log(this.$props);
            console.log(this.$data);
        }
    }
});

export default GlobalMixin;
