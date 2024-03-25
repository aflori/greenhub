<script setup>
    import { defineProps, computed } from 'vue';

    const props = defineProps(["text", "stepNumber", "position"])
    const stepIsDone = computed(() => props.stepNumber >= props.position);
    const stepToDo = computed(() => props.stepNumber <= props.position);
</script>

<template>
    <li :class="{'step': true, 'step-neutral': stepIsDone, 'step-secondary': stepToDo}">{{ text }}</li>
</template>