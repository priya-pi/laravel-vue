<template>
    <div class="container-fluid" style="margin-top: 150px">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Company Create</h4>
                        {{ v$.companyCreate.name }}
                        {{ v$.companyCreate.name.$error }}
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
                        <form v-on:submit.prevent="Create">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <div class="form-group">
                                        <label>company_id</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            v-model="
                                                v$.companyCreate.company_id
                                                    .$model
                                            "
                                        />
                                        <div
                                            class="input-errors"
                                            v-for="(error, index) of v$
                                                .companyCreate.company_id
                                                .$errors"
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
                                        <label>name</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            v-model="
                                                v$.companyCreate.name.$model
                                            "
                                        />
                                        <div
                                            class="input-errors"
                                            v-for="(error, index) of v$
                                                .companyCreate.name.$errors"
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
                                            v-model="
                                                v$.companyCreate.email.$model
                                            "
                                        />
                                        <div
                                            class="input-errors"
                                            v-for="(error, index) of v$
                                                .companyCreate.email.$errors"
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
                                        <label>Address</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            v-model="
                                                v$.companyCreate.address.$model
                                            "
                                        />
                                        <div
                                            class="input-errors"
                                            v-for="(error, index) of v$
                                                .companyCreate.address.$errors"
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
                                        create
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
import { reactive } from "vue";
import { useVuelidate } from "@vuelidate/core";
import { required, email } from "@vuelidate/validators";

export default {
    setup() {
        let companyCreate = reactive({
            company_id: "",
            name: "",
            email: "",
            address: "",
        });
        return {
            v$: useVuelidate(),
            companyCreate,
        };
    },
    validations() {
        return {
            companyCreate: {
                company_id: { required },
                name: { required },
                email: { required, email },
                address: { required },
            },
        };
    },
    methods: {
        async Create() {
            const isFormCorrect = await this.v$.$validate();
            if (!isFormCorrect) return;

            await this.$store.dispatch("CREATE_COMPANY", {
                model: this.companyCreate,
            });

            if (!this.$store.getters.errors) {
                this.$router.push({ name: "companies" });
                this.$toast.success("Company Added", { position: "top-right" });
            }
        },
    },
};
</script>
