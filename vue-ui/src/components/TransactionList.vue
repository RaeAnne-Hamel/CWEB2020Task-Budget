<template>
<div>
  <!-- Form for filtering by the bank ID -->
  <b-form-group class="w-25">
    <b-input-group>
      <b-form-input placeholder="Filter Bank By ID" v-model="currentBank" />
    </b-input-group>
  </b-form-group>

  <!-- Table displaying either all transactions, or only transactions of the BankID specified above -->
  <table class="table table-hover">
    <!-- Header of the table, displaying all column names -->
    <thead>
      <tr>
        <th>BankID</th>
        <th>Amount</th>
        <th>Transaction Type</th>
        <th>Paid</th>
        <th>Options</th>
     </tr>
    </thead>

    <!-- The body of the table. Displaying all rows of data depending on how much information is in the database,
     as well as the bank ID filter -->
    <tbody>

      <!-- Shows the rows based on the filtered rows and clicking on a row will highlight it and give options
       for deleting or marking them as paid. The rows change to red if the amount is negative, or green
       if the amount is positive-->
      <tr  v-for="transaction in filteredRows"  :key="transaction.id"
          :class="getClass(`${transaction.amount}`)" @click="select(transaction)">

        <td>{{transaction.bankID}}</td>
        <td>{{transaction.amount}}</td>
        <td>{{transaction.typeTransaction}}</td>
        <td>{{transaction.checkPaid}}</td>
        <td>

          <!-- The buttons for deleting and marking as paid which will only appear once a row is selected -->
          <!-- The delete button removes the selected transaction from the database. It removes it from the row aswell,
           but the page must be refreshed before the changes take place-->
          <b-iconstack font-scale="1.5" v-if="transaction.id === selectedTransaction.id"
                        variant="danger" @click="deleteTransaction">
            <b-icon stacked icon="square" variant="danger"></b-icon>
            <b-icon stacked icon="x" variant="danger"></b-icon>
          </b-iconstack>

          <!-- The mark as paid button simply changes the selected transactions paid status to "yes" -->
          <b-iconstack font-scale="1.5" v-if="transaction.id === selectedTransaction.id"
                        @click="setPaid">
            <b-icon stacked icon="square"></b-icon>
            <b-icon stacked icon="check2"></b-icon>
          </b-iconstack>
        </td>

      </tr>

    </tbody>
  </table>

  <!-- This displays the total balance of the entire table by adding up all of the amounts -->
  <h2>Current Balance: ${{totalAmount()}}</h2>

</div>
</template>

<script>
//Importing the Transaction mixin
import TransactionMixin from "@/mixins/transaction-maxin";

export default {
  name: "TransactionList",
  mixins:[TransactionMixin],
  props:{
    transaction:{
      type:Object,
      validator: t => t instanceof Object
    }
  },
  data() {
    return {
      tempTransaction: {},
      sortField:'id',
      sortDirection:'asc',
      selectedTransaction: {},
      transactionArray: [],
      currentBank: "",
      class: '',
      paidClass: ''
    };
  },
  methods:{

    //Gets all of the transactions from the database and adds them to the transactionArray. This is called at the
    //Page load so that all transactions are loaded into the array
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
    },

    //This checks whether the amount is positive or negative and applies a color accordingly
    getClass(amount){
      if(amount >= 0)
      {
        this.class = "positive";
        return this.class;
      }
      else
      {
        this.class = "negative";
        return this.class;
      }
    },

    //Method that sets the status of the currently selected transaction to be "Yes"
    setPaid(){
      this.setBusy(true);
      this.error = {};
      this.selectedTransaction.checkPaid = "Yes";
      this.callAPI('put', this.selectedTransaction)
          .finally(()=>{
            this.setBusy(false);
          });

    },
    //Method for selecting a transaction
    select(trans, force){
      if(!force && this.isDisabled)
      {
        return false;
      }
      this.selectedTransaction = !trans || !trans.id || this.selectedTransaction.id == trans.id ? {} : trans;
      this.tempTransaction = this.selectedTransaction;
      this.$emit('selected', this.selectedTransaction);

    },

    //Method deletes a transaction from the database. It also immediately deletes the transaction from the table.

    deleteTransaction() {
      this.setBusy(true);
      this.error = {};

      this.callAPI('delete', this.selectedTransaction)
          .then(resp=> {
            if (resp.status == 204)
            {
              this.selectedTransaction = {};
              this.tempTransaction = {};
              this.$emit('deleted', this.selectedTransaction);

            }
            else if (resp.status == 205)
            {
              this.tempTransaction = resp.data;
              this.$emit('reset', this.selectedTransaction);
            }
          })
          .catch(err=>{
            this.error = err && err.response ? err.response.data : {};
          })
          .finally(()=>{
            this.getTransactions();
            this.setBusy(false);
          });
    },

    //Method that calculates the total of all of the amounts for transactions
    totalAmount(){
      let i;
      let amount = 0;
      let conversion;
      for (i=0; i < this.transactionArray.length; i++)
      {
        conversion = parseInt(this.transactionArray[i]['amount']);
        amount = amount + conversion;
      }

      return amount;
    }
  },
  mounted() {
    //calls getTransactions when page is loaded
    this.getTransactions();
  },
  computed:{
    //Filters the transactions out based on which Bank ID is selected
    filteredRows() {
      return this.transactionArray.filter(trans => {
        const BankIDs = trans.bankID.toString();
        const searchTerm = this.currentBank;

        return BankIDs.includes(searchTerm);
      });
    }
  },
  watch:{
    transaction:{
      deep:true,
      handler(newTrans,oldTrans){
        if(!newTrans || !newTrans.id)
        {
          this.tempTransaction = {};
        }
        else if(!oldTrans || newTrans.id != oldTrans.id) {
          this.tempTransaction = Object.assign({}, this.transaction);
        }
        this.error = {};
      }
    }
  }



}
</script>

<style scoped>
.positive{
  background-color: green;

}
.negative{
  background-color: red;
  color: white;
}
</style>