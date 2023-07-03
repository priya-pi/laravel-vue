<template>
    <h1>company</h1>
    <router-link to="/companies/add" class="btn btn-info">Add</router-link>

    <div class="container-fluid" style="margin-top: 150px">
        <table class="table table-light">
            <thead class="table-dark">
                <tr>
                    <th>id</th>
                    <th>name</th>
                    <th>email</th>
                    <th>Address</th>
                    <th>Created_at</th>
                    <th>updated_at</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="(details, key) in this.$store.getters.companyData"
                    :key="key"
                >
                    <td>{{ details.id }}</td>
                    <td>{{ details.name }}</td>
                    <td>{{ details.email }}</td>
                    <td>{{ details.address }}</td>
                    <td>{{ details.created_at }}</td>
                    <td>{{ details.updated_at }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
export default {
    setup() {},
    async created() {
        const company = {
            method: "get",
            url: "api/company",
        };
        try {
            let res = await this.axios(company);
            console.log(res.data);
            this.company = res.data.data;
            this.$store.commit("GET_COMPANY", this.company);
        } catch (err) {
            console.log(err);
        }
    },
};
</script>
