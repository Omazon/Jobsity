<template>
  <div v-if="acfSortBar" class="grid md:grid-cols-2 gap-5 mb-10">
    <div class="relative h-10 w-full min-w-[200px] flex gap-3">
      <input v-model="filterName" class="peer h-full w-full rounded-[7px] border border-blue-gray-200 px-3 py-2.5 font-sans text-sm font-normal text-blue-gray-700 outline outline-0 transition-all placeholder-shown:border placeholder-shown:border-blue-gray-200 placeholder-shown:border-t-blue-gray-200 focus:border-2 focus:border-pink-500 focus:border-t-transparent focus:outline-0 disabled:border-0 disabled:bg-blue-gray-50"
             placeholder="Filter actor by Name"
      />
      <button @click="fetchQueryActor"
          class="w-40 middle none center rounded-lg bg-red-500 py-2 px-6 font-sans text-xs font-bold text-white shadow-md transition-all hover:shadow-lg focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none">
        More actors
      </button>
    </div>
    <div class="relative h-10 w-full min-w-[200px] flex gap-3">
      <input v-model="filterMovie" class="peer h-full w-full rounded-[7px] border border-blue-gray-200 px-3 py-2.5 font-sans text-sm font-normal text-blue-gray-700 outline outline-0 transition-all placeholder-shown:border placeholder-shown:border-blue-gray-200 placeholder-shown:border-t-blue-gray-200 focus:border-2 focus:border-pink-500 focus:border-t-transparent focus:outline-0 disabled:border-0 disabled:bg-blue-gray-50"
             placeholder="Filter actor by Movie"
      />
      <button @click="fetchQueryMovie"
          class="w-40 middle none center rounded-lg bg-red-500 py-2 px-6 font-sans text-xs font-bold text-white shadow-md transition-all hover:shadow-lg focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none">
        More movies
      </button>
    </div>
  </div>
  <div v-if="newMovies.length" class="mb-5">
    <span class="text-white"><strong>Choose a movie</strong></span>
    <div class="flex flex-wrap gap-2 mt-5">
      <button v-for="movie in newMovies" :key="movie.id" @click="fetchMovie(movie.id)"
              class="middle none center rounded-lg bg-orange-500 hover:bg-orange-700 transition py-3 px-6 font-sans text-xs text-white focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
      >
        {{movie.original_title}}
      </button>
    </div>

  </div>
  <div v-if="filteredTitles.length" class="text-white">
    <h3 class="text-white">Titles matching "{{ filterMovie }}":</h3>
    <ul>
      <li v-for="title in filteredTitles" :key="title">{{ title }}</li>
    </ul>
  </div>
  <div class="grid lg:grid-cols-4 md:grid-cols-3 grid-cols-1 gap-5">
    <div class="w-full" v-for="actor in filteredItems" :key="actor.id">
      <div class=" rounded-lg overflow-hidden mb-10 border border-black border-solid">
        <img :src="actor.profile_path ?'https://image.tmdb.org/t/p/original' + actor.profile_path: 'https://placehold.co/213x320?text=No+Image'" alt="image" class="w-full"/>
        <div class="p-5 justify-between flex text-center flex-col h-36 bg-white">
          <h3>
            <a  href="javascript:void(0)" class="font-semibold text-dark text-xl sm:text-[22px] md:text-xl lg:text-[22px] xl:text-xl 2xl:text-[22px] mb-4 block hover:text-primary">
              {{ actor.name }}
            </a>
          </h3>
          <a :href="generateSlug(actor.name, actor.id)" class="inline-block py-2 px-7 border border-gray-300 border-solid rounded-full text-base text-body-color font-medium">
            View Details
          </a>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import {computed, onMounted, ref, watch} from 'vue';

const popularActor = ref([]);
const popularActorOriginal = ref([]);
const acfAmount= ref(null);
const acfSortBar = ref(null);
const acfSortByName = ref(null);
const filterName = ref('');
const filterMovie = ref('');
const filteredTitles = ref([]);

const newMovies = ref([]);

