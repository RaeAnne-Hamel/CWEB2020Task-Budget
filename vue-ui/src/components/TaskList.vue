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
          <tr v-for="taskObject in taskArray" :key="taskObject.id" @click="selectTask(taskObject)"
              v-bind:class="{'table-info':selectedTask.id == taskObject.id}">
            <td>{{taskObject.taskType}}</td>
            <td>{{taskObject.urgency}}</td>
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
//import GlobalMixin from "@/mixins/global-mixin";
import TaskMixin from "@/mixins/task-mixin";

export default {
name: "TaskList",
  mixins: [TaskMixin],
  data:function (){
    return {
      taskArray: [],
      sortField: 'id',
      selectedTask: {}
    };
  },
  methods: {
    getTask: function () {
      //tell the parent that the component is loading from api
      this.setBusy(true);

      this.taskArray = [];

      this.axios.get(this.TASK_API_URL,
          {params: {sort: this.sortField, order: this.sortDirection}})
          .then(resp => {
            console.log(resp);
            //in this case the data will be an array of task objects
            this.taskArray = resp.data;
          })
          .catch(err => {
            //console.log(err);
            if (err.response.status == 404) {
              // now becomes require to send an empty object
              this.taskArray = [];
            }
          }).finally(() => {
        //send the array to parent
        this.$emit('refreshed', this.taskArray);

        this.setBusy(false);

      });
    },
    sortBy:function(field){
      if(this.sortField === field){
        // need to check the order it is already sorting
        this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';

      }else{
        this.sortDirection ='asc'
      }
      this.arrowStyle = this.sortDirection == 'asc' ? 'arrow-up' : 'arrow-down';
      this.sortField = field;
      this.getTask();
    },
    selectTask: function (task){

      this.selectedTask = !task|| !task.id || this.selectedTask.id == task.id ? {} : task;
      //tell the parent component that a new student has been select
      this.$emit('selected', this.selectedTask);

    }
  },
  mounted() {
    this.getTask();
  }
}
</script>

<style scoped>

</style>