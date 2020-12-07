const TRANSACTION_API_URL = 'http://localhost:8005/transactionapi.php';

const GlobalMixin = ({
    data: function(){
        return {
            TRANSACTION_API_URL
        }
    }
});

export default GlobalMixin;
