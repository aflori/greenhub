<script setup>
    import { useLoggingStore } from "@/stores/loggin.js"
    // import { useRouter } from 'vue-router'
    import { ref } from 'vue'

    /*
    const router = useRouter()

    function returnToHome() {
        router.push({name: "home"})
    }
    */
    const logData = useLoggingStore();

    /*
    const email = ref("");
    const password = ref("password");
    const hasErrorInLogging = ref(false);
    const isWaitingForAnswer = ref(false);

    }
    */
</script>

<template>
        <form class="max-w-80">
            ceci est le form de connection
        </form>
</template>

<style scoped>
.warning {
    color: #F00;
    font-size: 2rem;
}
</style>
