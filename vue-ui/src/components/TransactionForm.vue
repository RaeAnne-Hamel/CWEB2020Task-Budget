<template>
<div>
<table>
  <tbody>
  <tr>

    <td>
  <!--Bank ID-->
  <b-form-group  :state="state.bi" class="mb-1">
    <b-input-group>
      <b-input-group-prepend is-text v-b-tooltip.hover.right="display.bi">
        <b-icon-hash />
      </b-input-group-prepend>

      <b-form-input :placeholder="display.bi" :state="state.bi" trim v-model="tempTransaction.bankID"/>
    </b-input-group>
  </b-form-group>
    </td>

    <td>
  <!--Amount-->
  <b-form-group  :state="state.am" class="mb-1">
    <b-input-group>
      <b-input-group-prepend is-text v-b-tooltip.hover.right="display.am">
        <b-icon-wallet2 />
      </b-input-group-prepend>

      <b-form-input :placeholder="display.am" :state="state.am" trim v-model="tempTransaction.amount"/>
    </b-input-group>
  </b-form-group>
    </td>

    <td>
  <!--Transaction Type-->
  <b-form-group  :state="state.tt" class="mb-1">
    <b-input-group>
      <b-input-group-prepend is-text v-b-tooltip.hover.right="display.tt">
        <b-icon-arrow-left-right />
      </b-input-group-prepend>

      <b-form-input :placeholder="display.tt" :state="state.tt" trim v-model="tempTransaction.typeTransaction"/>
    </b-input-group>
  </b-form-group>
    </td>

    <td>
  <!--Paid-->
  <b-form-group  :state="state.pa" class="mb-1" >
    <b-input-group>

      <b-input-group-prepend is-text v-b-tooltip.hover.right="display.pa">
        <b-icon-check2 />
      </b-input-group-prepend>

      <b-form-select :placeholder="display.pa" :options="booleans" :state="state.pa" trim v-model="tempTransaction.checkPaid"/>
    </b-input-group>
  </b-form-group>
    </td>

    <!-- Buttons -->
    <td>
      <b-button-group class="mb-1np">
        <b-button @click="saveTransaction">Add Transaction</b-button>
      </b-button-group>
    </td>

  </tr>
  </tbody>
</table>
</div>
</template>

<script>

import TransactionMixin from "@/mixins/transaction-maxin";

export default {
  name: "TransactionForm",
  mixins:[TransactionMixin],
  props:{
    transaction:{
      type:Object,
      validator: t => t instanceof Object
    }
  },
  data(){
    return {
      tempTransaction: {},
      error:{},
      display:{
        bi:'BankID',
        am:'Amount',
        tt:'Transaction Type',
        pa:'Paid'
      },
      booleans: [
        { value: 'Yes', text: 'Yes'},
        { value: 'No', text: 'No'}
      ]

    };
  },
  watch:{
    transaction:{
      deep:true,
      handler(newTrans, oldTrans){

        if(!newTrans || !newTrans.id){
          this.tempTransaction = {};
        }else if( !oldTrans || newTrans.id != oldTrans.id) {
          this.tempTransaction = Object.assign({}, this.transaction);
        }
        this.error = {};
      }
    }
  },
  methods:{
    saveTransaction() {
      this.setBusy(true);

      this.error={};

      this.callAPI(this.isNew? 'post' : 'put', this.tempTransaction)
          .then(resp=>{
            if(resp.status == 200)
            {
              this.tempTransaction = resp.data;
              this.$emit('updated', this.tempTransaction);
            }
            else
            {
              this.tempTransaction = {};
              this.$emit('added', resp.data);
            }
          })
          .catch(err=>{
            this.error = err && err.response ? err.response.data: {};
          })
          .finally(()=>{
            this.setBusy(false);
          });

    }

  },
  computed:{
    state(){
      return {
        bi: this.error.bankID ? false : null,
        am: this.error.amount ? false : null,
        tt: this.error.typeTransaction ? false : null,
        pa: this.error.checkPaid ? false : null
      };
    },

    isNew(){
      return !this.transaction || !this.transaction.id;
    }

  }
}
</script>

<style scoped>

</style>