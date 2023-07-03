<template>
    <div class="container-fluid" style="margin-top: 150px">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Update Profile</h4>
                        <div
                            class="m-2 alert alert-danger"
                            v-for="(errorArray, idx) in this.$store.getters
                                .errors"
                            :key="idx"
                        >
                            <div
                                v-for="(allErrors, idx) in errorArray"
                                :key="idx"
                            >
                                <span class="text-danger"
                                    >{{ allErrors }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="update">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <div class="form-group">
                                        <label>name</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            v-model="v$.profile.name.$model"
                                        />
                                        <div
                                            class="input-errors"
                                            v-for="(error, index) of v$.profile
                                                .name.$errors"
                                            :key="index"
                                        >
                                            <div class="error-msg">
                                                {{ error.$message }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mb-2">
                                    <div class="form-group">
                                        <label>email</label>
                                        <input
                                            type="email"
                                            class="form-control"
                                            v-model="v$.profile.email.$model"
                                        />
                                        <div
                                            class="input-errors"
                                            v-for="(error, index) of v$.profile
                                                .email.$errors"
                                            :key="index"
                                        >
                                            <div class="error-msg">
                                                {{ error.$message }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button
                                        type="submit"
                                        class="btn btn-primary"
                                    >
                                        Update
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { reactive, ref } from "vue";
import { useVuelidate } from "@vuelidate/core";
import { required, email } from "@vuelidate/validators";
export default {
    setup() {
        let profile = reactive({
            id: "",
            name: "",
            email: "",
        });
        let errors = ref([]);
        return {
            v$: useVuelidate(),
            profile,
            errors,
        };
    },
    validations() {
        return {
            profile: {
                name: { required },
                email: { required, email },
            },
        };
    },
    mounted() {
        this.showProfile();
    },
    methods: {
        async showProfile() {
            if (this.$store.getters.userData.length > 0) {
                console.log("if");

                const id = `${this.$route.params.id}`;
                this.$store.dispatch("EDIT_USER_DATA", { id: id });

                const data = this.$store.getters.editUserData[0];
                this.profile.name = data.name;
                this.profile.email = data.email;
            } else {
                console.log("else");
                await this.axios
                    .get(this.url + `api/user/${this.$route.params.id}`)
                    .then((response) => {
                        this.$store.commit("EDIT_USER", response.data.edit);

                        const data = this.$store.getters.editUserData;
                        this.profile.name = data.name;
                        this.profile.email = data.email;
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
        },

        async update() {
            const id = this.$route.params.id;
            await this.$store.dispatch("UPDATE_USER_PROFILE", {
                id: id,
                model: this.profile,
            });
            if (!this.$store.getters.errors) {
                this.$router.push({ name: "dashboard" });
                this.$toast.success("Data Updated", { position: "top-right" });
            }
        },
    },
};
</script>
