<template>
<div>
  <h1>Transactions</h1>
  <transform ref="transactionForm" @added="addHandler" @updated="updateHandler" @busy="setBusy"/>
  <translist ref="transactionList" @refreshed="handleRefreshed" @busy="setBusy" @deleted="deleteHandler" @reset="resetHandler"
          @selected="selectHandler"/>
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
      selectedTransaction: {},
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
      Object.assign(this.selectedTransaction, transactionFromAPI);
    },
    deleteHandler(transactionFromAPI){
      let index = this.transactionArray.findIndex(t=> t.id === transactionFromAPI.id);
      if(index > -1) {
        this.transactionArray.splice(index, 1);
        this.selectedTransaction = {};
      }
    },
    resetHandler(transactionFromAPI){
      let index = this.transactionArray.findIndex(t=> t.id === transactionFromAPI.id);

      if(index > -1) {
        Object.assign(this.transactionArray[index], transactionFromAPI);
      }
      else
      {
        this.addHandler(transactionFromAPI);
      }
    },
    selectHandler(transactionFromAPI){
      this.selectedTransaction = transactionFromAPI;
    }

  }
}
</script>

<style scoped>

</style>