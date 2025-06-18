import React from "react";
import { createRoot } from "react-dom/client";
import { BrowserRouter, Routes, Route } from "react-router-dom";

import AboutPage from "./pages/AboutPage";
import ContactPage from "./pages/ContactPage";

const App = () => {
    return (
        <BrowserRouter>
            <Routes>
                <Route path="/" element={<AboutPage />} />
                <Route path="/contact" element={<ContactPage />} />
            </Routes>
        </BrowserRouter>
    );
};

const rootElement = document.getElementById("react-root");

if (rootElement) {
    createRoot(rootElement).render(<App />);
}
