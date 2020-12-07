<template>
<div>
  <h1>Transactions</h1>
  <table class="table table-striped table-hover table-dark">
    <thead>
      <tr>
        <th>BankID</th>
        <th>Amount</th>
        <th>Transaction Type</th>
        <th>Paid</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="transaction in transactionArray" :key="transaction.id">
        <td>{{transaction.bankID}}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
    </tbody>
  </table>

</div>
</template>

<script>
import TransactionMixin from "@/mixins/transaction-maxin";

export default {
  name: "TransactionList",
  mixins:[TransactionMixin],
  data:function() {
    return {
      transactionArray: []
    };
  },
  methods:{
    getTransactions: function() {

      this.axios.get(this.TRANSACTION_API_URL)
          .then( (resp) => {
            this.transactionArray = resp.data;
            this.$emit('refreshed', this.transactionArray);
          })
          .catch(err => {
            if(err.response.status == 404)
            {
              this.transactionArray = [];
            }
          }).finally(()=>{

          });
    }
  },
  mounted() {
    this.getTransactions();
  }


}
</script>

<style scoped>

</style>