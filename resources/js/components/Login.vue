<template>
    <main class="py-4 container-fluid">
        <div class="text-center body-sign-in">
            <form autocomplete="off" @submit.prevent="login" method="post" class="form-sign-in">

                <!-- Logo -->
                <logotype class="mb-5"/>
                <!-- Logo End -->

                <!-- Email -->
                <div class="row mb-4">
                    <input
                        id="email"
                        type="email"
                        v-model="email"
                        v-bind:class="{ active: email, red: has_error }"
                        class="inp col-sm"
                        name="email"
                        autocomplete="email"
                        autofocus
                        placeholder="Логин"
                    >

                    <span class="invalid-feedback" v-if="errors.email" role="alert">
                        <strong>{{error.email}}</strong>
                    </span>
                </div>
                <!-- Email End -->

                <!-- Password -->
                <div class="row">
                    <input
                        id="password"
                        :type="!eye ? 'password' : 'text'"
                        v-model="password"
                        v-bind:class="{ active: password, red: has_error }"
                        class="inp col-sm"
                        name="password"
                        autocomplete="current-password"
                        placeholder="Пароль"
                    >
                    <div
                        class="eye" v-on:click="toggleEye()"
                        v-bind:class="{off: password && !eye, on: password && eye, active: password}"
                    >
                    </div>

                    <span class="invalid-feedback" v-if="errors.password" role="alert">
                        <strong>{{error.password}}</strong>
                    </span>
                </div>
                <!-- Password End -->

                <!-- Button -->
                <div class="row mt-5">
                    <button
                        class="btn btn-lg btn-primary btn-block mx-auto"
                        v-bind:class="{ disabled: !email || !password }"
                        :disabled="!email || !password"
                    >
                        Войти
                    </button>
                </div>
                <!-- Button End -->
            </form>
        </div>
    </main>
</template>

<script>
import Logotype from "./fragmets/logotype";

export default {
    name: 'Login',
    components: {Logotype},
    data() {
        return {
            email: null,
            password: null,
            eye: false,
            success: false,
            has_error: false,
            errors: {}
        }
    },
    mounted() {
        //
    },
    methods: {
        toggleEye () {
            this.eye = !this.eye;
        },
        asyncRouterPush (route) {
            return new Promise(resolve => {
                resolve(this.$router.push({ path: route }))
            })
        },
        async login() {
            let app = this;
            await this.$auth.login({
                data: {
                    email: app.email,
                    password: app.password
                },
                success: function() {
                    app.success = true;
                },
                error: function(res) {
                    app.has_error = true;
                    app.errors = res.response.data.errors;
                },
                rememberMe: true,
                fetchUser: true
            });
            if (this.$auth.check()){
                await this.asyncRouterPush('/booking')
            }
        }
    }
}
</script>

<style scoped>
.body-sign-in {
    display: flex;
    align-items: center;
    padding-top: 5%;
    padding-bottom: 40px;
}
.form-sign-in {
    width: 100%;
    max-width: 315px;
    margin: auto;
}
.inp::placeholder {
    font-size: 10px;
    line-height: 12px;
    color: #3F5983;
    font-weight: normal;
}
.inp {
    border: none;
    border-bottom: 1px solid #005792;
    width: 100%;
    padding-left: 5px;
    background: none;

    font-weight: 500;
    font-size: 16px;
    line-height: 20px;
    color: #000000;
}
.inp.active,.eye.active {
    border-bottom: 1px solid #FF6550;
}
.red {
    color: #D20000;
    border-bottom: 2px solid #D20000;
}
.btn.disabled {
    background-color: #C6D7F3;
    border: 1px solid #C6D7F3;
    color: #8CA5D0;
}
.btn {
    max-width: 138px;
}
.eye {
    width: 24px;
    background-repeat: no-repeat;
    background-position: center;
    border-bottom: 1px solid #005792;
    cursor: pointer;
}
.eye.on {
    background-size: 21px 15px;
    background-image: url("../../../public/images/eye.svg");
}
.eye.off {
    background-size: 21px 19px;
    background-image: url("../../../public/images/eye-off.svg");
}
</style>
