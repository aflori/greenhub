<script setup>
    import { ref } from 'vue';
    import { useFormStore } from "@/stores/form.js";

    const props = defineProps({
        label: String,
        form: String,
    });

    const formStore = useFormStore();
    const formUsed = formStore[props.form][props.label]

</script>

<template>
    <div>
        <label>
            {{ formUsed.label }}
            <!-- I know that correspond to 2 distinct component, refacto later -->
            <input class="input input-bordered max-w-10/12" :type="formUsed.type" :name="props.label" v-model="formUsed.value" v-if="formUsed.type!='select'"/>
            <select class="select select-bordered maw-w-10/12"                    :name="props.label" v-model="formUsed.value" v-else>
                <option :value="v.value" v-for="v in formUsed.values"> {{v.name}} </option>
            </select>
        </label>
    </div>
</template>
