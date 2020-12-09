const TransactionMixin = ({
    props:{
        disabled:{
            type: Boolean,
            default:()=>(false)
        }
    },
    data(){
        return {
            isBusy: false
        }
    },
    computed:{
        isDisabled() {
            return this.isBusy || this.disabled
        }
    },
    methods: {
        setBusy(state) {
            this.isBusy = state;
            this.$emit('busy',state);
        },
        callAPI(method, dataToSend){
            let config={
                method: method.toLowerCase(),
                url: this.TRANSACTION_API_URL,
                params:{}
            };

            if(['put', 'post'].includes(config.method)){
                config.data = dataToSend;
            } else {
                config.params = dataToSend
            }

            if(this.debug){
                Object.assign(config.params, {XDEBUG_SESSION_START:'phpdebug'});
            }

            return this.axios(config);
        }
    }
});

export default TransactionMixin