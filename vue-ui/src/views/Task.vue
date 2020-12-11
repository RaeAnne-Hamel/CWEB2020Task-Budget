<template>
<div>

  <div class="task">
    <h2>
      List of Tasks
    </h2>

    <div class="row">
      <tasklist debug
        @selected="handleSelected" @deleted="handleDeleted"
         @refreshed="handleRefreshed" @added="handleAdded"
        ref="taskList"/>

    </div>

  </div>
</div>
</template>

<script>
import TaskMixin from "@/mixins/task-mixin";
import TaskList from "@/components/TaskList";

export default {
  name: "Task",
  mixins:[TaskMixin],
  components: {

  tasklist : TaskList

  },
  data: function(){
    return{
      selectedTask:{startDate:'Selected', type:'Task'},
      taskArray:[]
    };
  },
  methods:{
    handleSelected: function (receivedTask){
      this.selectedTask = receivedTask;
    },
    // handleUpdated:function (updatedTask){
    //
    //   Object.assign(this.selectedTask, updatedTask);
    // },
    handleRefreshed:function (taskFromAPI)
    {
      this.taskArray = taskFromAPI;
    },
    handleAdded:function (newTask)
    {
      this.taskArray.unshift(newTask);
      this.$refs.tasklist.selectedTask(newTask);
    },
    handleDeleted:function(deletedTask)
    {

      let index = this.taskArray.findIndex(t => t.id == deletedTask.id);

      this.taskArray.splice(index, 1);

      this.$refs.taskList.selectedTask({});
    }
  }
}
</script>

<style scoped>

</style>
