/**
 * Mixins are a flexible way to distribute reusable functionalities for Vue components.
 * https://vuejs.org/v2/guide/mixins.html
 * The following mixin will only be imported to ALL components in this project (even the 3rd party components)
 * all the following methods and variables will be available to ALL components as this mixin will be imported in main.js
 */

const API_URL = 'http://localhost:8002/';
const WEATHER_API_URL = 'https://cors-anywhere.herokuapp.com/https://samples.openweathermap.org/data/2.5/group?id=6141256,6119109,6113335,6078112,6160603,6185607&units=metric&appid=05393dfda137d70aa1c5d90b9175fc4e';

const GlobalMixin = ({
    data(){
        return {
            API_URL,
			WEATHER_API_URL
        }
    }
});

export default GlobalMixin
