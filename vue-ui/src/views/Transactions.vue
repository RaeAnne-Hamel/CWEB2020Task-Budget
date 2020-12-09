<template>
<div>
  <h1>Transactions</h1>
  <transform ref="transactionForm" @added="addHandler" @updated="updateHandler" @busy="setBusy"/>
  <translist ref="transactionList" @refreshed="handleRefreshed" @busy="setBusy"/>
</div>
</template>

<script>

import TransactionList from "@/components/TransactionList";
import TransactionMixin from "@/mixins/transaction-maxin";
import TransactionForm from "@/components/TransactionForm";

export default {
  name: "Transactions",
  mixins:[TransactionMixin],
  components:{
    translist: TransactionList,
    transform: TransactionForm
  },
  data: function () {
    return {
      selectedStudent: {},
      transactionArray:[]
    }
  },
  methods:{
    handleRefreshed(transactionsFromApi){
      this.transactionArray = transactionsFromApi;
    },
    addHandler(transactionFromAPI){
      this.transactionArray.unshift(transactionFromAPI);
      this.$refs.transactionList.select(transactionFromAPI,true);
    },
    updateHandler(transactionFromAPI){
      Object.assign(this.selectedStudent, transactionFromAPI);
    }
  }
}
</script>

<style scoped>

</style>