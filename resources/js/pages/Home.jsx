import React from "react";
import Navbar from "../Components/Navbar";
import MainHome from "../Components/MainHome";
import { Head } from "@inertiajs/react";

export default function Home(props) {
    return (
        <>
            <Head title="Homepage"></Head>
            <Navbar />
            <MainHome />
        </>
    );
}
