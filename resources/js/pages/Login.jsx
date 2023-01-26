import { Head } from "@inertiajs/react";
import logo from "./../../../public/img/logo.png";
import bg from "./../../../public/img/login-bg.png";

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
                <div className="h-3/5 w-2/5 min-w-[25rem] min-h-[25rem] bg-[#060D47] rounded-[123px] shadow-cum flex flex-col justify-center items-center gap-10 p-10">
                    <img src={logo} alt="Logo DuLearn" className="w-64" />
                    <span className="text-white text-lg">Log In</span>
                    <div className="flex flex-col gap-5">
                        <input
                            type="text"
                            name="nisn"
                            id="nisn"
                            placeholder="Masukkan NISN"
                            className="bg-[#464A83] p-3 rounded-2xl w-[25rem]"
                        />
                        <input
                            type="password"
                            name="pass"
                            id="pass"
                            placeholder="Masukkan Password"
                            className="bg-[#464A83] p-3 rounded-2xl"
                        />
                    </div>
                    <button className="text-lg font-bold bg-[#FAA41A] rounded-3xl px-10 py-2">
                        Log In
                    </button>
                </div>
            </div>
        </>
    );
}
