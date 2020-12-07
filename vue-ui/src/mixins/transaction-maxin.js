const TransactionMixin = ({
    methods:{
        callAPI:function(method, dataToSend){
            let config = {
                method : method.toLowerCase(),
                url: this.TRANSACTION_API_URL,
                params: {}
            }

            if(['post','put'].includes(config.method)){
                config.data = dataToSend;
            }else{
                config.params = dataToSend;
            }

            return this.axios(config);
        }
    }
});

export default TransactionMixin