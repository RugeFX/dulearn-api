import "./bootstrap";
import "../css/app.css";
import React from "react";
import { createRoot } from "react-dom/client";

//Components
import TestComp from "./components/TestComp";

createRoot(document.getElementById("root")).render(<TestComp />);
