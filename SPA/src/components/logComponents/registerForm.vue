<script setup>
    import { useLoggingStore } from "@/stores/loggin.js"
    // import { useRouter } from 'vue-router'
    import { ref, reactive, computed } from 'vue'

    /*
    const router = useRouter()

    function returnToHome() {
        router.push({name: "home"})
    }
    */
    const logData = useLoggingStore();


    const fieldValue = reactive({
        firstName: "",
        lastName: "",
        pseudoname: "",
        email: "",
        emailConfirmed: "",
        password: "",
        passwordConfirmed: ""
    });

    const errorMessage = computed(() => {
        function isInObject(value, object_) {
            return Object.values(object_).includes(value);
        }


        if ( isInObject("", fieldValue)) {
            return "Tous les champs du formulaire doivent être remplis"
        }

        if (fieldValue.email !== fieldValue.emailConfirmed) {
            return "les 2 adresses mails doivent être identiques"
        }

        if (fieldValue.password !== fieldValue.passwordConfirmed) {
            return "les mots de passe ne sont pas identiques"
        }

        if ( fieldValue.firstName.length >= 255 ) {
            return "le prénom est trop long"
        }

        if ( fieldValue.lastName.length >= 255 ) {
            return "le nom est trop long"
        }

        if ( fieldValue.email.length >= 255 ) {
            return "l'adresse mail est trop long"
        }

        if (fieldValue.pseudoname.length >= 50 ) {
            return "le pseudo est trop long"
        }


        return "";
    });
</script>

<template>
    <form class="max-w-96">
        <label class="input input-bordered flex items-center gap-2">
            prénom:
            <input type="text" class="grow" placeholder="Jhon" v-model="fieldValue.firstName" />
        </label>
        <label class="input input-bordered flex items-center gap-2">
            nom:
            <input type="text" class="grow" placeholder="Doe" v-model="fieldValue.lastName" />
        </label>
        <label class="input input-bordered flex items-center gap-2">
            pseudo:
            <input type="text" class="grow" placeholder="doigt" v-model="fieldValue.pseudoname" />
        </label>
        <label class="input input-bordered flex items-center gap-2">
            mail:
            <input type="email" class="grow" placeholder="exemple@exemple.com" v-model="fieldValue.email" />
        </label>
        <label class="input input-bordered flex items-center gap-2">
            confirmer l'adresse mail:
            <input type="email" class="grow" placeholder="exemple@exemple.com" v-model="fieldValue.emailConfirmed" @paste.prevent />
        </label>
        <label class="input input-bordered flex items-center gap-2">
            mot de passe:
            <input type="password" class="grow" v-model="fieldValue.password" />
        </label>
        <label class="input input-bordered flex items-center gap-2">
            confirmer le mot de passe:
            <input type="password" class="grow" v-model="fieldValue.passwordConfirmed" @paste.prevent />
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
