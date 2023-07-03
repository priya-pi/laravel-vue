<template>
    <h1>Dashboard</h1>
    <button type="button" @click="logout" class="btn btn-danger">
        logout btn
    </button>
    <router-link to="/companies" class="btn btn-info">Company Data</router-link>
    <!-- <img :src="previewImage" class="uploading-image"  />
    <input type="file" accept="image/jpeg" @change="uploadImage" height="300px" width="400px" /> -->
    <div class="container-fluid" style="margin-top: 150px">
        <div class="bg-secondary p-3 m-2">
            <input
                class="form-control mr-sm-2 w-50 m-2"
                type="search"
                placeholder="Search"
                aria-label="Search"
                v-model="searchValue"
                @keyup="searchData"
            />
        </div>
        <table class="table table-light">
            <thead class="table-dark">
                <tr>
                    <th @click="sortList('id')">id &#8597;</th>
                    <th @click="sortList('name')">name &#8597;</th>
                    <th @click="sortList('email')">email &#8597;</th>
                    <th>Created_at</th>
                    <th>updated_at</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody v-if="this.$store.getters.userData.length > 0">
                <tr
                    v-for="(details, key) in this.$store.getters.userData"
                    :key="key"
                >
                    <td>{{ details.id }}</td>
                    <td>{{ details.name }}</td>
                    <td>{{ details.email }}</td>
                    <td>{{ details.created_at }}</td>
                    <td>{{ details.updated_at }}</td>
                    <td>
                        <router-link
                            :to="{
                                name: 'profileEdit',
                                params: { id: details.id },
                            }"
                            class="btn btn-success"
                            >Edit</router-link
                        >
                        <button
                            type="button"
                            @click="deleteUser(details.id)"
                            class="btn btn-danger"
                        >
                            Delete
                        </button>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td align="center" colspan="3">No record found.</td>
                </tr>
            </tbody>
        </table>

        <nav
            v-if="this.$store.getters.userData.length > 0"
            aria-label="Page navigation example"
        >
            <ul class="pagination">
                <li class="page-item">
                    <button
                        class="page-link"
                        :disabled="this.page === 1"
                        @click="clickpre"
                    >
                        Previous
                    </button>
                </li>
                <li
                    class="page-item"
                    @click="pagechange(pageNo)"
                    v-for="(pageNo, n) in this.$store.getters.pageLink"
                    :key="n"
                >
                    <a class="page-link">{{ pageNo }}</a>
                </li>
                <li class="page-item">
                    <button
                        class="page-link"
                        :disabled="this.page === this.$store.getters.pageLink"
                        @click="clickNext"
                    >
                        Next
                    </button>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
export default {
    data() {
        return {
            searchValue: "",
            columnName: "id",
            columnSort: "ASC",
            sort: true,
            page: 1,
            perPageLimit: 5,
            previewImage: null,
        };
    },
    mounted() {
        this.userData();
    },
    methods: {
        uploadImage(e) {
            const image = e.target.files[0];
            const reader = new FileReader();
            reader.readAsDataURL(image);
            reader.onload = (e) => {
                this.previewImage = e.target.result;
                console.log(this.previewImage);
            };
        },
        async userData() {
            try {
                const save = this;
                const limit = this.perPageLimit;
                await axios
                    .post("api/user", {
                        search: this.searchValue,
                        limit: this.perPageLimit,
                        offset: (this.page - 1) * this.perPageLimit,
                        column: this.columnName,
                        order: this.columnSort,
                    })
                    .then(function (response) {
                        save.$store.commit("SET_USER", response.data.data);
                        save.$store.dispatch(
                            "PAGE_LINK",
                            Math.ceil(response.data.count / limit)
                        );
                    });
            } catch (err) {
                console.log(err);
            }
        },
        deleteUser(id) {
            this.$swal({
                title: "Are you sure?",
                text: "Are you sure want to delete this item!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: " #228B22",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes",
            }).then((result) => {
                if (result.value == true) {
                    this.$toast.success("Data Deleted", {
                        position: "top-right",
                    });
                    this.$store.dispatch("DELETE_USER", { id: id });
                }
            });
        },
        logout() {
            this.$store.dispatch("REMOVE_TOKEN");
            this.$router.push({ name: "home" });
        },
        searchData() {
            this.page = 1;
            this.userData();
        },
        sortList(column) {
            if (this.sort) {
                this.columnName = column;
                this.columnSort = "DESC";
                console.log("desceding");
                this.sort = false;
            } else {
                this.columnName = column;
                this.columnSort = "ASC";
                console.log("asceding");
                this.sort = true;
            }
            this.userData();
        },
        pagechange(pageNo) {
            this.page = pageNo;
            this.userData();
        },
        clickpre() {
            this.page = this.page - 1;
            this.userData();
        },
        clickNext() {
            this.page = this.page + 1;
            this.userData();
        },
    },
};
</script>
<style scoped>
th:hover,
.page-link {
    cursor: pointer;
}
</style>
