<template>
<div>
  <b-overlay>
    <div>
<!--      code if statement if the there are no tasks-->
    </div>
<!--    create a table for the tasks make sure to include an else-->
    <div>
      <table class="table table-striped table-bordered table-hover">
          <caption>Task List</caption>
        <thead class="thead-dark">
          <tr>
            <th>Task Type</th>
            <th>Priority</th>
            <th>Start Date</th>
            <th>Due Date</th>
            <th>Completion Date</th>
            <th>Details</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="taskObject in taskArray" :key="taskObject.id" v-bind:class="{'table-info':selectedTask.id == taskObject.id}">
            <td>{{taskObject.type}}</td>
            <td>{{taskObject.Priority}}</td>
            <td>{{taskObject.startDate}}</td>
            <td>{{taskObject.dueDate}}</td>
            <td>{{taskObject.completedDate}}</td>
            <td>{{taskObject.detail}}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </b-overlay>
</div>
</template>

<script>
import GlobalMixin from "@/mixins/global-mixin";
export default {
name: "TaskList",
  mixins: [GlobalMixin],
  data:function (){
    return {
      taskArray: [],
    };
  }
}
</script>

<style scoped>

</style>