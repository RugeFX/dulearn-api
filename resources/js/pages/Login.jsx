import { Head } from "@inertiajs/react";
import logo from "/public/img/logoglow.png";
import bg from "/public/img/login-bg.png";
import { FaUser, FaLock, FaCircleNotch } from "react-icons/fa";
import { useState } from "react";
import axios from "axios";
import { motion } from "framer-motion";

import "./../../css/shake.css";

export default function Login(props) {
    const [nisn, setNisn] = useState("");
    const [password, setPassword] = useState("");
    const [error, setError] = useState({});
    const [loading, setLoading] = useState(false);
    const [focusNisn, setFocusNisn] = useState(false);
    const [focusPass, setFocusPass] = useState(false);

    const handleLogin = () => {
        setLoading(true);
        axios.get("/sanctum/csrf-cookie").then((res) => {
            axios
                .post("/login", { reg_num: nisn, password: password })
                .then((res) => {
                    setLoading(false);
                    console.log(res);
                    if (res.data.success === "Success") {
                        window.location.href = "/home";
                    }
                })
                .catch((err) => {
                    setLoading(false);
                    const errResponse = err.response.data;
                    setError(errResponse.data);
                    console.error(errResponse);
                });
        });
    };
    return (
        <>
            <Head title="Login"></Head>
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
                    <motion.div
                        initial={{ opacity: 0, translateY: 10 }}
                        animate={{ opacity: 1, translateY: 0 }}
                        transition={{ duration: 0.5 }}
                        className="flex flex-col gap-5 w-[25rem]"
                    >
                        <div className="inline-flex items-center bg-[#1c215c] rounded-lg divide-gray-300 shadow-none shadow-[#FAA41A] transition-all">
                            <FaUser color="#FAA41A" className="m-4" />
                            <input
                                type="text"
                                name="nisn"
                                id="nisn"
                                className={`w-full px-3 pt-5 pb-2 border-l-2 bg-[#464A83] rounded-r-lg text-white outline-none ${
                                    error.reg_num != null || error.all != null
                                        ? "text-red-600"
                                        : ""
                                } ${
                                    !focusNisn && "pb-3.5 pt-3.5"
                                } transition-all`}
                                onFocus={(e) => {
                                    setFocusNisn(true);
                                    e.target.parentElement.style.scale = "105%";
                                    e.target.parentElement.style.boxShadow =
                                        "0 0 0 3px #FAA41A";
                                }}
                                onBlur={(e) => {
                                    setFocusNisn(false);
                                    e.target.parentElement.style.scale = "100%";
                                    e.target.parentElement.style.boxShadow =
                                        "0 0 0 0 #FAA41A";
                                }}
                                onChange={(e) => {
                                    setNisn(e.target.value);
                                }}
                                onKeyDown={(e) => {
                                    if (e.code === "Enter") handleLogin();
                                }}
                            />
                            <span
                                className={`absolute text-white translate-x-16 pointer-events-none ${
                                    nisn && !focusNisn
                                        ? "opacity-0"
                                        : "opacity-50"
                                } ${
                                    focusNisn &&
                                    "-translate-y-4 translate-x-14 scale-75 opacity-100"
                                } transition-all`}
                            >
                                NISN
                            </span>
                        </div>
                        <div className="inline-flex items-center bg-[#1c215c] rounded-lg shadow-none shadow-[#FAA41A] transition-all">
                            <FaLock color="#FAA41A" className="m-4" />
                            <input
                                type="password"
                                name="pass"
                                id="pass"
                                className={`w-full px-3 pt-5 pb-2 border-l-2 bg-[#464A83] rounded-r-lg text-white outline-none ${
                                    (error.password != null ||
                                        error.all != null) &&
                                    "text-red-600"
                                } ${
                                    !focusPass && "pb-3.5 pt-3.5"
                                } transition-all`}
                                onFocus={(e) => {
                                    setFocusPass(true);
                                    e.target.parentElement.style.scale = "105%";
                                    e.target.parentElement.style.boxShadow =
                                        "0 0 0 3px #FAA41A";
                                }}
                                onBlur={(e) => {
                                    setFocusPass(false);
                                    e.target.parentElement.style.scale = "100%";
                                    e.target.parentElement.style.boxShadow =
                                        "0 0 0 0 #FAA41A";
                                }}
                                onChange={(e) => {
                                    setPassword(e.target.value);
                                }}
                                onKeyDown={(e) => {
                                    if (e.code === "Enter") handleLogin();
                                }}
                            />
                            <span
                                className={`absolute text-white translate-x-16 pointer-events-none ${
                                    password && !focusPass
                                        ? "opacity-0"
                                        : "opacity-50"
                                } ${
                                    focusPass &&
                                    "-translate-y-4 translate-x-[3.20rem] scale-75 opacity-100"
                                } transition-all`}
                            >
                                Password
                            </span>
                        </div>
                    </motion.div>
                    <div className="text-red-600 font-bold text-center overflow-hidden animate-pulse">
                        {Object.keys(error).length !== 0 && (
                            <ul>
                                {Object.values(error).map((msg, i) => (
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
                        onClick={handleLogin}
                        className={`flex items-center gap-4 text-lg font-bold text-[#1c215c] bg-[#FAA41A] hover:bg-[#ffb949] rounded-lg px-5 py-2 focus:outline-0 focus:shadow-input focus:shadow-white transition-all hover:scale-110`}
                    >
                        {loading && (
                            <FaCircleNotch
                                className="animate-spin"
                                color="white"
                            />
                        )}
                        Log In
                    </button>
                    <span className="text-white">
                        Tidak punya akun?{" "}
                        <a
                            href="/register"
                            className="text-[#FAA41A] font-bold"
                        >
                            Buat akun disini!
                        </a>
                    </span>
                </div>
            </div>
        </>
    );
}
