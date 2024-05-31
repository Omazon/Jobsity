<template>
  <div v-if="acfSort" class="grid md:grid-cols-3 sm:grid-cols-2 gap-5 mb-10">
    <div class="relative h-10 w-full min-w-[200px] flex gap-3">
      <input v-model="filterTitle" class="peer h-full w-full rounded-[7px] border border-blue-gray-200 px-3 py-2.5 font-sans text-sm font-normal text-blue-gray-700 outline outline-0 transition-all placeholder-shown:border placeholder-shown:border-blue-gray-200 placeholder-shown:border-t-blue-gray-200 focus:border-2 focus:border-pink-500 focus:border-t-transparent focus:outline-0 disabled:border-0 disabled:bg-blue-gray-50"
             placeholder="Filter movie by Title"
      />
      <button @click="fetchQueryMovie"
          class="w-40 middle none center rounded-lg bg-red-500 py-2 px-6 font-sans text-xs font-bold text-white shadow-md transition-all hover:shadow-lg focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none">
        More titles
      </button>
    </div>
    <div class="relative h-10 w-full min-w-[200px] flex gap-3">
      <input v-model="filterYear" type="number" class="peer h-full w-full rounded-[7px] border border-blue-gray-200 px-3 py-2.5 font-sans text-sm font-normal text-blue-gray-700 outline outline-0 transition-all placeholder-shown:border placeholder-shown:border-blue-gray-200 placeholder-shown:border-t-blue-gray-200 focus:border-2 focus:border-pink-500 focus:border-t-transparent focus:outline-0 disabled:border-0 disabled:bg-blue-gray-50"
             placeholder="Filter movie by Year"
      />
      <button @click="fetchQueryYear"
          class="w-40 middle none center rounded-lg bg-red-500 py-2 px-6 font-sans text-xs font-bold text-white shadow-md transition-all hover:shadow-lg focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none">
        More Years
      </button>
    </div>
    <div class="relative h-10 w-full min-w-[200px] flex gap-3">
      <select v-model="filterGenre" class="peer h-full w-full rounded-[7px] border border-blue-gray-200 px-3 py-1.5 font-sans text-sm font-normal text-blue-gray-700 outline outline-0 transition-all placeholder-shown:border placeholder-shown:border-blue-gray-200 placeholder-shown:border-t-blue-gray-200 disabled:border-0 disabled:bg-blue-gray-50">
        <option value="">Select genre Genre</option>
        <option v-for="genre in availableGenres" :key="genre.id" :value="genre.id">{{ genre.name }}</option>
      </select>
      <button @click="fetchQueryGenre"
          class="w-40 middle none center rounded-lg bg-red-500 py-2 px-6 font-sans text-xs font-bold text-white shadow-md transition-all hover:shadow-lg focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none">
        More Genres
      </button>
    </div>
  </div>
  <div class="grid lg:grid-cols-4 md:grid-cols-3 grid-cols-1 gap-5">
    <div class="w-full" v-for="movie in filteredItems" :key="movie.id">
      <div class=" rounded-lg overflow-hidden mb-10 border border-black border-solid hover:shadow-xl hover:shadow-red-500 transition">
        <img :src="movie.poster_path ? 'https://image.tmdb.org/t/p/original' + movie.poster_path : 'https://placehold.co/213x320?text=No+Image'" alt="image" class="w-full min-h-96"/>
        <div class="p-5 justify-between flex text-center flex-col h-64 min-h-60 bg-black ">
          <h3 class="font-semibold text-xl sm:text-[22px] md:text-xl lg:text-[22px] xl:text-xl 2xl:text-[22px] mb-4 block text-white">
              {{ movie.title }}
          </h3>
          <span class="text-base text-body-color text-white leading-relaxed">
            {{ formattedDate(movie.release_date) }}
          </span>
          <span class="text-base text-body-color text-whiteleading-relaxed">
            {{ getGenreNames(movie.genre_ids) }}
          </span>
          <a :href="generateSlug(movie.title, movie.id)" class="inline-block text-white py-2 px-7 border border-gray-300 border-solid rounded-full text-base text-body-color font-medium">
            View Details
          </a>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import {computed, onMounted, ref, watch} from 'vue';

const upcomingMovies = ref([]);
const upcomingMoviesOriginal = ref([]);
  const genres = ref({});
  const genresArray = ref([]);
  const apiUrl = siteData.apiUrl;

  const acfAmount = ref(null);
  const acfSort = ref(null);
  const acfSortByName = ref(null);

  const filterTitle = ref('');
  const filterYear = ref('');
  const filterGenre = ref('');

  const fetchUpcomingMovies = async () => {
    try {
      const response = await fetch(apiUrl+'upcoming-movies');
      const data = await response.json();
      upcomingMovies.value = data.results;
      upcomingMoviesOriginal.value = data.results;
    } catch (error) {
      console.error('Error fetching upcoming movies:', error);
    }
  };

