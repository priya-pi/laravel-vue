<template>
    <div id="main" class="main-container">
        <div class="box">
            <h2>Sign Up</h2>

            <div
                class="m-2 alert alert-danger"
                v-for="(errorArray, idx) in notifmsg"
                :key="idx"
            >
                <div v-for="(allErrors, idx) in errorArray" :key="idx">
                    <span class="text-danger">{{ allErrors }} </span>
                </div>
            </div>

            <form v-on:submit.prevent="register">
                <div class="input-box">
                    <input type="text" v-model="form.name" />
                    <label>Name</label>
                    <div
                        class="input-errors"
                        v-for="(error, index) of v$.form.name.$errors"
                        :key="index"
                    >
                        <div class="error-msg">{{ error.$message }}</div>
                    </div>
                </div>
                <div class="input-box">
                    <input type="text" v-model="form.email" />
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
                    <input type="password" v-model="form.password" />
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
                <router-link to="/login">
                    <a href="#">Already have an Account</a>
                </router-link>
            </p>
        </div>
    </div>
</template>

<script>
import { useVuelidate } from "@vuelidate/core";
import {
    required,
    helpers,
    email,
    minLength,
    alphaNum,
} from "@vuelidate/validators";
import { reactive, ref } from "vue";

export default {
    setup() {
        let form = reactive({
            name: "",
            email: "",
            password: "",
        });
        let notifmsg = ref("");
        return {
            v$: useVuelidate(),
            form,
            notifmsg,
        };
    },
    validations() {
        return {
            form: {
                name: {
                    required: helpers.withMessage(
                        "This field cannot be empty",
                        required
                    ),
                    minLength: helpers.withMessage(
                        ({ $invalid, $params, $model }) =>
                            `This field has a value of '${$model}' but must have a min length of ${
                                $params.min
                            } so it is ${$invalid ? "invalid" : "valid"}`,
                        minLength(4)
                    ),
                },
                email: {
                    required: helpers.withMessage(
                        "This field cannot be empty",
                        required
                    ),
                    email: helpers.withMessage("invalid email", email),
                },
                password: {
                    required: helpers.withMessage(
                        "This field cannot be empty",
                        required
                    ),
                    minLength: helpers.withMessage(
                        ({ $invalid, $params, $model }) =>
                            `This field has a value of '${$model}' but must have a min length of ${
                                $params.min
                            } so it is ${$invalid ? "invalid" : "valid"}`,
                        minLength(3)
                    ),
                    alphaNum: helpers.withMessage(
                        "aplpha numeric password allowed",
                        alphaNum
                    ),
                },
            },
        };
    },
    methods: {
        async register() {
            const isFormCorrect = await this.v$.$validate();
            if (!isFormCorrect) return;

            await axios
                .post("api/register", this.form)
                .then((response) => {
                    if (response.data.results) {
                        this.$router.push({ name: "login" });
                    } else {
                        this.notifmsg = response.data.message;
                    }
                })
                .catch((err) => {
                    console.log(err);
                });
        },
    },
};
</script>
