<template>
    <div class="vue-tempalte">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <router-link class="navbar-brand float-left" to="/">
                Home
            </router-link>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <router-link
                            to="/dashboard"
                            v-if="$store.getters.getToken != 0"
                        >
                            <button class="button-34" role="button">
                                Dashboard
                            </button>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link
                            to="/register"
                            v-if="$store.getters.getToken == 0"
                        >
                            <button class="button-34" role="button">
                                Sign up
                            </button>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link
                            to="/login"
                            v-if="$store.getters.getToken == 0"
                        >
                            <button class="button-34" role="button">
                                Sign in
                            </button>
                        </router-link>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="App">
            <div class="vertical-center">
                <div class="inner-block">
                    <router-view />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {};
    },
};
</script>
