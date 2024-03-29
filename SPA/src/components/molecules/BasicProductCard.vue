<script setup>
    import { ref, computed } from 'vue';
    import { RouterLink } from 'vue-router';

    const props = defineProps([
        'product',//: Object
        /* contains:
        id: Number,
        title: String,
        price: String,
        categories: Array,
        description: String,
        image: String,
        */
    ]);


    const isImageDefined = computed(() => props.product.image !== undefined);
</script>

<template>
    <div class="card w-96 bg-base-100 shadow-xl p-4 m-2 bg-primary w-80 mx-4 min-mx-2">
        <RouterLink :to="{name: 'product', params: {id: props.product.id}}" class="">
            <h2 class="card-title h-24 text-center mx-auto w-fit">
                {{ props.product.title }}
            </h2>
            <figure class="w-80 h-80 mx-auto">
                <img :src="props.product.image" :alt="props.product.title" v-if="isImageDefined"/>
            </figure>
        </RouterLink>
        <div class="card-body p-4">
            <p class="leading-6">{{ props.product.description }}</p>
            <div class="card-actions justify-end flex">
                <p>{{props.product.price}}</p>
                <div class="badge badge-outline" v-for="category in props.product.categories"> {{ category }}</div>
            </div>
        </div>
    </div>
</template>
