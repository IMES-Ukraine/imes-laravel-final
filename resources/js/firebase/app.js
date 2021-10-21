import credentials from './credentials'


import { initializeApp } from 'firebase/app';
import { getDatabase } from "firebase/database";
import {getFirestore} from "firebase/firestore";

// Set the configuration for your app
export const app = initializeApp(credentials.firebaseConfig);
export const store = getFirestore(app);

// Get a reference to the database service
export const database = getDatabase(app);