const fetchPopularActors = async () => {
  try {
    const response = await fetch(siteData.apiUrl+'popular-actors');
    const data = await response.json();
    popularActor.value = data.results;
    popularActorOriginal.value = data.results;
  } catch (error) {
    console.error('Error fetching upcoming movies:', error);
  }
};

const fetchQueryActor = async () => {
  try {
    const response = await fetch(siteData.apiUrl+'person?query=' + filterName.value);
    const data = await response.json();
    popularActor.value = data.results;
  } catch (error) {
    console.error('Error fetching upcoming movies:', error);
  }
};

const fetchQueryMovie = async () => {
  filteredTitles.value = [];
  try {
    const response = await fetch(siteData.apiUrl+'movie?query=' + filterMovie.value);
    const data = await response.json();
    newMovies.value = data.results;
  } catch (error) {
    console.error('Error fetching upcoming movies:', error);
  }
};

const fetchMovie = async (movieId) => {
  filteredTitles.value = [];
  try {
    const response = await fetch(siteData.apiUrl+'movie/' + movieId+'/credits');
    let data = await response.json();
    popularActor.value = data.cast;
  } catch (error) {
    console.error('Error fetching upcoming movies:', error);
  }
};

const limitedActors = computed(()=>{
  return sortedActors.value.slice(0, acfAmount.value);
});

const sortedActors = computed(() => {
  if (acfSortByName.value) {
    return sortByName(popularActor.value);
  } else {
    return popularActor.value;
  }
});

onMounted(()=>{
  const element = document.getElementById('popular-actors');

  acfAmount.value = JSON.parse(element.getAttribute('data-amount'));
  acfSortBar.value = JSON.parse(element.getAttribute('data-sort-bar'));
  acfSortByName.value = JSON.parse(element.getAttribute('data-sort-by-name'));

  fetchPopularActors();
});

const sortByName = (actors) => {
  return actors.slice().sort((a, b) => a.name.localeCompare(b.name));
};

const filteredItems = computed(() => {
  return limitedActors.value.filter(item => {
    const matchesTitle = item.name.toLowerCase().includes(filterName.value.toLowerCase());
    let matchesMovie;
    if(item.known_for){
      matchesMovie = item.known_for.some(movie => {
        return cleanTitle(movie).toLowerCase().includes(filterMovie.value.toLowerCase())
      });
    } else {
      return matchesMovie = item;
    }


    return matchesTitle && matchesMovie;
  });
});

watch(filterMovie, (newFilterMovie) => {
  filteredTitles.value = [];
  if (newFilterMovie) {
    limitedActors.value.forEach(actor => {
      if(actor.known_for){
        actor.known_for.forEach(movie => {
          if (cleanTitle(movie).toLowerCase().includes(newFilterMovie.toLowerCase())) {
            if (!filteredTitles.value.includes(cleanTitle(movie))) {
              filteredTitles.value.push(cleanTitle(movie));
            }
          }
        });
      }
    });
  }
});
watch(filterName, (newFilterName) => {
  if(newFilterName.length === 0){
    popularActor.value = popularActorOriginal.value;
  }
});

watch(filterMovie, (newFilterMovie) => {
  if(newFilterMovie.length === 0){
    popularActor.value = popularActorOriginal.value;
    newMovies.value = [];
  }
});

function cleanTitle(movie){
  let title = null;
  if(movie.title){
    title = movie.title
  }
  else if(movie.original_title){
    title = movie.original_title;
  }
  else if(movie.original_name){
    title = movie.original_name;
  }
  return title;
}
function generateSlug(actor, id) {
  let normalizedStr = actor.normalize('NFD').replace(/[\u0300-\u036f]/g, '');


  let slug = normalizedStr
      .toLowerCase()
      .replace(/\s+/g, '-')
      .replace(/[^\w\-]+/g, '')
      .replace(/\-\-+/g, '-')
      .replace(/^-+/, '')
      .replace(/-+$/, '');

  return siteData.siteBase+'actor/'+id+'-'+slug;
}
</script>