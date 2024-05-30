<template>
  <div class="relative flex flex-col justify-center overflow-hidden">
    <div class="w-full items-center">
      <div class="group grid w-full md:grid-cols-3 grid-cols-1 gap-10">
        <div class="relative flex items-end flex-col overflow-hidden">
          <div class="rounded-xl overflow-hidden">
            <img :src="'https://image.tmdb.org/t/p/original'+movie.poster_path" alt="">
          </div>
        </div>
        <div class="col-span-2">
          <div>
            <h1 class="text-6xl">
              {{movie.original_title}}
            </h1>
            <div class="mb-5 text-3xl">{{formattedDate(movie.release_date)}}</div>
            <div class="flex gap-3">
              <div v-for="genre in movie.genres" :key="genre.id" class="center relative inline-block select-none whitespace-nowrap rounded-lg bg-red-500 py-2 px-3.5 align-baseline font-sans text-xs font-bold uppercase leading-none text-white">
                <div class="mt-px">{{genre.name}}</div>
              </div>
            </div>
            <p class="text-2xl mt-6">
              {{movie.overview}}
            </p>
          </div>
          <div class="grid md:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-10 mt-10">
            <div v-if="alternativeTitles.length > 0">
              <h3 class="text-4xl">Alternative Titles</h3>
              <ul class="h-40 overflow-y-scroll">
                <li v-for="alternativeTitle in alternativeTitles" :key="alternativeTitle.iso_3166_1">
                  {{alternativeTitle.title}}
                </li>
              </ul>
            </div>
            <div class="md:col-span-2" v-if="movie.production_companies && movie.production_companies.length > 0">
              <h3 class="text-4xl">Production Companies</h3>
              <div class="flex flex-col flex-wrap lg:flex-row justify-center lg:justify-between gap-5 items-center">
                <img v-for="productionCompany in movie.production_companies"
                     :key="productionCompany"
                     class="h-auto max-w-28 w-full"
                     :src="'https://image.tmdb.org/t/p/original'+productionCompany.logo_path" alt="">
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mt-5">
        <div>
          <h3 v-if="movie.original_language" class="text-3xl">Original Language: <strong>{{movie.original_language}}</strong></h3>
        </div>
        <div>
          <h3 v-if="movie.popularity" class="text-3xl">Popularity: <strong>{{movie.popularity}}</strong></h3>
        </div>
        <div class="md:col-span-3" v-if="trailer">
          <h3 class="text-3xl">Trailer</h3>
          <iframe width="100%" height="700px" :src="'https://www.youtube.com/embed/'+trailer.key" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        </div>
      </div>
      <div v-if="displayedCredits.length > 0">
        <h3 class="text-3xl my-10">Cast</h3>
        <div class="grid lg:grid-cols-4 md:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-8">
          <div class="flex flex-col justify-center items-center hover:shadow-xl rounded-lg pb-8 transition-all" v-for="(crew, index) in displayedCredits" :key="index">
            <img :src="crew.profile_path ? 'https://image.tmdb.org/t/p/original'+crew.profile_path : 'https://placehold.co/213x320'" alt="">
            <span class="text-2xl mt-5">{{crew.name}}</span>
            <span class="text-lg mb-5">{{crew.character}}</span>
            <a :href="generateSlug(crew.name, crew.id, 'actor')" class="inline-block py-2 px-7 border border-gray-300 border-solid rounded-full text-base text-body-color font-medium">
              View Details
            </a>
          </div>
        </div>
      </div>
      <button @click="loadMore" v-if="hasMoreItems" class="my-5 flex justify-center mx-auto py-2 px-7 border border-gray-300 border-solid rounded-full text-base text-body-color font-medium transition hover:shadow-xl">
        Load more
      </button>
      <div v-if="reviews.length > 0">
        <h3 class="text-3xl my-10">Reviews</h3>
        <div class="grid md:grid-cols-3 grid-cols-1 gap-8">
          <div class="flex flex-col items-center p-8 h-[800px]" v-for="review in reviews" :key="review.id">
            <img  class="max-h-80" :src="review.author_details.avatar_path ? 'https://image.tmdb.org/t/p/original'+review.author_details.avatar_path : 'https://placehold.co/213x320'" alt="">
            <span class="text-2xl mt-5">{{review.author}}</span>
            <p class="text-lg mb-5 overflow-y-scroll">{{review.content}}</p>
            <a :href="review.url" target="_blank" class="inline-block py-2 px-7 border border-gray-300 border-solid rounded-full text-base text-body-color font-medium">
              more details
            </a>
          </div>
        </div>
      </div>
      <div v-if="similarMovies.length > 0">
        <h3 class="text-3xl my-10">Similar Movies</h3>
        <div class="grid lg:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-8">
          <div class="flex flex-col justify-center items-center hover:shadow-xl rounded-lg p-8 transition-all" v-for="similar in similarMovies" :key="similar.id">
            <img  class="max-h-80" :src="similar.poster_path ? 'https://image.tmdb.org/t/p/original'+similar.poster_path : 'https://placehold.co/213x320'" alt="">
            <span class="text-2xl mt-5 text-center">{{similar.title}}</span>
            <span class="text-lg mb-5">{{formattedDate(similar.release_date)}}</span>
            <a :href="generateSlug(similar.title, similar.id)" class="inline-block py-2 px-7 border border-gray-300 border-solid rounded-full text-base text-body-color font-medium">
              View Details
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import {onMounted, ref, computed} from "vue";
import { formattedDate, generateSlug } from '../utils/utils'

