<template>
<div>
  <b-overlay>
    <div>
<!--      code if statement if the there are no tasks-->
    </div>
<!--    create a table for the tasks make sure to include an else-->
    <div>
      <table class="table table-striped  table-hover">
        <thead class="thead-dark ">
          <tr>
            <th @click="sortBy('taskType')" class="btn-primary text-info">
              <b-icon v-if="sortField=='taskType'" :icon="arrowStyle"/>Task Type</th>

            <th @click="sortBy('urgency')" class="btn-primary text-info">
              <b-icon v-if="sortField =='urgency'" :icon="arrowStyle"/>Priority</th>

            <th @click="sortBy('startDate')" class="btn-primary text-info">
              <b-icon v-if="sortField=='startDate'" :icon="arrowStyle"/>Start Date</th>

            <th @click="sortBy('dueDate')" class="btn-primary text-info">
              <b-icon v-if="sortField=='dueDate'" :icon="arrowStyle"/>Due Date</th>

<!--            <th>Completion Date</th>-->
            <th @click="sortBy('detail')" class="btn-primary text-info">
              <b-icon v-if="sortField=='detail'" :icon="arrowStyle"/>Details</th>

            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="taskObject in taskArray" :key="taskObject.id" @click="selectTask(taskObject)"
              v-bind:class="{'table-info':selectedTask.id == taskObject.id}">
            <td >{{taskObject.taskType}}</td>
            <td>{{taskObject.urgency}}</td>
            <td>{{taskObject.startDate}}</td>
            <td>{{taskObject.dueDate}}</td>
<!--           <td>{{taskObject.completedDate}}</td>-->
            <td>{{taskObject.detail}}</td>
            <td>
              <b-button size="sm" class="mb-2" variant="outline-info" @click="deleteTask">
                <b-iconstack font-scale="1.5" >
                  <b-icon stacked icon="square" variant="danger" ></b-icon>
                  <b-icon stacked icon="x" variant="danger" ></b-icon>
                </b-iconstack>
              </b-button>
            </td>

          </tr>
        </tbody>
      </table>
      <div>
        <button v-b-modal.task-form>Add Task</button>

        <b-modal id="task-form" centered size="lg" title="Enter New Task" @ok="saveTasks"
            ok-variant="success" cancel-variant="danger" header-border-variant="dark"
            header-bg-variant="info">

              <b-form-group
                  debug="true"
                  :invalid-feedback="error.taskType"
                  trim
                  id="input-group-1"
                  label="Task Type:"
                  label-for="input-type">
                    <b-form-input
                        id="input-type"
                        type="text"
                        v-model="tempTask.taskType"
                        required
                        placeholder="Enter a task name or type">
                    </b-form-input>
              </b-form-group>

          <b-form-group
              debug="true"
              :invalid-feedback="error.urgency"
              trim
              id="input-group-2"
              label="Priority:"
              label-for="input-Priority">
                  <b-form-select
                      id="input-Priority"
                      class="mb-2 mr-sm-2 mb-sm-0"
                      :options="[{text: 'Choose...', value: null}, 'High', 'Medium','Low']"
                      :value="null"
                      v-model="tempTask.urgency">
                  </b-form-select>
          </b-form-group>

          <b-form-group
              id="input-group-3"
              label="Start Date:"
              label-for="input-stDate">
                  <b-form-datepicker
                      id="input-stDate"
                      v-model="tempTask.startDate"
                      :min="min"
                      :max="max"
                      locale="en"
                      required
                      placeholder="dt.std">
                  </b-form-datepicker>
          </b-form-group>

          <b-form-group
              id="input-group-4"
              label="Due Date:"
              label-for="input-dueDate">
            <b-form-datepicker
                id="input-dueDate"
                v-model="tempTask.dueDate"
                :min="min"
                :max="max"
                locale="en"
                required
                placeholder="dt.dud">
            </b-form-datepicker>
          </b-form-group>

          <b-form-group
              debug="true"
              :invalid-feedback="error.detail"
              trim
              id="input-group-5"
              label="Task Details:"
              label-for="input-detail">
                  <b-form-input
                      id="input-detail"
                      type="text"
                      v-model="tempTask.detail"
                      placeholder="dt.dtl">
                  </b-form-input>
          </b-form-group>

        </b-modal>

      </div>


    </div>
  </b-overlay>

