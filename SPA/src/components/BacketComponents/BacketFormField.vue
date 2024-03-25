<script setup>
    import { ref, defineProps } from 'vue';
    import { useFormStore } from "@/stores/form.js";

    const props = defineProps({
        key: String,
        form: String,
    });

    const formStore = useFormStore();
</script>

<template>
    <div>
        {{ form }} -- {{ key }}
    </div>
    <!-- <label class="input input-bordered flex items-center gap-2">
        <span>{{ name }} </span>
        <input type="type" class="grow" :placeholder="name" v-model="value"/>
    </label> -->
</template>
