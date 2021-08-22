import { extend } from "vee-validate";
import { required, email, min } from "vee-validate/dist/rules";

// Install required rule and message.
extend("required", {...required, message: "Поле обязательно"});

// Install email rule and message.
extend("email", email);

// Install min rule and message.
extend("min", min);
