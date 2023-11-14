import './css/styl/error.styl'
import {setCollapsible} from "./js/error_page";

declare global {
    interface Window {
        setCollapsible: (id: string) => void;
    }
}

window.setCollapsible = setCollapsible;
