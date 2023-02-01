import { Head } from "@inertiajs/react";
import bg from "/public/img/people.png";
import logo from "/public/img/logolarge.png";
import { useState } from "react";

const Landing = () => {
    return (
        <>
            <Head title="Landing" />
            <main
                className="h-screen bg-[#42489E]"
                style={{
                    backgroundImage: `url(${bg})`,
                    backgroundSize: "50vw",
                    backgroundPosition: "left center",
                    backgroundPositionX: 50,
                    backgroundRepeat: "no-repeat",
                }}
            >
                <div className="flex flex-col items-center justify-center gap-10 w-[40vw] h-full bg-[#070B30] absolute right-0 rounded-l-[10rem] shadow-lg">
                    <div>
                        <img
                            src={logo}
                            alt="DuLearn Logo"
                            className="w-96 h-fit"
                        />
                        <span className="text-lg text-white">
                            Dupat Learning App
                        </span>
                    </div>
                    <div className="w-80 mb-11">
                        <button
                            onClick={() => (window.location.href = "/login")}
                            className="text-3xl text-white font-bold w-80 py-4 bg-[#42489E] rounded-lg absolute transition-all duration-200 z-10 hover:-translate-x-3 hover:-translate-y-2 active:translate-x-0 active:translate-y-0 active:bg-[#282b5e] active:shadow-input"
                        >
                            Masuk
                        </button>
                        <div className="text-3xl text-transparent font-bold w-80 py-4 bg-[#282b5e] rounded-lg absolute z-0">
                            A
                        </div>
                    </div>
                    <div className="w-80">
                        <button
                            onClick={() => (window.location.href = "/register")}
                            className="text-3xl text-white font-bold w-80 py-4 bg-[#FAA41A] rounded-lg absolute transition-all duration-200 z-10 hover:-translate-x-3 hover:-translate-y-2 active:translate-x-0 active:translate-y-0 active:bg-[#9b6715] active:shadow-input"
                        >
                            Buat Akun
                        </button>
                        <div className="text-3xl text-transparent font-bold w-80 py-4 bg-[#9b6715] rounded-lg absolute z-0">
                            A
                        </div>
                    </div>
                </div>
            </main>
        </>
    );
};

export default Landing;
