import { createApp } from 'vue'

import upcomingMovies from '../../../blocks/upcoming-movies/src/upcoming-movies.vue'
import popularActors from '../../../blocks/popular-actors/src/popular-actors.vue'

document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById('upcoming-movies')) {
        createApp(upcomingMovies).mount('#upcoming-movies');
    }
    if (document.getElementById('popular-actors')) {
        createApp(popularActors).mount('#popular-actors');
    }
});