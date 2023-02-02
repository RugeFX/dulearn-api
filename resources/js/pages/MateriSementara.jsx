import React from "react";
import Navbar from "../Components/Navbar";
import { Head } from "@inertiajs/react";
import { FaBook, FaHome } from "react-icons/fa";
import Sidebar from "../Components/Sidebar";

const MateriSementara = () => {
    return (
        <>
            <Head title="Materi" />
            <Navbar />
            <Sidebar />
            {/* background: linear-gradient(180deg, #42489E 0%, #161A58 95.83%); */}
            <main className="h-screen bg-[#070B30] mt-20 pl-56 pt-5">
                <div className="fixed w-[20vw] left-60 bottom-0 p-5 rounded-t-xl border-b-4 bg-[#42489E]">
                    <h1 className="text-2xl font-bold text-white">Diskusi</h1>
                </div>
                <div className="h-full mx-10 px-7 py-10 rounded-lg bg-gradient-to-b from-[#42489E] to-[#161A58]">
                    <div className="mb-3">
                        <span className="text-3xl text-white font-bold">
                            Membuat Desain dengan Addobe Ilustrator
                        </span>
                        <span className="pl-3 text-[#FAA41A] text-setMaterials">
                            DDG
                        </span>
                    </div>
                    <p className="text-lg text-white">
                        Pembuatan desain pada matapelajaran DDG ini kita
                        berkenalan dengan Addobe Ilustrator yang menjadi dasar
                        dalam pembuatan desain digital
                    </p>
                </div>
            </main>
        </>
    );
};

export default MateriSementara;
0;
