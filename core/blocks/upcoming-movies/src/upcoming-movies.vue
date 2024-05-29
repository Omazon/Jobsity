<template>
  <div v-if="acfSort" class="grid md:grid-cols-3 sm:grid-cols-2 gap-5 mb-10">
    <div class="relative h-10 w-full min-w-[200px]">
      <input v-model="filterTitle" class="peer h-full w-full rounded-[7px] border border-blue-gray-200 px-3 py-2.5 font-sans text-sm font-normal text-blue-gray-700 outline outline-0 transition-all placeholder-shown:border placeholder-shown:border-blue-gray-200 placeholder-shown:border-t-blue-gray-200 focus:border-2 focus:border-pink-500 focus:border-t-transparent focus:outline-0 disabled:border-0 disabled:bg-blue-gray-50"
             placeholder=" "
      />
      <label class="before:content[' '] after:content[' '] pointer-events-none absolute left-0 -top-1.5 flex h-full w-full select-none text-[11px] font-normal leading-tight text-blue-gray-400 transition-all before:pointer-events-none before:mt-[6.5px] before:mr-1 before:box-border before:block before:h-1.5 before:w-2.5 before:rounded-tl-md before:border-t before:border-l before:border-blue-gray-200 before:transition-all after:pointer-events-none after:mt-[6.5px] after:ml-1 after:box-border after:block after:h-1.5 after:w-2.5 after:flex-grow after:rounded-tr-md after:border-t after:border-r after:border-blue-gray-200 after:transition-all peer-placeholder-shown:text-sm peer-placeholder-shown:leading-[3.75] peer-placeholder-shown:text-blue-gray-500 peer-placeholder-shown:before:border-transparent peer-placeholder-shown:after:border-transparent peer-focus:text-[11px] peer-focus:leading-tight peer-focus:text-pink-500 peer-focus:before:border-t-2 peer-focus:before:border-l-2 peer-focus:before:border-pink-500 peer-focus:after:border-t-2 peer-focus:after:border-r-2 peer-focus:after:border-pink-500 peer-disabled:text-transparent peer-disabled:before:border-transparent peer-disabled:after:border-transparent peer-disabled:peer-placeholder-shown:text-blue-gray-500">
        Filter movie by Name
      </label>
    </div>
    <div class="relative h-10 w-full min-w-[200px]">
      <input v-model="filterYear" type="number" class="peer h-full w-full rounded-[7px] border border-blue-gray-200 px-3 py-2.5 font-sans text-sm font-normal text-blue-gray-700 outline outline-0 transition-all placeholder-shown:border placeholder-shown:border-blue-gray-200 placeholder-shown:border-t-blue-gray-200 focus:border-2 focus:border-pink-500 focus:border-t-transparent focus:outline-0 disabled:border-0 disabled:bg-blue-gray-50"
             placeholder=" "
      />
      <label class="before:content[' '] after:content[' '] pointer-events-none absolute left-0 -top-1.5 flex h-full w-full select-none text-[11px] font-normal leading-tight text-blue-gray-400 transition-all before:pointer-events-none before:mt-[6.5px] before:mr-1 before:box-border before:block before:h-1.5 before:w-2.5 before:rounded-tl-md before:border-t before:border-l before:border-blue-gray-200 before:transition-all after:pointer-events-none after:mt-[6.5px] after:ml-1 after:box-border after:block after:h-1.5 after:w-2.5 after:flex-grow after:rounded-tr-md after:border-t after:border-r after:border-blue-gray-200 after:transition-all peer-placeholder-shown:text-sm peer-placeholder-shown:leading-[3.75] peer-placeholder-shown:text-blue-gray-500 peer-placeholder-shown:before:border-transparent peer-placeholder-shown:after:border-transparent peer-focus:text-[11px] peer-focus:leading-tight peer-focus:text-pink-500 peer-focus:before:border-t-2 peer-focus:before:border-l-2 peer-focus:before:border-pink-500 peer-focus:after:border-t-2 peer-focus:after:border-r-2 peer-focus:after:border-pink-500 peer-disabled:text-transparent peer-disabled:before:border-transparent peer-disabled:after:border-transparent peer-disabled:peer-placeholder-shown:text-blue-gray-500">
        Filter movie by Year
      </label>
    </div>
    <div class="relative h-10 w-full min-w-[200px]">
      <select v-model="filterGenre" class="peer h-full w-full rounded-[7px] border border-blue-gray-200 px-3 py-1.5 font-sans text-sm font-normal text-blue-gray-700 outline outline-0 transition-all placeholder-shown:border placeholder-shown:border-blue-gray-200 placeholder-shown:border-t-blue-gray-200 disabled:border-0 disabled:bg-blue-gray-50">
        <option value="">Select genre Genre</option>
        <option v-for="genre in availableGenres" :key="genre.id" :value="genre.id">{{ genre.name }}</option>
      </select>
    </div>
  </div>
  <div class="grid lg:grid-cols-4 md:grid-cols-3 grid-cols-1 gap-5">
    <div class="w-full" v-for="movie in filteredItems" :key="movie.id">
      <div class=" rounded-lg overflow-hidden mb-10 border border-black border-solid">
        <img :src="'https://image.tmdb.org/t/p/original' + movie.poster_path" alt="image" class="w-full"/>
        <div class="p-5 justify-between flex text-center flex-col h-60 bg-white">
          <h3>
            <a  href="javascript:void(0)" class="font-semibold text-dark text-xl sm:text-[22px] md:text-xl lg:text-[22px] xl:text-xl 2xl:text-[22px] mb-4 block hover:text-primary">
              {{ movie.title }}
            </a>
          </h3>
          <span class="text-base text-body-color leading-relaxed">
            {{ formattedDate(movie.release_date) }}
          </span>
          <span class="text-base text-body-color leading-relaxed">
            {{ getGenreNames(movie.genre_ids) }}
          </span>
          <a href="javascript:void(0)" class="inline-block py-2 px-7 border border-gray-300 border-solid rounded-full text-base text-body-color font-medium">
            View Details
          </a>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import {ref, onMounted, computed} from 'vue';

  const upcomingMovies = ref([]);
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
    const date = new Date(dateString);
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    formattedDate.value = new Intl.DateTimeFormat('en-US', options).format(date);
    return formattedDate.value;
  }

const filteredItems = computed(() => {
  return limitedMovies.value.filter(item => {
    const matchesTitle = item.original_title.toLowerCase().includes(filterTitle.value.toLowerCase());
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
</script>