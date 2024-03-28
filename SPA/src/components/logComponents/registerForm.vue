<script setup>
    import { useLoggingStore } from "@/stores/loggin.js"
    // import { useRouter } from 'vue-router'
    import { ref, computed } from 'vue'

    /*
    const router = useRouter()

    function returnToHome() {
        router.push({name: "home"})
    }
    */
    const logData = useLoggingStore();


    const firstName = ref("");
    const lastName = ref("");
    const pseudoname = ref("");
    const email = ref("");
    const emailConfirmed = ref("");
    const password = ref("");
    const passwordConfirmed = ref("");

    const errorMessage = computed(() => {
        return "";
    });
</script>

<template>
    <form class="max-w-96">
        <label class="input input-bordered flex items-center gap-2">
            prénom:
            <input type="text" class="grow" placeholder="Jhon" v-model="firstName" />
        </label>
        <label class="input input-bordered flex items-center gap-2">
            nom:
            <input type="text" class="grow" placeholder="Doe" v-model="lastName" />
        </label>
        <label class="input input-bordered flex items-center gap-2">
            pseudoname:
            <input type="text" class="grow" placeholder="doigt" v-model="pseudoname" />
        </label>
        <label class="input input-bordered flex items-center gap-2">
            mail:
            <input type="email" class="grow" placeholder="exemple@exemple.com" v-model="email" />
        </label>
        <label class="input input-bordered flex items-center gap-2">
            confirmer l'adresse mail:
            <input type="email" class="grow" placeholder="exemple@exemple.com" v-model="emailConfirmed" @paste.prevent />
        </label>
        <label class="input input-bordered flex items-center gap-2">
            mot de passe:
            <input type="password" class="grow" v-model="password" />
        </label>
        <label class="input input-bordered flex items-center gap-2">
            confirmer le mot de passe:
            <input type="password" class="grow" v-model="passwordConfirmed" @paste.prevent />
        </label>

        <div :class="{'tooltip': errorMessage!='',  'tooltip-right': true,  'max-lg:tooltip-open': true}" :data-tip="errorMessage">
            <button class="btn" @click.prevent > Créer un compte </button>
        </div>
    </form>
</template>

<style scoped>
.warning {
    color: #F00;
    font-size: 2rem;
}
</style>