const acfMovieId = ref('');
const movie = ref({});
const alternativeTitles = ref({});
const trailer = ref({});
const credits = ref({});
const reviews = ref({});
const similarMovies = ref({});

const creditsToShow = 8;
const currentPage = ref(1);
const displayedCredits = ref({});

onMounted(async () => {
  const element = document.getElementById('single-movie');

  acfMovieId.value = JSON.parse(element.getAttribute('data-movie-id'));

  fetchMovie();
  fetchAlternativeTitles();
  fetchVideos();
  fetchCredits();
  fetchReviews();
  fetchSimilar()

});

const fetchMovie = async () => {
   try {
    const response = await fetch(siteData.apiUrl+'movie/' + acfMovieId.value);
    movie.value = await response.json();
  } catch (error) {
    console.error('Error fetching upcoming movies:', error);
    window.location.href = "/404"
    throw new Error(error);
  }
};

const fetchAlternativeTitles = async () => {
  try {
    const response = await fetch(siteData.apiUrl+'movie/' + acfMovieId.value+'/alternative-titles');
    let data = await response.json();
    alternativeTitles.value = data.titles;
  } catch (error) {
    console.error('Error fetching upcoming movies:', error);
  }
};

const fetchVideos = async () => {
  try {
    const response = await fetch(siteData.apiUrl+'movie/' + acfMovieId.value+'/videos');
    let data = await response.json();
    trailer.value =  data.results.find(video => video.type === "Trailer");
  } catch (error) {
    console.error('Error fetching upcoming movies:', error);
  }
};

const fetchCredits = async () => {
  try {
    const response = await fetch(siteData.apiUrl+'movie/' + acfMovieId.value+'/credits');
    let data = await response.json();
    credits.value =  data.cast;
    displayedCredits.value = credits.value.slice(0, creditsToShow);
  } catch (error) {
    console.error('Error fetching upcoming movies:', error);
  }
};

const fetchReviews = async () => {
  try {
    const response = await fetch(siteData.apiUrl+'movie/' + acfMovieId.value+'/reviews');
    let data = await response.json();
    reviews.value =  data.results;
  } catch (error) {
    console.error('Error fetching upcoming movies:', error);
  }
};

const fetchSimilar = async () => {
  try {
    const response = await fetch(siteData.apiUrl+'movie/' + acfMovieId.value+'/similar');
    let data = await response.json();
    similarMovies.value =  data.results;
  } catch (error) {
    console.error('Error fetching upcoming movies:', error);
  }
};

const loadMore = () => {
  currentPage.value++;
  const newItems = credits.value.slice(0, currentPage.value * creditsToShow);
  displayedCredits.value = newItems;
};

const hasMoreItems = computed(() => {
  return displayedCredits.value.length < credits.value.length;
});
</script>