<!--  <pre>-->
<!--    DATA:-->
<!--    {{$data}}-->

<!--    PROPS:-->
<!--    {{$props}}-->
<!--  </pre>-->

</div>
</template>

<script>
//import GlobalMixin from "@/mixins/global-mixin";
import TaskMixin from "@/mixins/task-mixin";

export default {
  name: "TaskList",
  mixins: [TaskMixin],
  props: {
    task: {
      type: Object
    }
  },
  data: function () {
    const now = new Date()
    const today = new Date(now.getFullYear(), now.getMonth(), now.getDate())
    const minDate = new Date(today)
    minDate.setMonth(minDate.getMonth() - 2)
    minDate.setDate(15)
    const maxDate = new Date(today)
    maxDate.setMonth(maxDate.getMonth() + 2)
    maxDate.setDate(15)


    return {
      taskArray: [],
      sortField: 'id',
      sortDirection: 'asc',
      arrowStyle: 'arrow-up',
      selectedTask: {},
      value: '',
      min: minDate,
      max: maxDate,
      tempTask: {},
      error: {},

    }
  },
  methods: {
    getTask: function () {
      this.taskArray = [];

      this.callAPI('get',
          {sort: this.sortField, order: this.sortDirection})
          .then(resp => {
            console.log(resp);
            //in this case the data will be an array of task objects
            this.taskArray = resp.data;
          })
          .catch(err => {
            // console.log(err);
            if (err.response.status == 404) {
              // now becomes require to send an empty object
              this.taskArray = [];
            }
          })
          .finally(() => {
            //send the array to parent
            this.$emit('refreshed', this.taskArray);

          });
    },
    sortBy: function (field) {
      if (this.sortField === field) {
        // need to check the order it is already sorting
        this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';

      } else {
        this.sortDirection = 'asc'
      }
      this.arrowStyle = this.sortDirection == 'asc' ? 'arrow-up' : 'arrow-down';
      this.sortField = field;
      this.getTask();
    },
    selectTask: function (task) {

      this.selectedTask = !task || !task.id || this.selectedTask.id == task.id ? {} : task;
      //tell the parent component that a new student has been select
      this.$emit('selected', this.selectedTask);

    },
    deleteTask: function () {
      this.error = {};
      // this.setBusy(true);

      this.callAPI('delete', this.tempTask)
          .then(resp => {
            console.log(resp);
            if (resp.status == 204) //success deletion
            {
              this.tempTask = {};
              this.$emit('deleted', this.task);
            } else if (resp.status == 205) //failed due to restriction
            {
              this.tempTask = resp.data;
              this.$emit('reset', resp.data);
            }
          })
          .catch(err => {
            console.log(err);
          })
    },
    saveTasks: function () {
      this.error = {}; // clear the error msg

      this.callAPI('post', this.tempTask)
          .then(resp => {
            console.log(resp);
            this.tempTask = {};
            this.$emit('added', resp.data);

          })
          .catch(err => {
            console.log(err);
            this.error = err && err.response ? err.response.data : {};

          })
    }
  },
  watch: {
    task: {
      //watch for changes in the props section
      deep: true,
      handler: function (newtask, oldtask) {
        if (!newtask || !newtask.id) //if newtask is null or does not have id
        {
          this.tempTask = {}; //reset tempStudent -- it gonna blank out all the text boxes
        } else if (!oldtask || !newtask.id || oldtask.id != newtask.id) {

          //copy just the values from task to temptask
          this.tempTask = Object.assign({}, this.task);

        }
        this.error = {};
        }
      }
    },
    computed: {
      state () {
        return {
          //set bootstrap state
          //false -- red border
          //null -- nothing happens
          //true -- green border and check mark --> we are not going to use this
          tt: this.error.taskType ? false : null,
          ug: this.error.urgency ? false : null,
          dtl: this.error.detail ? false : null,
          std: this.error.startDate ? false : null,
          dud: this.error.dueDate ? false : null
        }
      }
    },
    mounted() {
      this.getTask();
    }
}
</script>

<style scoped>

</style>