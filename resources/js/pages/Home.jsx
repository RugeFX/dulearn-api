import Navbar from "../components/Navbar";
import MainHome from "../components/MainHome";
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
