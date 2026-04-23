import "./bootstrap";

import Alpine from "alpinejs";
import collapse from "@alpinejs/collapse"; // Tambahkan baris ini

Alpine.plugin(collapse); // Tambahkan baris ini SEBELUM Alpine.start()

window.Alpine = Alpine;
Alpine.start();
