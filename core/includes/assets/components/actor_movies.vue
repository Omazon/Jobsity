<template>
  <h3 v-if="displayedCredits.length > 0" class="text-3xl my-10">{{ title }}</h3>
  <div class="grid lg:grid-cols-4 md:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-8">
    <div class="flex flex-col justify-center items-center hover:shadow-xl rounded-lg pb-8 transition-all" v-for="(crew, index) in displayedCredits" :key="index">
      <img :src="crew.poster_path ? 'https://image.tmdb.org/t/p/original'+crew.poster_path : 'https://placehold.co/213x320'" alt="">
      <span class="text-2xl mt-5 text-center">{{crew.original_title}}</span>
      <span class="text-lg">{{crew.character}}</span>
      <span class="text-lg mb-5">{{formattedDate(crew.release_date)}}</span>
      <a :href="generateSlug(crew.original_title, crew.id)" class="inline-block py-2 px-7 border border-gray-300 border-solid rounded-full text-base text-body-color font-medium hover:shadow-xl transition">
        View Details
      </a>
    </div>
  </div>
  <button @click="loadMore" v-if="hasMoreItems" class="my-5 flex justify-center mx-auto py-2 px-7 border border-gray-300 border-solid rounded-full text-base text-body-color font-medium transition hover:shadow-xl">
    Load more
  </button>
</template>
<script setup>

import {computed, ref, watch} from "vue";
import { generateSlug, formattedDate } from "../utils/utils";

const props = defineProps(['actor_id','title']);

const creditsToShow = 8;
const currentPage = ref(1);
const displayedCredits = ref([]);
const movies = ref([]);

const loadMore = () => {
  currentPage.value++;
  displayedCredits.value = movies.value.slice(0, currentPage.value * creditsToShow);
};

const hasMoreItems = computed(() => {
  return displayedCredits.value.length < movies.value.length;
});

const sortedMovies = computed(() => {
    return sortByDate(movies.value);
});
const sortByDate = (movies) => {
  return movies.slice().sort((a, b) => new Date(b.release_date) - new Date(a.release_date));
};

const fetchActorMovies = async () => {
  try {
    const response = await fetch(siteData.apiUrl+'person/' + props.actor_id+'/movie-credits');
    let data = await response.json();
    movies.value = sortByDate(data.cast);
    displayedCredits.value = movies.value.slice(0, creditsToShow);
  } catch (error) {
    console.error('Error fetching upcoming movies:', error);
  }
};

watch(() => props.actor_id,
    (newActorId) => {
      if(newActorId) {
        fetchActorMovies();
      }
    },
    { immediate: true }
);

</script>