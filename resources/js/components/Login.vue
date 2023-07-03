<template>
    <div id="main" class="main-container">
        <div class="box">
            <h2>Login</h2>

            <div
                class="m-2 alert alert-danger"
                v-for="(errorArray, idx) in error"
                :key="idx"
            >
                <div v-for="(allErrors, idx) in errorArray" :key="idx">
                    <span class="text-danger">{{ allErrors }} </span>
                </div>
            </div>

            <form @submit.prevent="login">
                <div class="input-box">
                    <input
                        id="user-email"
                        type="text"
                        v-model="v$.form.email.$model"
                    />
                    <label>Email</label>
                    <div
                        class="input-errors"
                        v-for="(error, index) of v$.form.email.$errors"
                        :key="index"
                    >
                        <div class="error-msg">{{ error.$message }}</div>
                    </div>
                </div>
                <div class="input-box">
                    <input
                        id="user-pass"
                        type="password"
                        v-model="v$.form.password.$model"
                    />
                    <label>Password</label>
                    <div
                        class="input-errors"
                        v-for="(error, index) of v$.form.password.$errors"
                        :key="index"
                    >
                        <div class="error-msg">{{ error.$message }}</div>
                    </div>
                </div>

                <input type="submit" value="Submit" />
            </form>
            <p>
                <router-link to="/register">
                    <a href="#">Don't have an Account</a>
                </router-link>
            </p>
        </div>
    </div>
</template>

<script>
import { useVuelidate } from "@vuelidate/core";
import { required, email, minLength } from "@vuelidate/validators";
import { reactive, ref } from "vue";
import { useRouter } from "vue-router";
import { useStore } from "vuex";

export default {
    setup() {
        let form = reactive({
            email: "",
            password: "",
        });
        const router = useRouter();
        const store = useStore();
        let error = ref("");
        const login = async () => {
            await axios
                .post("api/login", form)
                .then((response) => {
                    if (response.data.success) {
                        store.dispatch("SET_TOKEN", response.data.token);
                        router.push({ name: "dashboard" });
                    } else {
                        error.value = response.data.message;
                    }
                })
                .catch((error) => {
                    console.log(error);
                });
        };

        return {
            v$: useVuelidate(),
            form,
            login,
            error,
        };
    },
    validations() {
        return {
            form: {
                email: { required, email },
                password: { required, min: minLength(3) },
            },
        };
    },
};
</script>
