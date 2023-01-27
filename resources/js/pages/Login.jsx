import { Head, Link } from "@inertiajs/react";
import logo from "./../../../public/img/logo.png";
import bg from "./../../../public/img/login-bg.png";
import { InertiaLink } from "@inertiajs/inertia-react";

export default function Login(props) {
    return (
        <>
            <Head title="Login"></Head>
            <div
                className="bg-[#070B30] h-screen flex items-center justify-center"
                style={{
                    backgroundImage: `url(${bg})`,
                    backgroundSize: "cover",
                    backgroundPosition: "center",
                }}
            >
                <div className="min-w-[25rem] min-h-[15rem] md:min-h-[25rem] lg:min-w-[40rem] lg:min-h-[30rem] bg-[#060D47] rounded-[70px] shadow-cum flex flex-col justify-center items-center gap-2 lg:gap-5 p-10">
                    <img src={logo} alt="Logo DuLearn" className="w-64" />
                    <div className="flex flex-col gap-5">
                        <input
                            type="text"
                            name="nisn"
                            id="nisn"
                            placeholder="Masukkan NISN"
                            className="bg-[#464A83] p-3 rounded-lg w-[25rem] text-white focus:outline focus:outline-2 outline-[#FAA41A] transition-all"
                        />
                        <input
                            type="password"
                            name="pass"
                            id="pass"
                            placeholder="Masukkan Password"
                            className="bg-[#464A83] p-3 rounded-lg text-white focus:outline focus:outline-2 outline-[#FAA41A] transition-all"
                        />
                    </div>
                    <a
                        href="/aa"
                        className="text-lg font-bold text-white bg-[#FAA41A] rounded-lg px-10 py-2 hover:bg-[#a76e14] transition-all focus:outline focus:outline-2 outline-white"
                    >
                        Log In
                    </a>
                </div>
            </div>
        </>
    );
}
