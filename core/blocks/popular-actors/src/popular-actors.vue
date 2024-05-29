<template>
  <div v-if="acfSortBar" class="grid md:grid-cols-3 sm:grid-cols-2 gap-5 mb-10">
    <div class="relative h-10 w-full min-w-[200px]">
      <input v-model="filterName" class="peer h-full w-full rounded-[7px] border border-blue-gray-200 px-3 py-2.5 font-sans text-sm font-normal text-blue-gray-700 outline outline-0 transition-all placeholder-shown:border placeholder-shown:border-blue-gray-200 placeholder-shown:border-t-blue-gray-200 focus:border-2 focus:border-pink-500 focus:border-t-transparent focus:outline-0 disabled:border-0 disabled:bg-blue-gray-50"
             placeholder=" "
      />
      <label class="before:content[' '] after:content[' '] pointer-events-none absolute left-0 -top-1.5 flex h-full w-full select-none text-[11px] font-normal leading-tight text-blue-gray-400 transition-all before:pointer-events-none before:mt-[6.5px] before:mr-1 before:box-border before:block before:h-1.5 before:w-2.5 before:rounded-tl-md before:border-t before:border-l before:border-blue-gray-200 before:transition-all after:pointer-events-none after:mt-[6.5px] after:ml-1 after:box-border after:block after:h-1.5 after:w-2.5 after:flex-grow after:rounded-tr-md after:border-t after:border-r after:border-blue-gray-200 after:transition-all peer-placeholder-shown:text-sm peer-placeholder-shown:leading-[3.75] peer-placeholder-shown:text-blue-gray-500 peer-placeholder-shown:before:border-transparent peer-placeholder-shown:after:border-transparent peer-focus:text-[11px] peer-focus:leading-tight peer-focus:text-pink-500 peer-focus:before:border-t-2 peer-focus:before:border-l-2 peer-focus:before:border-pink-500 peer-focus:after:border-t-2 peer-focus:after:border-r-2 peer-focus:after:border-pink-500 peer-disabled:text-transparent peer-disabled:before:border-transparent peer-disabled:after:border-transparent peer-disabled:peer-placeholder-shown:text-blue-gray-500">
        Filter actor by Name
      </label>
    </div>
    <div class="relative h-10 w-full min-w-[200px]">
      <input v-model="filterMovie" class="peer h-full w-full rounded-[7px] border border-blue-gray-200 px-3 py-2.5 font-sans text-sm font-normal text-blue-gray-700 outline outline-0 transition-all placeholder-shown:border placeholder-shown:border-blue-gray-200 placeholder-shown:border-t-blue-gray-200 focus:border-2 focus:border-pink-500 focus:border-t-transparent focus:outline-0 disabled:border-0 disabled:bg-blue-gray-50"
             placeholder=" "
      />
      <label class="before:content[' '] after:content[' '] pointer-events-none absolute left-0 -top-1.5 flex h-full w-full select-none text-[11px] font-normal leading-tight text-blue-gray-400 transition-all before:pointer-events-none before:mt-[6.5px] before:mr-1 before:box-border before:block before:h-1.5 before:w-2.5 before:rounded-tl-md before:border-t before:border-l before:border-blue-gray-200 before:transition-all after:pointer-events-none after:mt-[6.5px] after:ml-1 after:box-border after:block after:h-1.5 after:w-2.5 after:flex-grow after:rounded-tr-md after:border-t after:border-r after:border-blue-gray-200 after:transition-all peer-placeholder-shown:text-sm peer-placeholder-shown:leading-[3.75] peer-placeholder-shown:text-blue-gray-500 peer-placeholder-shown:before:border-transparent peer-placeholder-shown:after:border-transparent peer-focus:text-[11px] peer-focus:leading-tight peer-focus:text-pink-500 peer-focus:before:border-t-2 peer-focus:before:border-l-2 peer-focus:before:border-pink-500 peer-focus:after:border-t-2 peer-focus:after:border-r-2 peer-focus:after:border-pink-500 peer-disabled:text-transparent peer-disabled:before:border-transparent peer-disabled:after:border-transparent peer-disabled:peer-placeholder-shown:text-blue-gray-500">
        Filter actor by Movie
      </label>
    </div>
  </div>
  <div v-if="filteredTitles.length">
    <h3>Titles matching "{{ filterMovie }}":</h3>
    <ul>
      <li v-for="title in filteredTitles" :key="title">{{ title }}</li>
    </ul>
  </div>
  <div class="grid lg:grid-cols-4 md:grid-cols-3 grid-cols-1 gap-5">
    <div class="w-full" v-for="actor in filteredItems" :key="actor.id">
      <div class=" rounded-lg overflow-hidden mb-10 border border-black border-solid">
        <img :src="'https://image.tmdb.org/t/p/original' + actor.profile_path" alt="image" class="w-full"/>
        <div class="p-5 justify-between flex text-center flex-col h-36 bg-white">
          <h3>
            <a  href="javascript:void(0)" class="font-semibold text-dark text-xl sm:text-[22px] md:text-xl lg:text-[22px] xl:text-xl 2xl:text-[22px] mb-4 block hover:text-primary">
              {{ actor.name }}
            </a>
          </h3>
          <a href="javascript:void(0)" class="inline-block py-2 px-7 border border-gray-300 border-solid rounded-full text-base text-body-color font-medium">
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
const acfAmount= ref(null);
const acfSortBar = ref(null);
const acfSortByName = ref(null);
const filterName = ref('');
const filterMovie = ref('');
const filteredTitles = ref([]);

const fetchPopularActors = async () => {
  try {
    const response = await fetch(siteData.apiUrl+'popular-actors');
    const data = await response.json();
    popularActor.value = data.results;
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
    const matchesMovie = item.known_for.some(movie => {
      return cleanTitle(movie).toLowerCase().includes(filterMovie.value.toLowerCase())
    });

    return matchesTitle && matchesMovie;
  });
});

watch(filterMovie, (newFilterMovie) => {
  filteredTitles.value = [];
  if (newFilterMovie) {
    limitedActors.value.forEach(actor => {
      actor.known_for.forEach(movie => {
        if (cleanTitle(movie).toLowerCase().includes(newFilterMovie.toLowerCase())) {
          if (!filteredTitles.value.includes(cleanTitle(movie))) {
            filteredTitles.value.push(cleanTitle(movie));
          }
        }
      });
    });
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
</script>