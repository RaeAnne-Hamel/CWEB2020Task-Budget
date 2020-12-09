<template>
<div>
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
      <tr v-for="transaction in transactionArray" :key="transaction.id" :class="{'table-info':selectedTransaction.id == transaction.id}">
        <td>{{transaction.bankID}}</td>
        <td>{{transaction.amount}}</td>
        <td>{{transaction.typeTransaction}}</td>
        <td>{{transaction.checkPaid}}</td>
      </tr>
    </tbody>
  </table>
{{$data}}
</div>
</template>

<script>
import TransactionMixin from "@/mixins/transaction-maxin";

export default {
  name: "TransactionList",
  mixins:[TransactionMixin],
  data() {
    return {
      sortField:'id',
      sortDirection:'asc',
      selectedTransaction: {},
      transactionArray: [],
      CurrentBank: 1
    };
  },
  methods:{
    getTransactions() {
      this.setBusy(true);
      this.callAPI('get', {sort:this.sortField,order:this.sortDirection})
        .then(response => {
          this.transactionArray = response.data;
          this.$emit('refreshed', this.transactionArray)
        })
        .catch(errors => {
          if(errors.response.status==404) {
            this.transactionArray = [];
          }
        })
        .finally(() => {
          this.setBusy(false);
        });
    }
  },
  mounted() {
    this.getTransactions();
  },


}
</script>

<style scoped>

</style>