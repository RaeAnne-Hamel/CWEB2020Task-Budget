<template>
<div>
  <translist />
</div>
</template>

<script>

import TransactionList from "@/components/TransactionList";
import GlobalMixin from "@/mixins/global-mixin";

export default {
  name: "Transactions",
  mixins:[GlobalMixin],
  components:{
    translist: TransactionList
  }
}
</script>

<style scoped>

</style>