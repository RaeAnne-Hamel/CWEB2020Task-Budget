const TaskMixin = ({

    //communication from the parent to the child
    props: {

        // disabled: {
        //     type: Boolean,
        //     default: () => (false)
        // }
    },
    data: function () {
        return {
            isBusy: false
        }
    },
    computed:{
            isdisabled: function (){
                return this.isBusy || this.disabled;
            }
        },
    methods: {
            setBusy: function (state) {
                this.isBusy = state;
                this.$emit('busy', state);
            },
            callAPI: function(method, dataToSend) {

                let config = {
                    method: method.toLowerCase(),
                    url: this.TASK_API_URL,
                    params: {}

                }

                 //if the put or post is call
                 if (['post', 'put'].includes(config.method)) {
                     //send data in json body format
                     config.data = dataToSend;
                 } else {
                     //for get and delete send attributes through URL
                     config.params = dataToSend;
                 }

                 //if true for debug prop object
                 if (this.debug) {
                     //send the url params to the php xdebug
                     Object.assign(config.params, {XDEBUG_SESSION_START: 'phpdebug'});
                 }
                 //returns an axios promise object
                 return this.axios(config);
             }
    }


});

 export default TaskMixin;