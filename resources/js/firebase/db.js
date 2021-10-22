import {FireApp} from './app'
import { getDatabase } from "firebase/database";


export const FireDB = getDatabase(FireApp);

