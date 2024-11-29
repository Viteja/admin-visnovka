import { useState } from "react";
import "./App.css";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import Login from "./login";
import Admin from "./admin";

function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route exact path="/admin" element={<Login />} />
        <Route path="/admin/admin-panel" element={<Admin />} />
      </Routes>
    </BrowserRouter>
  );
}

export default App;
