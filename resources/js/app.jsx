// import './bootstrap';

import React from "react";
import { createRoot } from "react-dom/client";

import AboutPage from "./pages/AboutPage";
import ContactPage from "./pages/ContactPage";

const rootElement = document.getElementById("react-root");
const pages = {
    AboutPage: <AboutPage />,
    ContactPage: <ContactPage />,
};

if (rootElement) {
    const page = rootElement.dataset.page; // ambil nama komponen dari data attribute
    const pageComponent = pages[page];

    if (pageComponent) {
        createRoot(rootElement).render(pageComponent);
    }
}
