import { createApp } from 'vue'

import upcomingMovies from '../../../blocks/upcoming-movies/src/upcoming-movies.vue'
import popularActors from '../../../blocks/popular-actors/src/popular-actors.vue'
import singleMovie from '../src/single-movie.vue'
import singleActor from '../src/single-actor.vue'

document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById('upcoming-movies')) {
        createApp(upcomingMovies).mount('#upcoming-movies');
    }
    if (document.getElementById('popular-actors')) {
        createApp(popularActors).mount('#popular-actors');
    }
    if (document.getElementById('single-movie')) {
        createApp(singleMovie).mount('#single-movie');
    }
    if (document.getElementById('single-actor')) {
        createApp(singleActor).mount('#single-actor');
    }
});