const fetchQueryMovie = async () => {
  try {
    const response = await fetch(siteData.apiUrl+'movie?query=' + filterTitle.value);
    const data = await response.json();
    upcomingMovies.value = data.results;
  } catch (error) {
    console.error('Error fetching upcoming movies:', error);
  }
};

const fetchQueryYear = async () => {
  try {
    const response = await fetch(siteData.apiUrl+'discover/movie/year?query=' + filterYear.value);
    const data = await response.json();
    upcomingMovies.value = data.results;
  } catch (error) {
    console.error('Error fetching upcoming movies:', error);
  }
};

const fetchQueryGenre = async () => {
  try {
    const response = await fetch(siteData.apiUrl+'discover/movie/genre?query=' + filterGenre.value);
    const data = await response.json();
    upcomingMovies.value = data.results;
  } catch (error) {
    console.error('Error fetching upcoming movies:', error);
  }
};

const fetchGenres = async () => {
  try {
    const response = await fetch(apiUrl+'genres');
    const data = await response.json();
    genresArray.value = data.genres;
    data.genres.forEach(genre => {
      genres.value[genre.id] = genre.name;
    });
  } catch (error) {
    console.error('Error fetching upcoming movies:', error);
  }
};

  const limitedMovies = computed(()=>{
    return sortedMovies.value.slice(0, acfAmount.value);
  });

const sortedMovies = computed(() => {
  if (acfSortByName.value) {
    return sortByTitle(upcomingMovies.value);
  } else {
    return sortByDate(upcomingMovies.value);
  }
});

const getGenreNames = (genreIds) => {
  return genreIds.map(id => genres.value[id]).join(', ');
};

const sortByDate = (movies) => {
  return movies.slice().sort((a, b) => new Date(b.release_date) - new Date(a.release_date));
};

const sortByTitle = (movies) => {
  return movies.slice().sort((a, b) => a.title.localeCompare(b.title));
};

  onMounted(async () => {
    const element = document.getElementById('upcoming-movies');

    acfAmount.value = JSON.parse(element.getAttribute('data-amount'));
    acfSort.value = JSON.parse(element.getAttribute('data-sort-bar'));
    acfSortByName.value = JSON.parse(element.getAttribute('data-sort-by-name'));

    await fetchGenres();
    fetchUpcomingMovies();
  });

  function formattedDate(dateString) {
    if(!dateString) return;
    const date = new Date(dateString);
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    formattedDate.value = new Intl.DateTimeFormat('en-US', options).format(date);
    return formattedDate.value;
  }

const filteredItems = computed(() => {
  return limitedMovies.value.filter(item => {
    const matchesTitle = item.title.toLowerCase().includes(filterTitle.value.toLowerCase());
    const matchesYear = filterYear.value ? item.release_date.startsWith(filterYear.value) : true;
    const matchesGenre = filterGenre.value ? item.genre_ids.includes(parseInt(filterGenre.value)) : true;

    return matchesTitle && matchesYear && matchesGenre;
  });
});

const availableGenres = computed(() => {
  const genreIds = new Set();
  limitedMovies.value.forEach(item => {
    item.genre_ids.forEach(id => genreIds.add(id));
  });
  return genresArray.value.filter(genre => genreIds.has(genre.id));
});

function generateSlug(movie, id) {
  let normalizedStr = movie.normalize('NFD').replace(/[\u0300-\u036f]/g, '');


  let slug = normalizedStr
      .toLowerCase()
      .replace(/\s+/g, '-')
      .replace(/[^\w\-]+/g, '')
      .replace(/\-\-+/g, '-')
      .replace(/^-+/, '')
      .replace(/-+$/, '');

  return siteData.siteBase+'movie/'+id+'-'+slug;
}

watch(filterTitle, (newFilterTitle) => {
  if(newFilterTitle.length === 0){
    upcomingMovies.value = upcomingMoviesOriginal.value;
  }
});

watch(filterYear, (newFilterYear) => {
  if(newFilterYear.length === 0){
    upcomingMovies.value = upcomingMoviesOriginal.value;
  }
});

watch(filterGenre, (newFilterGenre) => {
  if(newFilterGenre.length === 0){
    upcomingMovies.value = upcomingMoviesOriginal.value;
  }
});

</script>