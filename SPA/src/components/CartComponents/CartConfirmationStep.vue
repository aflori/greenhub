<script setup>
import { useFormStore } from '@/stores/form.js'

const formStore = useFormStore()
</script>

<template>
    <div v-for="(values, key) in formStore.getFieldList">
        <strong> {{ key }} </strong>
        <div v-for="value in values">
            <span class="custom_label">{{ value.label }}</span>: <span class="custom_value">{{ value.value }}</span>
        </div>
    </div>
</template>

<style scoped>
.custom_label {
    font-weight: 600;
}

.custom_value {
    font-style: oblique, 10deg;
}

strong {
    font-weight: 800;
}
</style>