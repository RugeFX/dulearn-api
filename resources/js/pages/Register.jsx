import { Head } from "@inertiajs/react";
import logo from "/public/img/logoglow.png";
import bg from "/public/img/login-bg.png";
import { FaUser, FaLock, FaCircleNotch } from "react-icons/fa";
import { useState, useEffect } from "react";
import axios from "axios";

// Firebase Imports
import { initializeApp } from "firebase/app";
import {
    getAuth,
    RecaptchaVerifier,
    signInWithPhoneNumber,
} from "firebase/auth";
import { firebaseConfig } from "../Auth";

import "./../../css/shake.css";

export default function Register(props) {
    // Input Field States
    const [nisn, setNisn] = useState("");
    const [password, setPassword] = useState("");
    const [passConf, setPassConf] = useState("");
    const [otpInput, setOtpInput] = useState("");
    // Misc States
    const [error, setError] = useState({});
    const [loading, setLoading] = useState(false);
    const [verificationDone, setVerificationDone] = useState("no");
    // Fouc Set States
    const [focusNisn, setFocusNisn] = useState(false);
    const [focusPass, setFocusPass] = useState(false);
    const [focusPassConf, setFocusPassConf] = useState(false);
    const [focusOtpInput, setFocusOtpInput] = useState(false);

    const app = initializeApp(firebaseConfig);
    const auth = getAuth(app);

    useEffect(() => {
        auth.languageCode = "id";

        window.recaptchaVerifier = new RecaptchaVerifier(
            "registerBtn",
            {
                size: "invisible",
                callback: (response) => {},
            },
            auth
        );
    }, []);

    const getPhoneNumberByNISN = async (reg) => {
        try {
            const res = await axios.post("/api/auth/reginfo", {
                reg_num: reg,
            });
            const { phone_num } = res.data.data;
            if (res.data.data.is_used === 1) {
                setError({ reg_num: ["NISN Telah Dipakai!"] });
                setLoading(false);
                return null;
            }
            setError({});
            return phone_num;
        } catch (err) {
            setError({ reg_num: ["NISN Tidak Ditemukan / Tidak Valid!"] });
            setLoading(false);
            return null;
        }
    };

    const handleLogin = async () => {
        setLoading(true);
        const phoneNumber = await getPhoneNumberByNISN(nisn);
        if (!phoneNumber) return;
        signInWithPhoneNumber(auth, phoneNumber, window.recaptchaVerifier)
            .then((result) => {
                console.log(result);
                window.confirmationResult = result;
                setVerificationDone("done");
                setLoading(false);
            })
            .catch((err) => {
                setError({ reg_num: ["NISN Not Found"] });
                console.error(err);
                setLoading(false);
            });
        console.log(phoneNumber);
    };

    const handleOTPVerification = () => {
        setLoading(true);
        confirmationResult
            .confirm(otpInput)
            .then((result) => {
                console.log(result);
                setVerificationDone("after");
                setLoading(false);
            })
            .catch((error) => {
                console.log(error);
                setError({ otp: ["Kode OTP Salah!"] });
                setLoading(false);
            });
    };

    const handleRegistration = () => {
        setLoading(true);
        if (password !== passConf) {
            setLoading(false);
            setError({ password: ["Password Tidak Cocok!"] });
            return;
        }
        axios
            .post("/register", {
                reg_num: nisn,
                password: password,
            })
            .then((res) => {
                console.log(res);
                window.location.href = "/login";
            })
            .catch((err) => {
                console.log(err);
                setError(err.response.data.data);
                // setError(err)
                setLoading(false);
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
                    {verificationDone === "done" ? (
                        <div className="flex flex-col gap-5 w-[25rem]">
                            <span className="text-white text-sm">
                                Tolong masukkan kode OTP yang telah dikirim ke
                                nomor telepon anda
                            </span>
                            <div className="inline-flex items-center bg-[#1c215c] rounded-lg divide-gray-300 shadow-none shadow-[#FAA41A] transition-all">
                                <FaLock color="#FAA41A" className="m-4" />
                                <input
                                    type="text"
                                    name="otp"
                                    id="otp"
                                    className={`w-full px-3 pt-5 pb-2 border-l-2 bg-[#464A83] rounded-r-lg text-white outline-none ${
                                        error.otp != null && "text-red-600"
                                    } ${
                                        !focusOtpInput && "pb-3.5 pt-3.5"
                                    } transition-all`}
                                    onFocus={(e) => {
                                        setFocusOtpInput(true);
                                        e.target.parentElement.style.scale =
                                            "105%";
                                        e.target.parentElement.style.boxShadow =
                                            "0 0 0 3px #FAA41A";
                                    }}
                                    onBlur={(e) => {
                                        setFocusOtpInput(false);
                                        e.target.parentElement.style.scale =
                                            "100%";
                                        e.target.parentElement.style.boxShadow =
                                            "0 0 0 0 #FAA41A";
                                    }}
                                    onChange={(e) => {
                                        setOtpInput(e.target.value);
                                    }}
                                    onKeyDown={(e) => {
                                        if (e.code === "Enter")
                                            handleOTPVerification();
                                    }}
                                />
                                <span
                                    className={`absolute text-white translate-x-16 pointer-events-none ${
                                        otpInput && !focusOtpInput
                                            ? "opacity-0"
                                            : "opacity-50"
                                    } ${
                                        focusOtpInput &&
                                        "-translate-y-4 translate-x-[3.25rem] scale-75 opacity-100"
                                    } transition-all`}
                                >
                                    OTP Code
                                </span>
                            </div>
                        </div>
                    ) : verificationDone === "no" ? (
                        <div className="flex flex-col gap-5 w-[25rem]">
                            <div className="inline-flex items-center bg-[#1c215c] rounded-lg divide-gray-300 shadow-none shadow-[#FAA41A] transition-all">
                                <FaUser color="#FAA41A" className="m-4" />
                                <input
                                    type="text"
                                    name="nisn"
                                    id="nisn"
                                    className={`w-full px-3 pt-5 pb-2 border-l-2 bg-[#464A83] rounded-r-lg text-white outline-none ${
                                        error.reg_num != null ||
                                        error.all != null
                                            ? "text-red-600"
                                            : ""
                                    } ${
                                        !focusNisn && "pb-3.5 pt-3.5"
                                    } transition-all`}
                                    onFocus={(e) => {
                                        setFocusNisn(true);
                                        e.target.parentElement.style.scale =
                                            "105%";
                                        e.target.parentElement.style.boxShadow =
                                            "0 0 0 3px #FAA41A";
                                    }}
                                    onBlur={(e) => {
                                        setFocusNisn(false);
                                        e.target.parentElement.style.scale =
                                            "100%";
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
                        </div>
                    ) : (
                        verificationDone === "after" && (
                            <div className="flex flex-col gap-5 w-[25rem]">
                                <div className="inline-flex items-center bg-[#1c215c] rounded-lg divide-gray-300 shadow-none shadow-[#FAA41A] transition-all">
                                    <FaLock color="#FAA41A" className="m-4" />
                                    <input
                                        type="password"
                                        name="pass"
                                        id="pass"
                                        className={`w-full px-3 pt-5 pb-2 border-l-2 bg-[#464A83] rounded-r-lg text-white outline-none ${
                                            error.password != null ||
                                            error.all != null
                                                ? "text-red-600"
                                                : ""
                                        } ${
                                            !focusPass && "pb-3.5 pt-3.5"
                                        } transition-all`}
                                        onFocus={(e) => {
                                            setFocusPass(true);
                                            e.target.parentElement.style.scale =
                                                "105%";
                                            e.target.parentElement.style.boxShadow =
                                                "0 0 0 3px #FAA41A";
                                        }}
                                        onBlur={(e) => {
                                            setFocusPass(false);
                                            e.target.parentElement.style.scale =
                                                "100%";
                                            e.target.parentElement.style.boxShadow =
                                                "0 0 0 0 #FAA41A";
                                        }}
                                        onChange={(e) => {
                                            setPassword(e.target.value);
                                        }}
                                        onKeyDown={(e) => {
                                            if (e.code === "Enter")
                                                handleRegistration();
                                        }}
                                    />
                                    <span
                                        className={`absolute text-white translate-x-16 pointer-events-none ${
                                            password && !focusPass
                                                ? "opacity-0"
                                                : "opacity-50"
                                        } ${
                                            focusPass &&
                                            "-translate-y-4 translate-x-14 scale-75 opacity-100"
                                        } transition-all`}
                                    >
                                        Password
                                    </span>
                                </div>
                                <div className="inline-flex items-center bg-[#1c215c] rounded-lg divide-gray-300 shadow-none shadow-[#FAA41A] transition-all">
                                    <FaLock color="#FAA41A" className="m-4" />
                                    <input
                                        type="password"
                                        name="passconf"
                                        id="passconf"
                                        value={passConf}
                                        className={`w-full px-3 pt-5 pb-2 border-l-2 bg-[#464A83] rounded-r-lg text-white outline-none ${
                                            error.password != null ||
                                            error.all != null
                                                ? "text-red-600"
                                                : ""
                                        } ${
                                            !focusPassConf && "pb-3.5 pt-3.5"
                                        } transition-all`}
                                        onFocus={(e) => {
                                            setFocusPassConf(true);
                                            e.target.parentElement.style.scale =
                                                "105%";
                                            e.target.parentElement.style.boxShadow =
                                                "0 0 0 3px #FAA41A";
                                        }}
                                        onBlur={(e) => {
                                            setFocusPassConf(false);
                                            e.target.parentElement.style.scale =
                                                "100%";
                                            e.target.parentElement.style.boxShadow =
                                                "0 0 0 0 #FAA41A";
                                        }}
                                        onChange={(e) => {
                                            setPassConf(e.target.value);
                                        }}
                                        onKeyDown={(e) => {
                                            if (e.code === "Enter")
                                                handleRegistration();
                                        }}
                                    />
                                    <span
                                        className={`absolute text-white translate-x-16 pointer-events-none ${
                                            passConf && !focusPassConf
                                                ? "opacity-0"
                                                : "opacity-50"
                                        } ${
                                            focusPassConf &&
                                            "-translate-y-4 translate-x-11 scale-75 opacity-100"
                                        } transition-all`}
                                    >
                                        Confirm Password
                                    </span>
                                </div>
                            </div>
                        )
                    )}

                    <div className="text-red-600 font-bold text-center overflow-hidden animate-pulse">
                        {Object.keys(error).length !== 0 && (
                            <ul>
                                {Object.values(error).map((err) =>
                                    err.map((msg, i) => (
                                        <li
                                            style={{
                                                animation:
                                                    "shake 150ms 2 linear",
                                            }}
                                            key={i}
                                        >
                                            {msg}
                                        </li>
                                    ))
                                )}
                            </ul>
                        )}
                    </div>
                    <button
                        id="registerBtn"
                        onClick={() => {
                            if (verificationDone === "no") {
                                handleLogin();
                            } else if (verificationDone === "done") {
                                handleOTPVerification();
                            } else if (verificationDone === "after") {
                                handleRegistration();
                            }
                        }}
                        className={`flex items-center gap-4 text-lg font-bold text-[#1c215c] bg-[#FAA41A] hover:bg-[#ffb949] rounded-lg px-5 py-2 focus:outline-0 focus:shadow-input focus:shadow-white transition-all hover:scale-110`}
                    >
                        {loading && (
                            <FaCircleNotch
                                className="animate-spin"
                                color="white"
                            />
                        )}
                        {verificationDone === "done"
                            ? "Konfirmasi OTP"
                            : verificationDone === "no"
                            ? "Buat Akun"
                            : verificationDone === "after" && "Atur Password"}
                    </button>
                    <span className="text-white">
                        Sudah memiliki akun?{" "}
                        <a href="/login" className="text-[#FAA41A] font-bold">
                            Masuk disini!
                        </a>
                    </span>
                </div>
            </div>
        </>
    );
}
