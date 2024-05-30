<template>
  <div class="relative flex flex-col justify-center overflow-hidden">
    <div class="w-full items-center">
      <div class="group grid w-full md:grid-cols-3 grid-cols-1 gap-10">
        <div class="relative flex items-end flex-col overflow-hidden">
          <div class="rounded-xl overflow-hidden">
            <img :src="'https://image.tmdb.org/t/p/original'+actor.profile_path" alt="">
          </div>
        </div>
        <div class="col-span-2">
          <div>
            <h1 class="text-6xl">
              {{actor.name}}
            </h1>
            <div class="mb-5 text-3xl">{{formattedDate(actor.birthday)}}</div>
            <p class="text-2xl mt-6" v-if="actor.place_of_birth">
              <strong>Place of birth: </strong>{{actor.place_of_birth}}
            </p>
            <p class="text-2xl mt-6" v-if="actor.deathday">
              <strong>Deathday: </strong>{{formattedDate(actor.deathday)}}
            </p>
            <p class="text-2xl mt-6" v-if="actor.homepage">
              <strong>Homepage: </strong><a :href="actor.homepage" target="_blank">{{ actor.homepage }}</a>
            </p>
            <p class="text-2xl mt-6" v-if="actor.popularity">
              <strong>Popularity: </strong>{{actor.popularity}}
            </p>
            <p class="text-2xl mt-6">
              {{actor.biography}}
            </p>
          </div>
        </div>
      </div>
      <div class="grid lg:grid-cols-5 md:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-10 mt-10">
        <img class="rounded cursor-pointer" @click="openLightboxOnSlide(index+1)" v-for="(image, index) in limitedImages" :key="image.file_path" :src="'https://image.tmdb.org/t/p/original'+image.file_path" alt="">
      </div>
      <FsLightbox
          :toggler="toggler"
          :slide="slide"
          :sources="convertImages(limitedImages)"
      />
      <div>
        <actor-movies :title="'Movies'" :actor_id="actor.id" />
      </div>
    </div>
  </div>
</template>
<script setup>
import {computed, onMounted, ref} from "vue";
import FsLightbox from "fslightbox-vue/v3";
import ActorMovies from "../components/actor_movies.vue";

const acfActorId = ref('');
const actor = ref({});
const toggler = ref(false);

const images= ref([]);
const slide = ref(0);

onMounted(async () => {
  const element = document.getElementById('single-actor');

  acfActorId.value = JSON.parse(element.getAttribute('data-actor-id'));

  fetchActor();
  fetchActorImages();

});

const fetchActor = async () => {
  try {
    const response = await fetch(siteData.apiUrl+'person/' + acfActorId.value);
    actor.value = await response.json();
  } catch (error) {
    console.error('Error fetching upcoming movies:', error);
    window.location.href = "/404"
    throw new Error(error);
  }
};

const fetchActorImages = async () => {
  try {
    const response = await fetch(siteData.apiUrl+'person/' + acfActorId.value+'/images');
    let data = await response.json();
    images.value = data.profiles;
  } catch (error) {
    console.error('Error fetching upcoming movies:', error);
  }
};
function formattedDate(dateString) {
  if(!dateString) return;
  const date = new Date(dateString);
  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  formattedDate.value = new Intl.DateTimeFormat('en-US', options).format(date);
  return formattedDate.value;
}

function convertImages(images) {
  return images.map(image => 'https://image.tmdb.org/t/p/original'+image.file_path)
}

const limitedImages = computed(()=>{
  return images.value.slice(0, 10);
});

function openLightboxOnSlide(number) {
  console.log(number);
  slide.value = number;
  toggler.value = !toggler.value;
}
</script>
