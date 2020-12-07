<template>
  <div id="app">
    <b-nav pills align="center" class="mb-4">
      <b-nav-item to="/" exact-active-class="active">Home</b-nav-item>
      <b-nav-item to="/about" exact-active-class="active">About</b-nav-item>
      <b-nav-item to="/transactions" exact-active-class="active">Transactions</b-nav-item>
    </b-nav>
    <router-view class="container-xl"/>
  </div>
</template>

<style>

</style>
