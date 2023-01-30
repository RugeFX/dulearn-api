import { Head, router } from "@inertiajs/react";
import logo from "/public/img/logoglow.png";
import bg from "/public/img/login-bg.png";
import { FaUser, FaLock, FaCircleNotch } from "react-icons/fa";
import { useEffect, useState } from "react";
import axios from "axios";

import "./../../css/shake.css";

export default function Register(props) {
    const [nisn, setNisn] = useState("");
    const [password, setPassword] = useState("");
    const [conf, setConf] = useState("");
    const [error, setError] = useState({});
    const [loading, setLoading] = useState(false);

    const handleRegister = () => {
        setLoading(true);
        if (password !== conf) {
            setError(["Password doesn't match!"]);
            setLoading(false);
            return;
        }
        axios
            .post("/register", { reg_num: nisn, password: password })
            .then((res) => {
                setLoading(false);
                console.log(res);
                if (res.data.success === "Success") {
                    setTimeout(() => {
                        window.location.href = "/home";
                    }, 600);
                }
            })
            .catch((err) => {
                setLoading(false);
                const errResponse = err.response.data;
                setError(errResponse.data);
                console.error(errResponse);
            });
    };
    return (
        <>
            <Head title="Register"></Head>
            <div
                className={
                    "bg-[#070B30] h-screen flex items-center justify-center"
                }
                style={{
                    backgroundImage: `url(${bg})`,
                    backgroundSize: "cover",
                    backgroundPosition: "center",
                }}
            >
                <div className="min-w-[25rem] min-h-[15rem] lg:min-w-[40rem] lg:min-h-[30rem] bg-[#060D47] rounded-[70px] shadow-cum flex flex-col justify-center items-center gap-2 lg:gap-5 p-10">
                    <img src={logo} alt="Logo DuLearn" className="w-96" />
                    <div className="flex flex-col gap-5 w-[25rem]">
                        <div className="inline-flex items-center bg-[#1c215c] rounded-lg divide-x-2 divide-gray-300 shadow-none shadow-[#FAA41A] transition-all">
                            <FaUser color="#FAA41A" className="m-4" />
                            <input
                                type="text"
                                name="nisn"
                                id="nisn"
                                placeholder="Masukkan NISN"
                                className={`w-full p-3 bg-[#464A83] rounded-r-lg text-white outline-none transition-colors duration-300 ${
                                    error.length !== 0 ? "text-red-600" : ""
                                }`}
                                onFocus={(e) => {
                                    e.target.parentElement.style.scale = "105%";
                                    e.target.parentElement.style.boxShadow =
                                        "0 0 0 3px #FAA41A";
                                }}
                                onBlur={(e) => {
                                    e.target.parentElement.style.scale = "100%";
                                    e.target.parentElement.style.boxShadow =
                                        "0 0 0 0 #FAA41A";
                                }}
                                onChange={(e) => {
                                    setNisn(e.target.value);
                                }}
                                onKeyDown={(e) => {
                                    if (e.code === "Enter") handleRegister();
                                }}
                            />
                        </div>
                        <div className="inline-flex items-center bg-[#1c215c] rounded-lg divide-x-2 divide-gray-300 shadow-none shadow-[#FAA41A] transition-all">
                            <FaLock color="#FAA41A" className="m-4" />
                            <input
                                type="password"
                                name="pass"
                                id="pass"
                                placeholder="Masukkan Password"
                                className={`w-full p-3 bg-[#464A83] rounded-r-lg text-white outline-none transition-colors duration-300 ${
                                    error.length !== 0 ? "text-red-600" : ""
                                }`}
                                onFocus={(e) => {
                                    e.target.parentElement.style.scale = "105%";
                                    e.target.parentElement.style.boxShadow =
                                        "0 0 0 3px #FAA41A";
                                }}
                                onBlur={(e) => {
                                    e.target.parentElement.style.scale = "100%";
                                    e.target.parentElement.style.boxShadow =
                                        "0 0 0 0 #FAA41A";
                                }}
                                onChange={(e) => {
                                    setPassword(e.target.value);
                                }}
                                onKeyDown={(e) => {
                                    if (e.code === "Enter") handleRegister();
                                }}
                            />
                        </div>
                        <div className="inline-flex items-center bg-[#1c215c] rounded-lg divide-x-2 divide-gray-300 shadow-none shadow-[#FAA41A] transition-all">
                            <FaLock color="#FAA41A" className="m-4" />
                            <input
                                type="password"
                                name="passconf"
                                id="passconf"
                                placeholder="Konfirmasi Password"
                                className={`w-full p-3 bg-[#464A83] rounded-r-lg text-white outline-none transition-colors duration-300 ${
                                    error.length !== 0 ? "text-red-600" : ""
                                }`}
                                onFocus={(e) => {
                                    e.target.parentElement.style.scale = "105%";
                                    e.target.parentElement.style.boxShadow =
                                        "0 0 0 3px #FAA41A";
                                }}
                                onBlur={(e) => {
                                    e.target.parentElement.style.scale = "100%";
                                    e.target.parentElement.style.boxShadow =
                                        "0 0 0 0 #FAA41A";
                                }}
                                onChange={(e) => {
                                    setConf(e.target.value);
                                }}
                                onKeyDown={(e) => {
                                    if (e.code === "Enter") handleRegister();
                                }}
                            />
                        </div>
                    </div>
                    <div className="text-red-600 font-bold text-center overflow-hidden animate-pulse">
                        {Object.keys(error).length !== 0 && (
                            <ul>
                                {error.map((msg, i) => (
                                    <li
                                        style={{
                                            animation: "shake 150ms 2 linear",
                                        }}
                                        key={i}
                                    >
                                        {msg}
                                    </li>
                                ))}
                            </ul>
                        )}
                    </div>
                    <button
                        onClick={handleRegister}
                        className={`flex items-center gap-4 text-lg font-bold text-[#1c215c] bg-[#FAA41A] hover:bg-[#ffb949] rounded-lg px-5 py-2 focus:outline-0 focus:shadow-input focus:shadow-white transition-all hover:scale-110`}
                    >
                        {loading && (
                            <FaCircleNotch
                                className="animate-spin"
                                color="white"
                            />
                        )}
                        Buat Akun
                    </button>
                    <span className="text-white">
                        Sudah mempunyai akun?{" "}
                        <a href="/login" className="text-[#FAA41A] font-bold">
                            Masuk disini!
                        </a>
                    </span>
                </div>
            </div>
        </>
    );
}
