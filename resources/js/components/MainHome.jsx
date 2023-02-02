import React from "react";
import axios from "axios";
import MaterialBox from "./MaterialBox";
import { FaSearch } from "react-icons/fa";
import subjectbox from "/public/img/subjectbox.png";

const { useEffect, useState } = React;

const MainHome = (props) => {
    const [materials, setMaterials] = useState([]);

    useEffect(() => {
        axios
            .get("/api/materials")
            .then((res) => {
                console.log(res.data);
                setMaterials(res.data.data);
            })
            .catch((err) => {
                console.error(err);
            });
    }, []);
    return (
        <main className="h-screen bg-[#070B30] mt-20 pt-5 px-5">
            <div className="grid grid-cols-2 h-52 mb-5 gap-5">
                <div className="h-full p-5 bg-[#42489E] rounded-lg grid place-content-center">
                    <h1 className="text-3xl text-white font-bold text-center">
                        Selamat Datang Pengguna DuLearn
                    </h1>
                </div>
                <div className="h-full">
                    <div className="grid grid-rows-2 h-full items-center">
                        <div>
                            <h2 className="text-xl text-white font-bold">
                                Cari Materi
                            </h2>
                            <div className="inline-flex w-full h-12 rounded-lg bg-white">
                                <input
                                    className="w-full rounded-lg p-3 outline-none transition-all focus:shadow-[#FAA41A] focus:shadow-input"
                                    type="text"
                                ></input>
                                <button className="px-5 bg-[#FAA41A] rounded-lg transition-all hover:bg-[#bd8120]">
                                    <FaSearch color="white" />
                                </button>
                            </div>
                        </div>
                        <div>
                            <h2 className="text-xl text-white font-bold">
                                Kelas
                            </h2>
                            <div className="grid grid-cols-3 gap-5 w-full h-12 rounded-lg">
                                <button
                                    className="px-5 rounded-lg text-white text-lg font-bold transition-all hover:opacity-70"
                                    style={{
                                        backgroundImage: `url(${subjectbox})`,
                                        backgroundSize: `cover`,
                                    }}
                                >
                                    X
                                </button>
                                <button
                                    className="px-5 rounded-lg text-white text-lg font-bold transition-all hover:opacity-70"
                                    style={{
                                        backgroundImage: `url(${subjectbox})`,
                                        backgroundSize: `cover`,
                                    }}
                                >
                                    XI
                                </button>
                                <button
                                    className="px-5 rounded-lg text-white text-lg font-bold transition-all hover:opacity-70"
                                    style={{
                                        backgroundImage: `url(${subjectbox})`,
                                        backgroundSize: `cover`,
                                    }}
                                >
                                    XII
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <h1 className="text-3xl text-white font-bold">Materi Terbaru</h1>
            <div className="py-5 grid grid-flow-col justify-start w-full gap-5 overflow-auto">
                {materials.map((mat) => (
                    <MaterialBox
                        key={mat.id}
                        title={mat.title}
                        subject={mat.subject.subject}
                        onClick={() => {
                            window.location.href = "/materi";
                        }}
                    >
                        {mat.material}
                    </MaterialBox>
                ))}
                <MaterialBox
                    key="asd"
                    title="Cara membuat CSS Input"
                    subject="PWPB"
                    onClick={() => {
                        window.location.href = "/materi";
                    }}
                >
                    Cara membuat CSS Input
                </MaterialBox>
                <MaterialBox
                    key="dsa"
                    title="Membuat sistem post di Laravel dan React Native"
                    subject="PWPB"
                    onClick={() => {
                        window.location.href = "/materi";
                    }}
                >
                    Membuat sistem post di Laravel dan React Native
                </MaterialBox>
                <MaterialBox
                    key="das"
                    title="Instalasi NetBeans 8.2 dengan JDK 1.8"
                    subject="PBO"
                    onClick={() => {
                        window.location.href = "/materi";
                    }}
                >
                    Instalasi NetBeans 8.2 dengan JDK 1.8
                </MaterialBox>
            </div>
        </main>
    );
};

export default MainHome;
